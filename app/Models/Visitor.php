<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Visitor extends Model
{
    protected $fillable = [
        'student_id',
        'visitor_name',
        'phone',
        'entry_time',
        'exit_time',
        'purpose',
        'status', // Added status
        'visit_date', // Added visit_date
    ];

    public function student()
    {
        return $this->belongsTo(User::class, 'student_id');
    }
}
