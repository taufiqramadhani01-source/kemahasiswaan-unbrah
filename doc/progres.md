# Log Progres Pengembangan — Portal Kemahasiswaan UNBRAH

Dokumen ini mencatat riwayat *setup*, audit sistem, dan perbaikan *bug* pada proyek **Portal Kemahasiswaan Universitas Baiturrahmah**.

---

## 📅 29 Oktober 2026 — Setup Lokal, Audit Menyeluruh & Perbaikan 12 Kelompok Bug

### 1. Inisialisasi Repository & Setup Lingkungan Lokal
- Menarik kode sumber dari repositori `https://github.com/taufiqramadhani01-source/kemahasiswaan-unbrah` (branch `main`).
- Mengonfigurasi file `.env` dan membuat basis data SQLite (`database/database.sqlite`).
- Menjalankan instalasi dependensi PHP (`composer install`), pembuatan `APP_KEY`, migrasi dan *seeding* awal (`php artisan migrate --seed`), serta `php artisan storage:link`.
- Menjalankan instalasi dependensi *frontend* (`npm install`) dan kompilasi aset produksi (`npm run build`).

---

### 2. Perbaikan Bug Halaman Publik (Frontend)

1. **Perbaikan Status & Deskripsi Halaman Beasiswa (`/beasiswa`)**
   - **File**:
     - `resources/views/livewire/public/beasiswa-list.blade.php`
     - `app/Livewire/Public/BeasiswaList.php`
     - `app/Livewire/Public/Home.php`
   - **Masalah**:
     - Blade memeriksa kolom `$beasiswa->start_date` dan `$beasiswa->end_date` yang tidak ada di tabel `beasiswas`, sehingga status beasiswa selalu tertulis *"Ditutup"* dan tombol *"Daftar Sekarang"* tidak pernah muncul meskipun `is_active = true`.
     - Query pencarian menggunakan `orWhere('provider', ...)` tanpa *closure* pengelompokan, sehingga membocorkan data beasiswa yang tidak aktif (`is_active = false`) dan mengabaikan filter penyelenggara.
     - Halaman Beranda (`Home.php`) menampilkan beasiswa yang sudah ditutup pada daftar beasiswa terbaru.
   - **Perbaikan**:
     - Mengubah pengecekan `$isOpen` menjadi `(bool) $beasiswa->is_active` serta menampilkan deskripsi singkat (`$beasiswa->description`).
     - Membungkus kondisi pencarian `where('name', ...)->orWhere('provider', ...)` di dalam *closure* `where(function ($q) { ... })`.
     - Menambahkan filter `->where('is_active', true)` pada `$latestBeasiswas` di `Home.php`.

2. **Perbaikan Navigasi Tab, Judul Banner & Slug Layanan (`/layanan`)**
   - **File**:
     - `app/Livewire/Public/LayananList.php`
     - `resources/views/livewire/public/layanan-list.blade.php`
     - `database/seeders/DatabaseSeeder.php`
   - **Masalah**:
     - Judul *Hero Banner* di `/layanan` terpaku pada `$tabs->first()?->title` dan tidak berubah saat berpindah tab.
     - Belum ada deretan tombol tab di halaman `/layanan` untuk berpindah antar layanan secara langsung.
     - *Slug* layanan di `DatabaseSeeder.php` hanya berisi `beasiswa`, `konseling`, dan `karir-alumni`, sedangkan kartu layanan di Beranda dan Footer mengarah ke `?tab=psikologi`, `?tab=p2k2`, `?tab=ppktp`, `?tab=saps`, dan `?tab=hasimaru`.
   - **Perbaikan**:
     - Menambahkan *fallback* otomatis ke tab pertama jika parameter `?tab=` tidak ditemukan di database.
     - Mengubah judul *Hero Banner* agar mengikuti `$currentTab?->title`.
     - Menambahkan bar navigasi tombol tab interaktif (`wire:click="switchTab(...)"`) di bagian atas konten `/layanan`.
     - Melengkapi `DatabaseSeeder.php` dengan ke-6 layanan utama (`beasiswa`, `psikologi`, `p2k2`, `ppktp`, `saps`, `hasimaru`) beserta kategori dan informasi tarifnya.

3. **Penambahan Navigasi Tab Organisasi Mahasiswa (`/ormawa`)**
   - **File**: `resources/views/livewire/public/ormawa-list.blade.php`
   - **Masalah**: Pengguna yang membuka `/ormawa` tidak memiliki tombol navigasi di dalam halaman untuk berpindah antar profil Ormawa (`BEM`, `DPM`, dll.).
   - **Perbaikan**: Menambahkan deretan tombol tab interaktif (`wire:click="switchTab('...')"`).

4. **Perbaikan Format Blok Halaman Dinamis (`sop-ormawa`) & Link Footer**
   - **File**:
     - `database/seeders/DatabaseSeeder.php`
     - `resources/views/layouts/footer.blade.php`
   - **Masalah**:
     - Data awal halaman `sop-ormawa` menggunakan `'type' => 'hero_banner'` dan `'content'` yang tidak dikenali oleh `resources/views/public/page.blade.php` (yang mengharapkan `'type' => 'hero'` dan `'html'`), sehingga halaman `/page/sop-ormawa` tampil kosong.
     - Link menu bertipe `page` di `footer.blade.php` mengarah ke `/{{ $m->page->slug }}` (404) alih-alih `/page/{{ $m->page->slug }}`, serta belum melakukan *eager loading* `with('page')`.
   - **Perbaikan**:
     - Menyesuaikan struktur JSON blok `sop-ormawa` di `DatabaseSeeder.php` ke format `'type' => 'hero'` dan `'data' => ['html' => ...]`.
     - Memperbaiki URL di `footer.blade.php` menjadi `/page/{{ $m->page->slug }}` dan menambahkan `with('page')`.

---

### 3. Perbaikan Keamanan RBAC, CMS & Panel Admin

5. **Pengetatan Otorisasi Hak Akses (RBAC) pada Komponen Livewire Admin**
   - **File**:
     - `app/Livewire/Admin/ManageKegiatans.php`
     - `app/Livewire/Admin/ManagePrestasis.php`
     - `app/Livewire/Admin/ManageDokumens.php`
     - `app/Livewire/Admin/ManagePosts.php`
   - **Masalah**: Keempat komponen belum memiliki pengecekan `hasAccessToMenu(...)` di `mount()`, sehingga staf yang tidak memiliki izin menu tetap dapat mengakses halaman melalui URL langsung. Selain itu, `resetExcept([])` pada `ManageKegiatans` dan `ManagePosts` mereset seluruh state termasuk kata kunci pencarian, serta query pencarian `ManagePrestasis` belum dibungkus *closure*.
   - **Perbaikan**:
     - Menambahkan method `mount()` dengan validasi `auth()->user()->hasAccessToMenu(...)` dan `abort(403)` jika tidak berhak.
     - Mengubah `$this->resetExcept([])` menjadi `$this->resetExcept(['search', 'isFormOpen'])`.
     - Membungkus pencarian `student_name` dan `title` pada `ManagePrestasis` di dalam *closure*.

6. **Penambahan Opsi Role `staf_humas` pada Manajemen Akun Admin**
   - **File**: `resources/views/livewire/admin/manage-users.blade.php`
   - **Masalah**: Dropdown pilihan tipe akun hanya menampilkan `superadmin` dan `staf_layanan`, sehingga saat mengedit akun Staf Humas (`role = staf_humas`), perannya otomatis berubah menjadi `staf_layanan`.
   - **Perbaikan**: Menambahkan `<option value="staf_humas">📰 Staf Humas & Publikasi</option>` pada dropdown tipe akun.

7. **Perbaikan Page Builder (`page-editor.blade.php`)**
   - **File**: `resources/views/livewire/admin/page-editor.blade.php`
   - **Masalah**:
     - Penulisan `wire:model="blocks.{{ $i }}.data.items.{{ $subIdx }.title"` kurang satu kurung kurawal penutup `}` sehingga memicu *syntax error* saat merender blok Accordion (FAQ).
     - Memuat ulang CKEditor 5 dari CDN padahal `layouts/app.blade.php` sudah memuat `/js/ckeditor5.umd.js` secara lokal.
   - **Perbaikan**: Memperbaiki sintaks Blade menjadi `{{ $subIdx }}.title` dan menghapus pemuatan skrip CKEditor CDN duplikat.

8. **Perbaikan Model & Komponen Admin Ormawa (Kompatibilitas PHP 8.5)**
   - **File**:
     - `app/Models/Ormawa.php`
     - `app/Livewire/Admin/ManageOrmawas.php`
   - **Masalah**: `ManageOrmawas` menetapkan properti `$this->image_path` dan `$this->image_file` yang tidak dideklarasikan di kelas (memicu *deprecation notice* di PHP 8.5), dan atribut `#[Fillable]` di `Ormawa.php` belum menyertakan `image_path`.
   - **Perbaikan**: Menghapus assignment properti dinamis yang tidak terpakai di `ManageOrmawas.php` dan menambahkan `'image_path'` ke atribut `#[Fillable]` di `Ormawa.php`.

9. **Perbaikan Tampilan Target URL di Manajemen Menu Navigasi**
   - **File**: `resources/views/livewire/admin/manage-menus.blade.php`
   - **Masalah**: Menu bertipe `ormawa` dan `layanan` menampilkan peringatan *"⚠ Tidak ada target"* di daftar menu admin, dan menu bertipe `page` menampilkan `/slug` alih-alih `/page/slug`.
   - **Perbaikan**: Menambahkan kondisi tampilan target untuk tipe `ormawa`, `layanan`, dan memperbaiki prefix `/page/` untuk tipe `page` baik pada menu induk maupun sub-menu.

10. **Sinkronisasi Helper `setting()`, Pengaturan Gradasi Tema & Duplikasi Alpine.js**
    - **File**:
      - `app/helpers.php`
      - `resources/views/layouts/frontend.blade.php`
    - **Masalah**:
      - Fungsi `setting()` di `app/helpers.php` menggunakan *static array* terpisah dari `Setting::get()`, dan `frontend.blade.php` membaca kunci `theme_gradient` padahal `ManageSettings.php` menyimpan kunci `theme_use_gradient` (`'true'`/`'false'`).
      - `frontend.blade.php` memuat `<script defer src="/js/alpine.js"></script>` secara manual padahal Livewire 4 sudah menyertakan Alpine.js di dalam `@livewireScripts`.
    - **Perbaikan**:
      - Mengintegrasikan helper `setting()` langsung ke `Setting::get($key, $default)`.
      - Mengubah pembacaan variabel gradasi di `frontend.blade.php` menjadi `setting('theme_use_gradient', 'true') === 'true'`.
      - Menghapus pemuatan `/js/alpine.js` ganda di `frontend.blade.php`.

11. **Pembersihan File Komponen Duplikat Tidak Terpakai**
    - Menghapus file duplikat yang tidak terpakai:
      - `resources/views/components/admin/⚡manage-users.blade.php`
      - `resources/views/components/public/⚡prestasi-list.blade.php`

12. **Perbaikan Manajemen Beranda (`/admin/homepage`) & Cache `Setting`**
    - **File**:
      - `app/Livewire/Admin/ManageHomepage.php`
      - `resources/views/livewire/admin/manage-homepage.blade.php`
      - `app/Models/Setting.php`
      - `resources/views/livewire/public/home.blade.php`
    - **Masalah**:
      1. Dropdown **Pilih Layanan** pada tab *Kartu Layanan* selalu menampilkan *"— Tidak dipilih —"* karena data default tidak memiliki `layanan_id`, dan pemilihan layanan di `selectLayananCard()` menimpa seluruh ikon serta warna kartu menjadi `briefcase` + `brand-green`.
      2. Input teks pada tab *Banner Slider*, *Kartu Layanan*, dan *Footer* menggunakan `value="..."` + `wire:change` sehingga berisiko tidak tersimpan jika tombol **Simpan Semua** langsung diklik sebelum *blur* dan tidak memperbarui *Live Preview* secara langsung.
      3. Pratinjau foto baru (`slide_photo_files` dan `wr3_photo_file`) tidak menampilkan gambar *temporary upload* sebelum disimpan.
      4. `Setting::get($key, $default)` menyimpan nilai `$default` secara permanen di `Cache::rememberForever("setting_{$key}")`, sehingga pemanggilan `setting('wr3_photo_path')`, `setting('wr3_para2')`, dan `setting('wr3_quote')` tanpa default di `home.blade.php` mengunci nilai `null` di cache.
      5. Link default tombol 2 pada Slide #1 mengarah ke `/profil` (404 Not Found) alih-alih `#about` dan atribut `wire:navigate` dipasang pada link anchor `#`.
    - **Perbaikan**:
      - Menyinkronkan `layanan_id` otomatis berdasarkan slug `?tab=` pada `mount()` dan mempertahankan pemetaan ikon & warna spesifik tiap layanan di `selectLayananCard()`.
      - Mengganti seluruh `wire:change` menjadi `wire:model.live` serta menambahkan alert ringkasan error validasi di bagian atas halaman `/admin/homepage`.
      - Menambahkan pratinjau langsung menggunakan `temporaryUrl()` untuk unggahan foto *Slide Banner* dan *Foto WR III* baik di form maupun di panel pratinjau kanan.
      - Memperbarui `Setting::get()` agar hanya meng-cache kamus data dari tabel `settings` (`app_settings_all`) tanpa mengunci parameter `$default`, serta menyelaraskan nilai default Sambutan WR III di `home.blade.php`.
      - Mengubah link default tombol 2 Slide #1 dari `/profil` ke `#about` dan menonaktifkan `wire:navigate` untuk link anchor (`#`) maupun eksternal (`http`).

---

### 4. Verifikasi Akhir
- Menjalankan `php artisan migrate:fresh --seed --force` untuk memperbarui seluruh data *seeder* di SQLite.
- Menjalankan `php artisan view:cache` dan `php artisan view:clear` — seluruh template Blade berhasil dikompilasi tanpa *syntax error*.

