<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>{{ $title ?? 'Dasbor Kemahasiswaan - Universitas Baiturrahmah' }}</title>
    
    <!-- Google Fonts -->
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Outfit:wght@300;400;500;600;700&display=swap" rel="stylesheet">
    <link rel="icon" type="image/png" href="{{ asset('images/logo-unbrah.png') }}">

    <!-- Styles & Scripts (Tailwind CSS v4 & Alpine.js via Livewire) -->
    @vite(['resources/css/app.css', 'resources/js/app.js'])

    <!-- CKEditor 5 Assets (Lokal & Global) -->
    <script src="/js/ckeditor5.umd.js"></script>
    <link rel="stylesheet" href="/js/ckeditor5.css">

    <!-- SweetAlert2 (Lokal & Global) -->
    <script src="/js/sweetalert2.js"></script>

    {{-- Inject CSS Custom Properties dari pengaturan database --}}
    @php
        use App\Models\Setting;
        $cp = Setting::get('color_primary', '#052c22');
        $ca = Setting::get('color_accent',  '#c8b282');
        $cb = Setting::get('color_bg',      '#fcfbf9');
        $ct = Setting::get('color_text',    '#1a2421');
        $cs = Setting::get('color_sidebar', '#ffffff');
        $useGrad = Setting::get('theme_use_gradient', 'true') === 'true';
        $cpDark  = $useGrad ? "color-mix(in srgb, {$cp} 80%, black)" : $cp;
    @endphp
    <style>
        :root {
            --color-primary:  {{ $cp }};
            --color-accent:   {{ $ca }};
            --color-bg:       {{ $cb }};
            --color-text:     {{ $ct }};
            --color-sidebar:  {{ $cs }};
            
            /* Gradient variables */
            --color-gradient-start: {{ $cp }};
            --color-gradient-end:   {{ $cpDark }};
        }

        body {
            font-family: 'Outfit', sans-serif;
        }

        /* ===== CKEditor 5 Content Styles (image float & wrap) ===== */
        .ck-content figure.image {
            display: table;
            clear: both;
            text-align: center;
            margin: 1rem auto;
        }
        .ck-content figure.image img {
            display: block;
            margin: 0 auto;
            max-width: 100%;
            min-width: 50px;
            height: auto;
        }
        .ck-content figure.image figcaption {
            display: table-caption;
            caption-side: bottom;
            word-break: break-word;
            color: #888;
            font-size: 0.75rem;
            padding: 0.3rem 0;
        }
        /* Float kanan — teks melilit di kiri */
        .ck-content figure.image.image-style-side,
        .ck-content figure.image.image_resized.image-style-side {
            float: right;
            margin-left: 1.5rem;
            margin-bottom: 0.5rem;
            max-width: 50%;
        }
        /* Float kiri — teks melilit di kanan */
        .ck-content figure.image.image-style-align-left,
        .ck-content figure.image.image_resized.image-style-align-left {
            float: left;
            margin-right: 1.5rem;
            margin-bottom: 0.5rem;
            max-width: 50%;
        }
        /* Inline (di tengah teks) */
        .ck-content .image-style-inline {
            display: inline-block;
            margin: 0;
        }
        /* Clearfix setelah figure */
        .ck-content::after {
            content: '';
            display: table;
            clear: both;
        }
        /* Resize handle — gambar dengan ukuran custom */
        .ck-content figure.image_resized {
            display: block;
            height: auto;
        }
        .ck-content figure.image_resized img {
            width: 100%;
        }
    </style>
</head>
<body x-data="{ mobileMenuOpen: false }" class="bg-[#fcfbf9] text-[#1a2421] min-h-screen flex flex-col md:flex-row antialiased selection:bg-[#c8b282]/20">

    <!-- Top Bar Mobile (Hanya tampil di Layar Kecil / HP) -->
    <div class="md:hidden bg-white border-b border-[#e5e2d9] p-4 flex items-center justify-between sticky top-0 z-40 shadow-sm">
        <div class="flex items-center gap-3">
            <img src="{{ asset('images/logo-unbrah.png') }}" alt="Logo" class="w-8 h-8 object-contain">
            <div>
                <h1 class="font-bold text-[#052c22] tracking-wide text-xs">KEMAHASISWAAN</h1>
                <span class="text-[8px] text-[#8c887d] font-bold uppercase tracking-wider block -mt-0.5">Unbrah Backoffice</span>
            </div>
        </div>
        <button @click="mobileMenuOpen = !mobileMenuOpen" class="p-2 rounded-xl border border-[#e5e2d9] bg-[#faf9f6] text-[#052c22] text-xs font-bold focus:outline-none flex items-center gap-1.5">
            <span x-show="!mobileMenuOpen">☰ Menu Admin</span>
            <span x-show="mobileMenuOpen" x-cloak>✕ Tutup</span>
        </button>
    </div>

    <!-- Backdrop Overlay (Gelap transparan saat sidebar HP terbuka) -->
    <div x-show="mobileMenuOpen" x-cloak 
        @click="mobileMenuOpen = false"
        x-transition:enter="transition-opacity ease-linear duration-200"
        x-transition:enter-start="opacity-0"
        x-transition:enter-end="opacity-100"
        x-transition:leave="transition-opacity ease-linear duration-200"
        x-transition:leave-start="opacity-100"
        x-transition:leave-end="opacity-0"
        class="fixed inset-0 bg-black/40 backdrop-blur-sm z-40 md:hidden"></div>

    <!-- Sidebar Navigation Drawer (Statis di Desktop, Slide Drawer Overlay di HP) -->
    <aside 
        class="fixed md:sticky top-0 inset-y-0 left-0 z-50 w-72 md:w-64 h-screen border-r border-[#e5e2d9] flex flex-col justify-between shrink-0 p-6 bg-white transition-transform duration-200 ease-in-out shadow-2xl md:shadow-none overflow-y-auto"
        :class="mobileMenuOpen ? 'translate-x-0' : '-translate-x-full md:translate-x-0'"
        style="background-color: var(--color-sidebar);">
        <div>
            <!-- Brand Logo & Header Sidebar -->
            <div class="flex items-center justify-between md:justify-start gap-3 mb-8">
                <div class="flex items-center gap-3">
                    <img src="{{ asset('images/logo-unbrah.png') }}" alt="Logo" class="w-10 h-10 object-contain">
                    <div>
                        <h1 class="font-bold text-[#052c22] tracking-wide text-xs">KEMAHASISWAAN</h1>
                        <span class="text-[9px] text-[#8c887d] font-bold uppercase tracking-wider">Unbrah Backoffice</span>
                    </div>
                </div>
                <!-- Tombol Tutup Sidebar di HP -->
                <button @click="mobileMenuOpen = false" class="md:hidden text-[#8c887d] hover:text-[#052c22] p-1 text-sm font-bold">
                    ✕
                </button>
            </div>

            <!-- Navigation Links -->
            <nav class="space-y-1">
                @php
                    $user = Auth::user();
                    
                    // Definisikan menu dengan kunci/key otorisasi
                    $allLinks = [
                        ['key' => 'dashboard',  'href' => '/admin/dashboard',  'icon' => '📊', 'label' => 'Dashboard'],
                        ['key' => 'layanans',   'href' => '/admin/layanans',   'icon' => '🛡️', 'label' => 'Layanan Mahasiswa'],
                        ['key' => 'ormawas',    'href' => '/admin/ormawas',    'icon' => '👥', 'label' => 'Organisasi (Ormawa)'],
                        ['key' => 'beasiswas',  'href' => '/admin/beasiswas',  'icon' => '🎓', 'label' => 'Info Beasiswa'],
                        ['key' => 'prestasis',  'href' => '/admin/prestasis',  'icon' => '🏆', 'label' => 'Prestasi Mahasiswa'],
                        ['key' => 'dokumens',   'href' => '/admin/dokumens',   'icon' => '📥', 'label' => 'Dokumen & Unduhan'],
                        ['key' => 'posts',      'href' => '/admin/posts',      'icon' => '📰', 'label' => 'Berita & Artikel'],
                        ['key' => 'kegiatans',  'href' => '/admin/kegiatans',  'icon' => '📅', 'label' => 'Agenda Kegiatan'],
                        ['key' => 'pages',      'href' => '/admin/pages',      'icon' => '📄', 'label' => 'Halaman Dinamis'],
                        ['key' => 'menus',      'href' => '/admin/menus',      'icon' => '🔗', 'label' => 'Navigasi Menu'],
                    ];
                @endphp
                @foreach ($allLinks as $link)
                    @if ($user && $user->hasAccessToMenu($link['key']))
                        @php $isActive = request()->is(ltrim($link['href'], '/')) @endphp
                        <a href="{{ $link['href'] }}" wire:navigate @click="mobileMenuOpen = false"
                            class="flex items-center gap-3 px-4 py-2.5 rounded-xl text-xs font-bold transition duration-200
                                {{ $isActive
                                    ? 'bg-[#052c22] text-[#c8b282]'
                                    : 'text-[#5c5950] hover:text-[#052c22] hover:bg-[#faf9f6]' }}">
                            {{ $link['icon'] }} {{ $link['label'] }}
                        </a>
                    @endif
                @endforeach

                {{-- Divider Pengaturan (Hanya Super Admin) --}}
                @if($user && $user->isSuperAdmin())
                    <div class="pt-3 mt-1 border-t border-[#e5e2d9] space-y-1">
                        <p class="text-[9px] font-bold uppercase tracking-widest text-[#c8b282]/60 px-4 mb-1">Pengaturan Utama</p>
                        @php $isHomepage = request()->is('admin/homepage') @endphp
                        <a href="/admin/homepage" wire:navigate @click="mobileMenuOpen = false"
                            class="flex items-center gap-3 px-4 py-2.5 rounded-xl text-xs font-bold transition duration-200
                                {{ $isHomepage ? 'bg-[#052c22] text-[#c8b282]' : 'text-[#5c5950] hover:text-[#052c22] hover:bg-[#faf9f6]' }}">
                            🏠 Konten Beranda
                        </a>
                        @php $isGroups = request()->is('admin/groups') @endphp
                        <a href="/admin/groups" wire:navigate @click="mobileMenuOpen = false"
                            class="flex items-center gap-3 px-4 py-2.5 rounded-xl text-xs font-bold transition duration-200
                                {{ $isGroups ? 'bg-[#052c22] text-[#c8b282]' : 'text-[#5c5950] hover:text-[#052c22] hover:bg-[#faf9f6]' }}">
                            👥 Kelompok Hak Akses
                        </a>
                        @php $isUsers = request()->is('admin/users') @endphp
                        <a href="/admin/users" wire:navigate @click="mobileMenuOpen = false"
                            class="flex items-center gap-3 px-4 py-2.5 rounded-xl text-xs font-bold transition duration-200
                                {{ $isUsers ? 'bg-[#052c22] text-[#c8b282]' : 'text-[#5c5950] hover:text-[#052c22] hover:bg-[#faf9f6]' }}">
                            🔑 Kelola Admin / User
                        </a>
                        @php $isSettings = request()->is('admin/settings') @endphp
                        <a href="/admin/settings" wire:navigate @click="mobileMenuOpen = false"
                            class="flex items-center gap-3 px-4 py-2.5 rounded-xl text-xs font-bold transition duration-200
                                {{ $isSettings ? 'bg-[#052c22] text-[#c8b282]' : 'text-[#5c5950] hover:text-[#052c22] hover:bg-[#faf9f6]' }}">
                            🎨 Tema & Warna Situs
                        </a>
                    </div>
                @endif

            </nav>
        </div>

        <!-- User Profile & Logout -->
        <div class="pt-4 border-t border-[#e5e2d9] mt-6 flex items-center justify-between gap-2">
            <div class="flex items-center gap-2.5 min-w-0 flex-1">
                <div class="w-8 h-8 rounded-full bg-[#faf9f6] border border-[#c8b282]/40 flex items-center justify-center font-bold text-[#052c22] text-xs shrink-0 shadow-sm">
                    {{ substr(Auth::user()->name ?? 'A', 0, 1) }}
                </div>
                <div class="min-w-0 flex-1">
                    <p class="text-xs font-extrabold text-[#052c22] truncate leading-tight">{{ Auth::user()->name ?? 'Admin Mhs' }}</p>
                    <p class="text-[9px] text-[#8c887d] truncate font-bold uppercase tracking-wider mt-0.5">
                        @if($user && $user->isSuperAdmin())
                            👑 Super Admin
                        @elseif($user && $user->isStafLayanan())
                            🎓 Staf Layanan
                        @elseif($user && $user->isStafHumas())
                            📰 Staf Humas
                        @else
                            Administrator
                        @endif
                    </p>
                </div>
            </div>
            
            <form id="logout-form" method="POST" action="/admin/logout" class="shrink-0">
                @csrf
                <button type="button" 
                    onclick="confirmLogout()"
                    class="p-2 text-slate-400 hover:text-red-600 hover:bg-red-50 rounded-xl transition border border-transparent hover:border-red-100 flex items-center justify-center text-sm" 
                    title="Keluar / Logout">
                    🚪
                </button>
            </form>
        </div>
    </aside>

    <script>
        function confirmLogout() {
            if (typeof Swal !== 'undefined') {
                Swal.fire({
                    title: 'Konfirmasi Keluar',
                    text: 'Apakah Anda yakin ingin keluar dari dasbor admin?',
                    icon: 'warning',
                    showCancelButton: true,
                    confirmButtonColor: '#052c22',
                    cancelButtonColor: '#d33',
                    confirmButtonText: 'Ya, Keluar',
                    cancelButtonText: 'Batal',
                    customClass: {
                        popup: 'rounded-2xl border border-[#e5e2d9]',
                        confirmButton: 'px-4 py-2 rounded-xl font-bold text-xs uppercase tracking-wider',
                        cancelButton: 'px-4 py-2 rounded-xl font-bold text-xs uppercase tracking-wider'
                    }
                }).then((result) => {
                    if (result.isConfirmed) {
                        document.getElementById('logout-form').submit();
                    }
                });
            } else {
                if (confirm('Apakah Anda yakin ingin keluar dari dasbor admin?')) {
                    document.getElementById('logout-form').submit();
                }
            }
        }
    </script>

    <!-- Main Content Area -->
    <main class="flex-1 min-w-0 p-4 md:p-10 overflow-x-hidden max-w-7xl mx-auto w-full">
        <!-- Top Workspace Bar -->
        <div class="mb-8 flex items-center justify-between p-4 rounded-2xl bg-white border border-[#e5e2d9] shadow-sm">
            <div class="flex items-center gap-2">
                <span class="w-2.5 h-2.5 rounded-full bg-[#052c22]"></span>
                <span class="text-xs font-bold text-[#052c22] uppercase tracking-widest">Situs Aktif: Kemahasiswaan</span>
            </div>
            <a href="/" target="_blank" class="px-3 py-1 text-[10px] rounded-full bg-[#faf9f6] text-[#052c22] border border-[#e5e2d9] hover:bg-[#e5e2d9]/30 transition font-bold uppercase tracking-wider">
                Lihat Web Publik 🌐
            </a>
        </div>

        <!-- Main Slot -->
        {{ $slot }}
    </main>

</body>
</html>
