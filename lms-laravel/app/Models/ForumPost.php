<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

/**
 * @property int $id
 * @property int $forum_thread_id
 * @property int $user_id
 * @property string $content
 * @property string|null $attachment_path
 * @property string|null $attachment_name
 * @property string|null $attachment_type
 * @property User $user
 * @property ForumThread $thread
 */
class ForumPost extends Model
{
    protected $fillable = [
        'forum_thread_id', 'user_id', 'content',
        'attachment_path', 'attachment_name', 'attachment_type',
    ];

    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }

    public function thread(): BelongsTo
    {
        return $this->belongsTo(ForumThread::class, 'forum_thread_id');
    }
}
