<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        // Update Tabel Assignments (Soal dari Dosen)
        Schema::table('assignments', function (Blueprint $table) {
            if (!Schema::hasColumn('assignments', 'attachment_path')) {
                $table->string('attachment_path')->nullable(); // File soal
                $table->string('attachment_name')->nullable();
            }
            if (!Schema::hasColumn('assignments', 'is_lock')) {
                $table->boolean('is_lock')->default(false); // Manual lock oleh dosen
            }
        });

        // Update Tabel Submissions (Jawaban Siswa)
        Schema::table('submissions', function (Blueprint $table) {
            // Kita ubah file_path jadi nullable karena siswa bisa submit via text saja
            $table->string('file_path')->nullable()->change();

            if (!Schema::hasColumn('submissions', 'file_name')) {
                $table->string('file_name')->nullable();
            }
            if (!Schema::hasColumn('submissions', 'text_content')) {
                $table->longText('text_content')->nullable(); // Input text langsung
            }
            if (!Schema::hasColumn('submissions', 'status')) {
                $table->enum('status', ['submitted', 'late', 'graded'])->default('submitted');
            }
        });
    }

    public function down(): void
    {
        // Drop columns if needed
    }
};
