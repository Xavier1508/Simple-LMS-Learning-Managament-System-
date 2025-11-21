<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

/**
 * @property int $id
 * @property int $user_id
 * @property int $course_class_id
 */
class Enrollment extends Model
{
    protected $fillable = ['user_id', 'course_class_id'];
}
