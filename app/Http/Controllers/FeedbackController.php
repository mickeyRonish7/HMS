<?php

namespace App\Http\Controllers;

use App\Models\Feedback;
use Illuminate\Http\Request;
use App\Http\Requests\StoreFeedbackRequest;
use Illuminate\Support\Facades\DB;

class FeedbackController extends Controller
{
    /**
     * Show feedback submission form
     */
    public function create()
    {
        return view('feedback.create');
    }

    /**
     * Store feedback
     */
    public function store(StoreFeedbackRequest $request)
    {
        // Validation and role check are handled in StoreFeedbackRequest
        
        $data = $request->only([
            'room_rating',
            'mess_rating',
            'security_rating',
            'staff_rating',
            'comments',
        ]);

        $data['is_anonymous'] = $request->has('is_anonymous');
        $data['user_id'] = $data['is_anonymous'] ? null : auth()->id();
        $data['status'] = 'pending';

        $feedback = Feedback::create($data);

        app(\App\Services\NotificationService::class)->notifyNewFeedback($feedback->id, auth()->id());

        return redirect()->back()->with('success', __('Feedback submitted successfully'));
    }

    /**
     * Admin: View all feedbacks
     */
    public function index(Request $request)
    {
        $query = Feedback::with('user')->latest();

        // Filter by status
        if ($request->has('status') && $request->status !== 'all') {
            $query->where('status', $request->status);
        }

        // Filter by rating
        if ($request->has('min_rating') && $request->min_rating) {
            $minRating = (int) $request->min_rating;
            $query->where(function($q) use ($minRating) {
                $q->where('room_rating', '>=', $minRating)
                  ->orWhere('mess_rating', '>=', $minRating)
                  ->orWhere('security_rating', '>=', $minRating)
                  ->orWhere('staff_rating', '>=', $minRating);
            });
        }

        // Search in comments
        if ($request->has('search') && $request->search) {
            $query->where('comments', 'like', '%' . $request->search . '%');
        }

        $feedbacks = $query->paginate(20);

        return view('admin.feedback.index', compact('feedbacks'));
    }

    /**
     * Admin: View feedback analytics
     */
    public function analytics()
    {
        $totalFeedbacks = Feedback::count();
        
        $averageRatings = [
            'room' => round(Feedback::whereNotNull('room_rating')->avg('room_rating'), 1),
            'mess' => round(Feedback::whereNotNull('mess_rating')->avg('mess_rating'), 1),
            'security' => round(Feedback::whereNotNull('security_rating')->avg('security_rating'), 1),
            'staff' => round(Feedback::whereNotNull('staff_rating')->avg('staff_rating'), 1),
        ];

        // Get rating distribution
        $ratingDistribution = [
            'room' => $this->getRatingDistribution('room_rating'),
            'mess' => $this->getRatingDistribution('mess_rating'),
            'security' => $this->getRatingDistribution('security_rating'),
            'staff' => $this->getRatingDistribution('staff_rating'),
        ];

        // Get feedback trends (last 6 months)
        $trends = Feedback::select(
                DB::raw('DATE_FORMAT(created_at, "%Y-%m") as month'),
                DB::raw('COUNT(*) as count'),
                DB::raw('AVG((room_rating + mess_rating + security_rating + staff_rating) / 4) as avg_rating')
            )
            ->where('created_at', '>=', now()->subMonths(6))
            ->groupBy('month')
            ->orderBy('month')
            ->get();

        // Calculate General Satisfaction (Bucket Average of all ratings)
        $satisfactionRaw = Feedback::select(
            DB::raw('
                CASE 
                    WHEN (COALESCE(room_rating,0) + COALESCE(mess_rating,0) + COALESCE(security_rating,0) + COALESCE(staff_rating,0)) / 4 >= 4 THEN "Excellent"
                    WHEN (COALESCE(room_rating,0) + COALESCE(mess_rating,0) + COALESCE(security_rating,0) + COALESCE(staff_rating,0)) / 4 >= 3 THEN "Good"
                    WHEN (COALESCE(room_rating,0) + COALESCE(mess_rating,0) + COALESCE(security_rating,0) + COALESCE(staff_rating,0)) / 4 >= 2 THEN "Average"
                    ELSE "Poor"
                END as satisfaction_level
            '),
            DB::raw('COUNT(*) as count')
        )
        ->groupBy('satisfaction_level')
        ->pluck('count', 'satisfaction_level');

        // Ensure all keys exist
        $satisfactionDistribution = [
            'Excellent' => $satisfactionRaw['Excellent'] ?? 0,
            'Good' => $satisfactionRaw['Good'] ?? 0,
            'Average' => $satisfactionRaw['Average'] ?? 0,
            'Poor' => $satisfactionRaw['Poor'] ?? 0,
        ];

        // Status breakdown
        $statusBreakdown = Feedback::select('status', DB::raw('COUNT(*) as count'))
            ->groupBy('status')
            ->pluck('count', 'status');

        return view('admin.feedback.analytics', compact(
            'totalFeedbacks',
            'averageRatings',
            'ratingDistribution',
            'trends',
            'statusBreakdown',
            'satisfactionDistribution'
        ));
    }

    /**
     * Admin: Update feedback status
     */
    public function updateStatus(Request $request, $id)
    {
        $request->validate([
            'status' => 'required|in:pending,reviewed,resolved',
            'admin_response' => 'nullable|string|max:1000',
        ]);

        $feedback = Feedback::findOrFail($id);
        
        $feedback->update([
            'status' => $request->status,
            'admin_response' => $request->admin_response,
            'reviewed_at' => now(),
        ]);

        return redirect()->back()->with('success', 'Feedback status updated successfully');
    }

    /**
     * Helper: Get rating distribution for a category
     */
    private function getRatingDistribution($column)
    {
        return Feedback::select($column, DB::raw('COUNT(*) as count'))
            ->whereNotNull($column)
            ->groupBy($column)
            ->orderBy($column)
            ->pluck('count', $column)
            ->toArray();
    }
}
