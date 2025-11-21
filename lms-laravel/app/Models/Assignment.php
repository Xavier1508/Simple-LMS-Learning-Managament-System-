<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Carbon\Carbon;

/**
 * @property int $id
 * @property int $course_class_id
 * @property string $title
 * @property string|null $description
 * @property \Illuminate\Support\Carbon|null $due_date
 * @property string|null $attachment_path
 * @property string|null $attachment_name
 * @property bool $is_lock
 * @property CourseClass $class
 * @property \Illuminate\Database\Eloquent\Collection|Submission[] $submissions
 */
class Assignment extends Model
{
    protected $fillable = [
        'course_class_id', 'title', 'description',
        'due_date', 'attachment_path', 'attachment_name', 'is_lock'
    ];

    protected $casts = [
        'due_date' => 'datetime',
        'is_lock' => 'boolean',
    ];

    public function class(): BelongsTo
    {
        return $this->belongsTo(CourseClass::class, 'course_class_id');
    }

    public function submissions(): HasMany
    {
        return $this->hasMany(Submission::class);
    }

    public function isOverdue(): bool
    {
        if (!$this->due_date) {
            return false;
        }
        return Carbon::now()->gt($this->due_date);
    }

    public function isSubmittedBy(int|string $userId): bool
    {
        return $this->submissions()->where('user_id', $userId)->exists();
    }
}
