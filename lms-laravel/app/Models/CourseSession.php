<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

/**
 * @property int $id
 * @property int $course_class_id
 * @property CourseClass $class
 * @property \Illuminate\Database\Eloquent\Collection|CourseMaterial[] $materials
 * @property \Illuminate\Database\Eloquent\Collection|Attendance[] $attendances
 */
class CourseSession extends Model
{
    // Definisikan nama tabel secara eksplisit agar aman
    protected $table = 'course_sessions';

    protected $fillable = [
        'course_class_id', 'title', 'learning_outcome', 'session_number',
        'start_time', 'end_time', 'delivery_mode', 'zoom_link'
    ];

    protected $casts = [
        'start_time' => 'datetime',
        'end_time' => 'datetime',
    ];

    // Relasi ke CourseClass
    public function class(): BelongsTo
    {
        return $this->belongsTo(CourseClass::class, 'course_class_id');
    }

    // Relasi ke CourseMaterial
    public function materials(): HasMany
    {
        return $this->hasMany(CourseMaterial::class, 'course_session_id');
    }

    // Relasi ke Attendance
    public function attendances(): HasMany
    {
        return $this->hasMany(Attendance::class, 'course_session_id');
    }

    // Helper: Cek kehadiran user
    public function isAttendedBy(int|string $userId): bool
    {
        return $this->attendances()->where('user_id', $userId)->exists();
    }
}
