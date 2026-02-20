<?php

namespace App\Observers;

use App\Jobs\SyncToSatuSehat;
use App\Models\Payment;
use Illuminate\Support\Facades\Log;

class PaymentObserver
{
    /**
     * When a payment is updated to 'paid', dispatch Satu Sehat sync
     * for the related medical record (via billing → medical_record).
     */
    public function updated(Payment $payment): void
    {
        // Only trigger when status changes to 'paid'
        if ($payment->isDirty('status') && $payment->status === 'paid') {
            $billing = $payment->billing()->with('medicalRecord')->first();

            if ($billing && $billing->medical_record_id) {
                Log::info("PaymentObserver: Dispatching Satu Sehat sync for medical_record #{$billing->medical_record_id}");

                SyncToSatuSehat::dispatch(
                    $billing->medical_record_id,
                    $payment->clinic_id
                );
            }
        }
    }
}
