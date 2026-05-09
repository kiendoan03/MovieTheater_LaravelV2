<?php

namespace App\Events;

use Illuminate\Broadcasting\Channel;
use Illuminate\Broadcasting\InteractsWithSockets;
use Illuminate\Broadcasting\PresenceChannel;
use Illuminate\Broadcasting\PrivateChannel;
use Illuminate\Contracts\Broadcasting\ShouldBroadcast;
use Illuminate\Foundation\Events\Dispatchable;
use Illuminate\Queue\SerializesModels;

class SeatStatusChanged implements ShouldBroadcast
{
    use Dispatchable, InteractsWithSockets, SerializesModels;

    public $scheduleId;
    public $seatId;
    public $status;
    public $staffId;

    public function __construct($scheduleId, $seatId, $status, $staffId)
    {
        $this->scheduleId = $scheduleId;
        $this->seatId = $seatId;
        $this->status = $status;
        $this->staffId = $staffId;
    }

    public function broadcastOn(): array
    {
        return [
            new Channel('schedule.' . $this->scheduleId),
        ];
    }

    public function broadcastAs(): string
    {
        return 'seat.status.changed';
    }

    public function broadcastWith(): array
    {
        return [
            'seat_id' => $this->seatId,
            'status' => $this->status,
            'staff_id' => $this->staffId,
        ];
    }
}
