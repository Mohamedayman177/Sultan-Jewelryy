<?php

namespace App\Support;

final class MyFatoorahInvoiceStatus
{
    /**
     * @param  array<string, mixed>  $data
     */
    public static function indicatesPaid(array $data): bool
    {
        /** @see https://docs.myfatoorah.com/docs/get-payment-status — قد تُرجع القيم مسافات زائدة */
        $invoiceStatus = strtolower(trim((string) ($data['InvoiceStatus'] ?? '')));
        if ($invoiceStatus === 'paid') {
            return true;
        }

        $transactions = $data['InvoiceTransactions'] ?? [];
        if (! is_array($transactions)) {
            return false;
        }

        foreach ($transactions as $tx) {
            if (! is_array($tx)) {
                continue;
            }
            $txStatus = strtolower(trim((string) ($tx['TransactionStatus'] ?? '')));
            // الوثائق: Succss (إملائي)، وأحياناً Success
            if ($txStatus === 'succss' || $txStatus === 'success' || str_contains($txStatus, 'succ')) {
                return true;
            }
        }

        return false;
    }

    public static function indicatesTerminalFailure(string $invoiceStatus): bool
    {
        $s = strtolower(trim($invoiceStatus));

        return in_array($s, ['canceled', 'cancelled', 'failed', 'voided', 'refunded'], true);
    }

    /**
     * للعرض والمقارنة مع Pending بعد التقليم.
     */
    public static function normalizedInvoiceStatus(array $data): string
    {
        return trim((string) ($data['InvoiceStatus'] ?? ''));
    }
}
