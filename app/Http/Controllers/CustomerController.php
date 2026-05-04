<?php

namespace App\Http\Controllers;

use App\Models\Customer;
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
                'service_key.required' => 'يجب تحديد نوع الخدمة.',
                'service_key.in' => 'نوع الخدمة غير صالح.',
            ]
            : [
                'phone.required' => 'Mobile number is required.',
                'email.email' => 'Please enter a valid email address.',
                'service_key.required' => 'Please choose a service.',
                'service_key.in' => 'Invalid service type.',
            ];

        $validator = Validator::make($request->all(), [
            'name' => ['nullable', 'string', 'max:255'],
            'national_id' => ['nullable', 'string', 'max:64'],
            'phone' => ['required', 'string', 'max:32'],
            'email' => ['nullable', 'email', 'max:255'],
            'service_key' => ['required', 'string', Rule::in(array_keys(Customer::serviceLabels()))],
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
            'service_key' => $data['service_key'],
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
        $labels = Customer::serviceLabels();
        $key = $data['service_key'];
        $ar = $labels[$key]['ar'];
        $en = $labels[$key]['en'];

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
