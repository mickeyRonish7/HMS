<?php

namespace App\Http\Controllers;

use App\Models\Notice;
use App\Models\Fee;
use App\Models\Complaint;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class StudentController extends Controller
{
    public function dashboard()
    {
        $student = Auth::user();
        $notices = \App\Models\Notice::whereIn('audience', ['all', 'students'])->latest()->take(5)->get();
        // Calculate due fees
        $dueFees = $student->fees()->where('status', 'pending')->sum('amount');
        
        return view('student.dashboard', compact('student', 'notices', 'dueFees'));
    }

    public function profile()
    {
        $user = Auth::user();
        return view('student.profile', compact('user'));
    }

    public function updateProfile(Request $request)
    {
        $user = Auth::user();
        $request->validate([
            'phone' => 'nullable|string|max:20',
            'address' => 'nullable|string|max:255',
            'profile_photo' => 'nullable|image|max:1024', // 1MB Max
        ]);

        $data = $request->only(['phone', 'address']);

        if ($request->hasFile('profile_photo')) {
            $path = $request->file('profile_photo')->store('profile-photos', 'public');
            $data['profile_photo_path'] = $path;
        }

        $user->update($data);

        return back()->with('success', 'Profile updated successfully.');
    }

    public function room()
    {
        $room = Auth::user()->bed->room ?? null;
        $bed = Auth::user()->bed ?? null;
        return view('student.room', compact('room', 'bed'));
    }

    public function fees()
    {
        $fees = Auth::user()->fees()->with('payments')->latest()->get();
        return view('student.fees', compact('fees'));
    }

    /**
     * Display pending visitors awaiting student approval
     */
    public function pendingVisitors()
    {
        // 1. Account Approvals (User model)
        $visitors = \App\Models\User::where('role', 'visitor')
            ->where('is_approved', false)
            ->latest()
            ->get();
        
        // 2. Specific Visit Requests (Visitor model)
        $visitRequests = \App\Models\Visitor::where('student_id', \Illuminate\Support\Facades\Auth::id())
            ->latest()
            ->get();
        
        return view('student.visitors.pending', compact('visitors', 'visitRequests'));
    }

    /**
     * Approve a visitor (student approval)
     */
    public function approveVisitor($id)
    {
        $visitor = \App\Models\User::where('role', 'visitor')->findOrFail($id);
        
        $visitor->update([
            'student_approved' => true,
            'rejection_reason' => null,
            'rejected_by' => null
        ]);

        app(\App\Services\NotificationService::class)->notifyStudentVisitorApproval($visitor->id, Auth::id());
        
        return back()->with('success', 'Visitor approved successfully. Waiting for admin approval.');
    }

    /**
     * Reject a visitor with reason
     */
    public function rejectVisitor(Request $request, $id)
    {
        $request->validate([
            'rejection_reason' => 'required|string|min:10|max:500'
        ]);
        
        $visitor = \App\Models\User::where('role', 'visitor')->findOrFail($id);
        
        $visitor->update([
            'student_approved' => false,
            'rejection_reason' => $request->rejection_reason,
            'rejected_by' => 'student'
        ]);

        // Optional: Notify visitor about rejection reason via notification? 
        // For now just keep it as is.
        
        return back()->with('success', 'Visitor request rejected.');
    }

    /**
     * Approve a specific visit request
     */
    public function approveVisitRequest($id)
    {
        $visit = \App\Models\Visitor::where('student_id', \Illuminate\Support\Facades\Auth::id())->findOrFail($id);
        $visit->update(['status' => 'approved']);

        app(\App\Services\NotificationService::class)->notifyVisitRequestStatus($visit->id, 'approved');
        
        return back()->with('success', 'Visit request approved.');
    }

    /**
     * Reject a specific visit request
     */
    public function rejectVisitRequest($id)
    {
        $visit = \App\Models\Visitor::where('student_id', \Illuminate\Support\Facades\Auth::id())->findOrFail($id);
        $visit->update(['status' => 'rejected']);

        app(\App\Services\NotificationService::class)->notifyVisitRequestStatus($visit->id, 'rejected');
        
        return back()->with('success', 'Visit request rejected.');
    }
}
