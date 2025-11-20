<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class GradeComponent extends Model
{
    protected $fillable = ['course_id', 'name', 'weight', 'type'];

    public function course()
    {
        return $this->belongsTo(Course::class);
    }
}
