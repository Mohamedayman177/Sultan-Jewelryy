<?php

namespace App\Http\Controllers;

use App\Models\Customer;
use App\Models\PaymentLink;
use App\Services\MyFatoorahClient;
use App\Support\MyFatoorahInvoiceStatus;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;
use Illuminate\View\View;

class PaymentCallbackController extends Controller
{
    /**
     * Successful payment redirect from MyFatoorah (includes ?paymentId= or ?Id=).
     */
    public function callback(Request $request, MyFatoorahClient $client): RedirectResponse
    {
        $paymentId = $this->paymentIdFromRequest($request);
        if (! filled($paymentId)) {
            return redirect()->route('payment.error', ['reason' => 'missing_payment']);
        }

        $result = $this->evaluatePaymentStatus($client, (string) $paymentId);

        if (isset($result['redirect_route'])) {
            return redirect()->route($result['redirect_route']);
        }

        if ($result['redirect_url'] !== null) {
            return redirect()->away($result['redirect_url']);
        }

        $query = array_filter([
            'paymentId' => $paymentId,
            'reason' => $result['error_query']['reason'] ?? null,
            'invoice_status' => $result['error_query']['invoice_status'] ?? null,
        ], fn ($v) => $v !== null && $v !== '');

        return redirect()->route('payment.error', $query);
    }

    /**
     * Failed / cancelled payment (ErrorUrl). MyFatoorah may still append paymentId — verify via API.
     */
    public function error(Request $request, MyFatoorahClient $client): View|RedirectResponse
    {
        $paymentId = $this->paymentIdFromRequest($request);

        if (! filled($paymentId)) {
            return view('payment.error', [
                'reason' => $request->query('reason'),
                'paymentId' => null,
                'invoice_status' => null,
                'gateway_message' => null,
            ]);
        }

        $result = $this->evaluatePaymentStatus($client, (string) $paymentId);

        if (isset($result['redirect_route'])) {
            return redirect()->route($result['redirect_route']);
        }

        if ($result['redirect_url'] !== null) {
            return redirect()->away($result['redirect_url']);
        }

        return view('payment.error', [
            'reason' => $result['error_view']['reason'] ?? $request->query('reason'),
            'paymentId' => $paymentId,
            'invoice_status' => $result['error_view']['invoice_status'] ?? $request->query('invoice_status'),
            'gateway_message' => $result['error_view']['gateway_message'] ?? null,
        ]);
    }

    private function paymentIdFromRequest(Request $request): ?string
    {
        $raw = $request->query('paymentId') ?? $request->query('Id');

        return filled($raw) ? (string) $raw : null;
    }

    /**
     * @return array{
     *     redirect_url: ?string,
     *     redirect_route: ?string,
     *     error_query: array{reason?: string, invoice_status?: string},
     *     error_view: array{reason?: string, invoice_status?: ?string, gateway_message?: ?string}
     * }
     */
    private function evaluatePaymentStatus(MyFatoorahClient $client, string $paymentId): array
    {
        $statusPayload = $this->fetchPaymentStatusWithRetries($client, $paymentId);

        $emptyError = [
            'redirect_url' => null,
            'redirect_route' => null,
            'error_query' => ['reason' => 'status_failed'],
            'error_view' => [
                'reason' => 'status_failed',
                'invoice_status' => null,
                'gateway_message' => is_array($statusPayload) ? ($statusPayload['Message'] ?? null) : null,
            ],
        ];

        if ($statusPayload === [] || ! ($statusPayload['IsSuccess'] ?? false)) {
            return $emptyError;
        }

        $data = $statusPayload['Data'] ?? [];
        $invoiceStatusRaw = MyFatoorahInvoiceStatus::normalizedInvoiceStatus($data);
        $paymentLink = $this->resolvePaymentLinkFromPaymentData($data);
        $customer = $paymentLink ? null : $this->resolveCustomerFromPaymentData($data);

        if (MyFatoorahInvoiceStatus::indicatesPaid($data) && $paymentLink) {
            if ($paymentLink->payment_status !== 'paid') {
                $paymentLink->update([
                    'payment_status' => 'paid',
                    'paid_at' => now(),
                ]);
            }

            return [
                'redirect_url' => null,
                'redirect_route' => 'payment.success',
                'error_query' => [],
                'error_view' => [],
            ];
        }

        if (MyFatoorahInvoiceStatus::indicatesPaid($data) && $customer) {
            if ($customer->payment_status !== 'paid') {
                $customer->update([
                    'payment_status' => 'paid',
                    'paid_at' => now(),
                ]);
            }
            $customer->loadMissing('service');

            return [
                'redirect_url' => $customer->whatsappContactUrl(),
                'redirect_route' => null,
                'error_query' => [],
                'error_view' => [],
            ];
        }

        if (MyFatoorahInvoiceStatus::indicatesPaid($data) && ! $customer && ! $paymentLink) {
            return [
                'redirect_url' => null,
                'redirect_route' => null,
                'error_query' => ['reason' => 'customer'],
                'error_view' => [
                    'reason' => 'customer',
                    'invoice_status' => 'Paid',
                    'gateway_message' => $statusPayload['Message'] ?? null,
                ],
            ];
        }

        if ($paymentLink !== null && MyFatoorahInvoiceStatus::indicatesTerminalFailure($invoiceStatusRaw)) {
            $paymentLink->update(['payment_status' => 'failed']);
        }

        if ($customer !== null && MyFatoorahInvoiceStatus::indicatesTerminalFailure($invoiceStatusRaw)) {
            $customer->update(['payment_status' => 'failed']);
        }

        $stillPending = strtolower($invoiceStatusRaw) === 'pending';

        if ($stillPending) {
            Log::warning('MyFatoorah invoice still Pending after status polling', [
                'payment_id' => $paymentId,
                'attempts' => config('services.myfatoorah.status_poll_attempts'),
                'invoice_status_raw' => $invoiceStatusRaw,
            ]);
        }

        return [
            'redirect_url' => null,
            'redirect_route' => null,
            'error_query' => [
                'reason' => $stillPending ? 'still_pending' : 'not_paid',
                'invoice_status' => $invoiceStatusRaw,
            ],
            'error_view' => [
                'reason' => $stillPending ? 'still_pending' : 'not_paid',
                'invoice_status' => $invoiceStatusRaw !== '' ? $invoiceStatusRaw : null,
                'gateway_message' => $statusPayload['Message'] ?? null,
            ],
        ];
    }

    /**
     * بعد إعادة التوجيه من صفحة الدفع غالباً تكون InvoiceStatus = Pending لعدة ثوانٍ.
     *
     * @return array<string, mixed>
     */
    private function fetchPaymentStatusWithRetries(MyFatoorahClient $client, string $paymentId): array
    {
        $attempts = (int) config('services.myfatoorah.status_poll_attempts', 14);
        $delayMs = (int) config('services.myfatoorah.status_poll_delay_ms', 450);

        $lastPayload = [];

        for ($i = 0; $i < $attempts; $i++) {
            if ($i > 0) {
                usleep($delayMs * 1000);
            }

            $lastPayload = $client->getPaymentStatus($paymentId);

            if ($lastPayload === [] || ! ($lastPayload['IsSuccess'] ?? false)) {
                continue;
            }

            $data = $lastPayload['Data'] ?? [];

            if (MyFatoorahInvoiceStatus::indicatesPaid($data)) {
                return $lastPayload;
            }

            $invoiceStatusRaw = MyFatoorahInvoiceStatus::normalizedInvoiceStatus($data);
            if (MyFatoorahInvoiceStatus::indicatesTerminalFailure($invoiceStatusRaw)) {
                return $lastPayload;
            }
        }

        return $lastPayload;
    }

    /**
     * @param  array<string, mixed>  $data
     */
    private function resolvePaymentLinkFromPaymentData(array $data): ?PaymentLink
    {
        $ref = isset($data['CustomerReference']) ? trim((string) $data['CustomerReference']) : null;
        $linkId = PaymentLink::idFromMyfatoorahReference($ref);
        if ($linkId !== null) {
            $paymentLink = PaymentLink::query()->find($linkId);
            if ($paymentLink) {
                return $paymentLink;
            }
        }

        $invoiceId = $data['InvoiceId'] ?? null;
        if (filled($invoiceId)) {
            return PaymentLink::query()
                ->where('myfatoorah_invoice_id', (int) $invoiceId)
                ->first();
        }

        return null;
    }

    /**
     * @param  array<string, mixed>  $data
     */
    private function resolveCustomerFromPaymentData(array $data): ?Customer
    {
        $ref = isset($data['CustomerReference']) ? trim((string) $data['CustomerReference']) : null;
        if (filled($ref) && is_numeric($ref)) {
            $customer = Customer::query()->find((int) $ref);
            if ($customer) {
                return $customer;
            }
        }

        $invoiceId = $data['InvoiceId'] ?? null;
        if (filled($invoiceId)) {
            return Customer::query()
                ->where('myfatoorah_invoice_id', (int) $invoiceId)
                ->first();
        }

        return null;
    }
}
