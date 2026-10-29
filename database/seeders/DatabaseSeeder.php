<?php

namespace Database\Seeders;

use App\Models\User;
use App\Models\Ormawa;
use App\Models\Kegiatan;
use App\Models\Prestasi;
use App\Models\Beasiswa;
use App\Models\Page;
use App\Models\Menu;
use App\Models\Post;
use App\Models\Layanan;
use App\Models\Group;
use App\Models\DokumenKategori;
use App\Models\Dokumen;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;

class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     */
    public function run(): void
    {
        // ─── 1. SEED GROUPS (KELOMPOK HAK AKSES) ─────────────────────────────────
        $groupLayanan = Group::create([
            'name' => 'Staf Akademik & Layanan',
            'description' => 'Mengelola layanan mahasiswa, beasiswa, prestasi, dan unduhan berkas.',
            'accessible_menus' => ['dashboard', 'layanans', 'ormawas', 'beasiswas', 'prestasis', 'dokumens']
        ]);

        $groupHumas = Group::create([
            'name' => 'Staf Humas & Publikasi',
            'description' => 'Mengelola rilis berita, artikel, agenda, dan dokumentasi kegiatan.',
            'accessible_menus' => ['dashboard', 'posts', 'kegiatans']
        ]);

        // ─── 2. SEED ADMIN USERS ────────────────────────────────────────────────
        // Super Admin (akses penuh)
        User::create([
            'name' => 'Super Administrator',
            'email' => 'admin.mahasiswa@unbrah.ac.id',
            'password' => Hash::make('password'),
            'role' => 'superadmin',
            'group_id' => null,
        ]);

        // Staf Layanan
        User::create([
            'name' => 'Adinda Putri, S.Pd.',
            'email' => 'layanan@unbrah.ac.id',
            'password' => Hash::make('password'),
            'role' => 'staf_layanan',
            'group_id' => $groupLayanan->id,
        ]);

        // Staf Humas
        User::create([
            'name' => 'Rian Hidayat, S.Kom.',
            'email' => 'humas@unbrah.ac.id',
            'password' => Hash::make('password'),
            'role' => 'staf_humas',
            'group_id' => $groupHumas->id,
        ]);

        // ─── 3. SEED DYNAMIC LAYANANS (LAYANAN MAHASISWA) ────────────────────────
        Layanan::create([
            'title' => 'Layanan Beasiswa',
            'slug' => 'beasiswa',
            'icon' => '🎓',
            'category' => 'Bantuan Biaya Pendidikan & Prestasi',
            'fee' => 'Gratis Bagi Mahasiswa Aktif UNBRAH',
            'content' => '<h3>Informasi & Pendaftaran Beasiswa</h3><p>Universitas Baiturrahmah berkomitmen membantu studi mahasiswa melalui berbagai bantuan biaya pendidikan.</p><ul><li>Beasiswa KIP-Kuliah (Kemendikbudristek)</li><li>Beasiswa Peningkatan Prestasi Akademik (PPA)</li><li>Beasiswa Yayasan Pendidikan Baiturrahmah</li></ul>',
            'is_active' => true,
            'order' => 1,
        ]);

        Layanan::create([
            'title' => 'Layanan Konseling & Psikologi',
            'slug' => 'psikologi',
            'icon' => '🤝',
            'category' => 'Bimbingan & Konseling Mahasiswa',
            'fee' => 'Gratis Bagi Mahasiswa Aktif UNBRAH',
            'content' => '<h3>Layanan Konseling Psikologis Gratis</h3><p>Kami menyediakan bimbingan konseling gratis bagi mahasiswa yang mengalami kendala belajar, stres, maupun membutuhkan konseling karir bersama psikolog klinis universitas secara rahasia dan profesional.</p>',
            'is_active' => true,
            'order' => 2,
        ]);

        Layanan::create([
            'title' => 'Pusat Karir & Kewirausahaan (P2K2)',
            'slug' => 'p2k2',
            'icon' => '💼',
            'category' => 'Pengembangan Karir & Wirausaha',
            'fee' => 'Gratis Bagi Mahasiswa & Alumni UNBRAH',
            'content' => '<h3>Pusat Pengembangan Karir dan Kewirausahaan (P2K2)</h3><p>Membantu mahasiswa dan lulusan dalam transisi memasuki dunia industri (DUDI) melalui seminar kesiapan kerja, inkubasi bisnis mahasiswa, info lowongan kerja terpercaya, serta pelaksanaan Tracer Study resmi universitas.</p>',
            'is_active' => true,
            'order' => 3,
        ]);

        Layanan::create([
            'title' => 'Satgas PPKTP',
            'slug' => 'ppktp',
            'icon' => '🛡️',
            'category' => 'Pencegahan & Penanganan Kekerasan',
            'fee' => 'Layanan Pengaduan Rahasia & Gratis',
            'content' => '<h3>Satuan Tugas Pencegahan dan Penanganan Kekerasan di Perguruan Tinggi (PPKTP)</h3><p>Memberikan perlindungan, pendampingan, dan tindak lanjut atas laporan kekerasan, perundungan, atau intoleransi di lingkungan kampus Universitas Baiturrahmah dengan menjamin kerahasiaan pelapor.</p>',
            'is_active' => true,
            'order' => 4,
        ]);

        Layanan::create([
            'title' => 'Student Activity & Performance System (SAPS)',
            'slug' => 'saps',
            'icon' => '📊',
            'category' => 'Penilaian Keaktifan & Prestasi Mahasiswa',
            'fee' => 'Layanan Akademik & Kemahasiswaan',
            'content' => '<h3>Sistem Poin Kredit Keaktifan & Prestasi (SAPS)</h3><p>SAPS merekam dan mengakumulasi poin keaktifan mahasiswa dalam organisasi, kepanitiaan, perlombaan, dan kegiatan ilmiah sebagai syarat pendamping kelulusan dan Surat Keterangan Pendamping Ijazah (SKPI).</p>',
            'is_active' => true,
            'order' => 5,
        ]);

        Layanan::create([
            'title' => 'Hasimaru (Pembinaan Karakter)',
            'slug' => 'hasimaru',
            'icon' => '🌱',
            'category' => 'Orientasi & Pembinaan Karakter Mahasiswa Baru',
            'fee' => 'Program Wajib Mahasiswa Baru',
            'content' => '<h3>Hari Silaturahmi Mahasiswa Baru (Hasimaru)</h3><p>Program pengenalan kehidupan kampus dan pembinaan karakter Islami bagi mahasiswa baru Universitas Baiturrahmah guna membentuk insan akademis yang berakhlakul karimah dan berdaya saing unggul.</p>',
            'is_active' => true,
            'order' => 6,
        ]);

        // ─── 4. SEED ORMAWAS ─────────────────────────────────────────────────────
        $bem = Ormawa::create([
            'name' => 'Badan Eksekutif Mahasiswa (BEM)',
            'slug' => 'bem',
            'logo_path' => 'https://kemahasiswaan.unbrah.ac.id/images/bem-logo.png',
            'description' => 'Lembaga eksekutif tertinggi mahasiswa di tingkat Universitas Baiturrahmah yang menaungi aspirasi dan kegiatan mahasiswa.',
            'structure' => [
                ['role' => 'Presiden Mahasiswa', 'name' => 'Faisal Tanjung'],
                ['role' => 'Wakil Presiden Mahasiswa', 'name' => 'Adinda Putri'],
                ['role' => 'Sekretaris Jenderal', 'name' => 'Rian Hidayat'],
            ],
            'focus_items' => [
                'Mengawal aspirasi dan advokasi kesejahteraan mahasiswa',
                'Mengembangkan program pemberdayaan kepemimpinan masyarakat',
                'Kolaborasi sosial kemasyarakatan dan aksi kemanusiaan daerah'
            ],
            'order' => 1
        ]);

        $dpm = Ormawa::create([
            'name' => 'Dewan Perwakilan Mahasiswa (DPM)',
            'slug' => 'dpm',
            'logo_path' => 'https://kemahasiswaan.unbrah.ac.id/images/dpm-logo.png',
            'description' => 'Lembaga legislatif mahasiswa yang bertugas mengawasi kinerja eksekutif dan menyusun regulasi kemahasiswaan.',
            'structure' => [
                ['role' => 'Ketua Umum', 'name' => 'Riki Kurniawan'],
                ['role' => 'Sekretaris Umum', 'name' => 'Nabila Syakieb'],
            ],
            'focus_items' => [
                'Penyusunan dan pengawasan produk hukum / UU ormawa',
                'Legislasi pengesahan anggaran program kerja tahunan BEM',
                'Penyerapan aspirasi murni mahasiswa tingkat universitas'
            ],
            'order' => 2
        ]);

        // ─── 5. SEED DOKUMEN & KATEGORI ──────────────────────────────────────────
        $katHasimaru = DokumenKategori::create(['name' => 'Pedoman Hasimaru', 'slug' => 'hasimaru']);
        $katSaps     = DokumenKategori::create(['name' => 'Pedoman SAPS', 'slug' => 'saps']);
        $katSop      = DokumenKategori::create(['name' => 'Standar SOP', 'slug' => 'sop']);

        Dokumen::create([
            'dokumen_kategori_id' => $katHasimaru->id,
            'judul' => 'Buku Panduan Hasimaru (Hari Silaturahmi Mahasiswa Baru) 2026',
            'url_file' => 'https://kemahasiswaan.unbrah.ac.id/docs/hasimaru-2026.pdf',
            'tipe_file' => 'pdf',
            'ukuran_file' => '2.4 MB',
            'is_active' => true,
            'order' => 1
        ]);

        Dokumen::create([
            'dokumen_kategori_id' => $katSaps->id,
            'judul' => 'Pedoman Sistem Aplikasi Prestasi SKS Mahasiswa (SAPS)',
            'url_file' => 'https://kemahasiswaan.unbrah.ac.id/docs/saps-guide.pdf',
            'tipe_file' => 'pdf',
            'ukuran_file' => '1.8 MB',
            'is_active' => true,
            'order' => 1
        ]);

        // ─── 6. SEED PAGES (HALAMAN DINAMIS) ─────────────────────────────────────
        $sopPage = Page::create([
            'title' => 'SOP Ormawa & Kegiatan',
            'slug' => 'sop-ormawa',
            'is_published' => true,
            'content' => [
                [
                    'type' => 'hero',
                    'data' => [
                        'label' => 'PEDOMAN RESMI',
                        'title' => 'Standar Operasional Prosedur',
                        'highlight' => 'Ormawa & Kegiatan',
                        'subtitle' => 'Panduan pengajuan kegiatan dan anggaran organisasi mahasiswa.',
                        'align' => 'center'
                    ]
                ],
                [
                    'type' => 'text',
                    'data' => [
                        'html' => '<p class="mb-4">Setiap ormawa yang ingin menyelenggarakan kegiatan wajib mengajukan proposal minimal 14 hari sebelum hari H melalui Bagian Kemahasiswaan Universitas Baiturrahmah.</p>'
                    ]
                ]
            ]
        ]);

        // ─── 7. SEED HEADER MENUS (NAVIGASI) ─────────────────────────────────────
        $homeMenu = Menu::create([
            'title' => 'Beranda',
            'type' => 'external',
            'external_url' => '/',
            'order' => 1,
        ]);

        // Parent Menu Layanan (dropdown)
        $parentLayananMenu = Menu::create([
            'title' => 'Layanan',
            'type' => 'external',
            'external_url' => '#',
            'order' => 2,
        ]);

        // Submenu Layanan Beasiswa (tipe layanan)
        Menu::create([
            'parent_id' => $parentLayananMenu->id,
            'title' => 'Beasiswa Mahasiswa',
            'type' => 'layanan',
            'external_url' => '/layanan?tab=beasiswa',
            'order' => 1,
        ]);

        // Submenu Layanan Konseling (tipe layanan)
        Menu::create([
            'parent_id' => $parentLayananMenu->id,
            'title' => 'Konseling Psikologi',
            'type' => 'layanan',
            'external_url' => '/layanan?tab=psikologi',
            'order' => 2,
        ]);

        // Submenu SOP & Pengajuan (tipe page)
        Menu::create([
            'parent_id' => $parentLayananMenu->id,
            'title' => 'SOP & Pengajuan',
            'type' => 'page',
            'page_id' => $sopPage->id,
            'order' => 3,
        ]);

        // Parent Menu Ormawa (dropdown)
        $parentOrmawaMenu = Menu::create([
            'title' => 'Ormawa',
            'type' => 'external',
            'external_url' => '#',
            'order' => 3,
        ]);

        // Submenu Ormawa BEM (tipe ormawa)
        Menu::create([
            'parent_id' => $parentOrmawaMenu->id,
            'title' => 'BEM Universitas',
            'type' => 'ormawa',
            'external_url' => '/ormawa?tab=bem',
            'order' => 1,
        ]);

        // Submenu Ormawa DPM (tipe ormawa)
        Menu::create([
            'parent_id' => $parentOrmawaMenu->id,
            'title' => 'DPM Legislatif',
            'type' => 'ormawa',
            'external_url' => '/ormawa?tab=dpm',
            'order' => 2,
        ]);

        // Menu Berita & Artikel
        Menu::create([
            'title' => 'Berita',
            'type' => 'external',
            'external_url' => '/berita',
            'order' => 4,
        ]);

        // Menu Dokumen
        Menu::create([
            'title' => 'Pusat Unduhan',
            'type' => 'external',
            'external_url' => '/dokumen',
            'order' => 5,
        ]);

        // ─── 8. SEED KEGIATANS, PRESTASIS, BEASISWAS & POSTS ─────────────────────
        Kegiatan::create([
            'title' => 'Latihan Kepemimpinan Manajemen Mahasiswa (LKMM)',
            'slug' => 'lkmm-2026',
            'banner_path' => 'https://kemahasiswaan.unbrah.ac.id/images/kegiatan-lkmm.jpg',
            'description' => 'Pelatihan kepemimpinan tingkat dasar untuk membekali calon pengurus organisasi mahasiswa dengan keterampilan manajemen organisasi.',
            'date' => '2026-08-20',
            'location' => 'Aula Rektorat Lt. 4, Kampus A Unbrah',
            'ormawa_id' => $bem->id,
        ]);

        Prestasi::create([
            'student_name' => 'Doni Saputra',
            'title' => 'Juara 1 Lomba Karya Tulis Ilmiah Nasional (LKTIN)',
            'level' => 'Nasional',
            'year' => 2025,
            'certificate_path' => 'https://kemahasiswaan.unbrah.ac.id/docs/sertifikat-doni.pdf',
        ]);

        Beasiswa::create([
            'name' => 'Kartu Indonesia Pintar Kuliah (KIP-K)',
            'provider' => 'Kementerian Pendidikan, Kebudayaan, Riset, dan Teknologi',
            'description' => 'Bantuan biaya pendidikan dari pemerintah bagi lulusan SMA/sederajat yang memiliki potensi akademik baik tetapi memiliki keterbatasan ekonomi.',
            'requirements' => "1. Terdaftar di DTKS Kementerian Sosial\n2. Melampirkan kartu KIP/KKS/PKH\n3. Lulus seleksi PMB Universitas Baiturrahmah",
            'is_active' => true,
        ]);

        Post::create([
            'title' => 'Delegasi Unbrah Raih Juara LKTIN di Universitas Diponegoro',
            'slug' => 'delegasi-unbrah-raih-juara-lktin',
            'image_path' => 'https://kemahasiswaan.unbrah.ac.id/images/news-lktin.jpg',
            'content' => '<p class="mb-4">Tim mahasiswa Universitas Baiturrahmah yang dipimpin oleh Doni Saputra berhasil meraih juara pertama dalam ajang Lomba Karya Tulis Ilmiah Nasional (LKTIN) yang diselenggarakan di Universitas Diponegoro, Semarang.</p><p>Karya ilmiah yang diusung bertemakan inovasi kesehatan berbasis digital...</p>',
            'is_published' => true,
            'published_at' => now(),
        ]);
    }
}
