<?php

namespace App\Models;

use Illuminate\Contracts\Auth\MustVerifyEmail;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;

class User extends Authenticatable implements MustVerifyEmail
{
    use HasFactory, Notifiable;

    protected $fillable = [
        'first_name',
        'last_name',
        'email',
        'phone_number',
        'password',
        'role',
        'lecturer_code',
        'private_number',
        'otp_code',
        'otp_expires_at',
    ];

    protected $hidden = [
        'password',
        'remember_token',
        'private_number',
    ];

    protected function casts(): array
    {
        return [
            'email_verified_at' => 'datetime',
            'password' => 'hashed',
            'otp_expires_at' => 'datetime',
        ];
    }

    // Relasi Dosen
    public function teachingClasses()
    {
        return $this->hasMany(CourseClass::class, 'lecturer_id');
    }

    // Relasi Siswa
    public function enrolledClasses()
    {
        return $this->belongsToMany(CourseClass::class, 'enrollments', 'user_id', 'course_class_id');
    }
}
