<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class Customer extends Model
{
    protected $fillable = [
        'service_id',
        'name',
        'national_id',
        'phone',
        'email',
        'locale',
        'payment_status',
        'myfatoorah_invoice_id',
        'paid_at',
    ];

    protected function casts(): array
    {
        return [
            'paid_at' => 'datetime',
        ];
    }

    public function service(): BelongsTo
    {
        return $this->belongsTo(Service::class);
    }

    /**
     * WhatsApp deep link after registration / successful payment.
     */
    public function whatsappContactUrl(): string
    {
        $number = preg_replace('/\D/', '', (string) config('services.whatsapp.number'));
        $service = $this->relationLoaded('service')
            ? $this->service
            : $this->service()->firstOrFail();

        $ar = $service->title_ar;
        $en = $service->title_en;

        $name = $this->name ?? '';
        $nid = $this->national_id ?? '';
        $phone = $this->phone;
        $email = $this->email ?? '';

        $text = "مرحباً، أرغب في المتابعة بخصوص الخدمة التالية:\n";
        $text .= "الخدمة: {$ar}\nService: {$en}\n\n";
        $text .= 'الاسم / Name: '.($name !== '' ? $name : '—')."\n";
        $text .= 'الهوية الوطنية / National ID: '.($nid !== '' ? $nid : '—')."\n";
        $text .= "الجوال / Mobile: {$phone}\n";
        $text .= 'البريد الإلكتروني / Email: '.($email !== '' ? $email : '—')."\n";

        return 'https://wa.me/'.$number.'?text='.rawurlencode($text);
    }
}
