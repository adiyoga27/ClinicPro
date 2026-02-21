<?php

namespace App\Models;

use App\Traits\BelongsToClinic;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Patient extends Model
{
    use HasFactory, BelongsToClinic;

    protected $fillable = [
        'clinic_id',
        'medical_record_no',
        'nik',
        'name',
        'birth_date',
        'gender',
        'address',
        'phone',
        'blood_type',
        'photo_path',
        'mother_name',
        'mother_nik',
        'deposit_balance',
        'satu_sehat_patient_id',
    ];

    protected $casts = [
        'birth_date' => 'date',
        'deposit_balance' => 'decimal:2',
    ];

    public function medicalRecords(): HasMany
    {
        return $this->hasMany(MedicalRecord::class);
    }

    public function depositTransactions(): HasMany
    {
        return $this->hasMany(DepositTransaction::class);
    }

    public function appointments(): HasMany
    {
        return $this->hasMany(Appointment::class);
    }

    public function queues(): HasMany
    {
        return $this->hasMany(ClinicQueue::class);
    }

    public function billings(): HasMany
    {
        return $this->hasMany(Billing::class);
    }
}
