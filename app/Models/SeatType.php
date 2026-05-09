<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class SeatType extends Model
{
    use HasFactory;

    protected $fillable = [
        'type',
        'price',
        'color',
        'is_couple',
    ];

    // Relationships
    public function seats()
    {
        return $this->hasMany(Seat::class, 'type_id');
    }
}
