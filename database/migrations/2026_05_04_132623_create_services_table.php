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
        Schema::create('services', function (Blueprint $table) {
            $table->id();
            $table->string('slug', 128)->unique();
            $table->string('title_ar');
            $table->string('title_en');
            $table->text('description_ar');
            $table->text('description_en');
            $table->decimal('price', 12, 2)->nullable();
            $table->boolean('is_free')->default(false);
            $table->boolean('requires_registration')->default(true);
            $table->unsignedInteger('sort_order')->default(0);
            $table->boolean('is_active')->default(true);
            $table->timestamps();
        });

        $now = now();

        DB::table('services')->insert([
            [
                'slug' => 'introductory_session',
                'title_ar' => 'جلسة تعريفية',
                'title_en' => 'Introductory Session',
                'description_ar' => 'جلسة تمهيدية لفهم المشروع، توضيح منهجية التقييم والخدمات المناسبة، وتقديم توجيه أولي احترافي قبل البدء بالتفاصيل.',
                'description_en' => 'An introductory session to understand the project, explain the evaluation methodology and suitable services, and provide professional initial guidance before moving into details.',
                'price' => null,
                'is_free' => true,
                'requires_registration' => false,
                'sort_order' => 10,
                'is_active' => true,
                'created_at' => $now,
                'updated_at' => $now,
            ],
            [
                'slug' => 'instant_consultation',
                'title_ar' => 'الاستشارات الفورية',
                'title_en' => 'Instant Consultation',
                'description_ar' => 'استشارة متخصصة عبر الصور لتقديم تقييم مبدئي دقيق، تتضمن قراءة احترافية للحالة العامة وتوضيح القيمة التقديرية خلال وقت قياسي.',
                'description_en' => 'A specialized consultation via images providing an accurate initial assessment, including a professional evaluation of the overall condition and an estimated value in record time.',
                'price' => 390,
                'is_free' => false,
                'requires_registration' => true,
                'sort_order' => 20,
                'is_active' => true,
                'created_at' => $now,
                'updated_at' => $now,
            ],
            [
                'slug' => 'photo_evaluation',
                'title_ar' => 'التقييم بالصور',
                'title_en' => 'Photo-Based Evaluation',
                'description_ar' => 'تقييم مهني شامل يعتمد على تحليل الصور، يتضمن تقريرًا تفصيليًا يوضح المواصفات، الحالة، والقيمة التقديرية، مع توصيات معتمدة.',
                'description_en' => 'A comprehensive professional evaluation based on image analysis, including a detailed report outlining specifications, condition, estimated value, and certified recommendations.',
                'price' => 750,
                'is_free' => false,
                'requires_registration' => true,
                'sort_order' => 30,
                'is_active' => true,
                'created_at' => $now,
                'updated_at' => $now,
            ],
            [
                'slug' => 'comprehensive_consultation',
                'title_ar' => 'الاستشارة الشاملة',
                'title_en' => 'Comprehensive Consultation',
                'description_ar' => 'جلسة متقدمة لتحليل القطع أو المشاريع بعمق، تشمل تقييمًا تفصيليًا وخطة تطوير متكاملة، مخصصة لأصحاب المجوهرات والعلامات الفاخرة.',
                'description_en' => 'An advanced session for in-depth analysis of pieces or projects, including a detailed evaluation and a comprehensive development plan tailored for jewelry owners and luxury brands.',
                'price' => 1500,
                'is_free' => false,
                'requires_registration' => true,
                'sort_order' => 40,
                'is_active' => true,
                'created_at' => $now,
                'updated_at' => $now,
            ],
        ]);
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('services');
    }
};
