<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class MedicalTreatment extends Model
{
    use HasFactory;

    protected $fillable = [
        'medical_examination_id',
        'note',
        'start',
        'end',
    ];

    public function medical_examination():BelongsTo
    {
        return $this->belongsTo(MedicalExamination::class);
    }

}
