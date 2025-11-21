<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;

/**
 * @property int $id
 * @property string $title
 * @property string $code
 * @property string $description
 * @property \Illuminate\Database\Eloquent\Collection|CourseClass[] $classes
 */
class Course extends Model
{
    use HasFactory;

    protected $fillable = ['title', 'code', 'description'];

    public function classes(): HasMany
    {
        return $this->hasMany(CourseClass::class);
    }
}
