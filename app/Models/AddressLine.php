<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class AddressLine extends Model
{
    use HasFactory;

    protected $fillable = [
        'street',
        'house_number',
        'city',
        'region',
        'postal_code',
        'country',
        'address_id',
    ];

    public function adress():BelongsTo
    {
        return $this->belongsTo(Address::class);
    }
}


