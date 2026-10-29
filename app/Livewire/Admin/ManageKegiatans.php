<?php

namespace App\Livewire\Admin;

use App\Models\Kegiatan;
use App\Models\Ormawa;
use Livewire\Component;
use Livewire\WithFileUploads;
use Illuminate\Support\Str;

class ManageKegiatans extends Component
{
    use WithFileUploads;

    // List properties
    public $search = '';
    
    // Form properties
    public $kegiatanId = null;
    public $title = '';
    public $slug = '';
    public $banner_path = '';
    public $banner_file = null; // for upload
    public $description = '';
    public $date = '';
    public $location = '';
    public $ormawa_id = null;

    public $isFormOpen = false;

    protected $rules = [
        'title' => 'required|string|max:255',
        'slug' => 'required|string|unique:kegiatans,slug',
        'banner_path' => 'nullable|string',
        'banner_file' => 'nullable|image|max:2048', // max 2MB
        'description' => 'nullable|string',
        'date' => 'nullable|date',
        'location' => 'nullable|string|max:255',
        'ormawa_id' => 'nullable|exists:ormawas,id',
    ];

    public function mount()
    {
        if (!auth()->user() || !auth()->user()->hasAccessToMenu('kegiatans')) {
            abort(403, 'Anda tidak memiliki hak akses untuk halaman ini.');
        }
    }

    public function updatedTitle($value)
    {
        $this->slug = Str::slug($value);
    }

    public function openForm($id = null)
    {
        $this->resetValidation();
        $this->resetExcept(['search', 'isFormOpen']);

        if ($id) {
            $kegiatan = Kegiatan::findOrFail($id);
            $this->kegiatanId = $kegiatan->id;
            $this->title = $kegiatan->title;
            $this->slug = $kegiatan->slug;
            $this->banner_path = $kegiatan->banner_path;
            $this->description = $kegiatan->description;
            $this->date = $kegiatan->date ? $kegiatan->date->format('Y-m-d') : '';
            $this->location = $kegiatan->location;
            $this->ormawa_id = $kegiatan->ormawa_id;
        } else {
            $this->kegiatanId = null;
            $this->title = '';
            $this->slug = '';
            $this->banner_path = '';
            $this->banner_file = null;
            $this->description = '';
            $this->date = date('Y-m-d');
            $this->location = '';
            $this->ormawa_id = null;
        }

        $this->isFormOpen = true;
    }

    public function closeForm()
    {
        $this->isFormOpen = false;
    }

    public function save()
    {
        $rules = $this->rules;
        if ($this->kegiatanId) {
            $rules['slug'] = 'required|string|unique:kegiatans,slug,' . $this->kegiatanId;
        }

        $this->validate($rules);

        $data = [
            'title' => $this->title,
            'slug' => $this->slug,
            'description' => $this->description,
            'date' => $this->date ?: null,
            'location' => $this->location,
            'ormawa_id' => $this->ormawa_id ?: null,
        ];

        // Handle File Upload if selected
        if ($this->banner_file) {
            $path = $this->banner_file->store('banners', 'public');
            $data['banner_path'] = asset('storage/' . $path);
        } else {
            $data['banner_path'] = $this->banner_path;
        }

        if ($this->kegiatanId) {
            Kegiatan::findOrFail($this->kegiatanId)->update($data);
            session()->flash('message', 'Kegiatan berhasil diperbarui.');
        } else {
            Kegiatan::create($data);
            session()->flash('message', 'Kegiatan berhasil ditambahkan.');
        }

        $this->isFormOpen = false;
    }

    public function delete($id)
    {
        Kegiatan::findOrFail($id)->delete();
        session()->flash('message', 'Kegiatan berhasil dihapus.');
    }

    public function render()
    {
        $kegiatans = Kegiatan::with('ormawa')
            ->where('title', 'like', '%' . $this->search . '%')
            ->latest()
            ->paginate(10);

        $ormawas = Ormawa::all();

        return view('livewire.admin.manage-kegiatans', [
            'kegiatans' => $kegiatans,
            'ormawas' => $ormawas
        ])->layout('layouts.app');
    }
}
