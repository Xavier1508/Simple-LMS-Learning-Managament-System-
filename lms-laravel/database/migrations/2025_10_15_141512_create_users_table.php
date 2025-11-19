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
            // Ganti 'name' menjadi 'first_name' dan 'last_name' untuk detail yang lebih baik
            $table->string('first_name');
            $table->string('last_name');
            $table->string('name')->virtualAs('CONCAT(first_name, " ", last_name)'); // Name sebagai virtual field
            $table->string('email')->unique();
            $table->string('phone_number')->nullable(); // Nomor telepon
            $table->timestamp('email_verified_at')->nullable();
            $table->string('password');

            // Kolom Khusus Dosen
            $table->string('lecturer_code')->nullable()->unique(); // Kode Dosen (hanya diisi untuk Dosen)
            $table->string('private_number', 16)->nullable(); // Private Number (hanya diisi untuk Dosen)

            $table->string('otp_code', 9)->nullable();
            $table->timestamp('otp_expires_at')->nullable();
            
            $table->enum('role', ['student', 'lecturer', 'admin'])->default('student'); // Tambah role 'lecturer'
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
