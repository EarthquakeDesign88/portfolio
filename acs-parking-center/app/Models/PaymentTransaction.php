<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class PaymentTransaction extends Model
{
    use HasFactory;

    protected $fillable = [
        'payment_status',
        'payment_method_id',
        'paid_datetime',
        'fee',
        'parking_record_id',
    ];
}
