<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class CartItem extends Model
{
    use HasFactory;

    protected $fillable = [
        'cart_id',
        'itemable_id',
        'itemable_type',
        'quantity',
    ];

    public function cart():BelongsTo
    {
        return $this->belongsTo(Cart::class);
    }
}
