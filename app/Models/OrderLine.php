<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class OrderLine extends Model
{
    use HasFactory;

    protected $fillable = [
        'quantity',
        'unit_price',
        'order_id',
        'product_id',
    ];

    public function order():BelongsTo
    {
        return $this->belongsTo(Order::class);
    }

    public function product():BelongsTo
    {
        return $this->belongsTo(Product::class);
    }

    public function get_quantity() {
        return $this->attributes['quantity'];
    }

    public function get_unit_price() {
        return $this->attributes['unit_price'];
    }

    public function get_total_amount() {
        return $this->quantity * $this->unit_price;
    }

    public function get_product_name() {
        return $this->product->name;
    }
}
