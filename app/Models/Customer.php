<?php

namespace App\Models;

use App\Support\CustomerFormHelper;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Support\Facades\Storage;

class Customer extends Model
{
    protected $fillable = [
        'service_id',
        'item_category',
        'name',
        'national_id',
        'phone',
        'email',
        'city',
        'form_details',
        'attachments',
        'terms_accepted_at',
        'locale',
        'payment_status',
        'myfatoorah_invoice_id',
        'paid_at',
    ];

    protected function casts(): array
    {
        return [
            'form_details' => 'array',
            'attachments' => 'array',
            'paid_at' => 'datetime',
            'terms_accepted_at' => 'datetime',
        ];
    }

    public function service(): BelongsTo
    {
        return $this->belongsTo(Service::class);
    }

    public function itemCategoryLabel(string $locale = 'ar'): string
    {
        $categories = config('customer-form.item_categories', []);

        return $categories[$this->item_category][$locale]
            ?? $categories[$this->item_category]['ar']
            ?? ($this->item_category ?? '—');
    }

    /**
     * WhatsApp deep link after registration / successful payment.
     */
    public function whatsappContactUrl(): string
    {
        $number = preg_replace('/\D/', '', (string) config('services.whatsapp.number'));
        $service = $this->relationLoaded('service')
            ? $this->service
            : $this->service()->firstOrFail();

        $locale = $this->locale === 'en' ? 'en' : 'ar';
        $category = $this->item_category ?? 'jewelry';
        $details = $this->form_details ?? [];

        $text = "مرحباً، أرغب في المتابعة بخصوص الخدمة التالية:\n";
        $text .= "الخدمة: {$service->title_ar}\nService: {$service->title_en}\n\n";
        $text .= 'نوع القطعة / Item: '.$this->itemCategoryLabel('ar').' / '.$this->itemCategoryLabel('en')."\n";
        $text .= 'الاسم / Name: '.($this->name ?: '—')."\n";
        $text .= "الجوال / Mobile: {$this->phone}\n";
        $text .= 'المدينة / City: '.($this->city ?: '—')."\n";
        $text .= 'البريد / Email: '.($this->email ?: '—')."\n\n";

        if ($category === 'jewelry') {
            $text .= $this->whatsappJewelryBlock($details, $locale);
        } else {
            $text .= $this->whatsappGemstoneBlock($details, $locale);
        }

        if (! empty($details['brief_description'])) {
            $text .= "\nوصف / Description:\n".$details['brief_description'];
        }

        return 'https://wa.me/'.$number.'?text='.rawurlencode($text);
    }

    /**
     * @param  array<string, mixed>  $details
     */
    private function whatsappJewelryBlock(array $details, string $locale): string
    {
        $lines = ["— تفاصيل المجوهرات —\n"];
        $map = [
            ['piece_type', 'piece_types', 'نوع القطعة'],
            ['metal_type', 'metal_types', 'نوع المعدن'],
            ['pieces_count', null, 'عدد القطع'],
            ['approximate_weight', null, 'الوزن التقريبي'],
            ['karat', null, 'العيار'],
            ['brand', null, 'الماركة'],
            ['has_hallmark', 'yes_no_unknown', 'دمغة/ختم'],
            ['has_invoice', 'yes_no_unknown', 'فاتورة'],
            ['has_certificate', 'yes_no_unknown', 'شهادة'],
            ['piece_condition', 'piece_conditions', 'الحالة'],
        ];

        foreach ($map as [$key, $group, $label]) {
            if (! filled($details[$key] ?? null)) {
                continue;
            }
            $val = $group
                ? CustomerFormHelper::label('jewelry', $group, (string) $details[$key], $locale)
                : (string) $details[$key];
            $lines[] = "{$label}: {$val}";
        }

        if (! empty($details['evaluation_purpose'])) {
            $purposes = CustomerFormHelper::labelsList('jewelry', 'evaluation_purposes', $details['evaluation_purpose'], $locale);
            $lines[] = 'الغرض من التقييم: '.implode('، ', $purposes);
        }

        return implode("\n", $lines)."\n";
    }

    /**
     * @param  array<string, mixed>  $details
     */
    private function whatsappGemstoneBlock(array $details, string $locale): string
    {
        $lines = ["— تفاصيل الحجر —\n"];
        $map = [
            ['gemstone_type', 'gemstone_types', 'نوع الحجر'],
            ['stones_count', null, 'عدد الأحجار'],
            ['approximate_weight_carat', null, 'الوزن (قيراط)'],
            ['shape', 'shapes', 'الشكل'],
            ['color', null, 'اللون'],
            ['clarity_grade', null, 'النقاء'],
            ['origin_type', 'origin_types', 'طبيعي/مصنع'],
            ['treated', 'yes_no_unknown', 'معالجة'],
            ['country_of_origin', null, 'بلد المنشأ'],
            ['has_certificate', 'yes_no_unknown', 'شهادة'],
            ['stone_condition', 'stone_conditions', 'الحالة'],
        ];

        foreach ($map as [$key, $group, $label]) {
            if (! filled($details[$key] ?? null)) {
                continue;
            }
            $val = $group
                ? CustomerFormHelper::label('gemstone', $group, (string) $details[$key], $locale)
                : (string) $details[$key];
            $lines[] = "{$label}: {$val}";
        }

        if (! empty($details['evaluation_purpose'])) {
            $purposes = CustomerFormHelper::labelsList('gemstone', 'evaluation_purposes', $details['evaluation_purpose'], $locale);
            $lines[] = 'الغرض من التقييم: '.implode('، ', $purposes);
        }

        return implode("\n", $lines)."\n";
    }

    /**
     * @return list<array{label: string, url: string}>
     */
    public function attachmentLinks(): array
    {
        $links = [];
        foreach ($this->attachments ?? [] as $file) {
            if (! is_array($file) || empty($file['path'])) {
                continue;
            }
            if (! Storage::disk('public')->exists($file['path'])) {
                continue;
            }
            $links[] = [
                'label' => $file['label'] ?? $file['original_name'] ?? 'ملف',
                'url' => Storage::disk('public')->url($file['path']),
            ];
        }

        return $links;
    }
}
