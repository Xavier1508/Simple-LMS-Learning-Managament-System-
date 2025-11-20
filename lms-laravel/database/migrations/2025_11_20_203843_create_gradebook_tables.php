<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        // 1. Komponen Penilaian (Resep per Mata Kuliah)
        Schema::create('grade_components', function (Blueprint $table) {
            $table->id();
            $table->foreignId('course_id')->constrained()->cascadeOnDelete(); // Link ke Parent Course (bukan class)
            $table->string('name'); // e.g., "Mid Exam", "Final Project"
            $table->decimal('weight', 5, 2); // e.g., 30.00 (%)
            $table->enum('type', ['theory', 'lab', 'general'])->default('theory'); // Pembeda LEC/LAB
            $table->timestamps();
        });

        // 2. Nilai Siswa
        Schema::create('student_grades', function (Blueprint $table) {
            $table->id();
            $table->foreignId('grade_component_id')->constrained()->cascadeOnDelete();
            $table->foreignId('user_id')->constrained()->cascadeOnDelete(); // Siswa
            $table->foreignId('course_class_id')->constrained()->cascadeOnDelete(); // Kelas tempat nilai ini didapat

            $table->decimal('score', 5, 2)->nullable(); // Nilai 0-100
            $table->foreignId('graded_by')->nullable()->constrained('users'); // Siapa yang input (Dosen)
            $table->timestamp('graded_at')->useCurrent();

            $table->timestamps();

            // Satu siswa hanya punya 1 nilai per komponen di kelas tertentu
            $table->unique(['grade_component_id', 'user_id', 'course_class_id']);
        });

        // 3. Tambahkan SKS ke Course (Penting untuk GPA)
        Schema::table('courses', function (Blueprint $table) {
            if (!Schema::hasColumn('courses', 'credits')) {
                $table->integer('credits')->default(3); // Default 3 SKS
            }
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('student_grades');
        Schema::dropIfExists('grade_components');
    }
};
