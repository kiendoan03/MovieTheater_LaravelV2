<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class RefreshToken extends Model
{
    use HasFactory;

    protected $fillable = [
        'id',
        'token',
        'expires_at',
        'is_revoked',
        'revoked_at',
        'replaced_by_token',
        'account_id',
    ];

    protected $casts = [
        'expires_at' => 'datetime',
        'revoked_at' => 'datetime',
    ];

    // Relationships
    public function account()
    {
        return $this->belongsTo(Account::class, 'account_id', 'id');
    }

    // Tự động hash khi lưu
    protected function token(): Attribute
    {
        return Attribute::make(
            set: fn (string $value) => hash('sha256', $value),
        );
    }

    // hash khi tìm kiếm
    public function scopeWhereToken(Builder $query, string $plainTextToken): Builder
    {
        return $query->where('token', hash('sha256', $plainTextToken));
    }
}
