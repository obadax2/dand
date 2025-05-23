<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class PaymentLog extends Model
{
    protected $table = 'payment_log';

    protected $fillable = [
        'user_id',
        'amount',
        'payment_method',
        'transaction_id',
        'status',
    ];
}