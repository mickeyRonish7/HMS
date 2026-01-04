<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Visitor;
use App\Models\User;
use App\Http\Requests\StoreVisitRequest;
use Illuminate\Support\Facades\Auth;

class VisitorDashboardController extends Controller
{
    public function dashboard()
    {
        // Get visits requested by this phone number or user
        // Since we have Auth, we can use the user's phone or name?
        // But the 'visitors' table stores 'phone' and 'visitor_name'. 
        // Ideally we should have 'user_id' in visitors table if they are registered.
        // But for now, let's filter by phone number of the logged-in visitor.
        
        $user = Auth::user();
        $visits = Visitor::where('phone', $user->phone)->latest()->get();
        $students = User::where('role', 'student')->get(); // For the dropdown

        return view('visitor.dashboard', compact('visits', 'students'));
    }

    public function requestVisit(StoreVisitRequest $request)
    {
        // Validation and role check are handled in StoreVisitRequest
        
        $user = Auth::user();

        $visit = Visitor::create([
            'student_id' => $request->student_id,
            'visitor_name' => $user->name,
            'phone' => $user->phone,
            'purpose' => $request->purpose,
            'entry_time' => null, // Will be set upon entry
            'status' => 'pending',
            'visit_date' => $request->date,
            // We might want to store requested_date if different from entry_time,
            // but for simplicity we can just rely on 'created_at' as request time
            // Or assume entry_time IS the requested time?
            // The prompt says "Visit request status".
            // Let's assume the request is for "today" or "future". 
            // The migration modification made entry_time nullable.
            // I'll stick to nullable entry_time.
        ]);

        app(\App\Services\NotificationService::class)->notifyNewVisitRequest($visit->id, $request->student_id);

        return back()->with('success', 'Visit request submitted successfully.');
    }
}
