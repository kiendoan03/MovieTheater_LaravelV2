<?php

namespace App\Models;

use App\Enums\BookingStatus;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Ticket extends Model
{
    use HasFactory;

    protected $fillable = [
        'code',
        'final_price',
        'customer_id',
        'staff_id',
    ];

    // Relationships
    public function customer()
    {
        return $this->belongsTo(Customer::class);
    }

    public function staff()
    {
        return $this->belongsTo(Staff::class);
    }

    public function bookings()
    {
        return $this->hasMany(Booking::class);
    }

    // Helper Methods
    /**
     * Get ticket total amount formatted
     */
    public function getTotalFormatted(): string
    {
        return number_format($this->final_price, 0, ',', '.').' VNĐ';
    }

    /**
     * Get seat details from bookings
     */
    public function getSeatsAttribute()
    {
        return $this->bookings()
            ->with('seat')
            ->get()
            ->map(fn ($b) => "{$b->seat->row}{$b->seat->column}")
            ->join(', ');
    }

    /**
     * Check if all bookings are reserved (paid).
     * Uses the already-loaded "bookings" collection to avoid extra queries.
     */
    public function isFullyPaid(): bool
    {
        $bookings = $this->relationLoaded('bookings')
            ? $this->bookings
            : $this->bookings()->get();

        if ($bookings->isEmpty()) {
            return false;
        }

        return $bookings->every(
            fn ($b) => $b->status === BookingStatus::Reserved
        );
    }
}
