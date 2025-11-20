<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Carbon\Carbon;

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

    public function class()
    {
        return $this->belongsTo(CourseClass::class, 'course_class_id');
    }

    public function submissions()
    {
        return $this->hasMany(Submission::class);
    }

    // Helper: Cek apakah sudah lewat deadline
    public function isOverdue()
    {
        return Carbon::now()->gt($this->due_date);
    }

    // Helper: Cek apakah siswa tertentu sudah mengumpulkan
    public function isSubmittedBy($userId)
    {
        return $this->submissions()->where('user_id', $userId)->exists();
    }
}
