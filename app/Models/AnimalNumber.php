<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class AnimalNumber extends Model
{
    use HasFactory;

    protected $fillable = [
        'animal_number',
        'description',
    ];
}
