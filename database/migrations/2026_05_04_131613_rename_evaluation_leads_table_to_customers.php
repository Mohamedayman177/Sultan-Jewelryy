<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        if (Schema::hasTable('evaluation_leads') && ! Schema::hasTable('customers')) {
            Schema::rename('evaluation_leads', 'customers');
        }
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        if (Schema::hasTable('customers') && ! Schema::hasTable('evaluation_leads')) {
            Schema::rename('customers', 'evaluation_leads');
        }
    }
};
