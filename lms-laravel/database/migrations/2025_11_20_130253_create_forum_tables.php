<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        // Tabel Thread (Topik Utama)
        Schema::create('forum_threads', function (Blueprint $table) {
            $table->id();
            $table->foreignId('course_session_id')->constrained()->cascadeOnDelete();
            $table->foreignId('user_id')->constrained()->cascadeOnDelete(); // Pembuat Thread

            $table->string('title');
            $table->text('content'); // Isi thread (bisa HTML dari Rich Text)

            // Fitur Khusus Lecture
            $table->boolean('is_hidden')->default(false); // Mode Hidden Response
            $table->boolean('is_assessment')->default(false); // Mode Tugas
            $table->dateTime('deadline_at')->nullable(); // Deadline Tugas

            // Attachment untuk Thread utama
            $table->string('attachment_path')->nullable();
            $table->string('attachment_name')->nullable(); // Nama asli file
            $table->string('attachment_type')->nullable(); // extensi/mime

            $table->timestamps();
        });

        // Tabel Posts (Balasan/Komentar)
        Schema::create('forum_posts', function (Blueprint $table) {
            $table->id();
            $table->foreignId('forum_thread_id')->constrained()->cascadeOnDelete();
            $table->foreignId('user_id')->constrained()->cascadeOnDelete(); // Yang membalas

            $table->text('content');

            // Attachment untuk Balasan
            $table->string('attachment_path')->nullable();
            $table->string('attachment_name')->nullable();
            $table->string('attachment_type')->nullable();

            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('forum_posts');
        Schema::dropIfExists('forum_threads');
    }
};
