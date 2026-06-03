<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('customers', function (Blueprint $table) {
            $table->string('item_category', 16)->nullable()->after('service_id');
            $table->string('city', 128)->nullable()->after('email');
            $table->json('form_details')->nullable()->after('city');
            $table->json('attachments')->nullable()->after('form_details');
            $table->timestamp('terms_accepted_at')->nullable()->after('attachments');
        });
    }

    public function down(): void
    {
        Schema::table('customers', function (Blueprint $table) {
            $table->dropColumn([
                'item_category',
                'city',
                'form_details',
                'attachments',
                'terms_accepted_at',
            ]);
        });
    }
};
