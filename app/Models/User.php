<?php

namespace App\Models;

// use Illuminate\Contracts\Auth\MustVerifyEmail;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Laravel\Sanctum\HasApiTokens;

class User extends Authenticatable
{
    use HasApiTokens, HasFactory, Notifiable;

    /**
     * The attributes that are mass assignable.
     *
     * @var array<int, string>
     */
    protected $fillable = [
        'name',
        'email',
        'password',
        'role',
    ];

    /**
     * The attributes that should be hidden for serialization.
     *
     * @var array<int, string>
     */
    protected $hidden = [
        'password',
        'remember_token',
    ];

    /**
     * The attributes that should be cast.
     *
     * @var array<string, string>
     */
    protected $casts = [
        'email_verified_at' => 'datetime',
        'password' => 'hashed',
    ];

    /**
     * Label readable untuk Peran User.
     */
    public function getRoleLabelAttribute(): string
    {
        return match($this->role) {
            'admin' => 'Super Admin',
            'hakim' => 'Hakim',
            'jsp_pp' => 'JSP / PP',
            'ptsp' => 'PTSP',
            default => ucfirst($this->role ?? 'User'),
        };
    }

    public function isAdmin(): bool
    {
        return $this->role === 'admin';
    }

    public function isHakim(): bool
    {
        return $this->role === 'hakim';
    }

    public function isJspPp(): bool
    {
        return $this->role === 'jsp_pp';
    }

    public function isPtsp(): bool
    {
        return $this->role === 'ptsp';
    }
}
