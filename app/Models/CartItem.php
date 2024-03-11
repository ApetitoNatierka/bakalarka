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
        'product_id',
        'name',
        'unit_price',
        'quantity',
    ];

    public function cart():BelongsTo
    {
        return $this->belongsTo(Cart::class);
    }

    public function get_id() {
        return $this->attributes['id'];
    }

    public function get_item_id() {
        return $this->attributes['product_id'];
    }

    public function get_name() {
        return $this->attributes['name'];
    }

    public function get_unit_price() {
        return $this->attributes['unit_price'];
    }

    public function get_quantity() {
        return $this->attributes['quantity'];
    }
}
