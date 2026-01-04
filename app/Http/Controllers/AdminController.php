<?php

namespace App\Http\Controllers;

use App\Models\User;
use App\Models\Room;
use App\Models\Fee;
use App\Models\Complaint;
use App\Models\RoomRequest;
use App\Models\Feedback;
use Illuminate\Http\Request;

class AdminController extends Controller
{
    public function dashboard()
    {
        $totalStudents = User::where('role', 'student')->count();
        $totalRooms = Room::count();
        $occupiedBeds = \App\Models\Bed::where('is_occupied', true)->count();
        $pendingComplaints = Complaint::where('status', 'open')->count();
        $monthlyCollections = Fee::where('status', 'paid')->sum('amount');
        
        $pendingRequests = RoomRequest::pending()->count();
        $pendingFeedback = Feedback::pending()->count();
        $pendingVisits = \App\Models\Visitor::where('status', 'pending')->count();

        $recentPending = User::whereIn('role', ['student', 'visitor'])
                            ->where('is_approved', false)
                            ->orderBy('created_at', 'desc')
                            ->take(5)
                            ->get();

        return view('admin.dashboard', compact(
            'totalStudents', 
            'totalRooms', 
            'occupiedBeds', 
            'pendingComplaints', 
            'monthlyCollections',
            'pendingRequests',
            'pendingFeedback',
            'pendingVisits',
            'recentPending'
        ));
    }

    public function pendingUsers()
    {
        $users = User::whereIn('role', ['student', 'visitor'])->where('is_approved', false)->get();
        return view('admin.users.pending', compact('users'));
    }

    public function approveUser(User $user)
    {
        if ($user->role === 'visitor') {
            // For visitors, set admin_approved
            $user->update(['admin_approved' => true]);
            
            app(\App\Services\NotificationService::class)->notifyUser(
                $user->id,
                'success',
                __('Account Approved'),
                'Your visitor account has been fully approved by the Administrator. You can now login.',
                null,
                route('login')
            );
        } else {
            // For students, use is_approved
            $user->update(['is_approved' => true]);

            app(\App\Services\NotificationService::class)->notifyUser(
                $user->id,
                'success',
                __('Account Approved'),
                'Your student account has been approved by the Administrator. You can now access all features.',
                null,
                route('login')
            );
        }
        
        return back()->with('success', 'User approved successfully.');
    }

    public function rejectUser(User $user)
    {
        $user->delete();
        return back()->with('success', 'User rejected and removed.');
    }

    public function students()
    {
        $students = User::where('role', 'student')
                        ->where('is_approved', true)
                        ->with('bed.room')
                        ->latest()
                        ->get();
        return view('admin.students.index', compact('students'));
    }

    public function showStudentProfile($id)
    {
        $student = User::where('role', 'student')->with('bed.room', 'fees', 'complaints')->findOrFail($id);
        return view('admin.students.profile', compact('student'));
    }

    public function assignRoomForm($id)
    {
        $student = User::findOrFail($id);
        $availableBeds = \App\Models\Bed::where('is_occupied', false)->with('room')->get();
        return view('admin.students.assign-room', compact('student', 'availableBeds'));
    }

    public function assignRoom(Request $request, $id)
    {
        $request->validate([
            'bed_id' => 'required|exists:beds,id',
        ]);

        $student = User::findOrFail($id);
        $bed = \App\Models\Bed::findOrFail($request->bed_id);

        // Check if bed is available
        if ($bed->is_occupied) {
            return back()->withErrors(['bed_id' => 'This bed is already occupied.']);
        }

        // Free up old bed if student had one
        if ($student->bed_id) {
            $oldBed = \App\Models\Bed::find($student->bed_id);
            if ($oldBed) {
                $oldBed->update(['is_occupied' => false]);
            }
        }

        // Assign new bed
        $student->update(['bed_id' => $bed->id]);
        $bed->update(['is_occupied' => true]);

        return redirect()->route('admin.students.index')->with('success', 'Room assigned successfully.');
    }

    public function unassignRoom($id)
    {
        $student = User::findOrFail($id);

        if ($student->bed_id) {
            $bed = \App\Models\Bed::find($student->bed_id);
            if ($bed) {
                $bed->update(['is_occupied' => false]);
            }
            $student->update(['bed_id' => null]);
            return back()->with('success', 'Room unassigned successfully.');
        }

        return back()->with('error', 'Student does not have an assigned room.');
    }

    public function profile()
    {
        $user = auth()->user();
        return view('admin.profile', compact('user'));
    }

    public function updateProfile(Request $request)
    {
        $user = auth()->user();
        $request->validate([
            'phone' => 'nullable|string|max:20',
            'address' => 'nullable|string|max:255',
            'profile_photo' => 'nullable|image|max:1024',
        ]);

        $data = $request->only(['phone', 'address']);

        if ($request->hasFile('profile_photo')) {
            $path = $request->file('profile_photo')->store('profile-photos', 'public');
            $data['profile_photo_path'] = $path;
        }

        $user->update($data);

        return back()->with('success', 'Profile updated successfully.');
    }
    public function visitorRequests()
    {
        $requests = \App\Models\Visitor::with('student')->latest()->get();
        return view('admin.visitors.requests', compact('requests'));
    }

    public function editVisitRequest($id)
    {
        $request = \App\Models\Visitor::findOrFail($id);
        $students = User::where('role', 'student')->get();
        return view('admin.visitors.edit_request', compact('request', 'students'));
    }

    public function updateVisitRequest(Request $request, $id)
    {
        $visit = \App\Models\Visitor::findOrFail($id);
        
        $request->validate([
            'visitor_name' => 'required|string|max:255',
            'phone' => 'required|string|max:20',
            'purpose' => 'required|string',
            'visit_date' => 'required|date',
            'status' => 'required|in:pending,approved,rejected',
        ]);

        $visit->update($request->all());

        return redirect()->route('admin.visitors.requests')->with('success', 'Visit request updated successfully.');
    }
}
