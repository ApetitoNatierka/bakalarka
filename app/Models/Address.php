<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Relations\HasOne;

class Address extends Model
{
    use HasFactory;

    protected $fillable = [
        'address_name',
    ];

    public function addresses():HasMany
    {
        return $this->hasMany(AddressLine::class);
    }

    public function companies():HasMany
    {
        return $this->hasMany(Company::class);
    }

    public function users():HasMany
    {
        return $this->hasMany(User::class);
    }

}
