<?php

namespace App\Http\Controllers;

use App\Models\Customer;
use App\Models\Service;
use App\Services\MyFatoorahClient;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;
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

        $service = Service::query()
            ->where('is_active', true)
            ->where('requires_registration', true)
            ->findOrFail((int) $data['service_id']);

        if ($service->is_free) {
            $customer = Customer::create([
                'name' => filled($data['name'] ?? null) ? trim((string) $data['name']) : null,
                'national_id' => filled($data['national_id'] ?? null) ? trim((string) $data['national_id']) : null,
                'phone' => trim((string) $data['phone']),
                'email' => filled($data['email'] ?? null) ? trim((string) $data['email']) : null,
                'service_id' => (int) $data['service_id'],
                'locale' => $data['locale'] ?? 'ar',
                'payment_status' => null,
            ]);

            return response()->json([
                'whatsapp_url' => $customer->fresh(['service'])->whatsappContactUrl(),
            ]);
        }

        if (! filled(config('services.myfatoorah.api_key'))) {
            return response()->json([
                'message' => $locale === 'ar'
                    ? 'بوابة الدفع غير مهيأة على الخادم.'
                    : 'The payment gateway is not configured.',
            ], 503);
        }

        $invoiceValue = (float) $service->price;
        if ($invoiceValue <= 0) {
            return response()->json([
                'errors' => [
                    'service_id' => [
                        $locale === 'ar'
                            ? 'سعر الخدمة غير صالح للدفع.'
                            : 'This service does not have a valid price for payment.',
                    ],
                ],
            ], 422);
        }

        $customer = Customer::create([
            'name' => filled($data['name'] ?? null) ? trim((string) $data['name']) : null,
            'national_id' => filled($data['national_id'] ?? null) ? trim((string) $data['national_id']) : null,
            'phone' => trim((string) $data['phone']),
            'email' => filled($data['email'] ?? null) ? trim((string) $data['email']) : null,
            'service_id' => (int) $data['service_id'],
            'locale' => $data['locale'] ?? 'ar',
            'payment_status' => 'pending',
        ]);

        $client = MyFatoorahClient::fromConfig();

        $payload = [
            'NotificationOption' => 'Lnk',
            'InvoiceValue' => $invoiceValue,
            'CustomerName' => filled($data['name'] ?? null) ? trim((string) $data['name']) : 'Customer',
            'CustomerEmail' => filled($data['email'] ?? null)
                ? trim((string) $data['email'])
                : (string) config('services.myfatoorah.placeholder_email'),
            'CustomerMobile' => $this->normalizePhoneForMyFatoorah((string) $data['phone']),
            'CustomerReference' => (string) $customer->id,
            'CallBackUrl' => $this->paymentAbsoluteUrl('payment.callback'),
            'ErrorUrl' => $this->paymentAbsoluteUrl('payment.error'),
            'Language' => $locale === 'ar' ? 'ar' : 'en',
            'DisplayCurrencyIso' => 'SAR',
        ];

        $result = $client->sendPayment($payload);

        if (! ($result['IsSuccess'] ?? false)) {
            $gatewayDetail = $this->formatMyFatoorahError($result);
            Log::warning('MyFatoorah SendPayment failed', [
                'customer_id' => $customer->id,
                'detail' => $gatewayDetail,
            ]);

            $customer->delete();

            return response()->json([
                'message' => $locale === 'ar'
                    ? 'تعذّر إنشاء عملية الدفع. حاول لاحقًا.'
                    : 'Could not start payment. Please try again.',
                'gateway_message' => $gatewayDetail,
            ], 422);
        }

        $invoiceId = $result['Data']['InvoiceId'] ?? null;
        $invoiceUrl = $result['Data']['InvoiceURL'] ?? null;

        if (! filled($invoiceUrl)) {
            $customer->delete();

            return response()->json([
                'message' => $locale === 'ar'
                    ? 'لم يُرجَع رابط الدفع من بوابة الدفع.'
                    : 'Payment URL was not returned by the gateway.',
            ], 422);
        }

        $customer->update([
            'myfatoorah_invoice_id' => $invoiceId,
        ]);

        return response()->json([
            'payment_url' => $invoiceUrl,
        ]);
    }

    /**
     * روابط الرجوع يجب أن تكون HTTPS عامة؛ MyFatoorah ترفض غالباً localhost.
     */
    private function paymentAbsoluteUrl(string $routeName): string
    {
        $override = config('services.myfatoorah.public_app_url');
        if (filled($override)) {
            return rtrim((string) $override, '/').route($routeName, [], false);
        }

        return route($routeName);
    }

    /**
     * @param  array<string, mixed>  $result
     */
    private function formatMyFatoorahError(array $result): ?string
    {
        $parts = array_filter([
            $result['Message'] ?? null,
        ]);

        $validation = $result['ValidationErrors'] ?? null;
        if (is_array($validation) && $validation !== []) {
            $parts[] = json_encode($validation, JSON_UNESCAPED_UNICODE);
        }

        $merged = trim(implode(' ', $parts));

        return $merged !== '' ? $merged : null;
    }

    /**
     * MyFatoorah (بيئة الاختبار على الأقل) تقبل كحد أقصى 11 خانة لـ CustomerMobile — الرقم الوطني بدون كود الدولة.
     */
    private function normalizePhoneForMyFatoorah(string $phone): string
    {
        $digits = preg_replace('/\D/', '', $phone) ?? '';
        if ($digits === '') {
            return '500000000';
        }

        if (str_starts_with($digits, '966')) {
            $digits = substr($digits, 3);
        } elseif (str_starts_with($digits, '00966')) {
            $digits = substr($digits, 5);
        }

        if (str_starts_with($digits, '965')) {
            $digits = substr($digits, 3);
        }

        if (str_starts_with($digits, '0')) {
            $digits = substr($digits, 1);
        }

        if (strlen($digits) > 11) {
            $digits = substr($digits, 0, 11);
        }

        return $digits !== '' ? $digits : '500000000';
    }
}
