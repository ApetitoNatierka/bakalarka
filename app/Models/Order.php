<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Order extends Model
{
    use HasFactory;

    protected $fillable = [
        'state',
        'created',
        'customer_id',
    ];

    public function order_lines():HasMany
    {
        return $this->hasMany(OrderLine::class);
    }

    public function customer():BelongsTo {
        return $this->belongsTo(User::class);
    }

    public function get_state() {
        return $this->attributes['state'];
    }

    public function get_id()
    {
        return $this->attributes['id'];
    }

    public function get_created()
    {
        return $this->attributes['created'];
    }

    public function get_total_gross_amount() {
        return $this->order_lines->sum(function($orderLine) {
            return $orderLine->get_total_gross_amount();
        });
    }

    public function get_total_net_amount() {
        return $this->order_lines->sum(function($orderLine) {
            return $orderLine->get_total_net_amount();
        });
    }

}
