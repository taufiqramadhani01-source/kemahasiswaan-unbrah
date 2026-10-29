<?php

namespace App\Livewire\Public;

use Livewire\Component;
use Livewire\WithPagination;
use App\Models\Beasiswa;

class BeasiswaList extends Component
{
    use WithPagination;

    public $search = '';
    public $providerFilter = '';

    public function updatingSearch()
    {
        $this->resetPage();
    }

    public function updatingProviderFilter()
    {
        $this->resetPage();
    }

    public function render()
    {
        $query = Beasiswa::where('is_active', true);

        if (!empty($this->search)) {
            $query->where(function ($q) {
                $q->where('name', 'like', '%' . $this->search . '%')
                  ->orWhere('provider', 'like', '%' . $this->search . '%');
            });
        }

        if (!empty($this->providerFilter)) {
            $query->where('provider', 'like', '%' . $this->providerFilter . '%');
        }

        $beasiswas = $query->orderBy('created_at', 'desc')->paginate(9);

        return view('livewire.public.beasiswa-list', [
            'beasiswas' => $beasiswas
        ])->layout('layouts.frontend', [
            'title' => 'Info Beasiswa - Kemahasiswaan Universitas Baiturrahmah',
            'metaDescription' => 'Daftar program beasiswa internal kampus, pemerintah KIP-Kuliah, LLDIKTI, dan kemitraan swasta di Universitas Baiturrahmah.'
        ]);
    }
}
