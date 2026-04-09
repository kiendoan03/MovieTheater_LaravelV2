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
}
