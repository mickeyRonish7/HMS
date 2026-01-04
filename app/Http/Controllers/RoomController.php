<?php

namespace App\Http\Controllers;

use App\Models\Room;
use Illuminate\Http\Request;
use App\Http\Requests\StoreRoomRequest;

class RoomController extends Controller
{
    public function index()
    {
        $rooms = Room::with('beds')->get();
        return view('admin.rooms.index', compact('rooms'));
    }

    public function create()
    {
        return view('admin.rooms.create');
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'room_number' => 'required|unique:rooms',
            'capacity' => 'required|integer|min:1',
            'type' => 'required|in:standard,deluxe',
            'room_photo' => 'nullable|image|max:2048', // 2MB max
        ]);

        // Handle photo upload
        if ($request->hasFile('room_photo')) {
            $path = $request->file('room_photo')->store('room-photos', 'public');
            $validated['room_photo'] = $path;
        }

        $room = Room::create($validated);

        // Auto-create beds
        for ($i = 1; $i <= $room->capacity; $i++) {
            $room->beds()->create([
                'bed_number' => $room->room_number . '-' . $i,
            ]);
        }

        return redirect()->route('admin.rooms.index')->with('success', 'Room created successfully with beds.');
    }

    public function edit(Room $room)
    {
        return view('admin.rooms.edit', compact('room'));
    }

    public function update(Request $request, Room $room)
    {
        $validated = $request->validate([
            'room_number' => 'required|unique:rooms,room_number,' . $room->id,
            'capacity' => 'required|integer|min:1',
            'type' => 'required|in:standard,deluxe',
            'status' => 'required|in:active,maintenance',
            'room_photo' => 'nullable|image|max:2048', // 2MB max
        ]);

        // Handle photo upload
        if ($request->hasFile('room_photo')) {
            // Delete old photo if exists
            if ($room->room_photo && \Storage::disk('public')->exists($room->room_photo)) {
                \Storage::disk('public')->delete($room->room_photo);
            }
            
            $path = $request->file('room_photo')->store('room-photos', 'public');
            $validated['room_photo'] = $path;
        }

        // If capacity changes, we might need logic to add/remove beds, keeping simple for now (no implementation)
        $room->update($validated);

        return redirect()->route('admin.rooms.index')->with('success', 'Room updated successfully.');
    }

    public function destroy(Room $room)
    {
        // Delete room photo if exists
        if ($room->room_photo && \Storage::disk('public')->exists($room->room_photo)) {
            \Storage::disk('public')->delete($room->room_photo);
        }
        
        $room->delete();
        return back()->with('success', 'Room deleted.');
    }

    /**
     * Student: Browse all available rooms
     */
    public function browse()
    {
        $query = Room::with('beds')->where('status', 'active');
        
        if (request('type')) {
            $query->where('type', request('type'));
        }
        
        $rooms = $query->get();

        return view('student.rooms.browse', compact('rooms'));
    }

    /**
     * Student: View room details
     */
    public function showRoom($id)
    {
        $room = Room::with('beds')->findOrFail($id);

        return view('student.rooms.show', compact('room'));
    }

    /**
     * Student: Submit room request
     */
    public function requestRoom(StoreRoomRequest $request)
    {
        // Role check and validation are now handled in StoreRoomRequest
        
        // Check if user already has a pending request
        $existingRequest = \App\Models\RoomRequest::where('user_id', auth()->id())
            ->where('status', 'pending')
            ->first();

        if ($existingRequest) {
            return back()->withErrors(['error' => 'You already have a pending room request.']);
        }

        // Check if user already has a room assigned
        if (auth()->user()->bed_id) {
            return back()->withErrors(['error' => 'You already have a room assigned.']);
        }

        $roomRequest = \App\Models\RoomRequest::create([
            'user_id' => auth()->id(),
            'room_id' => $request->room_id,
            'bed_id' => $request->bed_id,
            'student_message' => $request->student_message,
            'status' => 'pending',
        ]);

        // Notify admins
        $notificationService = app(\App\Services\NotificationService::class);
        $notificationService->notifyNewRoomRequest($roomRequest->id, auth()->id());

        return redirect()->route('student.room-requests')
            ->with('success', 'Room request submitted successfully!');
    }

    /**
     * Student: View own room requests
     */
    public function myRequests()
    {
        $requests = \App\Models\RoomRequest::where('user_id', auth()->id())
            ->with(['room', 'bed', 'processedBy'])
            ->latest()
            ->get();

        return view('student.room-requests', compact('requests'));
    }

    /**
     * Admin: View all room requests
     */
    /**
     * Admin: View all room requests
     */
    public function adminRequests()
    {
        $requests = \App\Models\RoomRequest::with(['user', 'room', 'bed', 'processedBy'])
            ->orderByRaw("CASE WHEN status = 'pending' THEN 1 ELSE 2 END")
            ->latest()
            ->paginate(20);

        return view('admin.room-requests.index', compact('requests'));
    }

    /**
     * Admin: Approve room request
     */
    public function approveRequest($id)
    {
        $request = \App\Models\RoomRequest::findOrFail($id);

        if ($request->status !== 'pending') {
            return back()->withErrors(['error' => 'This request has already been processed.']);
        }

        $bed = $request->bed;

        // If no specific bed was requested, find the first available one in the room
        if (!$bed) {
            $bed = \App\Models\Bed::where('room_id', $request->room_id)
                ->where('is_occupied', false)
                ->first();
        }

        // Check if bed is still available (or if we found one)
        if (!$bed || $bed->is_occupied) {
            return back()->withErrors(['error' => 'No available beds found in the requested room.']);
        }

        // Free up old bed if student had one
        if ($request->user->bed_id) {
            $oldBed = \App\Models\Bed::find($request->user->bed_id);
            if ($oldBed) {
                $oldBed->update(['is_occupied' => false]);
            }
        }

        // Assign new bed
        $request->user->update(['bed_id' => $bed->id]);
        $bed->update(['is_occupied' => true]);

        // Update request status
        $request->update([
            'status' => 'approved',
            'processed_at' => now(),
            'processed_by' => auth()->id(),
        ]);

        // Notify student
        $notificationService = app(\App\Services\NotificationService::class);
        $notificationService->notifyRoomRequestStatus($request->user_id, 'approved');

        return back()->with('success', 'Room request approved successfully!');
    }

    /**
     * Admin: Reject room request
     */
    public function rejectRequest(Request $request, $id)
    {
        $roomRequest = \App\Models\RoomRequest::findOrFail($id);

        if ($roomRequest->status !== 'pending') {
            return back()->withErrors(['error' => 'This request has already been processed.']);
        }

        $roomRequest->update([
            'status' => 'rejected',
            'processed_at' => now(),
            'processed_by' => auth()->id(),
            'admin_notes' => $request->input('admin_notes'),
        ]);

        // Notify student
        $notificationService = app(\App\Services\NotificationService::class);
        $notificationService->notifyRoomRequestStatus(
            $roomRequest->user_id,
            'rejected',
            $request->input('admin_notes')
        );

        return back()->with('success', 'Room request rejected.');
    }
}
