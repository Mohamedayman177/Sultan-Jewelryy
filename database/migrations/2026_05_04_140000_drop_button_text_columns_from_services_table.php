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
        if (! Schema::hasTable('services')) {
            return;
        }

        if (Schema::hasColumn('services', 'button_text_ar')) {
            Schema::table('services', function (Blueprint $table) {
                $table->dropColumn(['button_text_ar', 'button_text_en']);
            });
        }
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        if (! Schema::hasTable('services')) {
            return;
        }

        if (! Schema::hasColumn('services', 'button_text_ar')) {
            Schema::table('services', function (Blueprint $table) {
                $table->string('button_text_ar')->nullable();
                $table->string('button_text_en')->nullable();
            });
        }
    }
};
