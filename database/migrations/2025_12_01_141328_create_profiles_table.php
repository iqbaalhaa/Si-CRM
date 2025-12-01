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
        Schema::create('profiles', function (Blueprint $table) {
            $table->id();

            // Relasi ke users
            $table->foreignId('user_id')
                ->constrained()
                ->cascadeOnDelete();

            // Relasi ke companies / perusahaan
            $table->foreignId('company_id')
                ->constrained('perusahaan')
                ->cascadeOnDelete();

            // Foto profil (path / filename)
            $table->string('photo', 2048)->nullable();

            // Jabatan
            $table->string('job_title', 150)->nullable(); // max 150 karakter

            // Soft deletes
            $table->softDeletes();

            $table->timestamps();

            // Opsional: 1 user hanya boleh punya 1 profile
            $table->unique('user_id');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('profiles');
    }
};