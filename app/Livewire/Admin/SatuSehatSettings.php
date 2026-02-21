<?php

namespace App\Livewire\Admin;

use App\Models\Clinic;
use Livewire\Attributes\Layout;
use Livewire\Component;

#[Layout('layouts.app')]
class SatuSehatSettings extends Component
{
    public $satusehat_client_id;
    public $satusehat_client_secret;
    public $satusehat_organization_id;

    public function mount()
    {
        $clinic = auth()->user()->clinic;
        $this->satusehat_client_id = $clinic->satusehat_client_id;
        $this->satusehat_client_secret = $clinic->satusehat_client_secret;
        $this->satusehat_organization_id = $clinic->satusehat_organization_id;
    }

    public function save()
    {
        $this->validate([
            'satusehat_client_id' => 'nullable|string|max:255',
            'satusehat_client_secret' => 'nullable|string|max:255',
            'satusehat_organization_id' => 'nullable|string|max:255',
        ]);

        $clinic = auth()->user()->clinic;
        $clinic->update([
            'satusehat_client_id' => $this->satusehat_client_id,
            'satusehat_client_secret' => $this->satusehat_client_secret,
            'satusehat_organization_id' => $this->satusehat_organization_id,
        ]);

        session()->flash('success', 'Pengaturan Satu Sehat berhasil disimpan!');
    }

    public function render()
    {
        return view('livewire.admin.satu-sehat-settings');
    }
}
