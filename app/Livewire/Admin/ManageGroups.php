<?php

namespace App\Livewire\Admin;

use App\Models\Group;
use Livewire\Component;

class ManageGroups extends Component
{
    // Form state
    public $groupId = null;
    public $name = '';
    public $description = '';
    public $accessible_menus = []; // array of selected menu keys

    public $isFormOpen = false;
    public $search = '';

    // Daftar menu yang tersedia untuk di-centang
    public static function menuOptions(): array
    {
        return [
            'dashboard'  => '📊 Dashboard Utama',
            'homepage'   => '🏠 Pengaturan Beranda (Slider & WR3)',
            'layanans'   => '🛡️ Layanan Mahasiswa',
            'ormawas'    => '👥 Organisasi (Ormawa)',
            'beasiswas'  => '🎓 Info Beasiswa',
            'prestasis'  => '🏆 Prestasi Mahasiswa',
            'dokumens'   => '📥 Dokumen & Unduhan',
            'posts'      => '📰 Berita & Artikel',
            'kegiatans'  => '📅 Agenda Kegiatan',
            'pages'      => '📄 Halaman Dinamis (CMS)',
            'menus'      => '🔗 Navigasi Menu Utama',
            'users'      => '🔑 Kelola Akun Admin',
            'settings'   => '🎨 Pengaturan Tema & Warna',
        ];
    }

    public function mount()
    {
        if (!auth()->user() || !auth()->user()->isSuperAdmin()) {
            abort(403, 'Anda tidak memiliki hak akses untuk halaman ini.');
        }
    }

    // ─── Open / Close Form ───────────────────────────────────────────────────
    public function openForm($id = null)
    {
        $this->resetValidation();
        $this->groupId = null;
        $this->name = '';
        $this->description = '';
        $this->accessible_menus = [];

        if ($id) {
            $group = Group::findOrFail($id);
            $this->groupId = $group->id;
            $this->name = $group->name;
            $this->description = $group->description;
            $this->accessible_menus = $group->accessible_menus ?? [];
        }

        $this->isFormOpen = true;
    }

    public function closeForm()
    {
        $this->isFormOpen = false;
    }

    // ─── Save Group ──────────────────────────────────────────────────────────
    public function save()
    {
        $this->validate([
            'name' => 'required|string|max:100',
            'description' => 'nullable|string|max:255',
            'accessible_menus' => 'nullable|array',
        ]);

        $data = [
            'name' => $this->name,
            'description' => $this->description,
            'accessible_menus' => $this->accessible_menus,
        ];

        if ($this->groupId) {
            Group::findOrFail($this->groupId)->update($data);
            session()->flash('message', 'Kelompok menu berhasil diperbarui.');
        } else {
            Group::create($data);
            session()->flash('message', 'Kelompok menu baru berhasil ditambahkan.');
        }

        $this->isFormOpen = false;
    }

    // ─── Delete ──────────────────────────────────────────────────────────────
    public function delete($id)
    {
        $group = Group::findOrFail($id);
        
        // Mencegah penghapusan jika masih ada user yang terhubung ke kelompok ini
        if ($group->users()->count() > 0) {
            session()->flash('error', 'Tidak dapat menghapus kelompok ini karena masih digunakan oleh beberapa user admin.');
            return;
        }

        $group->delete();
        session()->flash('message', 'Kelompok menu berhasil dihapus.');
    }

    // ─── Render ──────────────────────────────────────────────────────────────
    public function render()
    {
        $query = Group::query();

        if ($this->search) {
            $query->where('name', 'like', '%' . $this->search . '%')
                  ->orWhere('description', 'like', '%' . $this->search . '%');
        }

        $groups = $query->orderBy('name')->get();

        return view('livewire.admin.manage-groups', [
            'groups' => $groups,
            'menuOptions' => self::menuOptions()
        ])->layout('layouts.app');
    }
}
