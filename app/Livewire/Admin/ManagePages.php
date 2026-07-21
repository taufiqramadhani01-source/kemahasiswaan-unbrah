<?php

namespace App\Livewire\Admin;

use App\Models\Page;
use Livewire\Component;
use Livewire\WithPagination;

class ManagePages extends Component
{
    use WithPagination;

    public $search = '';

    protected $queryString = ['search'];

    public function mount()
    {
        if (!auth()->user() || !auth()->user()->hasAccessToMenu('pages')) {
            abort(403, 'Anda tidak memiliki hak akses untuk halaman ini.');
        }
    }

    public function updatingSearch(): void
    {
        $this->resetPage();
    }

    public function togglePublish(int $id): void
    {
        $page = Page::findOrFail($id);
        $page->update(['is_published' => ! $page->is_published]);
        session()->flash('message', 'Status publikasi halaman berhasil diubah.');
    }

    public function delete(int $id): void
    {
        $page = Page::findOrFail($id);
        $page->delete();
        session()->flash('message', 'Halaman berhasil dihapus.');
    }

    public function render()
    {
        $pages = Page::where('title', 'like', '%' . $this->search . '%')
            ->orWhere('slug', 'like', '%' . $this->search . '%')
            ->latest()
            ->paginate(10);

        return view('livewire.admin.manage-pages', ['pages' => $pages])
            ->layout('layouts.app');
    }
}
