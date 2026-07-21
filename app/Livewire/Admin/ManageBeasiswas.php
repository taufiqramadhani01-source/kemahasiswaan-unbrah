<?php

namespace App\Livewire\Admin;

use App\Models\Beasiswa;
use Livewire\Component;

class ManageBeasiswas extends Component
{
    public $search = '';

    // Form fields
    public $beasiswaId = null;
    public $name = '';
    public $provider = '';
    public $description = '';
    public $requirements = '';
    public $registration_url = '';
    public $is_active = true;

    public $isFormOpen = false;

    protected $rules = [
        'name' => 'required|string|max:255',
        'provider' => 'nullable|string|max:255',
        'description' => 'nullable|string',
        'requirements' => 'nullable|string',
        'registration_url' => 'nullable|url|max:500',
        'is_active' => 'boolean',
    ];

    public function mount()
    {
        if (!auth()->user() || !auth()->user()->hasAccessToMenu('beasiswas')) {
            abort(403, 'Anda tidak memiliki hak akses untuk halaman ini.');
        }
    }

    public function openForm($id = null)
    {
        $this->resetValidation();
        $this->resetExcept(['search', 'isFormOpen']);

        if ($id) {
            $beasiswa = Beasiswa::findOrFail($id);
            $this->beasiswaId = $beasiswa->id;
            $this->name = $beasiswa->name;
            $this->provider = $beasiswa->provider;
            $this->description = $beasiswa->description;
            $this->requirements = $beasiswa->requirements;
            $this->registration_url = $beasiswa->registration_url ?? '';
            $this->is_active = $beasiswa->is_active;
        } else {
            $this->beasiswaId = null;
            $this->name = '';
            $this->provider = '';
            $this->description = '';
            $this->requirements = '';
            $this->registration_url = '';
            $this->is_active = true;
        }

        $this->isFormOpen = true;
    }

    public function closeForm()
    {
        $this->isFormOpen = false;
    }

    public function save()
    {
        $this->validate();

        $data = [
            'name' => $this->name,
            'provider' => $this->provider,
            'description' => $this->description,
            'requirements' => $this->requirements,
            'registration_url' => $this->registration_url ?: null,
            'is_active' => $this->is_active,
        ];

        if ($this->beasiswaId) {
            Beasiswa::findOrFail($this->beasiswaId)->update($data);
            session()->flash('message', 'Beasiswa berhasil diperbarui.');
        } else {
            Beasiswa::create($data);
            session()->flash('message', 'Beasiswa berhasil ditambahkan.');
        }

        $this->isFormOpen = false;
    }

    public function toggleStatus($id)
    {
        $beasiswa = Beasiswa::findOrFail($id);
        $beasiswa->update(['is_active' => !$beasiswa->is_active]);
        session()->flash('message', 'Status beasiswa berhasil diubah.');
    }

    public function delete($id)
    {
        Beasiswa::findOrFail($id)->delete();
        session()->flash('message', 'Beasiswa berhasil dihapus.');
    }

    public function render()
    {
        $beasiswas = Beasiswa::where('name', 'like', '%' . $this->search . '%')
            ->latest()
            ->paginate(10);

        return view('livewire.admin.manage-beasiswas', [
            'beasiswas' => $beasiswas
        ])->layout('layouts.app');
    }
}
