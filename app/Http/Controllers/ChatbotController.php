<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class ChatbotController extends Controller
{
    public function ask(Request $request)
    {
        $request->validate([
            'message' => 'required|string',
        ]);

        $message = strtolower(trim($request->message));
        $user = Auth::user();

        $response = "I'm sorry, I didn't understand that. You can ask about your 'room', 'fees', 'attendance', or 'rules'.";

        // Logic for Students
        if ($user->role === 'student') {
            if (str_contains($message, 'room') || str_contains($message, 'bed')) {
                if ($user->bed) {
                    $response = "You are assigned to Room **" . $user->bed->room->room_number . "**, Bed **" . $user->bed->bed_number . "**.";
                } else {
                    $response = "You have not been assigned a room yet. Please contact the warden.";
                }
            }
            // Fees Logic
            elseif (str_contains($message, 'fee') || str_contains($message, 'payment') || str_contains($message, 'due')) {
                $pendingFees = $user->fees()->where('status', 'pending')->sum('amount');
                if ($pendingFees > 0) {
                    $response = "You have a total pending fee of **Rs. " . number_format($pendingFees, 2) . "**. You can pay this in the 'Fees' section.";
                } else {
                    $response = "Great news! You have no pending fees.";
                }
            }
            // Attendance Logic
            elseif (str_contains($message, 'attendance') || str_contains($message, 'present')) {
                 $todayAttendance = \App\Models\Attendance::where('student_id', $user->id)
                                    ->where('date', \Carbon\Carbon::today())
                                    ->first();
                if ($todayAttendance) {
                    $response = "You have been marked **Present** today. Time In: " . \Carbon\Carbon::parse($todayAttendance->time_in)->format('h:i A');
                } else {
                    $response = "You have **not** been marked present today yet.";
                }
            }
            // Complaint Logic
            elseif (str_contains($message, 'complaint') || str_contains($message, 'issue') || str_contains($message, 'report')) {
                $response = "You can submit a new complaint or track existing ones <a href='" . route('student.complaints.create') . "' class='text-blue-500 underline'>here</a>.";
            }
        } 
        
        // General Logic (For all users)
        if (str_contains($message, 'rule')) {
            $response = "<strong>Hostel Rules:</strong><br>1. Curfew is at 10 PM.<br>2. No loud music after 9 PM.<br>3. Keep your room clean.<br>4. Visitors are allowed only in the visiting area.";
        }
        elseif (str_contains($message, 'wifi') || str_contains($message, 'internet')) {
            $response = "The Wi-Fi SSID is **Hostel_Student**. The password is **Learning@2025**.";
        }
        elseif (str_contains($message, 'warden') || str_contains($message, 'contact')) {
            $response = "The Warden is **Mr. Sharma**. <br>Phone: 9800000000 <br>Office Hours: 4 PM - 6 PM.";
        }
        elseif (str_contains($message, 'hi') || str_contains($message, 'hello') || str_contains($message, 'hey')) {
            $response = "Hello " . $user->name . "! How can I assist you today?";
        }
        elseif (str_contains($message, 'bye') || str_contains($message, 'goodbye')) {
             $response = "Goodbye! Have a great day!";
        }

        return response()->json(['reply' => $response]);
    }
}
