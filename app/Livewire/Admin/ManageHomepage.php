<?php

namespace App\Livewire\Admin;

use App\Models\Setting;
use App\Models\Layanan;
use Livewire\Component;
use Livewire\WithFileUploads;

class ManageHomepage extends Component
{
    use WithFileUploads;

    public string $activeSection = 'slides'; // slides | sambutan | layanan | about | topbar | footer

    // ─── Slides ──────────────────────────────────────────────────────────────
    public array $slides = [];
    public array $slide_photo_files = [null, null, null];

    // ─── Sambutan WR III ─────────────────────────────────────────────────────
    public string $wr3_name       = '';
    public string $wr3_quote      = '';
    public string $wr3_para1      = '';
    public string $wr3_para2      = '';
    public string $wr3_photo_path = '';
    public $wr3_photo_file        = null;

    // ─── Layanan (6 kartu) ───────────────────────────────────────────────────
    public array $layanan_cards      = [];
    public array $availableLayanans  = [];

    // ─── Profil / About + Video ──────────────────────────────────────────────
    public string $about_label       = '';
    public string $about_title       = '';
    public string $about_description = '';
    public string $about_point1_title = '';
    public string $about_point1_desc  = '';
    public string $about_point2_title = '';
    public string $about_point2_desc  = '';
    public string $video_youtube_url  = '';
    public string $video_title        = '';

    // ─── Top Bar ─────────────────────────────────────────────────────────────
    public string $contact_phone      = '';
    public string $contact_email      = '';
    public string $contact_hours      = '';
    public string $topbar_link1_label = '';
    public string $topbar_link1_url   = '';
    public string $topbar_link2_label = '';
    public string $topbar_link2_url   = '';
    public string $whatsapp_number    = '';

    // ─── Footer ──────────────────────────────────────────────────────────────
    public string $footer_tagline     = '';
    public string $contact_address    = '';
    public string $footer_copyright   = '';
    public array  $footer_external_links = [];

    protected $rules = [
        'wr3_name'           => 'required|string|max:255',
        'wr3_quote'          => 'nullable|string|max:500',
        'wr3_para1'          => 'nullable|string',
        'wr3_para2'          => 'nullable|string',
        'about_label'        => 'nullable|string|max:100',
        'about_title'        => 'nullable|string|max:255',
        'about_description'  => 'nullable|string',
        'about_point1_title' => 'nullable|string|max:100',
        'about_point1_desc'  => 'nullable|string|max:255',
        'about_point2_title' => 'nullable|string|max:100',
        'about_point2_desc'  => 'nullable|string|max:255',
        'video_youtube_url'  => 'nullable|string|max:255',
        'video_title'        => 'nullable|string|max:255',
        'contact_phone'      => 'nullable|string|max:100',
        'contact_email'      => 'nullable|email|max:255',
        'contact_hours'      => 'nullable|string|max:255',
        'topbar_link1_label' => 'nullable|string|max:100',
        'topbar_link1_url'   => 'nullable|string|max:255',
        'topbar_link2_label' => 'nullable|string|max:100',
        'topbar_link2_url'   => 'nullable|string|max:255',
        'whatsapp_number'    => 'nullable|string|max:30',
        'footer_tagline'     => 'nullable|string|max:500',
        'contact_address'    => 'nullable|string|max:500',
        'footer_copyright'   => 'nullable|string|max:255',
        'slide_photo_files.*' => 'nullable|image|max:4096',
        'wr3_photo_file'      => 'nullable|image|max:4096',
    ];

    public function mount(): void
    {
        if (!auth()->user() || !auth()->user()->hasAccessToMenu('homepage')) {
            abort(403, 'Anda tidak memiliki hak akses untuk halaman ini.');
        }

        // Sambutan WR III
        $this->wr3_name       = Setting::get('wr3_name',       'Dr. rer. nat. Syafrimen Yasin, M.S.');
        $this->wr3_quote      = Setting::get('wr3_quote',      '"Membina Karakter Islami, Mengembangkan Potensi, Mengukir Prestasi."');
        $this->wr3_para1      = Setting::get('wr3_para1',      'Selamat datang di portal informasi resmi Bidang Kemahasiswaan & Alumni Universitas Baiturrahmah.');
        $this->wr3_para2      = Setting::get('wr3_para2',      'Melalui pembinaan karakter, dukungan beasiswa, jajaran organisasi kemahasiswaan (Ormawa), serta program penunjang karir alumni, kami berharap dapat melahirkan generasi muda yang tangguh dan berakhlak mulia.');
        $this->wr3_photo_path = Setting::get('wr3_photo_path', '/images/slider2.jpg');

        // Layanan Cards
        $defaultLayanan = [
            ['label'=>'Layanan 01','title'=>'Layanan Bimbingan Konseling','icon'=>'brain','url'=>'/layanan?tab=psikologi','color'=>'indigo'],
            ['label'=>'Layanan 02','title'=>'Pusat Karir & Kewirausahaan (P2K2)','icon'=>'briefcase','url'=>'/layanan?tab=p2k2','color'=>'amber'],
            ['label'=>'Layanan 03','title'=>'Satgas PPKTP','icon'=>'shield','url'=>'/layanan?tab=ppktp','color'=>'emerald'],
            ['label'=>'Layanan 04','title'=>'Layanan Beasiswa','icon'=>'money','url'=>'/layanan?tab=beasiswa','color'=>'blue'],
            ['label'=>'Layanan 05','title'=>'SAPS (Poin Kredit Prestasi)','icon'=>'gift','url'=>'/layanan?tab=saps','color'=>'rose'],
            ['label'=>'Layanan 06','title'=>'Hasimaru (Pembinaan Karakter)','icon'=>'book','url'=>'/layanan?tab=hasimaru','color'=>'brand-green'],
        ];
        $this->layanan_cards = json_decode(Setting::get('layanan_cards', json_encode($defaultLayanan)), true);
        while (count($this->layanan_cards) < 6) {
            $this->layanan_cards[] = ['layanan_id'=>null,'label'=>'Layanan','title'=>'','icon'=>'briefcase','url'=>'#','color'=>'slate'];
        }

        // Available layanans for dropdown
        $this->availableLayanans = Layanan::where('is_active', 1)
            ->orderBy('order')
            ->get(['id', 'title', 'slug', 'icon'])
            ->toArray();

        // About Section
        $this->about_label        = Setting::get('about_label',        'Tentang Kemahasiswaan');
        $this->about_title        = Setting::get('about_title',        'Mengoptimalkan Potensi Mahasiswa Menuju Lulusan Berdaya Saing');
        $this->about_description  = Setting::get('about_description',  'Bidang Kemahasiswaan & Alumni Universitas Baiturrahmah mengelola berbagai program pembinaan minat, bakat, penalaran, dan organisasi mahasiswa. Kami berkomitmen untuk menyediakan lingkungan kampus yang dinamis, inklusif, dan mendukung kesejahteraan mahasiswa agar mereka tumbuh menjadi individu berkarakter Islami, berjiwa wirausaha, serta siap bersaing di kancah nasional maupun internasional.');
        $this->about_point1_title = Setting::get('about_point1_title', 'Akreditasi Unggul');
        $this->about_point1_desc  = Setting::get('about_point1_desc',  'Semua program studi diploma telah terakreditasi BAN-PT dan LAM-PTKes.');
        $this->about_point2_title = Setting::get('about_point2_title', 'Mitra DUDI Luas');
        $this->about_point2_desc  = Setting::get('about_point2_desc',  'Jejaring magang klinis di berbagai rumah sakit & industri kesehatan.');
        $this->video_youtube_url  = Setting::get('video_youtube_url',  'https://www.youtube.com/embed/jXZVBE2wbs0');
        $this->video_title        = Setting::get('video_title',        'Jelajahi Kegiatan, Ormawa, dan Pembinaan Kemahasiswaan UNBRAH');

        // Top Bar
        $this->contact_phone      = Setting::get('contact_phone',      '(0751) 4641913');
        $this->contact_email      = Setting::get('contact_email',      'fk@unbrah.ac.id');
        $this->contact_hours      = Setting::get('contact_hours',      'Senin - Jumat: 08:00 - 16:00 WIB');
        $this->topbar_link1_label = Setting::get('topbar_link1_label', 'Arsip Berita');
        $this->topbar_link1_url   = Setting::get('topbar_link1_url',   '/berita');
        $this->topbar_link2_label = Setting::get('topbar_link2_label', 'Sistem Informasi');
        $this->topbar_link2_url   = Setting::get('topbar_link2_url',   '#');
        $this->whatsapp_number    = Setting::get('whatsapp_number',    '628116313712');

        // Footer
        $this->footer_tagline   = Setting::get('footer_tagline',   'Membina potensi, karakter, kreativitas, dan kesejahteraan mahasiswa Universitas Baiturrahmah untuk menghasilkan lulusan yang kompeten, beretika luhur, dan siap berkontribusi bagi bangsa.');
        $this->contact_address  = Setting::get('contact_address',  'Jl. Raya By Pass KM 15, Aie Pacah, Kec. Koto Tangah, Kota Padang, Sumatera Barat 25176');
        $this->footer_copyright = Setting::get('footer_copyright', '© ' . date('Y') . ' Bidang Kemahasiswaan Universitas Baiturrahmah. All rights reserved.');

        $defaultExtLinks = [
            ['label' => 'Univ. Baiturrahmah',  'url' => 'https://unbrah.ac.id'],
            ['label' => 'Kemendikbudristek',    'url' => 'https://kemdikbud.go.id'],
            ['label' => 'RSI Siti Rahmah',      'url' => 'https://rsisilirahmah.co.id'],
        ];
        $this->footer_external_links = json_decode(Setting::get('footer_external_links', json_encode($defaultExtLinks)), true);
        while (count($this->footer_external_links) < 5) {
            $this->footer_external_links[] = ['label' => '', 'url' => ''];
        }

        // Slides
        $defaultSlides = [
            ['image'=>'/images/slider2.jpg','badge'=>'Kemahasiswaan UNBRAH','title_line1'=>'Membina Karakter & Potensi','title_line2'=>'Lulusan Unggul & Beretika','description'=>'Mewujudkan pembinaan mahasiswa yang berorientasi pada kepemimpinan, integritas moral, serta kepedulian sosial yang tinggi.','button1_text'=>'Layanan Mahasiswa','button1_link'=>'/layanan','button2_text'=>'Profil Kemahasiswaan','button2_link'=>'/profil'],
            ['image'=>'/images/slider3.jpg','badge'=>'Prestasi & Minat Bakat','title_line1'=>'Raih Prestasi Gemilang','title_line2'=>'Salurkan Kreativitas & Bakat','description'=>'Didukung oleh berbagai Unit Kegiatan Mahasiswa (UKM) bidang penalaran, seni, olahraga, kerohanian, dan kemanusiaan.','button1_text'=>'Daftar Ormawa','button1_link'=>'/ormawa','button2_text'=>'Layanan Mahasiswa','button2_link'=>'/layanan'],
            ['image'=>'/images/slider1.jpg','badge'=>'Kesejahteraan Mahasiswa','title_line1'=>'Layanan & Bantuan Beasiswa','title_line2'=>'Mendukung Studi Masa Depan','description'=>'Menyediakan program Beasiswa KIP-Kuliah, Beasiswa Yayasan, layanan konseling psikologis gratis, dan pembinaan karir alumni.','button1_text'=>'Arsip Berita','button1_link'=>'/berita','button2_text'=>'Hubungi Kami','button2_link'=>'https://wa.me/628116313712'],
        ];
        $this->slides = json_decode(Setting::get('slides_list', json_encode($defaultSlides)), true);
    }

    public function switchSection(string $section): void
    {
        $this->activeSection = $section;
    }

    public function updateSlideField(int $index, string $field, $value): void
    {
        $this->slides[$index][$field] = $value;
    }

    public function updateLayananField(int $index, string $field, $value): void
    {
        $this->layanan_cards[$index][$field] = $value;
    }

    public function selectLayananCard(int $index, $layanandId): void
    {
        if ($layanandId && $layanan = Layanan::find((int) $layanandId)) {
            $this->layanan_cards[$index]['layanan_id'] = $layanan->id;
            $this->layanan_cards[$index]['title']      = $layanan->title;
            $this->layanan_cards[$index]['url']        = '/layanan?tab=' . $layanan->slug;
            $this->layanan_cards[$index]['icon']       = 'briefcase';
            $this->layanan_cards[$index]['color']      = 'brand-green';
        } else {
            $this->layanan_cards[$index]['layanan_id'] = null;
            $this->layanan_cards[$index]['title']      = '';
            $this->layanan_cards[$index]['url']        = '#';
        }
    }

    public function updateExternalLink(int $index, string $field, $value): void
    {
        $this->footer_external_links[$index][$field] = $value;
    }

    public function save(): void
    {
        $this->validate();

        // ── Slides ────────────────────────────────────────────────────────
        foreach ($this->slide_photo_files as $i => $file) {
            if ($file) {
                $path = $file->store('slides', 'public');
                $this->slides[$i]['image'] = '/storage/' . $path;
            }
        }
        Setting::set('slides_list', json_encode($this->slides), 'homepage', 'List Slides Banner');

        // ── WR III ────────────────────────────────────────────────────────
        if ($this->wr3_photo_file) {
            $path = $this->wr3_photo_file->store('homepage', 'public');
            $this->wr3_photo_path = '/storage/' . $path;
            Setting::set('wr3_photo_path', $this->wr3_photo_path, 'homepage', 'Foto WR3');
        }
        Setting::set('wr3_name',  $this->wr3_name,  'homepage', 'Nama WR3');
        Setting::set('wr3_quote', $this->wr3_quote, 'homepage', 'Motto/Quote WR3');
        Setting::set('wr3_para1', $this->wr3_para1, 'homepage', 'Paragraf Sambutan 1');
        Setting::set('wr3_para2', $this->wr3_para2, 'homepage', 'Paragraf Sambutan 2');

        // ── Layanan Cards ─────────────────────────────────────────────────
        Setting::set('layanan_cards', json_encode($this->layanan_cards), 'homepage', '6 Kartu Layanan');

        // ── About / Profil ────────────────────────────────────────────────
        Setting::set('about_label',        $this->about_label,        'homepage', 'Label Section About');
        Setting::set('about_title',        $this->about_title,        'homepage', 'Judul Section About');
        Setting::set('about_description',  $this->about_description,  'homepage', 'Deskripsi About');
        Setting::set('about_point1_title', $this->about_point1_title, 'homepage', 'Poin 1 Judul');
        Setting::set('about_point1_desc',  $this->about_point1_desc,  'homepage', 'Poin 1 Deskripsi');
        Setting::set('about_point2_title', $this->about_point2_title, 'homepage', 'Poin 2 Judul');
        Setting::set('about_point2_desc',  $this->about_point2_desc,  'homepage', 'Poin 2 Deskripsi');
        Setting::set('video_youtube_url',  $this->video_youtube_url,  'homepage', 'URL Video YouTube');
        Setting::set('video_title',        $this->video_title,        'homepage', 'Judul Video');

        // ── Top Bar ───────────────────────────────────────────────────────
        Setting::set('contact_phone',      $this->contact_phone,      'contact', 'Nomor Telepon');
        Setting::set('contact_email',      $this->contact_email,      'contact', 'Email Kontak');
        Setting::set('contact_hours',      $this->contact_hours,      'contact', 'Jam Kerja');
        Setting::set('topbar_link1_label', $this->topbar_link1_label, 'topbar',  'Label Link Topbar 1');
        Setting::set('topbar_link1_url',   $this->topbar_link1_url,   'topbar',  'URL Link Topbar 1');
        Setting::set('topbar_link2_label', $this->topbar_link2_label, 'topbar',  'Label Link Topbar 2');
        Setting::set('topbar_link2_url',   $this->topbar_link2_url,   'topbar',  'URL Link Topbar 2');
        Setting::set('whatsapp_number',    $this->whatsapp_number,    'contact', 'Nomor WhatsApp');

        // ── Footer ────────────────────────────────────────────────────────
        Setting::set('footer_tagline',   $this->footer_tagline,   'footer', 'Tagline Footer');
        Setting::set('contact_address',  $this->contact_address,  'contact', 'Alamat Kantor');
        Setting::set('footer_copyright', $this->footer_copyright, 'footer', 'Teks Copyright');
        $cleanLinks = array_values(array_filter($this->footer_external_links, fn($l) => !empty($l['label']) || !empty($l['url'])));
        Setting::set('footer_external_links', json_encode($cleanLinks), 'footer', 'Tautan Resmi Footer');

        Setting::clearCache();

        $this->slide_photo_files = [null, null, null];
        $this->wr3_photo_file    = null;

        session()->flash('message', 'Konten halaman beranda berhasil disimpan.');
    }

    public function render()
    {
        return view('livewire.admin.manage-homepage')
            ->layout('layouts.app');
    }
}
