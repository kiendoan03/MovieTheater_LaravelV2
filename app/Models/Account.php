<?php

namespace App\Models;

use App\Enums\UserRole;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Account extends Model
{
    use HasFactory;

    protected $fillable = [
        'username',
        'password',
        'role',
    ];

    protected $casts = [
        'role'     => UserRole::class,
        'password' => 'hashed',        // tự động hash khi gán, không cần Hash::make() thủ công
    ];

    protected $hidden = [
        'password',
    ];

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
