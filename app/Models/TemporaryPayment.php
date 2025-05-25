<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class TemporaryPayment extends Model
{
    protected $fillable = ['user_id', 'order_id', 'blog_id'];

}
