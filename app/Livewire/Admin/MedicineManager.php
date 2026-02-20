<?php

namespace App\Livewire\Admin;

use App\Models\Medicine;
use Livewire\Attributes\Layout;
use Livewire\Component;
use Livewire\WithPagination;

#[Layout('layouts.app')]
class MedicineManager extends Component
{
    use WithPagination;

    public $search = '';
    public $filterCategory = '';

    // Form
    public $showModal = false;
    public $editingId = null;
    public $name = '';
    public $generic_name = '';
    public $category = 'tablet';
    public $unit = 'pcs';
    public $price = 0;
    public $stock = 0;
    public $is_active = true;

    // Doctor fee
    public $doctorFee;
    public $showFeeModal = false;

    protected function rules()
    {
        return [
            'name' => 'required|min:2',
            'category' => 'required',
            'unit' => 'required',
            'price' => 'required|numeric|min:0',
            'stock' => 'required|integer|min:0',
        ];
    }

    public function mount()
    {
        $this->doctorFee = auth()->user()->clinic->doctor_fee ?? 50000;
    }

    public function updatingSearch()
    {
        $this->resetPage();
    }

    public function updatingFilterCategory()
    {
        $this->resetPage();
    }

    public function openModal($id = null)
    {
        $this->resetValidation();

        if ($id) {
            $medicine = Medicine::findOrFail($id);
            $this->editingId = $id;
            $this->name = $medicine->name;
            $this->generic_name = $medicine->generic_name;
            $this->category = $medicine->category;
            $this->unit = $medicine->unit;
            $this->price = $medicine->price;
            $this->stock = $medicine->stock;
            $this->is_active = $medicine->is_active;
        } else {
            $this->editingId = null;
            $this->name = '';
            $this->generic_name = '';
            $this->category = 'tablet';
            $this->unit = 'pcs';
            $this->price = 0;
            $this->stock = 0;
            $this->is_active = true;
        }

        $this->showModal = true;
    }

    public function save()
    {
        $this->validate();

        $data = [
            'clinic_id' => auth()->user()->clinic_id,
            'name' => $this->name,
            'generic_name' => $this->generic_name ?: null,
            'category' => $this->category,
            'unit' => $this->unit,
            'price' => $this->price,
            'stock' => $this->stock,
            'is_active' => $this->is_active,
        ];

        if ($this->editingId) {
            Medicine::findOrFail($this->editingId)->update($data);
            session()->flash('success', 'Obat berhasil diperbarui.');
        } else {
            Medicine::create($data);
            session()->flash('success', 'Obat berhasil ditambahkan.');
        }

        $this->showModal = false;
    }

    public function delete($id)
    {
        Medicine::findOrFail($id)->delete();
        session()->flash('success', 'Obat berhasil dihapus.');
    }

    public function toggleActive($id)
    {
        $medicine = Medicine::findOrFail($id);
        $medicine->update(['is_active' => !$medicine->is_active]);
    }

    public function saveDoctorFee()
    {
        $this->validate(['doctorFee' => 'required|numeric|min:0']);
        auth()->user()->clinic->update(['doctor_fee' => $this->doctorFee]);
        $this->showFeeModal = false;
        session()->flash('success', 'Jasa dokter berhasil diperbarui.');
    }

    public function render()
    {
        $medicines = Medicine::where('clinic_id', auth()->user()->clinic_id)
            ->when($this->search, fn($q) => $q->where(
                fn($q2) =>
                $q2->where('name', 'like', "%{$this->search}%")
                    ->orWhere('generic_name', 'like', "%{$this->search}%")
            ))
            ->when($this->filterCategory, fn($q) => $q->where('category', $this->filterCategory))
            ->orderBy('name')
            ->paginate(15);

        return view('livewire.admin.medicine-manager', [
            'medicines' => $medicines,
            'categories' => ['tablet', 'kapsul', 'sirup', 'salep', 'injeksi', 'infus', 'tetes', 'lainnya'],
        ]);
    }
}
