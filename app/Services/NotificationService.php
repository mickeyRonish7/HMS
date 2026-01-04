<?php

namespace App\Services;

use App\Models\Notification;
use App\Models\User;

class NotificationService
{
    /**
     * Create a notification for a specific user
     */
    public function notifyUser(
        int $userId,
        string $type,
        string $title,
        string $message,
        ?array $data = null,
        ?string $actionUrl = null
    ): Notification {
        return Notification::create([
            'user_id' => $userId,
            'type' => $type,
            'title' => $title,
            'message' => $message,
            'data' => $data,
            'action_url' => $actionUrl,
        ]);
    }

    /**
     * Create notifications for all users with a specific role
     */
    public function notifyRole(
        string $role,
        string $type,
        string $title,
        string $message,
        ?array $data = null,
        ?string $actionUrl = null
    ): int {
        $users = User::where('role', $role)->get();
        $count = 0;

        foreach ($users as $user) {
            $this->notifyUser($user->id, $type, $title, $message, $data, $actionUrl);
            $count++;
        }

        return $count;
    }

    /**
     * Send late return alert to a student
     */
    public function notifyLateReturn(int $userId, ?string $returnTime = null): Notification
    {
        $message = $returnTime 
            ? "You returned late at {$returnTime}. Please ensure to return before curfew time."
            : "You have been marked for late return. Please ensure to return before curfew time.";

        return $this->notifyUser(
            $userId,
            'late_return',
            __('Late Return Alert'),
            $message,
            ['return_time' => $returnTime],
            route('student.attendance')
        );
    }

    /**
     * Notify admin about new room request
     */
    public function notifyNewRoomRequest(int $requestId, int $studentId): int
    {
        $student = User::find($studentId);
        
        return $this->notifyRole(
            'admin',
            'room_request',
            __('New Room Request'),
            "New room request from {$student->name}",
            ['request_id' => $requestId, 'student_id' => $studentId],
            route('admin.room-requests.index')
        );
    }

    /**
     * Notify student about room request status
     */
    public function notifyRoomRequestStatus(
        int $userId,
        string $status,
        ?string $adminNotes = null
    ): Notification {
        $title = $status === 'approved' 
            ? __('Request Approved')
            : __('Request Rejected');

        $message = $status === 'approved'
            ? "Your room request has been approved."
            : "Your room request has been rejected.";

        if ($adminNotes) {
            $message .= " Admin notes: {$adminNotes}";
        }

        return $this->notifyUser(
            $userId,
            'room_request_status',
            $title,
            $message,
            ['status' => $status, 'admin_notes' => $adminNotes],
            route('student.room-requests')
        );
    }

    /**
     * Notify about new fee assignment
     */
    public function notifyNewFee(int $userId, float $amount, string $dueDate): Notification
    {
        return $this->notifyUser(
            $userId,
            'fee',
            'New Fee Assigned',
            "A new fee of Rs. {$amount} has been assigned. Due date: {$dueDate}",
            ['amount' => $amount, 'due_date' => $dueDate],
            route('student.fees')
        );
    }

    /**
     * Notify about complaint status update
     */
    public function notifyComplaintStatus(
        int $userId,
        int $complaintId,
        string $status
    ): Notification {
        $statusText = ucfirst($status);
        
        return $this->notifyUser(
            $userId,
            'complaint_status',
            'Complaint Status Updated',
            "Your complaint has been marked as {$statusText}",
            ['complaint_id' => $complaintId, 'status' => $status],
            route('student.complaints.index')
        );
    }

    /**
     * Notify about new notice
     */
    public function notifyNewNotice(string $audience, string $title): int
    {
        if ($audience === 'all') {
            // Notify all roles besides visitors? 
            // Usually admins, wardens, students.
            $count = 0;
            $count += $this->notifyRole('student', 'notice', 'New Notice Posted', "A new notice has been posted: {$title}", null, route('dashboard'));
            $count += $this->notifyRole('warden', 'notice', 'New Notice Posted', "A new notice has been posted: {$title}", null, route('dashboard'));
            return $count;
        } else {
            // Map audience to role (e.g., 'students' -> 'student', 'wardens' -> 'warden')
            $role = match($audience) {
                'students' => 'student',
                'wardens' => 'warden',
                default => 'student'
            };

            return $this->notifyRole(
                $role,
                'notice',
                'New Notice Posted',
                "A new notice has been posted: {$title}",
                null,
                route('dashboard')
            );
        }
    }

    /**
     * Mark all notifications as read for a user
     */
    public function markAllAsRead(int $userId): int
    {
        return Notification::where('user_id', $userId)
            ->whereNull('read_at')
            ->update(['read_at' => now()]);
    }

    /**
     * Notify admin about new visitor registration
     */
    public function notifyNewVisitorRegistration(int $visitorId): int
    {
        $visitor = User::find($visitorId);
        
        return $this->notifyRole(
            'admin',
            'visitor',
            __('New Visitor Registration'),
            "New visitor account registration: {$visitor->name}. Awaiting approvals.",
            ['visitor_id' => $visitorId],
            route('admin.users.pending')
        );
    }

    /**
     * Notify admin that a student has approved a visitor
     */
    public function notifyStudentVisitorApproval(int $visitorId, int $studentId): int
    {
        $visitor = User::find($visitorId);
        $student = User::find($studentId);
        
        return $this->notifyRole(
            'admin',
            'visitor',
            __('Student Approved Visitor'),
            "Student {$student->name} has approved visitor {$visitor->name}. Admin approval still required.",
            ['visitor_id' => $visitorId, 'student_id' => $studentId],
            route('admin.users.pending')
        );
    }

    /**
     * Notify student about a new visit request
     */
    public function notifyNewVisitRequest(int $visitId, int $studentId): Notification
    {
        $visit = \App\Models\Visitor::find($visitId);
        
        return $this->notifyUser(
            $studentId,
            'visitor',
            __('New Visit Request'),
            "New visit request from {$visit->visitor_name} on {$visit->visit_date}.",
            ['visit_id' => $visitId],
            route('student.visitors.pending')
        );
    }

    /**
     * Notify visitor about their visit request status
     */
    public function notifyVisitRequestStatus(int $visitId, string $status): Notification
    {
        $visit = \App\Models\Visitor::find($visitId);
        
        $title = $status === 'approved' 
            ? __('Visit Approved')
            : __('Visit Rejected');

        $message = $status === 'approved'
            ? "Your visit request for {$visit->visit_date} has been approved."
            : "Your visit request for {$visit->visit_date} has been rejected.";

        return $this->notifyUser(
            $visit->user_id ?? 0, // Fallback if no user linked
            'visitor',
            $title,
            $message,
            ['visit_id' => $visitId, 'status' => $status],
            route('dashboard')
        );
    }

    /**
     * Notify admin about new feedback
     */
    public function notifyNewFeedback(int $feedbackId, int $studentId): int
    {
        $student = User::find($studentId);
        $name = $student ? $student->name : 'Anonymous';
        
        return $this->notifyRole(
            'admin',
            'info',
            __('New Feedback Received'),
            "New feedback has been submitted by {$name}.",
            ['feedback_id' => $feedbackId],
            route('admin.feedback.index')
        );
    }

    /**
     * Notify admin about new complaint
     */
    public function notifyNewComplaint(int $complaintId, int $studentId): int
    {
        $student = User::find($studentId);
        
        return $this->notifyRole(
            'admin',
            'warning',
            __('New Complaint Received'),
            "Student {$student->name} has submitted a new complaint.",
            ['complaint_id' => $complaintId],
            route('admin.complaints.index')
        );
    }
}
