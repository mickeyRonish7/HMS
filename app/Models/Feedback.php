<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class Feedback extends Model
{
    protected $table = 'feedbacks';

    protected $fillable = [
        'user_id',
        'room_rating',
        'mess_rating',
        'security_rating',
        'staff_rating',
        'comments',
        'is_anonymous',
        'status',
        'admin_response',
        'reviewed_at',
    ];

    protected $casts = [
        'is_anonymous' => 'boolean',
        'reviewed_at' => 'datetime',
    ];

    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }

    /**
     * Get average rating
     */
    public function getAverageRatingAttribute(): float
    {
        $ratings = array_filter([
            $this->room_rating,
            $this->mess_rating,
            $this->security_rating,
            $this->staff_rating,
        ]);

        return count($ratings) > 0 ? round(array_sum($ratings) / count($ratings), 1) : 0;
    }

    /**
     * Scope for pending feedbacks
     */
    public function scopePending($query)
    {
        return $query->where('status', 'pending');
    }

    /**
     * Scope for reviewed feedbacks
     */
    public function scopeReviewed($query)
    {
        return $query->where('status', 'reviewed');
    }
}
