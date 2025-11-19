<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Attendance extends Model
{
    // GANTI: session_id -> course_session_id
    protected $fillable = ['course_session_id', 'user_id', 'attended_at', 'status'];

    protected $casts = [
        'attended_at' => 'datetime',
    ];

    public function session()
    {
        return $this->belongsTo(CourseSession::class, 'course_session_id');
    }
}
