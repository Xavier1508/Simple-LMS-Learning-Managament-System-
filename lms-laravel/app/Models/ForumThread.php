<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Carbon\Carbon;

class ForumThread extends Model
{
    protected $fillable = [
        'course_session_id', 'user_id', 'title', 'content',
        'is_hidden', 'is_assessment', 'deadline_at',
        'attachment_path', 'attachment_name', 'attachment_type'
    ];

    protected $casts = [
        'is_hidden' => 'boolean',
        'is_assessment' => 'boolean',
        'deadline_at' => 'datetime',
    ];

    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function session()
    {
        return $this->belongsTo(CourseSession::class, 'course_session_id');
    }

    public function posts()
    {
        return $this->hasMany(ForumPost::class);
    }

    // Helper: Apakah sudah deadline?
    public function isLocked()
    {
        if (!$this->is_assessment || !$this->deadline_at) return false;
        return Carbon::now()->gt($this->deadline_at);
    }
}
