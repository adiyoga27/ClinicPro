<?php

namespace App\Livewire\Admin;

use App\Models\Room;
use Livewire\Attributes\Layout;
use Livewire\Component;

#[Layout('layouts.app')]
class RoomManager extends Component
{
    public string $name = '';
    public bool $showForm = false;
    public ?int $editingId = null;

    protected $rules = [
        'name' => 'required|string|max:255',
    ];

    public function create(): void
    {
        $this->reset(['name', 'editingId']);
        $this->showForm = true;
    }

    public function edit(int $id): void
    {
        $room = Room::findOrFail($id);
        $this->editingId = $room->id;
        $this->name = $room->name;
        $this->showForm = true;
    }

    public function save(): void
    {
        $this->validate();

        if ($this->editingId) {
            Room::findOrFail($this->editingId)->update(['name' => $this->name]);
            session()->flash('success', 'Poli / Ruangan berhasil diperbarui.');
        } else {
            Room::create([
                'clinic_id' => auth()->user()->clinic_id,
                'name' => $this->name,
            ]);
            session()->flash('success', 'Poli / Ruangan berhasil ditambahkan.');
        }

        $this->showForm = false;
        $this->reset(['name', 'editingId']);
    }

    public function syncSatuSehat(int $id, \App\Services\SatuSehatService $satuSehatService)
    {
        $room = Room::findOrFail($id);
        
        // Build Location Resource
        $payload = [
            'resourceType' => 'Location',
            'identifier' => [
                [
                    'system' => 'http://sys-ids.kemkes.go.id/location/' . $satuSehatService->getOrganizationId(),
                    'value' => 'R-' . $room->id,
                ]
            ],
            'status' => 'active',
            'name' => $room->name,
            'description' => $room->name,
            'mode' => 'instance',
            'physicalType' => [
                'coding' => [
                    [
                        'system' => 'http://terminology.hl7.org/CodeSystem/location-physical-type',
                        'code' => 'ro',
                        'display' => 'Room'
                    ]
                ]
            ],
            'managingOrganization' => [
                'reference' => 'Organization/' . $satuSehatService->getOrganizationId()
            ]
        ];

        // If location has no ID, we POST. If it has, we probably should PUT, but let's just create for now.
        // Actually Kemenkes requires POST /Location then storing the ID.
        if ($room->satusehat_id) {
            session()->flash('success', 'Ruangan ini sudah tersinkronisasi (ID: ' . $room->satusehat_id . ').');
            return;
        }

        $result = $satuSehatService->sendResource('Location', $payload);

        if ($result['success'] && isset($result['data']['id'])) {
            $room->update(['satusehat_id' => $result['data']['id']]);
            session()->flash('success', 'Berhasil melakukan sinkronisasi Poli/Ruangan ke Satu Sehat (ID: ' . $room->satusehat_id . ').');
        } else {
            session()->flash('error', 'Gagal sinkronisasi: ' . ($result['error'] ?? 'Terjadi kesalahan pada integrasi Satu Sehat.'));
        }
    }

    public function pullFromSatuSehat(\App\Services\SatuSehatService $satuSehatService): void
    {
        $result = $satuSehatService->getLocations();

        if ($result['success']) {
            $entries = $result['data']['entry'] ?? [];
            $count = 0;
            $clinicId = auth()->user()->clinic_id;

            foreach ($entries as $entry) {
                $resource = $entry['resource'] ?? null;
                if ($resource && isset($resource['id'])) {
                    $existingRoom = Room::where('satusehat_id', $resource['id'])->first();
                    
                    if (!$existingRoom) {
                        Room::create([
                            'clinic_id' => $clinicId,
                            'name' => $resource['name'] ?? 'Ruangan ' . ($count + 1),
                            'satusehat_id' => $resource['id'],
                        ]);
                        $count++;
                    }
                }
            }

            if ($count > 0) {
                session()->flash('success', "Berhasil menarik $count ruangan baru dari Satu Sehat.");
            } else {
                session()->flash('success', "Tidak ada data ruangan baru yang dapat ditarik dari Satu Sehat.");
            }
        } else {
            session()->flash('error', 'Gagal menarik data ruangan: ' . ($result['error'] ?? 'Terjadi kesalahan pada integrasi Satu Sehat.'));
        }
    }

    public function delete(int $id): void
    {
        Room::findOrFail($id)->delete();
        session()->flash('success', 'Poli / Ruangan berhasil dihapus.');
    }

    public function render()
    {
        return view('livewire.admin.room-manager', [
            'rooms' => Room::where('clinic_id', auth()->user()->clinic_id)->latest()->get(),
        ]);
    }
}
