<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;

class User extends Authenticatable
{
    use HasFactory, Notifiable;

    protected $fillable = [
        'name', 'email', 'phone', 'password', 'role',
        'avatar', 'is_active', 'otp', 'otp_expires_at',
    ];

    protected $hidden = ['password', 'remember_token', 'otp'];

    protected $casts = [
        'email_verified_at' => 'datetime',
        'otp_expires_at' => 'datetime',
        'password' => 'hashed',
        'is_active' => 'boolean',
    ];

    public function isAdmin(): bool { return $this->role === 'admin'; }
    public function isStudent(): bool { return $this->role === 'student'; }
    public function isTeacher(): bool { return $this->role === 'teacher'; }
    public function isParent(): bool { return $this->role === 'parent'; }

    public function student() { return $this->hasOne(Student::class); }
    public function teacher() { return $this->hasOne(Teacher::class); }
    public function parent() { return $this->hasOne(ParentModel::class); }
    public function notifications() { return $this->hasMany(Notification::class); }

    public function generateOtp(): string {
        $otp = str_pad(random_int(0, 999999), 6, '0', STR_PAD_LEFT);
        $this->update(['otp' => $otp, 'otp_expires_at' => now()->addMinutes(10)]);
        return $otp;
    }

    public function isOtpValid(string $otp): bool {
        return $this->otp === $otp && $this->otp_expires_at && $this->otp_expires_at->isFuture();
    }
}
