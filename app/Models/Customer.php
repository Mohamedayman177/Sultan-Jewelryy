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
    ];

    public function service(): BelongsTo
    {
        return $this->belongsTo(Service::class);
    }
}
