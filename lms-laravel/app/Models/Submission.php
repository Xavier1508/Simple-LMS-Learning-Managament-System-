<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

/**
 * @property int $id
 * @property int $assignment_id
 * @property int $user_id
 * @property string $file_path
 * @property string $file_name
 * @property string|null $text_content
 * @property int|null $grade
 * @property string|null $feedback
 * @property \Illuminate\Support\Carbon|null $submitted_at
 * @property string $status
 * @property Assignment $assignment
 * @property User $user
 */
class Submission extends Model
{
    protected $fillable = [
        'assignment_id', 'user_id', 'file_path', 'file_name',
        'text_content', 'grade', 'feedback', 'submitted_at', 'status'
    ];

    protected $casts = [
        'submitted_at' => 'datetime',
    ];

    public function assignment(): BelongsTo
    {
        return $this->belongsTo(Assignment::class);
    }

    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }
}
