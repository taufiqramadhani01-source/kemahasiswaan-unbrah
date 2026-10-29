<?php

namespace App\Livewire\Public;

use Livewire\Component;

class LayananList extends Component
{
    public $activeTab = 'beasiswa';

    protected $queryString = ['activeTab' => ['as' => 'tab']];

    public function mount()
    {
        // Mendeteksi query string ?tab=... jika ada
        if (request()->has('tab')) {
            $this->activeTab = request()->query('tab');
        }
    }

    public function switchTab($tab)
    {
        $this->activeTab = $tab;
    }

    public function render()
    {
        $tabs = \App\Models\Layanan::where('is_active', true)->orderBy('order', 'asc')->get();

        if ($tabs->isNotEmpty() && !$tabs->contains('slug', $this->activeTab)) {
            $this->activeTab = $tabs->first()->slug;
        }
        
        $beasiswas = [];
        if ($this->activeTab === 'beasiswa') {
            $beasiswas = \App\Models\Beasiswa::orderBy('is_active', 'desc')
                ->latest()
                ->get();
        }

        return view('livewire.public.layanan-list', [
            'tabs' => $tabs,
            'beasiswas' => $beasiswas,
        ])->layout('layouts.frontend');
    }
}
