<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Schedule extends Model
{
    use HasFactory;

    protected $fillable = [
        'start_time',
        'end_time',
        'room_id',
        'movie_id',
    ];

    protected $casts = [
        'start_time' => 'datetime',
        'end_time' => 'datetime',
    ];

    // Relationships
    public function movie()
    {
        return $this->belongsTo(Movie::class);
    }

    public function room()
    {
        return $this->belongsTo(Room::class);
    }

    public function bookings()
    {
        return $this->hasMany(Booking::class);
    }
}
