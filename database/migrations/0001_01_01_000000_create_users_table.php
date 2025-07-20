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
            $table->id('idUser');
            $table->string('name');
            $table->string('email')->unique();
            $table->string('password');
            $table->enum('roleUser', ['superadmin', 'operatorDesa', 'operatorKecamatan', 'verifikatorDafduk', 'verifikatorCapil', 'admin']); // Menambahkan kolom roleUser
            $table->enum('status', ['aktif', 'nonaktif'])->default('aktif'); // Menambahkan kolom status
            $table->timestamps();
        });

        Schema::create('password_reset_tokens', function (Blueprint $table) {
            $table->string('email')->primary();
            $table->string('token');
            $table->timestamp('created_at')->nullable();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('users');
        Schema::dropIfExists('password_reset_tokens');
    }
};
