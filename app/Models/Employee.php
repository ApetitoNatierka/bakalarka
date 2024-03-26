<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class Employee extends Model
{
    use HasFactory;

    protected $fillable = [
        'surname',
        'last_name',
        'position',
        'birth_date',
        'start_date',
        'identification_number',
        'department',
        'organisation_id',
        'user_id',
    ];

    public function organisation():BelongsTo
    {
        return $this->belongsTo(Organisation::class);
    }

    public function user():BelongsTo
    {
        return $this->belongsTo(User::class);
    }

}
