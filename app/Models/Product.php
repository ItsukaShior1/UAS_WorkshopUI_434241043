<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Product extends Model
{
    use HasFactory;

    protected $fillable = [
        'name',
        'stock',
        'min',
        'price',
        'image_data',
        'image_mime',
    ];

    protected $casts = [
        'stock' => 'integer',
        'min' => 'integer',
        'price' => 'integer',
    ];
}
