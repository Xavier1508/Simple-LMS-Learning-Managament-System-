<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        // INI PERUBAHANNYA: Cek dulu apakah tabelnya sudah ada?
        if (!Schema::hasTable('attendances')) {

            Schema::create('attendances', function (Blueprint $table) {
                $table->id();
                // Relasi ke Sesi dan User (Siswa)
                $table->foreignId('course_session_id')->constrained()->cascadeOnDelete();
                $table->foreignId('user_id')->constrained()->cascadeOnDelete();

                // Status: present (Hadir), absent (Tidak Hadir/Bolos), late (Terlambat - Opsional)
                $table->enum('status', ['present', 'absent', 'late'])->default('present');

                // Waktu absen
                $table->timestamp('attended_at')->nullable();

                // Siapa yang menginput? (System/Student sendiri atau Lecturer override)
                $table->foreignId('recorded_by')->nullable()->constrained('users')->nullOnDelete();

                $table->timestamps();

                // Mencegah 1 user absen 2x di sesi yang sama
                $table->unique(['course_session_id', 'user_id']);
            });
        }
    }

    public function down(): void
    {
        Schema::dropIfExists('attendances');
    }
};
