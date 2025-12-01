<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up(): void
    {
        Schema::create('contact_details', function (Blueprint $table) {
            $table->id();

            // FK ke perusahaan (menjaga multi-tenant)
            $table->foreignId('company_id')
                ->constrained('perusahaan')
                ->cascadeOnUpdate()
                ->cascadeOnDelete();

            // FK ke contact induk
            $table->foreignId('contact_id')
                ->constrained('contacts')
                ->cascadeOnUpdate()
                ->cascadeOnDelete();

            // Label & Value
            $table->string('label');          // mis: 'jabatan', 'npwp', 'alamat_kantor'
            $table->text('value')->nullable(); // isian bebas

            $table->timestamps();
            $table->softDeletes();

            // Percepat query umum
            $table->index(['company_id', 'contact_id']);

            // Satu label unik per contact (opsional, feel free to remove if not desired)
            $table->unique(['contact_id', 'label']);
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('contact_details');
    }
};
