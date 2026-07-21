<?php

namespace App\Livewire\Public;

use App\Models\Dokumen;
use App\Models\DokumenKategori;
use Livewire\Component;

class DokumenList extends Component
{
    public $activeKategori = ''; // Menyimpan slug kategori aktif
    public $search = '';

    protected $queryString = [
        'activeKategori' => ['as' => 'kategori', 'except' => ''],
        'search'         => ['except' => ''],
    ];

    public function mount()
    {
        if (empty($this->activeKategori)) {
            // Ambil kategori pertama yang aktif
            $firstKat = DokumenKategori::whereHas('dokumens', function($q) {
                $q->where('is_active', true);
            })->first();
            
            $this->activeKategori = $firstKat ? $firstKat->slug : '';
        }
    }

    public function render()
    {
        $query = Dokumen::where('is_active', true);

        // Filter berdasarkan kategori yang dipilih (lewat slug)
        if ($this->activeKategori) {
            $query->whereHas('kategoriRelation', function($q) {
                $q->where('slug', $this->activeKategori);
            });
        }
        
        if ($this->search) {
            $query->where('judul', 'like', '%' . $this->search . '%');
        }

        $dokumens = $query->orderBy('order')->get();

        // Ambil semua kategori dari database
        $allKategoris = DokumenKategori::orderBy('name')->get();

        // Hanya tampilkan kategori yang memiliki dokumen aktif
        $availableKategoris = DokumenKategori::whereHas('dokumens', function($q) {
            $q->where('is_active', true);
        })->orderBy('name')->get();

        return view('livewire.public.dokumen-list', [
            'dokumens'           => $dokumens,
            'kategoris'          => $availableKategoris,
            'allKategoris'       => $allKategoris,
        ])->layout('layouts.frontend');
    }
}
