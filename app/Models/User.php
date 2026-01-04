<?php

namespace App\Models;

// use Illuminate\Contracts\Auth\MustVerifyEmail;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;

class User extends Authenticatable
{
    /** @use HasFactory<\Database\Factories\UserFactory> */
    use HasFactory, Notifiable;

    /**
     * The attributes that are mass assignable.
     *
     * @var list<string>
     */
    protected $fillable = [
        'name',
        'email',
        'password',
        'role', // admin, warden, student
        'phone',
        'parent_phone',
        'address',
        'year',
        'department',
        'semester',
        'is_approved',
        'student_approved',
        'admin_approved',
        'profile_photo_path',
        'bed_id',
        'student_id_number',
        'locale',
        'theme',
        'font_size',
        'id_card_issued_date',
        'id_card_expiry_date',
        'blood_group',
        'emergency_contact',
        'emergency_contact_name',
    ];

    /**
     * The attributes that should be hidden for serialization.
     *
     * @var list<string>
     */
    protected $hidden = [
        'password',
        'remember_token',
    ];

    /**
     * Get the attributes that should be cast.
     *
     * @return array<string, string>
     */
    protected function casts(): array
    {
        return [
            'email_verified_at' => 'datetime',
            'password' => 'hashed',
        ];
    }

    // Relationships
    public function bed()
    {
        return $this->belongsTo(Bed::class);
    }

    public function complaints()
    {
        return $this->hasMany(Complaint::class, 'student_id');
    }

    public function leaves()
    {
        return $this->hasMany(Leave::class, 'student_id');
    }

    public function fees()
    {
        return $this->hasMany(Fee::class, 'student_id');
    }

    public function visitors()
    {
        return $this->hasMany(Visitor::class, 'student_id');
    }

    public function notices()
    {
        // helper to get relevant notices
        if ($this->role === 'student') {
            return Notice::whereIn('audience', ['all', 'students'])->get();
        }
        return Notice::all();
    }

    public function notifications()
    {
        return $this->hasMany(Notification::class);
    }

    public function feedbacks()
    {
        return $this->hasMany(Feedback::class);
    }

    public function roomRequests()
    {
        return $this->hasMany(RoomRequest::class);
    }

    public function unreadNotifications()
    {
        return $this->hasMany(Notification::class)->whereNull('read_at');
    }
}
