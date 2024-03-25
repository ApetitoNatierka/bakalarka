<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Organisation extends Model
{
    use HasFactory;

    protected $fillable = [
        'organisation',
        'email',
        'phone_number',
        'address_id',
    ];

    public function address():BelongsTo
    {
        return $this->belongsTo(Address::class);
    }

    public function employees():HasMany
    {
        return $this->hasMany(Employee::class);
    }

    public function get_num_of_employees(){
        return $this->employees->count();
    }
}
