<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('report_templates', function (Blueprint $table) {
            $table->string('type', 50)
                ->after('company_id')
                ->nullable();
        });

        // Seed nilai "type" berdasarkan template_name lama
        DB::table('report_templates')->whereNull('type')->update([
            'type' => DB::raw("
                CASE 
                    WHEN template_name = 'employees' THEN 'employees'
                    WHEN template_name = 'customers' THEN 'customers'
                    ELSE 'customers'
                END
            "),
        ]);

        // Kalau mau type wajib diisi:
        Schema::table('report_templates', function (Blueprint $table) {
            $table->string('type', 50)->nullable(false)->change();
        });
    }

    public function down(): void
    {
        Schema::table('report_templates', function (Blueprint $table) {
            $table->dropColumn('type');
        });
    }
};
