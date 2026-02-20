<?php

namespace App\Models;

use App\Traits\BelongsToClinic;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class ClinicQueue extends Model
{
    use HasFactory, BelongsToClinic;

    protected $table = 'clinic_queues';

    protected $fillable = [
        'clinic_id',
        'patient_id',
        'doctor_id',
        'appointment_id',
        'queue_no',
        'date',
        'status',
        'checked_in_at',
    ];

    protected $casts = [
        'date' => 'date',
        'checked_in_at' => 'datetime',
    ];

    public function patient(): BelongsTo
    {
        return $this->belongsTo(Patient::class);
    }

    public function doctor(): BelongsTo
    {
        return $this->belongsTo(User::class, 'doctor_id');
    }

    public function appointment(): BelongsTo
    {
        return $this->belongsTo(Appointment::class);
    }

    public function medicalRecord()
    {
        return $this->hasOne(MedicalRecord::class, 'queue_id');
    }

    public static function nextQueueNo(int $clinicId, string $date): int
    {
        return static::withoutGlobalScopes()
            ->where('clinic_id', $clinicId)
            ->where('date', $date)
            ->max('queue_no') + 1;
    }
}
