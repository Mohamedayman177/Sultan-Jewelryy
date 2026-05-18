<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('payment_links', function (Blueprint $table) {
            $table->id();
            $table->string('customer_name')->nullable();
            $table->string('phone', 32);
            $table->string('email')->nullable();
            $table->decimal('amount', 10, 2);
            $table->string('description')->nullable();
            $table->string('payment_status', 16)->default('pending');
            $table->unsignedBigInteger('myfatoorah_invoice_id')->nullable();
            $table->text('invoice_url')->nullable();
            $table->timestamp('paid_at')->nullable();
            $table->foreignId('created_by')->nullable()->constrained('users')->nullOnDelete();
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('payment_links');
    }
};
