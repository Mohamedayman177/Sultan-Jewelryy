<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Customer extends Model
{
    public const SERVICE_INSTANT = 'instant_consultation';

    public const SERVICE_PHOTO = 'photo_evaluation';

    public const SERVICE_COMPREHENSIVE = 'comprehensive_consultation';

    protected $fillable = [
        'service_key',
        'name',
        'national_id',
        'phone',
        'email',
        'locale',
    ];

    /**
     * @return array<string, array{ar: string, en: string}>
     */
    public static function serviceLabels(): array
    {
        return [
            self::SERVICE_INSTANT => ['ar' => 'الاستشارات الفورية', 'en' => 'Instant Consultation'],
            self::SERVICE_PHOTO => ['ar' => 'التقييم بالصور', 'en' => 'Photo-Based Evaluation'],
            self::SERVICE_COMPREHENSIVE => ['ar' => 'الاستشارة الشاملة', 'en' => 'Comprehensive Consultation'],
        ];
    }
}
