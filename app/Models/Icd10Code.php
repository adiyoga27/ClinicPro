<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Icd10Code extends Model
{
    use HasFactory;

    protected $fillable = [
        'code',
        'name_id',
        'name_en',
    ];

    public function diagnoses(): HasMany
    {
        return $this->hasMany(Diagnosis::class, 'icd10_code_id');
    }
}
