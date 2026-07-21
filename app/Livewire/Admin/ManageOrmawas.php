<?php

namespace App\Livewire\Admin;

use App\Models\Ormawa;
use Livewire\Component;
use Livewire\WithFileUploads;
use Illuminate\Support\Str;

class ManageOrmawas extends Component
{
    use WithFileUploads;

    // List properties
    public $search = '';
    
    // Form properties
    public $ormawaId = null;
    public $name = '';
    public $slug = '';
    public $logo_path = '';
    public $logo_file = null; // for upload logo
    public $description = '';
    public $structure = []; // Array of ['role' => '', 'name' => '', 'photo' => '']
    public $focus_items = []; // Array of string

    // Temporary upload storage per member index
    // e.g. [0 => LivewireTemporaryUploadedFile]
    public $member_photos = [];

    public $isFormOpen = false;

    protected $rules = [
        'name'        => 'required|string|max:255',
        'slug'        => 'required|string|unique:ormawas,slug',
        'logo_path'   => 'nullable|string',
        'logo_file'   => 'nullable|image|max:1024',  // max 1MB
        'description' => 'nullable|string',
        'structure'   => 'nullable|array',
        'focus_items' => 'nullable|array',
    ];

    public function updatedName($value)
    {
        $this->slug = Str::slug($value);
    }

    public function mount()
    {
        if (!auth()->user() || !auth()->user()->hasAccessToMenu('ormawas')) {
            abort(403, 'Anda tidak memiliki hak akses untuk halaman ini.');
        }
    }

    public function openForm($id = null)
    {
        $this->resetValidation();
        $this->resetExcept(['isFormOpen', 'search']);
        $this->member_photos = [];

        if ($id) {
            $ormawa = Ormawa::findOrFail($id);
            $this->ormawaId    = $ormawa->id;
            $this->name        = $ormawa->name;
            $this->slug        = $ormawa->slug;
            $this->logo_path   = $ormawa->logo_path;
            $this->image_path  = $ormawa->image_path;
            $this->description = $ormawa->description;
            $this->structure   = $ormawa->structure ?? [];
            $this->focus_items = $ormawa->focus_items ?? [];
        } else {
            $this->ormawaId    = null;
            $this->name        = '';
            $this->slug        = '';
            $this->logo_path   = '';
            $this->logo_file   = null;
            $this->image_path  = '';
            $this->image_file  = null;
            $this->description = '';
            $this->focus_items = [];
            $this->structure   = [
                ['role' => 'Ketua Umum', 'name' => '', 'photo' => ''],
                ['role' => 'Sekretaris', 'name' => '', 'photo' => '']
            ];
        }

        $this->isFormOpen = true;
    }

    public function closeForm()
    {
        $this->isFormOpen = false;
    }

    // JSON Structure Management
    public function addStructureItem()
    {
        $this->structure[] = ['role' => '', 'name' => '', 'photo' => ''];
    }

    public function removeStructureItem($index)
    {
        unset($this->structure[$index]);
        $this->structure = array_values($this->structure);
        if (isset($this->member_photos[$index])) {
            unset($this->member_photos[$index]);
        }
    }

    // Hook otomatis dipicu Livewire setelah file member_photos selesai terunggah
    public function updatedMemberPhotos($file, $index)
    {
        $this->validate([
            "member_photos.{$index}" => 'image|max:1024', // max 1MB
        ]);

        if (isset($this->member_photos[$index])) {
            $path = $this->member_photos[$index]->store('members', 'public');
            $this->structure[$index]['photo'] = '/storage/' . $path;
        }
    }



    // Focus Items Management
    public function addFocusItem()
    {
        $this->focus_items[] = '';
    }

    public function removeFocusItem($index)
    {
        unset($this->focus_items[$index]);
        $this->focus_items = array_values($this->focus_items);
    }

    public function save()
    {
        $rules = $this->rules;
        if ($this->ormawaId) {
            $rules['slug'] = 'required|string|unique:ormawas,slug,' . $this->ormawaId;
        }

        $this->validate($rules);

        // Bersihkan focus items yang kosong
        $this->focus_items = array_values(array_filter($this->focus_items, fn($item) => trim($item) !== ''));

        $data = [
            'name'        => $this->name,
            'slug'        => $this->slug,
            'description' => $this->description,
            'structure'   => $this->structure,
            'focus_items' => $this->focus_items,
        ];

        // Handle File Logo Upload
        if ($this->logo_file) {
            $path = $this->logo_file->store('logos', 'public');
            $data['logo_path'] = '/storage/' . $path; // Pakai relative path
        } else {
            $data['logo_path'] = $this->logo_path;
        }

        $data['image_path'] = null; // Dihapus seperti instruksi user

        if ($this->ormawaId) {
            Ormawa::findOrFail($this->ormawaId)->update($data);
            session()->flash('message', 'Organisasi berhasil diperbarui.');
        } else {
            Ormawa::create($data);
            session()->flash('message', 'Organisasi berhasil ditambahkan.');
        }

        $this->isFormOpen = false;
    }

    public function delete($id)
    {
        Ormawa::findOrFail($id)->delete();
        session()->flash('message', 'Organisasi berhasil dihapus.');
    }

    public function render()
    {
        $ormawas = Ormawa::where('name', 'like', '%' . $this->search . '%')
            ->latest()
            ->paginate(10);

        return view('livewire.admin.manage-ormawas', [
            'ormawas' => $ormawas
        ])->layout('layouts.app');
    }
}
