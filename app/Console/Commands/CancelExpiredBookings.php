<?php

namespace App\Console\Commands;

use App\Enums\BookingStatus;
use App\Events\SeatStatusChanged;
use App\Models\Booking;
use Carbon\Carbon;
use Illuminate\Console\Command;
use Illuminate\Support\Facades\Log;

class CancelExpiredBookings extends Command
{
    protected $signature = 'bookings:cancel-expired 
                            {--minutes=5 : Số phút để hủy đặt ghế}';

    protected $description = 'Hủy các đặt ghế quá hạn thanh toán (mặc định 5 phút)';

    public function handle(): int
    {
        $minutes = (int) $this->option('minutes');
        
        $this->info("Đang kiểm tra các đặt ghế quá {$minutes} phút...");

        // Tìm tất cả bookings có status = Booked (1) và đã quá thời gian (dùng updated_at)
        $expiredBookings = Booking::where('status', BookingStatus::Booked->value)
            ->where('updated_at', '<=', Carbon::now()->subMinutes($minutes))
            ->get();

        if ($expiredBookings->isEmpty()) {
            $this->info('Không có đặt ghế nào quá hạn.');
            return Command::SUCCESS;
        }

        $this->info("Tìm thấy {$expiredBookings->count()} đặt ghế quá hạn.");

        foreach ($expiredBookings as $booking) {
            // Reset về available
            $booking->update([
                'status' => BookingStatus::Available->value,
                'ticket_id' => null,
                'staff_id' => null,
                'customer_id' => null,
            ]);

            // Broadcast event để cập nhật realtime
            broadcast(new SeatStatusChanged(
                $booking->schedule_id,
                $booking->seat_id,
                BookingStatus::Available->value,
                null
            ));

            Log::info('Expired booking cancelled', [
                'booking_id' => $booking->id,
                'seat_id' => $booking->seat_id,
                'schedule_id' => $booking->schedule_id,
            ]);

            $this->line("Đã hủy đặt ghế ID: {$booking->id}");
        }

        $this->info("Đã hủy {$expiredBookings->count()} đặt ghế quá hạn.");

        return Command::SUCCESS;
    }
}