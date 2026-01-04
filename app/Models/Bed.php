<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Bed extends Model
{
    protected $fillable = ['room_id', 'bed_number', 'is_occupied'];

    public function room()
    {
        return $this->belongsTo(Room::class);
    }

    public function student()
    {
        return $this->hasOne(User::class, 'bed_id');
    }
}
