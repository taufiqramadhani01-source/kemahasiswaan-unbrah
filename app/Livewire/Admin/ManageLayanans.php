<?php

namespace App\Livewire\Admin;

use App\Models\Layanan;
use Livewire\Component;
use Livewire\WithFileUploads;

class ManageLayanans extends Component
{
    use WithFileUploads;

    // Form state
    public $layananId       = null;
    public $slug            = '';
    public $title           = '';
    public $icon            = '';
    public $whatsapp_number = '';
    public $category        = 'Kemahasiswaan Universitas Baiturrahmah';
    public $fee             = 'Gratis Bagi Mahasiswa Aktif UNBRAH';
    public $content         = '';
    public $is_active       = true;
    public $order           = 0;
    
    public $isFormOpen = false;

    protected $rules = [
        'title'           => 'required|string|max:255',
        'slug'            => 'required|string|max:255|unique:layanans,slug',
        'icon'            => 'nullable|string|max:255',
        'whatsapp_number' => 'nullable|string|max:50',
        'category'        => 'nullable|string|max:255',
        'fee'             => 'nullable|string|max:255',
        'content'         => 'nullable|string',
        'is_active'       => 'boolean',
        'order'           => 'integer',
    ];

    public function mount()
    {
        if (!auth()->user() || !auth()->user()->hasAccessToMenu('layanans')) {
            abort(403, 'Anda tidak memiliki hak akses untuk halaman ini.');
        }
        $this->order = Layanan::max('order') + 1;
    }

    public function updatedTitle($value)
    {
        if (empty($this->layananId)) {
            $this->slug = \Illuminate\Support\Str::slug($value);
        }
    }

    // ─── Open / Close Form ───────────────────────────────────────────────────
    public function openForm($id = null)
    {
        $this->resetValidation();
        $this->layananId       = null;
        $this->title           = '';
        $this->slug            = '';
        $this->icon            = '';
        $this->whatsapp_number = '';
        $this->category        = 'Kemahasiswaan Universitas Baiturrahmah';
        $this->fee             = 'Gratis Bagi Mahasiswa Aktif UNBRAH';
        $this->content         = '';
        $this->is_active       = true;
        $this->order           = Layanan::max('order') + 1;

        if ($id) {
            $layanan = Layanan::findOrFail($id);
            $this->layananId       = $layanan->id;
            $this->title           = $layanan->title;
            $this->slug            = $layanan->slug;
            $this->icon            = $layanan->icon;
            $this->whatsapp_number = $layanan->whatsapp_number;
            $this->category        = $layanan->category ?? 'Kemahasiswaan Universitas Baiturrahmah';
            $this->fee             = $layanan->fee ?? 'Gratis Bagi Mahasiswa Aktif UNBRAH';
            $this->content         = $layanan->content;
            $this->is_active       = $layanan->is_active;
            $this->order           = $layanan->order;
        }

        $this->isFormOpen = true;
        $this->dispatch('form-opened');
    }

    public function closeForm()
    {
        $this->isFormOpen = false;
        $this->dispatch('form-closed');
    }

    // ─── Save ────────────────────────────────────────────────────────────────
    public function save()
    {
        if ($this->layananId) {
            $this->rules['slug'] = 'required|string|max:255|unique:layanans,slug,' . $this->layananId;
        }
        $this->validate();

        $data = [
            'title'           => $this->title,
            'slug'            => $this->slug,
            'icon'            => $this->icon,
            'whatsapp_number' => $this->whatsapp_number,
            'category'        => $this->category,
            'fee'             => $this->fee,
            'content'         => $this->content,
            'is_active'       => $this->is_active,
            'order'           => $this->order,
        ];

        if ($this->layananId) {
            Layanan::findOrFail($this->layananId)->update($data);
            session()->flash('message', 'Data Layanan berhasil diperbarui.');
        } else {
            Layanan::create($data);
            session()->flash('message', 'Data Layanan berhasil ditambahkan.');
        }

        $this->isFormOpen = false;
        $this->normalizeOrder();
    }

    private function normalizeOrder()
    {
        $layanans = Layanan::orderBy('order', 'asc')->get();
        foreach ($layanans as $index => $layanan) {
            $layanan->update(['order' => $index + 1]);
        }
    }

    // ─── Ubah Urutan (Move Up / Down) ────────────────────────────────────────
    public function moveUp(int $id)
    {
        $layanan = Layanan::findOrFail($id);
        $previous = Layanan::where('order', '<', $layanan->order)->orderBy('order', 'desc')->first();
        if ($previous) {
            $currentOrder = $layanan->order;
            $layanan->update(['order' => $previous->order]);
            $previous->update(['order' => $currentOrder]);
            $this->normalizeOrder();
        }
    }

    public function moveDown(int $id)
    {
        $layanan = Layanan::findOrFail($id);
        $next = Layanan::where('order', '>', $layanan->order)->orderBy('order', 'asc')->first();
        if ($next) {
            $currentOrder = $layanan->order;
            $layanan->update(['order' => $next->order]);
            $next->update(['order' => $currentOrder]);
            $this->normalizeOrder();
        }
    }

    // ─── Toggle & Delete ─────────────────────────────────────────────────────
    public function toggleActive($id)
    {
        $layanan = Layanan::findOrFail($id);
        $layanan->update(['is_active' => !$layanan->is_active]);
    }

    public function delete($id)
    {
        Layanan::findOrFail($id)->delete();
        $this->normalizeOrder();
        session()->flash('message', 'Data Layanan berhasil dihapus.');
    }

    // ─── Render ──────────────────────────────────────────────────────────────
    public function render()
    {
        $this->normalizeOrder();
        $layanans = Layanan::orderBy('order', 'asc')->get();
        return view('livewire.admin.manage-layanans', ['layanans' => $layanans])->layout('layouts.app');
    }
}
