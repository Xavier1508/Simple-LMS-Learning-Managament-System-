<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

/**
 * @property int $id
 * @property int $grade_component_id
 * @property int $user_id
 * @property int $course_class_id
 * @property float $score
 * @property int $graded_by
 * @property \Illuminate\Support\Carbon|null $graded_at
 * @property GradeComponent $component
 */
class StudentGrade extends Model
{
    protected $fillable = ['grade_component_id', 'user_id', 'course_class_id', 'score', 'graded_by', 'graded_at'];

    protected $casts = [
        'graded_at' => 'datetime',
    ];

    public function component(): BelongsTo
    {
        return $this->belongsTo(GradeComponent::class, 'grade_component_id');
    }
}
