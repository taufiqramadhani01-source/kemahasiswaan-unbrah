<?php

namespace App\Livewire\Public;

use App\Models\Prestasi;
use Livewire\Component;
use Livewire\WithPagination;

class PrestasiList extends Component
{
    use WithPagination;

    public $search = '';
    public $filterLevel = '';
    public $filterYear = '';

    protected $queryString = [
        'search' => ['except' => ''],
        'filterLevel' => ['except' => '', 'as' => 'tingkat'],
        'filterYear' => ['except' => '', 'as' => 'tahun'],
    ];

    public function updatingSearch(): void
    {
        $this->resetPage();
    }

    public function updatingFilterLevel(): void
    {
        $this->resetPage();
    }

    public function updatingFilterYear(): void
    {
        $this->resetPage();
    }

    public function render()
    {
        $query = Prestasi::query();

        if (!empty($this->search)) {
            $query->where(function($q) {
                $q->where('student_name', 'like', '%' . $this->search . '%')
                  ->orWhere('title', 'like', '%' . $this->search . '%');
            });
        }

        if (!empty($this->filterLevel)) {
            $query->where('level', $this->filterLevel);
        }

        if (!empty($this->filterYear)) {
            $query->where('year', $this->filterYear);
        }

        $prestasis = $query->latest()->paginate(12);

        // Ambil list tahun & tingkat unik dari database untuk pilihan filter dropdown
        $years = Prestasi::select('year')->distinct()->orderBy('year', 'desc')->pluck('year');
        $levels = Prestasi::select('level')->distinct()->orderBy('level', 'asc')->pluck('level');

        return view('livewire.public.prestasi-list', [
            'prestasis' => $prestasis,
            'years' => $years,
            'levels' => $levels,
        ])->layout('layouts.frontend');
    }
}
