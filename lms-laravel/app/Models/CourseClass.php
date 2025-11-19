<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class CourseClass extends Model
{
    protected $fillable = ['course_id', 'lecturer_id', 'class_code', 'semester', 'type'];

    public function course()
    {
        return $this->belongsTo(Course::class);
    }

    public function lecturer()
    {
        return $this->belongsTo(User::class, 'lecturer_id');
    }

    public function students()
    {
        return $this->belongsToMany(User::class, 'enrollments', 'course_class_id', 'user_id');
    }

    // PERBAIKAN: Relasi ke CourseSession
    public function sessions()
    {
        return $this->hasMany(CourseSession::class);
    }
}
