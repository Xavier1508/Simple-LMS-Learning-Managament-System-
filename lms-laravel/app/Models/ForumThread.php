<?php

namespace App\Models;

use Carbon\Carbon;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

/**
 * @property int $id
 * @property int $course_session_id
 * @property int $user_id
 * @property string $title
 * @property string $content
 * @property bool $is_hidden
 * @property bool $is_assessment
 * @property \Illuminate\Support\Carbon|null $deadline_at
 * @property string|null $attachment_path
 * @property string|null $attachment_name
 * @property string|null $attachment_type
 * @property CourseSession $session
 * @property User $user
 * @property \Illuminate\Database\Eloquent\Collection|ForumPost[] $posts
 */
class ForumThread extends Model
{
    protected $fillable = [
        'course_session_id', 'user_id', 'title', 'content',
        'is_hidden', 'is_assessment', 'deadline_at',
        'attachment_path', 'attachment_name', 'attachment_type',
    ];

    protected $casts = [
        'is_hidden' => 'boolean',
        'is_assessment' => 'boolean',
        'deadline_at' => 'datetime',
    ];

    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }

    public function session(): BelongsTo
    {
        return $this->belongsTo(CourseSession::class, 'course_session_id');
    }

    public function posts(): HasMany
    {
        return $this->hasMany(ForumPost::class);
    }

    public function isLocked(): bool
    {
        if (! $this->is_assessment || ! $this->deadline_at) {
            return false;
        }

        return Carbon::now()->gt($this->deadline_at);
    }
}
