<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up(): void
    {
        Schema::create('contacts', function (Blueprint $table) {
            $table->id();

            // FK ke perusahaan pemilik data
            $table->foreignId('company_id')
                ->constrained('perusahaan')
                ->cascadeOnUpdate()
                ->cascadeOnDelete();

            // Tipe kontak: Individu / Perusahaan / Organisasi
            $table->enum('type', ['individual', 'company', 'organization'])
                ->index();

            // Nama kontak (nama orang / nama perusahaan / nama organisasi)
            $table->string('name')->index();

            // Status aktif
            $table->boolean('is_active')->default(true)->index();

            // Siapa yang membuat data
            $table->foreignId('created_by')
                ->constrained('users')
                ->cascadeOnUpdate()
                ->restrictOnDelete();

            $table->timestamps();
            $table->softDeletes();

            // Sering dicari bersama
            $table->index(['company_id', 'type', 'is_active']);
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('contacts');
    }
};
