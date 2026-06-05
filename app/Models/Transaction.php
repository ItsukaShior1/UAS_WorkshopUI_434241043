<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Transaction extends Model
{
    use HasFactory;

    protected $fillable = [
        'type',
        'item_name',
        'category',
        'amount',
        'quantity',
        'unit_price',
        'notes',
        'product_id',
        'item_image_data',
        'item_image_mime',
    ];

    protected $casts = [
        'amount' => 'integer',
        'quantity' => 'integer',
        'unit_price' => 'integer',
        'product_id' => 'integer',
    ];
}
