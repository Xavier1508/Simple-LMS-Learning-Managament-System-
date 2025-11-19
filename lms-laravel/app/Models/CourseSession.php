<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class CourseSession extends Model
{
    // Definisikan nama tabel secara eksplisit agar aman
    protected $table = 'course_sessions';

    protected $fillable = ['course_class_id', 'title', 'learning_outcome', 'session_number', 'start_time', 'end_time', 'delivery_mode', 'zoom_link'];

    protected $casts = [
        'start_time' => 'datetime',
        'end_time' => 'datetime',
    ];

    public function class()
    {
        return $this->belongsTo(CourseClass::class, 'course_class_id');
    }
    
    public function materials()
    {
        return $this->hasMany(CourseMaterial::class, 'course_session_id');
    }

    public function attendances()
    {
        return $this->hasMany(Attendance::class, 'course_session_id');
    }

    public function isAttendedBy($userId)
    {
        return $this->attendances()->where('user_id', $userId)->exists();
    }
}
