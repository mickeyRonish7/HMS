<?php

namespace App\Http\Controllers;

use App\Models\Attendance;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Carbon\Carbon;

class AttendanceController extends Controller
{
    // Admin: View all attendance
    public function index(Request $request)
    {
        $query = Attendance::with('student');

        if ($request->date) {
            $query->whereDate('date', $request->date);
        } else {
            $query->whereDate('date', Carbon::today());
        }

        $attendances = $query->latest()->get();

        return view('admin.attendance.index', compact('attendances'));
    }

    // Student: View own attendance
    public function studentIndex()
    {
        $attendances = Attendance::where('student_id', Auth::id())->latest()->get();
        return view('student.attendance.index', compact('attendances'));
    }

    // Simulated Biometric/Manual Entry (Called by Admin or System)
    // For this demo, we'll allow Admins to manually log entry/exit for students
    public function store(Request $request)
    {
        $request->validate([
            'student_id' => 'required|exists:users,id',
            'type' => 'required|in:in,out',
        ]);

        $student = User::find($request->student_id);
        $today = Carbon::today();
        
        $attendance = Attendance::firstOrCreate(
            ['student_id' => $student->id, 'date' => $today],
            ['status' => 'present']
        );

        $now = Carbon::now();

        if ($request->type == 'in') {
            $attendance->time_in = $now->format('H:i:s');
            $message = "Student {$student->name} checked IN at {$now->format('h:i A')}.";
        } else {
            $attendance->time_out = $now->format('H:i:s');
            $message = "Student {$student->name} checked OUT at {$now->format('h:i A')}.";
        }

        $attendance->save();

        // Simulate WhatsApp Notification
        if ($student->parent_phone) {
            $this->sendWhatsAppNotification($student->parent_phone, $message);
        }

        return back()->with('success', 'Attendance logged successfully. ' . ($student->parent_phone ? 'WhatsApp notification sent.' : ''));
    }

    // Admin: Export to PDF
    public function export()
    {
        $attendances = Attendance::with('student')->latest()->get();
        // Use Barryvdh\DomPDF\Facade\Pdf
        $pdf = \Barryvdh\DomPDF\Facade\Pdf::loadView('admin.attendance.pdf', compact('attendances'));
        return $pdf->download('attendance_report.pdf');
    }

    private function sendWhatsAppNotification($phone, $message)
    {
        // Simulation Logic
        // In a real app, this would use Twilio or a similar API
        // For now, we assume it "works"
        return true;
    }
}
