<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Room extends Model
{
    protected $fillable = ['room_number', 'capacity', 'type', 'status', 'room_photo'];

    public function beds()
    {
        return $this->hasMany(Bed::class);
    }

    public function availableBedsCount()
    {
        if ($this->relationLoaded('beds')) {
            return $this->beds->where('is_occupied', false)->count();
        }
        return $this->beds()->where('is_occupied', false)->count();
    }

    public function occupiedBedsCount()
    {
        if ($this->relationLoaded('beds')) {
            return $this->beds->where('is_occupied', true)->count();
        }
        return $this->beds()->where('is_occupied', true)->count();
    }
}
