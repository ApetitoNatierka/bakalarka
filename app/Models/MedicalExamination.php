<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;

class MedicalExamination extends Model
{
    use HasFactory;

    protected $fillable = [
        'medical_examination',
        'description',
    ];

    public function medical_treatments():HasMany
    {
        return $this->hasMany(MedicalTreatment::class);
    }
}
