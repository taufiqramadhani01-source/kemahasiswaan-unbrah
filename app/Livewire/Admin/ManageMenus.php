<?php

namespace App\Livewire\Admin;

use App\Models\Menu;
use App\Models\Page;
use App\Models\Ormawa;
use App\Models\Layanan;
use Livewire\Component;

class ManageMenus extends Component
{
    // Form state
    public $menuId       = null;
    public $parent_id    = null;
    public $title        = '';
    public $type         = 'page';   // 'page' | 'external' | 'ormawa' | 'layanan'
    public $page_id      = null;
    public $ormawa_slug  = '';       // untuk tipe ormawa
    public $layanan_slug = '';       // untuk tipe layanan — pilih dari dropdown
    public $external_url = '';
    public $order        = 0;
    public $isFormOpen   = false;

    public function mount()
    {
        if (!auth()->user() || !auth()->user()->hasAccessToMenu('menus')) {
            abort(403, 'Anda tidak memiliki hak akses untuk halaman ini.');
        }
    }

    protected $rules = [
        'title'        => 'required|string|max:255',
        'type'         => 'required|in:page,external,ormawa,layanan',
        'page_id'      => 'nullable|exists:pages,id',
        'ormawa_slug'  => 'nullable|string',
        'layanan_slug' => 'nullable|string',
        'external_url' => 'nullable|string|max:500',
        'parent_id'    => 'nullable|exists:menus,id',
        'order'        => 'integer|min:0',
    ];

    // ─── Open / Close ────────────────────────────────────────────────────────
    public function openForm($id = null)
    {
        $this->resetValidation();
        $this->menuId       = null;
        $this->parent_id    = null;
        $this->title        = '';
        $this->type         = 'page';
        $this->page_id      = null;
        $this->ormawa_slug  = '';
        $this->layanan_slug = '';
        $this->external_url = '';
        $this->order        = 0;

        if ($id) {
            $menu = Menu::findOrFail($id);
            $this->menuId       = $menu->id;
            $this->parent_id    = $menu->parent_id;
            $this->title        = $menu->title;
            $this->type         = $menu->type;
            $this->page_id      = $menu->page_id;
            $this->external_url = $menu->external_url ?? '';
            $this->order        = $menu->order;

            // Deteksi ormawa_slug
            if ($menu->type === 'ormawa' || str_contains($menu->external_url ?? '', '/ormawa?tab=')) {
                $this->type        = 'ormawa';
                $this->ormawa_slug = str_replace('/ormawa?tab=', '', $menu->external_url);
            }

            // Deteksi layanan_slug
            if ($menu->type === 'layanan' || str_contains($menu->external_url ?? '', '/layanan?tab=')) {
                $this->type         = 'layanan';
                $this->layanan_slug = str_replace('/layanan?tab=', '', $menu->external_url);
            }
        }

        $this->isFormOpen = true;
    }

    public function openChildForm($parentId)
    {
        $this->openForm();
        $this->parent_id = $parentId;
    }

    public function closeForm()
    {
        $this->isFormOpen = false;
    }

    // ─── Save ────────────────────────────────────────────────────────────────
    public function save()
    {
        // Konversi string kosong ke null untuk kolom relasi
        $this->parent_id    = $this->parent_id ?: null;
        $this->page_id      = $this->page_id ?: null;
        $this->external_url = $this->external_url ?: null;

        $rules = [
            'title'        => 'required|string|max:255',
            'type'         => 'required|in:page,external,ormawa,layanan',
            'parent_id'    => 'nullable|exists:menus,id',
            'order'        => 'integer|min:0',
            'external_url' => 'nullable|string|max:500',
            'page_id'      => $this->type === 'page' ? 'required|exists:pages,id' : 'nullable',
        ];

        $this->validate($rules);

        if ($this->type === 'ormawa') {
            $url = $this->ormawa_slug ? '/ormawa?tab=' . $this->ormawa_slug : '/ormawa';
            $data = [
                'title'        => $this->title,
                'type'         => 'ormawa',
                'page_id'      => null,
                'external_url' => $url,
                'parent_id'    => $this->parent_id,
                'order'        => $this->order,
            ];
        } elseif ($this->type === 'layanan') {
            $url = $this->layanan_slug ? '/layanan?tab=' . $this->layanan_slug : '/layanan';
            $data = [
                'title'        => $this->title,
                'type'         => 'layanan',
                'page_id'      => null,
                'external_url' => $url,
                'parent_id'    => $this->parent_id,
                'order'        => $this->order,
            ];
        } else {
            $data = [
                'title'        => $this->title,
                'type'         => $this->type,
                'page_id'      => $this->type === 'page' ? $this->page_id : null,
                'external_url' => $this->type === 'external' ? $this->external_url : null,
                'parent_id'    => $this->parent_id,
                'order'        => $this->order,
            ];
        }

        if ($this->menuId) {
            Menu::findOrFail($this->menuId)->update($data);
            session()->flash('message', 'Menu berhasil diperbarui.');
        } else {
            Menu::create($data);
            session()->flash('message', 'Item menu berhasil ditambahkan.');
        }

        $this->isFormOpen = false;
    }

    // ─── Ubah Urutan (Move Up / Down) ────────────────────────────────────────
    public function moveUp(int $id): void
    {
        $menu     = Menu::findOrFail($id);
        $siblings = Menu::whereParentId($menu->parent_id)->orderBy('order')->get();
        $index    = $siblings->search(fn($m) => $m->id === $menu->id);

        if ($index > 0) {
            $prev = $siblings[$index - 1];
            [$menu->order, $prev->order] = [$prev->order, $menu->order];
            $menu->save();
            $prev->save();
        }
    }

    public function moveDown(int $id): void
    {
        $menu     = Menu::findOrFail($id);
        $siblings = Menu::whereParentId($menu->parent_id)->orderBy('order')->get();
        $index    = $siblings->search(fn($m) => $m->id === $menu->id);

        if ($index !== false && $index < $siblings->count() - 1) {
            $next = $siblings[$index + 1];
            [$menu->order, $next->order] = [$next->order, $menu->order];
            $menu->save();
            $next->save();
        }
    }

    // ─── Delete ──────────────────────────────────────────────────────────────
    public function delete($id)
    {
        Menu::findOrFail($id)->delete();
        session()->flash('message', 'Item menu berhasil dihapus.');
    }

    // ─── Render ──────────────────────────────────────────────────────────────
    public function render()
    {
        $menus = Menu::with(['children.page', 'page'])
            ->whereNull('parent_id')
            ->orderBy('order')
            ->get();

        $pages    = Page::orderBy('title')->get();
        $ormawas  = Ormawa::orderBy('name')->get();
        $layanans = Layanan::where('is_active', true)->orderBy('order')->get();

        return view('livewire.admin.manage-menus', [
            'menus'    => $menus,
            'pages'    => $pages,
            'ormawas'  => $ormawas,
            'layanans' => $layanans,
        ])->layout('layouts.app');
    }
}
