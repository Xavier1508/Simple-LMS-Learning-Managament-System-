<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class CourseMaterial extends Model
{
    // Mendefinisikan nama tabel secara eksplisit untuk keamanan
    protected $table = 'course_materials';

    // fillable sudah sesuai dengan kolom di database (migration)
    protected $fillable = ['course_session_id', 'file_name', 'file_path', 'file_type'];

    // Relasi sudah benar: Materi milik satu Sesi
    public function session()
    {
        return $this->belongsTo(CourseSession::class, 'course_session_id');
    }
}
