<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Complaint extends Model
{
    protected $fillable = ['student_id', 'category', 'description', 'status', 'admin_remark'];

    public function student()
    {
        return $this->belongsTo(User::class, 'student_id');
    }
}
