<?php

namespace App\Services;

class MyFatoorahInvoiceService
{
    public function __construct(
        private readonly MyFatoorahClient $client,
    ) {}

    public static function fromConfig(): self
    {
        return new self(MyFatoorahClient::fromConfig());
    }

    /**
     * @param  array{
     *     amount: float,
     *     customer_name: string,
     *     phone: string,
     *     email?: ?string,
     *     customer_reference: string,
     *     language?: string
     * }  $params
     * @return array{success: bool, invoice_id: ?int, invoice_url: ?string, error: ?string}
     */
    public function createInvoiceLink(array $params): array
    {
        $language = ($params['language'] ?? 'ar') === 'en' ? 'en' : 'ar';

        $payload = [
            'NotificationOption' => 'Lnk',
            'InvoiceValue' => (float) $params['amount'],
            'CustomerName' => filled($params['customer_name'] ?? null)
                ? trim((string) $params['customer_name'])
                : 'Customer',
            'CustomerEmail' => filled($params['email'] ?? null)
                ? trim((string) $params['email'])
                : (string) config('services.myfatoorah.placeholder_email'),
            'CustomerMobile' => $this->normalizePhoneForMyFatoorah((string) $params['phone']),
            'CustomerReference' => (string) $params['customer_reference'],
            'CallBackUrl' => $this->paymentAbsoluteUrl('payment.callback'),
            'ErrorUrl' => $this->paymentAbsoluteUrl('payment.error'),
            'Language' => $language === 'ar' ? 'ar' : 'en',
            'DisplayCurrencyIso' => 'SAR',
        ];

        $result = $this->client->sendPayment($payload);

        if (! ($result['IsSuccess'] ?? false)) {
            return [
                'success' => false,
                'invoice_id' => null,
                'invoice_url' => null,
                'error' => $this->formatMyFatoorahError($result),
            ];
        }

        $invoiceId = $result['Data']['InvoiceId'] ?? null;
        $invoiceUrl = $result['Data']['InvoiceURL'] ?? null;

        if (! filled($invoiceUrl)) {
            return [
                'success' => false,
                'invoice_id' => null,
                'invoice_url' => null,
                'error' => 'Payment URL was not returned by the gateway.',
            ];
        }

        return [
            'success' => true,
            'invoice_id' => filled($invoiceId) ? (int) $invoiceId : null,
            'invoice_url' => (string) $invoiceUrl,
            'error' => null,
        ];
    }

    public function paymentAbsoluteUrl(string $routeName): string
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
    public function formatMyFatoorahError(array $result): ?string
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

    public function normalizePhoneForMyFatoorah(string $phone): string
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
