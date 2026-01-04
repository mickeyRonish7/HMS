<?php

namespace App\Http\Controllers;

use App\Models\Visitor;
use App\Models\User;
use Illuminate\Http\Request;
use Carbon\Carbon;

class VisitorController extends Controller
{
    public function index()
    {
        $visitors = Visitor::with('student')->latest()->get();
        return view('admin.visitors.index', compact('visitors'));
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'student_id' => 'required|exists:users,id',
            'visitor_name' => 'required|string',
            'phone' => 'nullable|string',
            'purpose' => 'nullable|string',
        ]);

        Visitor::create([
            'student_id' => $validated['student_id'],
            'visitor_name' => $validated['visitor_name'],
            'phone' => $validated['phone'] ?? null,
            'purpose' => $validated['purpose'] ?? null,
            'entry_time' => Carbon::now(),
        ]);

        return back()->with('success', 'Visitor logged in successfully.');
    }

    public function update(Request $request, Visitor $visitor)
    {
        // Mark exit
        $visitor->update([
            'exit_time' => Carbon::now(),
        ]);

        return back()->with('success', 'Visitor logged out successfully.');
    }
}
