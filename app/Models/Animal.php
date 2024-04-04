<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class Animal extends Model
{
    use HasFactory;

    protected $fillable = [
        'animal_number_id',
        'weight',
        'height',
        'born',
        'condition',
        'gender',
        'warehouse_id',
    ];

    public function animal_number():BelongsTo
    {
        return $this->belongsTo(AnimalNumber::class);
    }

    public function warehouse(): BelongsTo
    {
        return $this->belongsTo(Warehouse::class);
    }

}