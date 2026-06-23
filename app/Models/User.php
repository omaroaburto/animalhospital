<?php

namespace App\Models;

// use Illuminate\Contracts\Auth\MustVerifyEmail;

use App\Enums\Role;
use Database\Factories\UserFactory;
use Illuminate\Contracts\Auth\MustVerifyEmail;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Relations\HasOne;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Tymon\JWTAuth\Contracts\JWTSubject;
use Illuminate\Support\Str;

class User extends Authenticatable implements JWTSubject, MustVerifyEmail
{
    /** @use HasFactory<UserFactory> */
    use HasFactory, Notifiable, SoftDeletes;
    protected $fillable = [
        'email',
        'password',
        'role',
        'email_verified_at',
        'verification_token',
    ];
    protected $hidden = [
        'password',
        'remember_token'
    ];

    protected function casts(): array
    {
        return [
            'email_verified_at' => 'datetime',
            'password' => 'hashed',
            'role' => Role::class,
        ];
    }

    public function client(): HasOne
    {
        return $this->hasOne(Client::class);
    }

    public function getJWTIdentifier(): int | string
    {
        return $this->getKey();
    }
    public function getJWTCustomClaims():array
    {
        return [];
    }

    /******************************
     *   Verificación de correo   *
     ******************************/
    public function generateVerificationToken(): string
    {
        $token = Str::random(64);

        $this->update([
            'verification_token' => $token,
        ]);

        return $token;
    }

    public function markEmailAsVerified(): void
    {
        $this->update([
            'email_verified_at' => now(),
            'verification_token' => null,
        ]);
    }

    public function hasVerifiedEmailCustom(): bool
    {
        return !is_null($this->email_verified_at);
    }
}
