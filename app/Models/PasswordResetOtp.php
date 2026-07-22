<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class PasswordResetOtp extends Model
{
    const UPDATED_AT = null;

    protected $fillable = [
        'email',
        'otp',
        'expires_at',
    ];

    protected $casts = [
        'expires_at' => 'datetime',
        'created_at' => 'datetime',
    ];

    public function scopeValid($query, string $email, string $otp)
    {
        return $query
            ->where('email', $email)
            ->where('otp', $otp)
            ->where('expires_at', '>', now())
            ->latest('created_at');
    }

    public static function generateOtp(): string
    {
        return str_pad((string) random_int(0, 999999), 6, '0', STR_PAD_LEFT);
    }

    public static function cleanExpired(): void
    {
        static::where('expires_at', '<', now())->delete();
    }
}
