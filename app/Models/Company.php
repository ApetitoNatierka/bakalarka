<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Relations\HasOne;

class Company extends Model
{
    use HasFactory;

    protected $fillable = [
        'company',
        'email',
        'type',
        'phone_number',
    ];

    public function address():HasOne
    {
        return $this->hasOne(Address::class);
    }

    public function users():HasMany
    {
        return $this->hasMany(User::class);
    }

    public static function allowedTypes(): array
    {
        return ['customer', 'supplier', 'mine'];
    }

}
