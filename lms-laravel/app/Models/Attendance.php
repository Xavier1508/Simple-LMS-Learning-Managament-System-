<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

/**
 * @property int $id
 * @property int $course_session_id
 * @property int $user_id
 * @property string $status
 * @property \Illuminate\Support\Carbon|null $attended_at
 * @property int|null $recorded_by
 * @property CourseSession $session
 * @property User $user
 */
class Attendance extends Model
{
    protected $fillable = [
        'course_session_id',
        'user_id',
        'status',
        'attended_at',
        'recorded_by',
    ];

    protected $casts = [
        'attended_at' => 'datetime',
    ];

    public function session(): BelongsTo
    {
        return $this->belongsTo(CourseSession::class, 'course_session_id');
    }

    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }
}
