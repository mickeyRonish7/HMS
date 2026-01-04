<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Leave extends Model
{
    protected $fillable = ['student_id', 'start_date', 'end_date', 'reason', 'status', 'admin_remark'];

    public function student()
    {
        return $this->belongsTo(User::class, 'student_id');
    }
}
