<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('course_classes', function (Blueprint $table) {
            $table->id();
            $table->foreignId('course_id')->constrained()->cascadeOnDelete();
            $table->foreignId('lecturer_id')->constrained()->cascadeOnDelete();
            $table->string('class_code'); // e.g., LA07, LC21
            $table->string('semester'); // e.g., "2024/2025 Ganjil"
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('course_classes');
    }
};
