<?php

namespace App\Console\Commands;

use App\Models\Customer;
use App\Services\MyFatoorahClient;
use App\Support\MyFatoorahInvoiceStatus;
use Illuminate\Console\Command;

class ReconcileMyFatoorahPayments extends Command
{
    /**
     * @var string
     */
    protected $signature = 'payments:reconcile-myfatoorah';

    /**
     * @var string
     */
    protected $description = 'مزامنة العملاء «بانتظار الدفع» مع MyFatoorah عبر رقم الفاتورة المحفوظ';

    public function handle(MyFatoorahClient $client): int
    {
        if (! filled(config('services.myfatoorah.api_key'))) {
            $this->error('MYFATOORAH_API_KEY غير مضبوط في البيئة.');

            return Command::FAILURE;
        }

        $pending = Customer::query()
            ->where('payment_status', 'pending')
            ->whereNotNull('myfatoorah_invoice_id')
            ->orderBy('id')
            ->get();

        if ($pending->isEmpty()) {
            $this->info('لا توجد طلبات بانتظار الدفع تحتوي على رقم فاتورة.');

            return Command::SUCCESS;
        }

        $updated = 0;

        foreach ($pending as $customer) {
            $invoiceId = (string) $customer->myfatoorah_invoice_id;
            $payload = $this->pollInvoiceStatus($client, $invoiceId);

            if ($payload === [] || ! ($payload['IsSuccess'] ?? false)) {
                $this->warn("#{$customer->id}: تعذّر الاستعلام — ".($payload['Message'] ?? '—'));

                continue;
            }

            $data = $payload['Data'] ?? [];

            if (! MyFatoorahInvoiceStatus::indicatesPaid($data)) {
                $this->line("#{$customer->id}: الحالة ".($data['InvoiceStatus'] ?? '—').' — لم يُحدَّث (تحقق من البطاقة التجريبية وروابط الرجوع).');

                continue;
            }

            $customer->update([
                'payment_status' => 'paid',
                'paid_at' => now(),
            ]);

            $this->info("#{$customer->id}: تم التحديث إلى مدفوع.");
            $updated++;
        }

        $this->info("تم تحديث {$updated} سجلًا.");

        return Command::SUCCESS;
    }

    /**
     * @return array<string, mixed>
     */
    private function pollInvoiceStatus(MyFatoorahClient $client, string $invoiceId): array
    {
        $attempts = (int) config('services.myfatoorah.status_poll_attempts', 14);
        $delayMs = (int) config('services.myfatoorah.status_poll_delay_ms', 450);

        $lastPayload = [];

        for ($i = 0; $i < $attempts; $i++) {
            if ($i > 0) {
                usleep($delayMs * 1000);
            }

            $lastPayload = $client->getPaymentStatusByInvoiceId($invoiceId);

            if ($lastPayload === [] || ! ($lastPayload['IsSuccess'] ?? false)) {
                continue;
            }

            $data = $lastPayload['Data'] ?? [];

            if (MyFatoorahInvoiceStatus::indicatesPaid($data)) {
                return $lastPayload;
            }

            $invoiceStatus = (string) ($data['InvoiceStatus'] ?? '');
            if (MyFatoorahInvoiceStatus::indicatesTerminalFailure($invoiceStatus)) {
                return $lastPayload;
            }
        }

        return $lastPayload;
    }
}
