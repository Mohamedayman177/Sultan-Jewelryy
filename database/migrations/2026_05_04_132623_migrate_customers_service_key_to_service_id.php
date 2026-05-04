<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::table('customers', function (Blueprint $table) {
            $table->foreignId('service_id')->nullable()->after('id')->constrained()->nullOnDelete();
        });

        $slugToId = DB::table('services')->pluck('id', 'slug')->all();

        DB::table('customers')
            ->whereNotNull('service_key')
            ->orderBy('id')
            ->lazyById()
            ->each(function ($row) use ($slugToId) {
                $slug = $row->service_key;
                if ($slug && isset($slugToId[$slug])) {
                    DB::table('customers')->where('id', $row->id)->update([
                        'service_id' => $slugToId[$slug],
                    ]);
                }
            });

        Schema::table('customers', function (Blueprint $table) {
            $table->dropColumn('service_key');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('customers', function (Blueprint $table) {
            $table->string('service_key', 64)->nullable()->index()->after('id');
        });

        $idToSlug = DB::table('services')->pluck('slug', 'id')->all();

        DB::table('customers')
            ->whereNotNull('service_id')
            ->orderBy('id')
            ->lazyById()
            ->each(function ($row) use ($idToSlug) {
                $slug = $idToSlug[$row->service_id] ?? null;
                if ($slug) {
                    DB::table('customers')->where('id', $row->id)->update([
                        'service_key' => $slug,
                    ]);
                }
            });

        Schema::table('customers', function (Blueprint $table) {
            $table->dropForeign(['service_id']);
            $table->dropColumn('service_id');
        });
    }
};
