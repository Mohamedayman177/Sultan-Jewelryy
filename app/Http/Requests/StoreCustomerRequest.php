<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class StoreCustomerRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    /**
     * @return array<string, mixed>
     */
    public function rules(): array
    {
        $locale = $this->input('locale') === 'en' ? 'en' : 'ar';
        $category = $this->input('item_category');
        $upload = config('customer-form.upload');

        $rules = [
            'item_category' => ['required', Rule::in(['jewelry', 'gemstone'])],
            'name' => ['required', 'string', 'max:255'],
            'phone' => ['required', 'string', 'max:32'],
            'email' => ['nullable', 'email', 'max:255'],
            'city' => ['required', 'string', 'max:128'],
            'service_id' => [
                'required',
                'integer',
                Rule::exists('services', 'id')->where(fn ($q) => $q->where('is_active', true)->where('requires_registration', true)),
            ],
            'locale' => ['nullable', 'string', Rule::in(['ar', 'en'])],
            'terms' => ['accepted'],
            'brief_description' => ['nullable', 'string', 'max:2000'],
            'additional_notes' => ['nullable', 'string', 'max:2000'],
            'referral_source' => ['nullable', Rule::in(array_keys(config('customer-form.referral_sources', [])))],
            'evaluation_purpose' => ['nullable', 'array'],
            'evaluation_purpose.*' => ['string', 'max:64'],
            'photos' => ['nullable', 'array', 'max:'.(int) ($upload['max_photos'] ?? 8)],
            'photos.*' => ['file', 'mimes:'.($upload['mimes'] ?? 'jpg,jpeg,png,webp'), 'max:'.(int) ($upload['max_file_kb'] ?? 5120)],
            'invoice' => ['nullable', 'file', 'mimes:'.($upload['mimes'] ?? 'jpg,jpeg,png,webp,pdf'), 'max:'.(int) ($upload['max_file_kb'] ?? 5120)],
            'certificates' => ['nullable', 'array', 'max:'.(int) ($upload['max_certificates'] ?? 5)],
            'certificates.*' => ['file', 'mimes:'.($upload['mimes'] ?? 'jpg,jpeg,png,webp,pdf'), 'max:'.(int) ($upload['max_file_kb'] ?? 5120)],
        ];

        if ($category === 'jewelry') {
            $rules = array_merge($rules, $this->jewelryRules());
        } elseif ($category === 'gemstone') {
            $rules = array_merge($rules, $this->gemstoneRules());
        }

        return $rules;
    }

    /**
     * @return array<string, mixed>
     */
    private function jewelryRules(): array
    {
        $j = config('customer-form.jewelry');

        return [
            'piece_type' => ['nullable', Rule::in(array_keys($j['piece_types'] ?? []))],
            'metal_type' => ['nullable', Rule::in(array_keys($j['metal_types'] ?? []))],
            'pieces_count' => ['nullable', 'integer', 'min:1', 'max:999'],
            'approximate_weight' => ['nullable', 'string', 'max:64'],
            'karat' => ['nullable', 'string', 'max:32'],
            'brand' => ['nullable', 'string', 'max:128'],
            'has_hallmark' => ['nullable', Rule::in(array_keys($j['yes_no_unknown'] ?? []))],
            'has_invoice' => ['nullable', Rule::in(array_keys($j['yes_no_unknown'] ?? []))],
            'has_certificate' => ['nullable', Rule::in(array_keys($j['yes_no_unknown'] ?? []))],
            'piece_condition' => ['nullable', Rule::in(array_keys($j['piece_conditions'] ?? []))],
            'evaluation_purpose.*' => ['nullable', Rule::in(array_keys($j['evaluation_purposes'] ?? []))],
        ];
    }

    /**
     * @return array<string, mixed>
     */
    private function gemstoneRules(): array
    {
        $g = config('customer-form.gemstone');

        return [
            'gemstone_type' => ['nullable', Rule::in(array_keys($g['gemstone_types'] ?? []))],
            'stones_count' => ['nullable', 'integer', 'min:1', 'max:999'],
            'approximate_weight_carat' => ['nullable', 'string', 'max:64'],
            'shape' => ['nullable', Rule::in(array_keys($g['shapes'] ?? []))],
            'color' => ['nullable', 'string', 'max:64'],
            'clarity_grade' => ['nullable', 'string', 'max:64'],
            'origin_type' => ['nullable', Rule::in(array_keys($g['origin_types'] ?? []))],
            'treated' => ['nullable', Rule::in(array_keys($g['yes_no_unknown'] ?? []))],
            'country_of_origin' => ['nullable', 'string', 'max:128'],
            'has_certificate' => ['nullable', Rule::in(array_keys($g['yes_no_unknown'] ?? []))],
            'stone_condition' => ['nullable', Rule::in(array_keys($g['stone_conditions'] ?? []))],
            'evaluation_purpose.*' => ['nullable', Rule::in(array_keys($g['evaluation_purposes'] ?? []))],
        ];
    }

    /**
     * @return array<string, string>
     */
    public function messages(): array
    {
        $ar = $this->input('locale') !== 'en';

        return $ar ? [
            'item_category.required' => 'يرجى اختيار نوع القطعة (مجوهرات أو أحجار كريمة).',
            'item_category.in' => 'نوع القطعة غير صالح.',
            'name.required' => 'الاسم الكامل مطلوب.',
            'phone.required' => 'رقم الجوال مطلوب.',
            'city.required' => 'المدينة مطلوبة.',
            'email.email' => 'صيغة البريد الإلكتروني غير صحيحة.',
            'service_id.required' => 'يجب تحديد نوع الخدمة.',
            'terms.accepted' => 'يجب الموافقة على الشروط والأحكام.',
            'photos.*.max' => 'حجم الصورة كبير جداً.',
            'photos.*.mimes' => 'صيغة الصورة غير مدعومة.',
        ] : [
            'item_category.required' => 'Please choose jewelry or gemstones.',
            'name.required' => 'Full name is required.',
            'phone.required' => 'Mobile number is required.',
            'city.required' => 'City is required.',
            'terms.accepted' => 'You must accept the terms and conditions.',
        ];
    }
}
