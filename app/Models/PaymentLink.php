<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class PaymentLink extends Model
{
    public const REFERENCE_PREFIX = 'PL';

    protected $fillable = [
        'customer_name',
        'phone',
        'email',
        'amount',
        'description',
        'payment_status',
        'myfatoorah_invoice_id',
        'invoice_url',
        'paid_at',
        'created_by',
    ];

    protected function casts(): array
    {
        return [
            'amount' => 'decimal:2',
            'paid_at' => 'datetime',
        ];
    }

    public function creator(): BelongsTo
    {
        return $this->belongsTo(User::class, 'created_by');
    }

    public function myfatoorahCustomerReference(): string
    {
        return self::REFERENCE_PREFIX.$this->id;
    }

    public static function idFromMyfatoorahReference(?string $reference): ?int
    {
        if (! filled($reference)) {
            return null;
        }

        if (! preg_match('/^'.self::REFERENCE_PREFIX.'(\d+)$/', trim($reference), $matches)) {
            return null;
        }

        return (int) $matches[1];
    }
}
