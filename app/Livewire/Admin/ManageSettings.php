<?php

namespace App\Livewire\Admin;

use App\Models\Setting;
use Livewire\Component;

class ManageSettings extends Component
{
    public string $activeTab = 'theme'; // theme

    // ─── Color Theme ────────────────────────────────────────────────────────
    public string $color_primary   = '#052c22';  // warna utama (hijau tua)
    public string $color_accent    = '#c8b282';  // warna aksen (emas)
    public string $color_bg        = '#fcfbf9';  // warna background halaman
    public string $color_text      = '#1a2421';  // warna teks utama
    public string $color_sidebar   = '#ffffff';  // warna sidebar
    public bool $theme_use_gradient = true;      // toggle gradasi

    // ─── Site Identity ───────────────────────────────────────────────────────
    public string $site_name       = 'Kemahasiswaan Unbrah';
    public string $site_tagline    = 'Universitas Baiturrahmah';
    public string $site_footer     = '© 2025 Universitas Baiturrahmah. All rights reserved.';

    protected $rules = [
        'color_primary'      => 'required|string|max:20',
        'color_accent'       => 'required|string|max:20',
        'color_bg'           => 'required|string|max:20',
        'color_text'         => 'required|string|max:20',
        'color_sidebar'      => 'required|string|max:20',
        'theme_use_gradient' => 'required|boolean',
        'site_name'          => 'required|string|max:255',
        'site_tagline'       => 'nullable|string|max:255',
        'site_footer'        => 'nullable|string|max:500',
    ];

    public function switchTab($tab): void
    {
        $this->activeTab = $tab;
    }

    public function mount(): void
    {
        if (!auth()->user() || !auth()->user()->hasAccessToMenu('settings')) {
            abort(403, 'Anda tidak memiliki hak akses untuk halaman ini.');
        }

        // Load existing settings from DB
        $this->color_primary      = Setting::get('color_primary', '#052c22');
        $this->color_accent       = Setting::get('color_accent',  '#c8b282');
        $this->color_bg           = Setting::get('color_bg',      '#fcfbf9');
        $this->color_text         = Setting::get('color_text',    '#1a2421');
        $this->color_sidebar      = Setting::get('color_sidebar', '#ffffff');
        $this->theme_use_gradient = Setting::get('theme_use_gradient', 'true') === 'true';
        $this->site_name          = Setting::get('site_name',     'Kemahasiswaan Unbrah');
        $this->site_tagline       = Setting::get('site_tagline',  'Universitas Baiturrahmah');
        $this->site_footer        = Setting::get('site_footer',   '© 2025 Universitas Baiturrahmah. All rights reserved.');
    }

    public function save(): void
    {
        $this->validate();

        // Save Color settings
        $colorSettings = [
            'color_primary' => [$this->color_primary, 'colors', 'Warna Utama (Primary)'],
            'color_accent'  => [$this->color_accent,  'colors', 'Warna Aksen / Highlight'],
            'color_bg'      => [$this->color_bg,      'colors', 'Warna Background Halaman'],
            'color_text'    => [$this->color_text,    'colors', 'Warna Teks Utama'],
            'color_sidebar' => [$this->color_sidebar, 'colors', 'Warna Sidebar'],
        ];

        foreach ($colorSettings as $key => [$value, $group, $label]) {
            Setting::set($key, $value, $group, $label);
        }

        Setting::set('theme_use_gradient', $this->theme_use_gradient ? 'true' : 'false', 'colors', 'Gunakan Gradasi');
        Setting::set('site_name',    $this->site_name,    'general', 'Nama Situs');
        Setting::set('site_tagline', $this->site_tagline, 'general', 'Tagline Situs');
        Setting::set('site_footer',  $this->site_footer,  'general', 'Teks Footer');

        // Flush cache
        Setting::clearCache();

        session()->flash('message', 'Pengaturan situs berhasil diperbarui.');
    }

    public function resetDefaults(): void
    {
        $this->color_primary      = '#052c22';
        $this->color_accent       = '#c8b282';
        $this->color_bg           = '#fcfbf9';
        $this->color_text         = '#1a2421';
        $this->color_sidebar      = '#ffffff';
        $this->theme_use_gradient = true;
    }

    public function render()
    {
        return view('livewire.admin.manage-settings')
            ->layout('layouts.app');
    }
}
