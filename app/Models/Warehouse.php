<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Warehouse extends Model
{
    use HasFactory;

    protected $fillable = [
        'warehouse',
        'location',
        'capacity',
        'user_id',
    ];


    public function items():HasMany
    {
        return $this->hasMany(Item::class);
    }

    public function user():BelongsTo {
        return $this->belongsTo(User::class);
    }
}