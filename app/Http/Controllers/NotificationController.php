<?php

namespace App\Http\Controllers;

use App\Models\Notification;
use App\Services\NotificationService;
use Illuminate\Http\Request;

class NotificationController extends Controller
{
    protected $notificationService;

    public function __construct(NotificationService $notificationService)
    {
        $this->notificationService = $notificationService;
    }

    /**
     * Display all notifications for the authenticated user
     */
    public function index(Request $request)
    {
        $query = auth()->user()->notifications()->latest();

        // Filter by type if provided
        if ($request->has('type') && $request->type !== 'all') {
            $query->where('type', $request->type);
        }

        // Filter by read status
        if ($request->has('status')) {
            if ($request->status === 'unread') {
                $query->whereNull('read_at');
            } elseif ($request->status === 'read') {
                $query->whereNotNull('read_at');
            }
        }

        $notifications = $query->paginate(20);
        $unreadCount = auth()->user()->unreadNotifications()->count();

        return view('notifications.index', compact('notifications', 'unreadCount'));
    }

    /**
     * Mark a notification as read
     */
    public function markAsRead($id)
    {
        $notification = Notification::where('user_id', auth()->id())
            ->findOrFail($id);

        $notification->markAsRead();

        if (request()->wantsJson()) {
            return response()->json(['success' => true]);
        }

        return redirect()->back()->with('success', 'Notification marked as read');
    }

    /**
     * Mark all notifications as read
     */
    public function markAllAsRead()
    {
        $count = $this->notificationService->markAllAsRead(auth()->id());

        if (request()->wantsJson()) {
            return response()->json([
                'success' => true,
                'count' => $count
            ]);
        }

        return redirect()->back()->with('success', "{$count} notifications marked as read");
    }

    /**
     * Delete a notification
     */
    public function destroy($id)
    {
        $notification = Notification::where('user_id', auth()->id())
            ->findOrFail($id);

        $notification->delete();

        if (request()->wantsJson()) {
            return response()->json(['success' => true]);
        }

        return redirect()->back()->with('success', 'Notification deleted');
    }

    /**
     * Get unread notifications count (for AJAX)
     */
    public function unreadCount()
    {
        $count = auth()->user()->unreadNotifications()->count();
        
        return response()->json(['count' => $count]);
    }

    /**
     * Get recent notifications (for dropdown)
     */
    public function recent()
    {
        $notifications = auth()->user()
            ->notifications()
            ->latest()
            ->take(5)
            ->get();

        return response()->json(['notifications' => $notifications]);
    }
}
