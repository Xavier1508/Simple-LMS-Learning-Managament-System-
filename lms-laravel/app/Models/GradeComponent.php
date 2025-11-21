<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

/**
 * @property int $id
 * @property int $course_id
 * @property string $name
 * @property int $weight
 * @property string $type
 * @property Course $course
 */
class GradeComponent extends Model
{
    protected $fillable = ['course_id', 'name', 'weight', 'type'];

    public function course(): BelongsTo
    {
        return $this->belongsTo(Course::class);
    }
}
