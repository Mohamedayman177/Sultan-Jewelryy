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
        Schema::table('customers', function (Blueprint $table) {
            $table->string('payment_status', 32)->nullable()->after('locale');
            $table->unsignedBigInteger('myfatoorah_invoice_id')->nullable()->after('payment_status');
            $table->timestamp('paid_at')->nullable()->after('myfatoorah_invoice_id');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('customers', function (Blueprint $table) {
            $table->dropColumn(['payment_status', 'myfatoorah_invoice_id', 'paid_at']);
        });
    }
};
