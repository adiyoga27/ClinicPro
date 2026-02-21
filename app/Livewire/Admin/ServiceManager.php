<?php

namespace App\Livewire\Admin;

use App\Models\Service;
use Livewire\Attributes\Layout;
use Livewire\Component;
use Livewire\WithPagination;

#[Layout('layouts.app')]
class ServiceManager extends Component
{
    use WithPagination;

    public $search = '';

    // Form
    public $showModal = false;
    public $editingId = null;
    public $name = '';
    public $price = 0;
    public $is_active = true;
    public $is_automatic = false;

    protected function rules()
    {
        return [
            'name' => 'required|min:2',
            'price' => 'required|numeric|min:0',
        ];
    }

    public function updatingSearch()
    {
        $this->resetPage();
    }

    public function openModal($id = null)
    {
        $this->resetValidation();

        if ($id) {
            $service = Service::findOrFail($id);
            $this->editingId = $id;
            $this->name = $service->name;
            $this->price = $service->price;
            $this->is_active = $service->is_active;
            $this->is_automatic = $service->is_automatic;
        } else {
            $this->editingId = null;
            $this->name = '';
            $this->price = 0;
            $this->is_active = true;
            $this->is_automatic = false;
        }

        $this->showModal = true;
    }

    public function save()
    {
        $this->validate();

        $data = [
            'clinic_id' => auth()->user()->clinic_id,
            'name' => $this->name,
            'price' => $this->price,
            'is_active' => $this->is_active,
            'is_automatic' => $this->is_automatic,
        ];

        if ($this->editingId) {
            Service::findOrFail($this->editingId)->update($data);
            session()->flash('success', 'Layanan berhasil diperbarui.');
        } else {
            Service::create($data);
            session()->flash('success', 'Layanan berhasil ditambahkan.');
        }

        $this->showModal = false;
    }

    public function delete($id)
    {
        Service::findOrFail($id)->delete();
        session()->flash('success', 'Layanan berhasil dihapus.');
    }

    public function toggleActive($id)
    {
        $service = Service::findOrFail($id);
        $service->update(['is_active' => !$service->is_active]);
    }

    public function render()
    {
        $services = Service::where('clinic_id', auth()->user()->clinic_id)
            ->when($this->search, fn($q) => $q->where('name', 'like', "%{$this->search}%"))
            ->orderBy('name')
            ->paginate(15);

        return view('livewire.admin.service-manager', [
            'services' => $services,
        ]);
    }
}
