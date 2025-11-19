<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Attendance extends Model
{
    protected $fillable = [
        'course_session_id',
        'user_id',
        'status',
        'attended_at',
        'recorded_by'
    ];

    protected $casts = [
        'attended_at' => 'datetime',
    ];

    public function session()
    {
        return $this->belongsTo(CourseSession::class, 'course_session_id');
    }

    public function user()
    {
        return $this->belongsTo(User::class);
    }
}
