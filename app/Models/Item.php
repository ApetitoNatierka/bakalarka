<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class Item extends Model
{
    use HasFactory;

    protected $fillable = [
        'item_type',
        'quantity',
        'warehouse_id',
        'item_no',
    ];

    public function warehouse(): BelongsTo
    {
        return $this->belongsTo(Warehouse::class);
    }

    public function item_things()
    {
        if ($this->item_type === 'animal') {
            return Animal::whereHas('animal_number', function ($query) {
                $query->where('animal_number', '=',$this->item_no )
                    ->where('warehouse_id', '=', $this->warehouse_id);
            })->get();
        } elseif ($this->item_type === 'supply') {
            return Supply::whereHas('supply_number', function ($query) {
                $query->where('supply_number', '=',$this->item_no )
                    ->where('warehouse_id', '=', $this->warehouse_id);
            })->get();
        }

        return null;
    }





}
