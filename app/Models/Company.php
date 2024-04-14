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
        'ICO',
        'DIC',
        'address_id',
    ];

    public function address():BelongsTo
    {
        return $this->belongsTo(Address::class);
    }

    public function users():HasMany
    {
        return $this->hasMany(User::class);
    }

    public function get_id()
    {
        return $this->attributes['id'];
    }

    public function get_company()
    {
        return $this->attributes['company'];
    }

    public function get_email()
    {
        return $this->attributes['email'];
    }

    public function get_type()
    {
        return $this->attributes['type'];
    }

    public function get_phone_number()
    {
        return $this->attributes['phone_number'];
    }

    public static function allowedTypes(): array
    {
        return ['customer', 'supplier', 'mine'];
    }

}
