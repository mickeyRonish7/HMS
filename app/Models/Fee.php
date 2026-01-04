<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Fee extends Model
{
    protected $fillable = ['student_id', 'amount', 'due_date', 'status', 'type'];

    public function student()
    {
        return $this->belongsTo(User::class, 'student_id');
    }

    public function payments()
    {
        return $this->hasMany(Payment::class);
    }
}
