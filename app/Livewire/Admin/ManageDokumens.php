<?php

namespace App\Livewire\Admin;

use App\Models\Dokumen;
use App\Models\DokumenKategori;
use Livewire\Component;
use Livewire\WithFileUploads;
use Illuminate\Support\Facades\Storage;

class ManageDokumens extends Component
{
    use WithFileUploads;

    // State form dokumen
    public $dokumenId           = null;
    public $dokumen_kategori_id = '';
    public $judul               = '';
    public $deskripsi           = '';
    public $url_file            = '';
    public $uploaded_file       = null; // untuk upload file lokal
    public $tipe_file           = 'pdf';
    public $ukuran_file         = '';
    public $is_active           = true;
    public $order               = 0;

    // State form kategori baru (modal inline)
    public $isKategoriFormOpen = false;
    public $kategoriName       = '';

    public $isFormOpen   = false;
    public $filterKategori = '';
    public $search = '';

    protected $rules = [
        'dokumen_kategori_id' => 'required|exists:dokumen_kategoris,id',
        'judul'               => 'required|string|max:255',
        'deskripsi'           => 'nullable|string',
        'url_file'            => 'nullable|string|max:500',
        'uploaded_file'       => 'nullable|file|max:10240', // max 10MB
        'tipe_file'           => 'required|in:pdf,link,docx,xlsx',
        'ukuran_file'         => 'nullable|string|max:50',
        'is_active'           => 'boolean',
        'order'               => 'integer',
    ];

    public function mount()
    {
        if (!auth()->user() || !auth()->user()->hasAccessToMenu('dokumens')) {
            abort(403, 'Anda tidak memiliki hak akses untuk halaman ini.');
        }
    }

    // ─── Open / Close Form ───────────────────────────────────────────────────
    public function openForm($id = null)
    {
        $this->resetValidation();
        $this->dokumenId           = null;
        
        // Ambil default kategori pertama jika ada
        $firstKat = DokumenKategori::first();
        $this->dokumen_kategori_id = $firstKat ? $firstKat->id : '';

        $this->judul               = '';
        $this->deskripsi           = '';
        $this->url_file            = '';
        $this->uploaded_file       = null;
        $this->tipe_file           = 'pdf';
        $this->ukuran_file         = '';
        $this->is_active           = true;
        $this->order               = Dokumen::max('order') + 1;

        if ($id) {
            $dok = Dokumen::findOrFail($id);
            $this->dokumenId           = $dok->id;
            $this->dokumen_kategori_id = $dok->dokumen_kategori_id;
            $this->judul               = $dok->judul;
            $this->deskripsi           = $dok->deskripsi;
            $this->url_file            = $dok->url_file;
            $this->tipe_file           = $dok->tipe_file;
            $this->ukuran_file         = $dok->ukuran_file;
            $this->is_active           = $dok->is_active;
            $this->order               = $dok->order;
        }
        $this->isFormOpen = true;
    }

    public function closeForm()
    {
        $this->isFormOpen = false;
    }

    // ─── Save Dokumen ────────────────────────────────────────────────────────
    public function save()
    {
        $this->validate();

        $data = [
            'dokumen_kategori_id' => $this->dokumen_kategori_id,
            'judul'               => $this->judul,
            'deskripsi'           => $this->deskripsi,
            'url_file'            => $this->url_file,
            'tipe_file'           => $this->tipe_file,
            'ukuran_file'         => $this->ukuran_file,
            'is_active'           => $this->is_active,
            'order'               => $this->order,
        ];

        // Jika ada file yang di-upload
        if ($this->uploaded_file) {
            // Dapatkan ekstensi file
            $ext = strtolower($this->uploaded_file->getClientOriginalExtension());
            
            // Simpan ke folder public/dokumens
            $path = $this->uploaded_file->store('dokumens', 'public');
            $data['url_file'] = Storage::url($path);

            // Set tipe file otomatis
            if (in_array($ext, ['pdf', 'docx', 'xlsx'])) {
                $data['tipe_file'] = $ext;
            } else {
                $data['tipe_file'] = 'link';
            }

            // Hitung ukuran file otomatis (dalam MB atau KB)
            $bytes = $this->uploaded_file->getSize();
            if ($bytes >= 1048576) {
                $data['ukuran_file'] = number_format($bytes / 1048576, 1) . ' MB';
            } else {
                $data['ukuran_file'] = number_format($bytes / 1024, 0) . ' KB';
            }
        }

        if ($this->dokumenId) {
            Dokumen::findOrFail($this->dokumenId)->update($data);
            session()->flash('message', 'Dokumen berhasil diperbarui.');
        } else {
            Dokumen::create($data);
            session()->flash('message', 'Dokumen berhasil ditambahkan.');
        }

        $this->uploaded_file = null;
        $this->isFormOpen = false;
    }

    // ─── Manage Kategori Dinamis ─────────────────────────────────────────────
    public function openKategoriForm()
    {
        $this->kategoriName = '';
        $this->isKategoriFormOpen = true;
    }

    public function saveKategori()
    {
        $this->validate([
            'kategoriName' => 'required|string|max:100|unique:dokumen_kategoris,name',
        ]);

        $slug = \Illuminate\Support\Str::slug($this->kategoriName);
        
        $newKat = DokumenKategori::create([
            'name' => $this->kategoriName,
            'slug' => $slug
        ]);

        $this->dokumen_kategori_id = $newKat->id;
        $this->isKategoriFormOpen = false;
        
        session()->flash('kategori_message', 'Kategori baru berhasil ditambahkan.');
    }

    // ─── Actions ─────────────────────────────────────────────────────────────
    public function toggleActive($id)
    {
        $dok = Dokumen::findOrFail($id);
        $dok->update(['is_active' => !$dok->is_active]);
    }

    public function delete($id)
    {
        Dokumen::findOrFail($id)->delete();
        session()->flash('message', 'Dokumen berhasil dihapus.');
    }

    // ─── Render ──────────────────────────────────────────────────────────────
    public function render()
    {
        $query = Dokumen::query();

        if ($this->filterKategori) {
            $query->where('dokumen_kategori_id', $this->filterKategori);
        }
        if ($this->search) {
            $query->where('judul', 'like', '%' . $this->search . '%');
        }

        $dokumens = $query->with('kategoriRelation')->orderBy('dokumen_kategori_id')->orderBy('order')->get();
        $kategoris = DokumenKategori::orderBy('name')->get();

        return view('livewire.admin.manage-dokumens', [
            'dokumens'  => $dokumens,
            'kategoris' => $kategoris,
        ])->layout('layouts.app');
    }
}
