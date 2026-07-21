<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Menu;
use App\Models\Page;

class MenuSeeder extends Seeder
{
    public function run(): void
    {
        // Bersihkan menu lama
        Menu::truncate();

        // 1. BERANDA
        Menu::create([
            'title' => 'Beranda',
            'type' => 'external',
            'external_url' => '/',
            'order' => 1
        ]);

        // 2. PROFIL
        $profil = Menu::create([
            'title' => 'Profil',
            'type' => 'external',
            'external_url' => '#',
            'order' => 2
        ]);
        
        $visiMisi = Page::where('slug', 'visi-misi')->first();
        if ($visiMisi) {
            Menu::create(['parent_id' => $profil->id, 'title' => 'Visi & Misi', 'type' => 'page', 'page_id' => $visiMisi->id, 'order' => 1]);
        }
        $struktur = Page::where('slug', 'struktur-organisasi')->first();
        if ($struktur) {
            Menu::create(['parent_id' => $profil->id, 'title' => 'Struktur Organisasi', 'type' => 'page', 'page_id' => $struktur->id, 'order' => 2]);
        }

        // 3. LAYANAN
        $layanan = Menu::create([
            'title' => 'Layanan',
            'type' => 'external',
            'external_url' => '/layanan',
            'order' => 3
        ]);
        Menu::create(['parent_id' => $layanan->id, 'title' => 'Psikologi & Konseling', 'type' => 'external', 'external_url' => '/layanan?tab=psikologi', 'order' => 1]);
        Menu::create(['parent_id' => $layanan->id, 'title' => 'P2K2', 'type' => 'external', 'external_url' => '/layanan?tab=p2k2', 'order' => 2]);
        Menu::create(['parent_id' => $layanan->id, 'title' => 'PPKPT', 'type' => 'external', 'external_url' => '/layanan?tab=ppktp', 'order' => 3]);
        Menu::create(['parent_id' => $layanan->id, 'title' => 'Info Beasiswa', 'type' => 'external', 'external_url' => '/beasiswa', 'order' => 4]);
        Menu::create(['parent_id' => $layanan->id, 'title' => 'Prestasi Mahasiswa', 'type' => 'external', 'external_url' => '/prestasi', 'order' => 5]);
        Menu::create(['parent_id' => $layanan->id, 'title' => 'SAPS', 'type' => 'external', 'external_url' => '/layanan?tab=saps', 'order' => 6]);
        Menu::create(['parent_id' => $layanan->id, 'title' => 'Hasimaru', 'type' => 'external', 'external_url' => '/layanan?tab=hasimaru', 'order' => 7]);

        // 4. ORMAWA
        $ormawa = Menu::create([
            'title' => 'Ormawa',
            'type' => 'external',
            'external_url' => '/ormawa',
            'order' => 4
        ]);
        Menu::create(['parent_id' => $ormawa->id, 'title' => 'BEMU', 'type' => 'external', 'external_url' => '/ormawa', 'order' => 1]);
        Menu::create(['parent_id' => $ormawa->id, 'title' => 'DPM-U', 'type' => 'external', 'external_url' => '/ormawa', 'order' => 2]);
        Menu::create(['parent_id' => $ormawa->id, 'title' => 'UKMO', 'type' => 'external', 'external_url' => '/ormawa', 'order' => 3]);
        Menu::create(['parent_id' => $ormawa->id, 'title' => 'UKS', 'type' => 'external', 'external_url' => '/ormawa', 'order' => 4]);
        Menu::create(['parent_id' => $ormawa->id, 'title' => 'KSR-PMI', 'type' => 'external', 'external_url' => '/ormawa', 'order' => 5]);
        Menu::create(['parent_id' => $ormawa->id, 'title' => 'UKMI-ARRAHMAH', 'type' => 'external', 'external_url' => '/ormawa', 'order' => 6]);
        Menu::create(['parent_id' => $ormawa->id, 'title' => 'MENWA', 'type' => 'external', 'external_url' => '/ormawa', 'order' => 7]);

        // 5. LOMBA & KOMPETENSI
        $lomba = Menu::create([
            'title' => 'Lomba & Kompetensi',
            'type' => 'external',
            'external_url' => '#',
            'order' => 5
        ]);
        $pkm = Page::where('slug', 'pkm')->first();
        if ($pkm) {
            Menu::create(['parent_id' => $lomba->id, 'title' => 'PKM', 'type' => 'page', 'page_id' => $pkm->id, 'order' => 1]);
        }
        Menu::create(['parent_id' => $lomba->id, 'title' => 'P2MW', 'type' => 'external', 'external_url' => '#', 'order' => 2]);
        Menu::create(['parent_id' => $lomba->id, 'title' => 'PILMAPRES', 'type' => 'external', 'external_url' => '#', 'order' => 3]);
        Menu::create(['parent_id' => $lomba->id, 'title' => 'POMNAS', 'type' => 'external', 'external_url' => '#', 'order' => 4]);
        Menu::create(['parent_id' => $lomba->id, 'title' => 'PEKSIMINAS', 'type' => 'external', 'external_url' => '#', 'order' => 5]);
        Menu::create(['parent_id' => $lomba->id, 'title' => 'EA', 'type' => 'external', 'external_url' => '#', 'order' => 6]);
        Menu::create(['parent_id' => $lomba->id, 'title' => 'PPK ORMAWA', 'type' => 'external', 'external_url' => '#', 'order' => 7]);

        // 6. ALUMNI
        $alumni = Menu::create([
            'title' => 'Alumni',
            'type' => 'external',
            'external_url' => '#',
            'order' => 6
        ]);
        $tracer = Page::where('slug', 'tracer-study')->first();
        if ($tracer) {
            Menu::create(['parent_id' => $alumni->id, 'title' => 'Tracer Study', 'type' => 'page', 'page_id' => $tracer->id, 'order' => 1]);
        }

        // 7. DOWNLOAD
        $download = Menu::create([
            'title' => 'Download',
            'type' => 'external',
            'external_url' => '/page/download',
            'order' => 7
        ]);
        $dlPage = Page::where('slug', 'download')->first();
        if ($dlPage) {
            Menu::create(['parent_id' => $download->id, 'title' => 'Pedoman Hasimaru', 'type' => 'page', 'page_id' => $dlPage->id, 'order' => 1]);
            Menu::create(['parent_id' => $download->id, 'title' => 'Pedoman SAPS', 'type' => 'page', 'page_id' => $dlPage->id, 'order' => 2]);
            Menu::create(['parent_id' => $download->id, 'title' => 'Peraturan Ormawa', 'type' => 'page', 'page_id' => $dlPage->id, 'order' => 3]);
            Menu::create(['parent_id' => $download->id, 'title' => 'SOP', 'type' => 'page', 'page_id' => $dlPage->id, 'order' => 4]);
            Menu::create(['parent_id' => $download->id, 'title' => 'SK Ormawa', 'type' => 'page', 'page_id' => $dlPage->id, 'order' => 5]);
        }
    }
}
