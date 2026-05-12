<?php

namespace App\Services;

use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Log;

class MyFatoorahClient
{
    public function __construct(
        private readonly string $baseUrl,
        private readonly string $apiKey,
    ) {}

    public static function fromConfig(): self
    {
        return new self(
            rtrim((string) config('services.myfatoorah.base_url'), '/'),
            (string) config('services.myfatoorah.api_key'),
        );
    }

    /**
     * @return array<string, mixed>
     */
    public function sendPayment(array $payload): array
    {
        $json = $this->postJson('/v2/SendPayment', $payload);

        return $json ?? [];
    }

    /**
     * @return array<string, mixed>
     */
    public function getPaymentStatus(string $paymentId): array
    {
        return $this->getPaymentStatusWithKeyTypes((string) $paymentId, ['PaymentId', 'paymentid']);
    }

    /**
     * استعلام بحسب رقم الفاتورة في MyFatoorah (مزامنة عندما لا يصل التوجيه إلى التطبيق).
     *
     * @return array<string, mixed>
     */
    public function getPaymentStatusByInvoiceId(string $invoiceId): array
    {
        return $this->getPaymentStatusWithKeyTypes((string) $invoiceId, ['InvoiceId', 'invoiceid']);
    }

    /**
     * @param  array<int, string>  $keyTypes
     * @return array<string, mixed>
     */
    private function getPaymentStatusWithKeyTypes(string $key, array $keyTypes): array
    {
        $last = [];

        foreach ($keyTypes as $keyType) {
            $last = $this->postJson('/v2/GetPaymentStatus', [
                'Key' => $key,
                'KeyType' => $keyType,
            ]);

            if ($last !== [] && ($last['IsSuccess'] ?? false)) {
                return $last;
            }
        }

        return $last;
    }

    /**
     * @return array<string, mixed>
     */
    private function postJson(string $path, array $body): array
    {
        try {
            $response = Http::withHeaders([
                'Authorization' => 'Bearer '.$this->apiKey,
                'Content-Type' => 'application/json',
                'Accept' => 'application/json',
            ])
                ->timeout((int) config('services.myfatoorah.timeout', 45))
                ->post($this->baseUrl.$path, $body);

            if (! $response->successful()) {
                Log::warning('MyFatoorah API HTTP error', [
                    'path' => $path,
                    'status' => $response->status(),
                    'body' => $response->body(),
                ]);

                return [];
            }

            return $response->json() ?? [];
        } catch (\Throwable $e) {
            Log::warning('MyFatoorah API request failed', [
                'path' => $path,
                'message' => $e->getMessage(),
            ]);

            return [];
        }
    }
}
