<?php

namespace App\Livewire\Doctor;

use App\Models\ClinicQueue;
use Livewire\Attributes\Layout;
use Livewire\Component;

#[Layout('layouts.app')]
class QueueList extends Component
{
    public string $filterStatus = 'active'; // active, completed, all

    public function callPatient(int $queueId): void
    {
        $queue = ClinicQueue::where('id', $queueId)
            ->where('doctor_id', auth()->id())
            ->firstOrFail();

        // Set any current in_progress back to waiting
        ClinicQueue::where('doctor_id', auth()->id())
            ->where('date', today())
            ->where('status', 'in_progress')
            ->update(['status' => 'waiting']);

        $queue->update(['status' => 'in_progress']);

        session()->flash('success', "Memanggil pasien: {$queue->patient->name}");
    }

    public function completePatient(int $queueId): void
    {
        $queue = ClinicQueue::where('id', $queueId)
            ->where('doctor_id', auth()->id())
            ->firstOrFail();

        $queue->update(['status' => 'completed']);

        session()->flash('success', "Pasien {$queue->patient->name} selesai diperiksa.");
    }

    public function skipPatient(int $queueId): void
    {
        $queue = ClinicQueue::where('id', $queueId)
            ->where('doctor_id', auth()->id())
            ->firstOrFail();

        $queue->update(['status' => 'skipped']);

        session()->flash('success', "Pasien {$queue->patient->name} dilewati.");
    }

    public function render()
    {
        $doctorId = auth()->id();

        $query = ClinicQueue::where('doctor_id', $doctorId)
            ->where('date', today())
            ->with('patient')
            ->orderBy('queue_no');

        if ($this->filterStatus === 'active') {
            $query->whereIn('status', ['waiting', 'in_progress']);
        } elseif ($this->filterStatus === 'completed') {
            $query->whereIn('status', ['completed', 'skipped']);
        }

        return view('livewire.doctor.queue-list', [
            'queues' => $query->get(),
            'waitingCount' => ClinicQueue::where('doctor_id', $doctorId)
                ->where('date', today())
                ->where('status', 'waiting')
                ->count(),
            'inProgressCount' => ClinicQueue::where('doctor_id', $doctorId)
                ->where('date', today())
                ->where('status', 'in_progress')
                ->count(),
            'completedCount' => ClinicQueue::where('doctor_id', $doctorId)
                ->where('date', today())
                ->where('status', 'completed')
                ->count(),
        ]);
    }
}
