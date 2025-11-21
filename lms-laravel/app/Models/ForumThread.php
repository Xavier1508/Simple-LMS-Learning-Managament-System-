<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Carbon\Carbon;

/**
 * @property int $id
 * @property int $course_session_id
 * @property int $user_id
 * @property CourseSession $session
 * @property User $user
 * @property \Illuminate\Database\Eloquent\Collection|ForumPost[] $posts
 */
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

    // Relasi ke User (Pembuat Thread)
    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }

    // Relasi ke CourseSession
    public function session(): BelongsTo
    {
        return $this->belongsTo(CourseSession::class, 'course_session_id');
    }

    // Relasi ke Postingan/Balasan
    public function posts(): HasMany
    {
        return $this->hasMany(ForumPost::class);
    }

    // Helper: Apakah sudah deadline?
    public function isLocked(): bool
    {
        if (!$this->is_assessment || !$this->deadline_at) {
            return false;
        }
        return Carbon::now()->gt($this->deadline_at);
    }
}
