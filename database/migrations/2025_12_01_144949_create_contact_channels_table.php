<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up(): void
    {
        Schema::create('contact_channels', function (Blueprint $table) {
            $table->id();

            $table->foreignId('company_id')
                ->constrained('perusahaan')
                ->cascadeOnUpdate()
                ->cascadeOnDelete();

            $table->foreignId('contact_id')
                ->constrained('contacts')
                ->cascadeOnUpdate()
                ->cascadeOnDelete();

            // Kecilkan panjang kolom yang ikut composite unique
            $table->string('label', 32);
            $table->string('value', 191);

            $table->boolean('is_primary')->default(false);

            $table->timestamps();
            $table->softDeletes();

            $table->index(['company_id', 'contact_id', 'label']);

            // Composite unique aman terhadap limit key length
            $table->unique(['contact_id', 'label', 'value'], 'contact_channels_contact_label_value_unique');
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('contact_channels');
    }
};
