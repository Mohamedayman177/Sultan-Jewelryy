<?php

namespace App\Http\Controllers;

use App\Http\Requests\StoreCustomerRequest;
use App\Models\Customer;
use App\Models\Service;
use App\Services\MyFatoorahClient;
use App\Support\CustomerFormHelper;
use Illuminate\Http\JsonResponse;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Storage;

class CustomerController extends Controller
{
    public function store(StoreCustomerRequest $request): JsonResponse
    {
        $data = $request->validated();
        $locale = ($data['locale'] ?? 'ar') === 'en' ? 'en' : 'ar';

        $service = Service::query()
            ->where('is_active', true)
            ->where('requires_registration', true)
            ->findOrFail((int) $data['service_id']);

        $itemCategory = (string) $data['item_category'];
        $formDetails = $itemCategory === 'gemstone'
            ? CustomerFormHelper::gemstoneDetailsFromRequest($data)
            : CustomerFormHelper::jewelryDetailsFromRequest($data);

        $customerPayload = [
            'name' => trim((string) $data['name']),
            'phone' => trim((string) $data['phone']),
            'email' => filled($data['email'] ?? null) ? trim((string) $data['email']) : null,
            'city' => trim((string) $data['city']),
            'service_id' => (int) $data['service_id'],
            'item_category' => $itemCategory,
            'form_details' => $formDetails,
            'locale' => $locale,
            'terms_accepted_at' => now(),
            'national_id' => null,
        ];

        if ($service->is_free) {
            $customer = Customer::create(array_merge($customerPayload, [
                'payment_status' => null,
            ]));
            $this->storeUploadedFiles($request, $customer);

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

        $customer = Customer::create(array_merge($customerPayload, [
            'payment_status' => 'pending',
        ]));
        $this->storeUploadedFiles($request, $customer);

        $client = MyFatoorahClient::fromConfig();

        $payload = [
            'NotificationOption' => 'Lnk',
            'InvoiceValue' => $invoiceValue,
            'CustomerName' => $customer->name,
            'CustomerEmail' => $customer->email
                ?? (string) config('services.myfatoorah.placeholder_email'),
            'CustomerMobile' => $this->normalizePhoneForMyFatoorah($customer->phone),
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
            $this->deleteCustomerFiles($customer);
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
            $this->deleteCustomerFiles($customer);
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

    private function storeUploadedFiles(StoreCustomerRequest $request, Customer $customer): void
    {
        $stored = [];
        $base = 'customer-submissions/'.$customer->id;

        if ($request->hasFile('photos')) {
            foreach ($request->file('photos') as $index => $file) {
                $path = $file->store($base.'/photos', 'public');
                $stored[] = [
                    'type' => 'photo',
                    'path' => $path,
                    'original_name' => $file->getClientOriginalName(),
                    'label' => 'صورة '.($index + 1),
                ];
            }
        }

        if ($request->hasFile('invoice')) {
            $file = $request->file('invoice');
            $path = $file->store($base.'/invoice', 'public');
            $stored[] = [
                'type' => 'invoice',
                'path' => $path,
                'original_name' => $file->getClientOriginalName(),
                'label' => 'فاتورة',
            ];
        }

        if ($request->hasFile('certificates')) {
            foreach ($request->file('certificates') as $index => $file) {
                $path = $file->store($base.'/certificates', 'public');
                $stored[] = [
                    'type' => 'certificate',
                    'path' => $path,
                    'original_name' => $file->getClientOriginalName(),
                    'label' => 'شهادة '.($index + 1),
                ];
            }
        }

        if ($stored !== []) {
            $customer->update(['attachments' => $stored]);
        }
    }

    private function deleteCustomerFiles(Customer $customer): void
    {
        foreach ($customer->attachments ?? [] as $file) {
            if (! empty($file['path'])) {
                Storage::disk('public')->delete($file['path']);
            }
        }
        Storage::disk('public')->deleteDirectory('customer-submissions/'.$customer->id);
    }

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
