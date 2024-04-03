<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class Supply extends Model
{
    use HasFactory;

    protected $fillable = [
        'supply_number_id',
        'weight',
        'height',
        'status',
        'description',
        'quantity',
        'units',
        'warehouse_id',
    ];

    public function supply_number():BelongsTo
    {
        return $this->belongsTo(SupplyNumber::class);
    }

    public function warehouse(): BelongsTo
    {
        return $this->belongsTo(Warehouse::class);
    }
}
