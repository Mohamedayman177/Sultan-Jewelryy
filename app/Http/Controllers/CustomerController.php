<?php

namespace App\Http\Controllers;

use App\Models\Customer;
use App\Models\Service;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;
use Illuminate\Validation\Rule;

class CustomerController extends Controller
{
    public function store(Request $request): JsonResponse
    {
        $locale = $request->input('locale') === 'en' ? 'en' : 'ar';

        $messages = $locale === 'ar'
            ? [
                'phone.required' => 'رقم الجوال مطلوب.',
                'email.email' => 'صيغة البريد الإلكتروني غير صحيحة.',
                'service_id.required' => 'يجب تحديد نوع الخدمة.',
                'service_id.exists' => 'الخدمة غير متاحة.',
            ]
            : [
                'phone.required' => 'Mobile number is required.',
                'email.email' => 'Please enter a valid email address.',
                'service_id.required' => 'Please choose a service.',
                'service_id.exists' => 'This service is not available.',
            ];

        $validator = Validator::make($request->all(), [
            'name' => ['nullable', 'string', 'max:255'],
            'national_id' => ['nullable', 'string', 'max:64'],
            'phone' => ['required', 'string', 'max:32'],
            'email' => ['nullable', 'email', 'max:255'],
            'service_id' => [
                'required',
                'integer',
                Rule::exists('services', 'id')->where(fn ($q) => $q->where('is_active', true)->where('requires_registration', true)),
            ],
            'locale' => ['nullable', 'string', Rule::in(['ar', 'en'])],
        ], $messages);

        if ($validator->fails()) {
            return response()->json(['errors' => $validator->errors()], 422);
        }

        $data = $validator->validated();

        Customer::create([
            'name' => filled($data['name'] ?? null) ? trim((string) $data['name']) : null,
            'national_id' => filled($data['national_id'] ?? null) ? trim((string) $data['national_id']) : null,
            'phone' => trim((string) $data['phone']),
            'email' => filled($data['email'] ?? null) ? trim((string) $data['email']) : null,
            'service_id' => (int) $data['service_id'],
            'locale' => $data['locale'] ?? 'ar',
        ]);

        return response()->json([
            'whatsapp_url' => $this->whatsappUrl($data),
        ]);
    }

    /**
     * @param  array<string, mixed>  $data
     */
    private function whatsappUrl(array $data): string
    {
        $number = preg_replace('/\D/', '', (string) config('services.whatsapp.number'));
        $service = Service::query()->findOrFail((int) $data['service_id']);

        $ar = $service->title_ar;
        $en = $service->title_en;

        $name = $data['name'] ?? '';
        $nid = $data['national_id'] ?? '';
        $phone = $data['phone'];
        $email = $data['email'] ?? '';

        $text = "مرحباً، أرغب في المتابعة بخصوص الخدمة التالية:\n";
        $text .= "الخدمة: {$ar}\nService: {$en}\n\n";
        $text .= 'الاسم / Name: '.($name !== '' ? $name : '—')."\n";
        $text .= 'الهوية الوطنية / National ID: '.($nid !== '' ? $nid : '—')."\n";
        $text .= "الجوال / Mobile: {$phone}\n";
        $text .= 'البريد الإلكتروني / Email: '.($email !== '' ? $email : '—')."\n";

        return 'https://wa.me/'.$number.'?text='.rawurlencode($text);
    }
}
