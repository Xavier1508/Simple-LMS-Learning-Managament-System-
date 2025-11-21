<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

/**
 * @property int $id
 * @property int $course_session_id
 * @property string $file_name
 * @property string $file_path
 * @property string $file_type
 * @property CourseSession $session
 */
class CourseMaterial extends Model
{
    protected $table = 'course_materials';

    protected $fillable = ['course_session_id', 'file_name', 'file_path', 'file_type'];

    public function session(): BelongsTo
    {
        return $this->belongsTo(CourseSession::class, 'course_session_id');
    }
}
