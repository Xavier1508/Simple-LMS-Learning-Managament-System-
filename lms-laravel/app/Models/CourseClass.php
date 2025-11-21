<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;
use Illuminate\Database\Eloquent\Relations\HasMany;

/**
 * @property int $id
 * @property int $course_id
 * @property int $lecturer_id
 * @property string $class_code
 * @property string $semester
 * @property string $type
 * @property Course $course
 * @property User $lecturer
 * @property \Illuminate\Database\Eloquent\Collection|User[] $students
 * @property \Illuminate\Database\Eloquent\Collection|CourseSession[] $sessions
 */
class CourseClass extends Model
{
    protected $fillable = ['course_id', 'lecturer_id', 'class_code', 'semester', 'type'];

    public function course(): BelongsTo
    {
        return $this->belongsTo(Course::class);
    }

    public function lecturer(): BelongsTo
    {
        return $this->belongsTo(User::class, 'lecturer_id');
    }

    public function students(): BelongsToMany
    {
        return $this->belongsToMany(User::class, 'enrollments', 'course_class_id', 'user_id');
    }

    public function sessions(): HasMany
    {
        return $this->hasMany(CourseSession::class);
    }
}
