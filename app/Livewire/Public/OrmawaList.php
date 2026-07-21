<?php

namespace App\Livewire\Public;

use App\Models\Ormawa;
use Livewire\Component;

class OrmawaList extends Component
{
    public $activeTab = '';

    protected $queryString = [
        'activeTab' => ['as' => 'tab', 'except' => ''],
    ];

    public function mount()
    {
        // Baca dari query string ?tab=... jika ada
        if (empty($this->activeTab)) {
            $first = Ormawa::first();
            $this->activeTab = $first->slug ?? 'bem';
        }
    }

    public function switchTab($tab)
    {
        $this->activeTab = $tab;
    }

    public function render()
    {
        $ormawas = Ormawa::get();

        $activeOrmawa = Ormawa::where('slug', $this->activeTab)->first();

        // Fallback ke yang pertama jika slug tidak ditemukan
        if (!$activeOrmawa) {
            $activeOrmawa = $ormawas->first();
            $this->activeTab = $activeOrmawa?->slug ?? '';
        }

        return view('livewire.public.ormawa-list', [
            'ormawas'      => $ormawas,
            'activeOrmawa' => $activeOrmawa,
        ])->layout('layouts.frontend');
    }
}
