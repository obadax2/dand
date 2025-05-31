<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Cart extends Model
{
    protected $table = 'cart'; // Table name should match the actual table name

    protected $fillable = [
        'user_id',
        'item_id',
        'item_type',
        'price',
        'quantity',
    ];

    public function item()
    {
        return $this->morphTo(null, 'item_type', 'item_id');
    }
    public function user()
{
    return $this->belongsTo(User::class);
}

}
