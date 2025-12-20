<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::create('users', function (Blueprint $table) {
            $table->id();
            $table->string('first_name');
            $table->string('last_name');
            $table->string('email')->unique();
            $table->string('phone_number', 20)->nullable();

            $table->timestamp('email_verified_at')->nullable();
            $table->string('password');

            // Kolom Khusus Dosen
            $table->string('lecturer_code')->nullable()->unique();
            $table->string('private_number', 16)->nullable(); // Ini sudah bagus (strict 16)

            $table->string('otp_code', 9)->nullable();
            $table->timestamp('otp_expires_at')->nullable();

            $table->enum('role', ['student', 'lecturer', 'admin'])->default('student');
            $table->rememberToken();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('users');
    }
};
