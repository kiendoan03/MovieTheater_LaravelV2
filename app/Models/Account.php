<?php

namespace App\Models;

use App\Enums\UserRole;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Tymon\JWTAuth\Contracts\JWTSubject;

class Account extends Authenticatable implements JWTSubject
{
    use HasFactory;

    protected $fillable = [
        'email',
        'password',
        'role',
        'is_active',
    ];

    protected $casts = [
        'role' => UserRole::class,
        'password' => 'hashed',
        // có trò tự động hash khi gán, không cần Hash::make() thủ công ngon vl ae :)))
    ];

    protected $hidden = [
        'password',
    ];

    // JWT

    // Trả về identifier dùng làm subject (sub) trong JWT payload.
    public function getJWTIdentifier(): mixed
    {
        return $this->getKey();
    }

    // Trả về các custom claims muốn đưa vào JWT (nhúng cái role vào tí còn claim)
    public function getJWTCustomClaims(): array
    {
        return [
            'role' => $this->role,
        ];
    }

    // Relationships
    public function customer()
    {
        return $this->hasOne(Customer::class, 'account_id', 'id');
    }

    public function staff()
    {
        return $this->hasOne(Staff::class, 'account_id', 'id');
    }

    public function refreshTokens()
    {
        return $this->hasMany(RefreshToken::class, 'account_id', 'id');
    }
}
