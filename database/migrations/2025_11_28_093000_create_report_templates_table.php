<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('report_templates', function (Blueprint $table) {
            $table->id();
            $table->foreignId('company_id')
                ->constrained('perusahaan')
                ->cascadeOnUpdate()
                ->cascadeOnDelete();
            $table->string('template_name', 150);
            $table->longText('content');
            $table->timestamps();

            $table->unique(['company_id', 'template_name']);
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('report_templates');
    }
};
