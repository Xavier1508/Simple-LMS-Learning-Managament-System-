<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        // 1. Tabel Sesi Pertemuan (GANTI NAMA: sessions -> course_sessions)
        Schema::create('course_sessions', function (Blueprint $table) {
            $table->id();
            $table->foreignId('course_class_id')->constrained()->cascadeOnDelete();
            $table->string('title');
            $table->text('learning_outcome')->nullable();
            $table->integer('session_number');
            $table->dateTime('start_time');
            $table->dateTime('end_time');
            $table->string('delivery_mode')->default('Onsite');
            $table->string('zoom_link')->nullable();
            $table->timestamps();
        });

        // 2. Tabel Materi (PPT/PDF)
        Schema::create('course_materials', function (Blueprint $table) {
            $table->id();
            // GANTI FK: session_id -> course_session_id
            $table->foreignId('course_session_id')->constrained('course_sessions')->cascadeOnDelete();
            $table->string('file_name');
            $table->string('file_path');
            $table->string('file_type');
            $table->timestamps();
        });

        // 3. Tabel Absensi Siswa
        Schema::create('attendances', function (Blueprint $table) {
            $table->id();
            // GANTI FK: session_id -> course_session_id
            $table->foreignId('course_session_id')->constrained('course_sessions')->cascadeOnDelete();
            $table->foreignId('user_id')->constrained()->cascadeOnDelete();
            $table->dateTime('attended_at');
            $table->string('status')->default('Present');
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('attendances');
        Schema::dropIfExists('course_materials');
        Schema::dropIfExists('course_sessions'); // Hapus tabel yang benar
    }
};
