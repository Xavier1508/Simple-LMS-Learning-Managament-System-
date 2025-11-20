<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class StudentGrade extends Model
{
    protected $fillable = ['grade_component_id', 'user_id', 'course_class_id', 'score', 'graded_by', 'graded_at'];

    protected $casts = [
        'graded_at' => 'datetime',
    ];

    public function component()
    {
        return $this->belongsTo(GradeComponent::class, 'grade_component_id');
    }
}
