<?php

namespace App\Console\Commands;

use App\Models\RefreshToken;
use Illuminate\Console\Command;

class CleanExpiredRefreshTokens extends Command
{
    protected $signature   = 'tokens:clean-expired
                              {--days=7 : Xóa token đã revoke/hết hạn từ bao nhiêu ngày trước}';

    protected $description = 'Xóa các refresh token đã hết hạn hoặc bị revoke khỏi database';

    public function handle(): int
    {
        $days = (int) $this->option('days');

        // 1. Xóa token đã hết hạn
        $expiredCount = RefreshToken::where('expires_at', '<', now()->subDays($days))->delete();

        // 2. Xóa token đã revoke từ $days ngày trước (đã processed xong)
        $revokedCount = RefreshToken::where('is_revoked', true)
            ->where('revoked_at', '<', now()->subDays($days))
            ->delete();

        $total = $expiredCount + $revokedCount;

        $this->info("✅ Đã xóa {$total} refresh token cũ ({$expiredCount} hết hạn + {$revokedCount} đã revoke).");

        return Command::SUCCESS;
    }
}
