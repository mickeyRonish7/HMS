<?php

namespace App\Http\Controllers;

use App\Models\Complaint;
use Illuminate\Http\Request;
use App\Http\Requests\StoreComplaintRequest;
use Illuminate\Support\Facades\Auth;

class ComplaintController extends Controller
{
    // Admin: List all pending complaints
    public function index()
    {
        $complaints = Complaint::with('student')->latest()->get();
        return view('admin.complaints.index', compact('complaints'));
    }

    // Student: Show create form
    public function create()
    {
        return view('student.complaints.create');
    }

    // Student: Store complaint
    public function store(StoreComplaintRequest $request)
    {
        // Validation and role check are handled in StoreComplaintRequest
        
        $complaint = Complaint::create([
            'student_id' => Auth::id(),
            'category' => $request->category,
            'description' => $request->description,
            'status' => 'pending',
        ]);

        app(\App\Services\NotificationService::class)->notifyNewComplaint($complaint->id, Auth::id());

        return redirect()->route('student.dashboard')->with('success', 'Complaint submitted successfully.');
    }

    // Admin: Update status
    public function update(Request $request, Complaint $complaint)
    {
        $request->validate([
            'status' => 'required|in:pending,resolved,rejected',
            'admin_remark' => 'nullable|string',
        ]);

        $complaint->update($request->only('status', 'admin_remark'));

        app(\App\Services\NotificationService::class)->notifyComplaintStatus(
            $complaint->student_id,
            $complaint->id,
            $request->status
        );

        return back()->with('success', 'Complaint updated successfully.');
    }
}
