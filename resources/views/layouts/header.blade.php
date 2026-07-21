<!-- TOP BAR (Mini-Header) -->
<div id="top-bar" class="bg-brand-green-950 text-slate-200 text-xs py-2 border-b border-brand-green-900/50 hidden md:block">
    <div class="max-w-[1440px] mx-auto px-4 sm:px-8 lg:px-12 flex justify-between items-center">
        <div class="flex items-center space-x-6">
            <span class="flex items-center space-x-2">
                <svg class="w-4 h-4 text-brand-gold-500 animate-pulse" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 5a2 2 0 012-2h3.28a1 1 0 01.94.725l.548 2.2a1 1 0 01-.321.988l-1.305.98a10.582 10.582 0 004.872 4.872l.98-1.305a1 1 0 01.988-.321l2.2.548a1 1 0 01.725.94V19a2 2 0 01-2 2h-1C9.716 21 3 14.284 3 6V5z"></path></svg>
                <span>{{ setting('contact_phone', '(0751) 4641913') }}</span>
            </span>
            <span class="flex items-center space-x-2">
                <svg class="w-4 h-4 text-brand-gold-500" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 8l7.89 5.26a2 2 0 002.22 0L21 8M5 19h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v10a2 2 0 002 2z"></path></svg>
                <span>{{ setting('contact_email', 'fk@unbrah.ac.id') }}</span>
            </span>
            <span class="flex items-center space-x-2">
                <svg class="w-4 h-4 text-brand-gold-500" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"></path></svg>
                <span>{{ setting('contact_hours', 'Senin - Jumat: 08:00 - 16:00 WIB') }}</span>
            </span>
        </div>
        <div class="flex items-center space-x-4">
            <a href="{{ setting('topbar_link1_url', '/berita') }}" class="hover:text-brand-gold-500 transition-colors">{{ setting('topbar_link1_label', 'Arsip Berita') }}</a>
            <span>|</span>
            <a href="{{ setting('topbar_link2_url', '#') }}" class="hover:text-brand-gold-500 transition-colors">{{ setting('topbar_link2_label', 'Sistem Informasi') }}</a>
            <span>|</span>
            <!-- Language Toggle Switch (Desktop) -->
            <button onclick="toggleLanguage()" 
               id="lang-switch" 
               class="relative inline-flex items-center h-6 w-14 rounded-full transition-colors duration-300 focus:outline-none cursor-pointer p-0.5 no-underline bg-brand-green-900 border border-brand-green-800"
               title="Ganti Bahasa (Switch Language)">
                <span id="lang-switch-knob" 
                      class="pointer-events-none inline-block h-4.5 w-4.5 rounded-full bg-white shadow-md transform transition-transform duration-300 ease-in-out translate-x-0"></span>
                <span id="lang-switch-text" 
                      class="pointer-events-none absolute text-[9px] font-black uppercase tracking-wider select-none transition-all duration-300 text-brand-gold-500 right-2.5">
                    ID
                </span>
            </button>
        </div>
    </div>
</div>

<!-- MAIN NAVBAR (Glassmorphism & Sticky) -->
<header id="main-header" class="sticky top-0 z-50 transition-all duration-300 w-full">
    <div id="navbar-bg" class="glass-light shadow-md transition-all duration-300">
        <div class="max-w-[1440px] mx-auto px-4 sm:px-8 lg:px-12 py-2.5 flex justify-between items-center">
            <!-- Branding / Logo -->
            <a href="/" class="flex items-center space-x-2.5 group">
                <img src="/images/logo_unbrah.png" 
                     alt="Logo Universitas Baiturrahmah" 
                     class="w-12.5 h-12.5 object-contain filter drop-shadow group-hover:scale-105 transition-transform duration-300">
                <div class="flex flex-col justify-center translate-y-[2px]">
                    <span class="text-[19px] md:text-[22px] font-bold tracking-tight text-brand-green-950 font-display group-hover:text-brand-green-600 transition-colors leading-tight">KEMAHASISWAAN</span>
                    <span class="text-[12px] font-bold tracking-wider text-brand-green-900 uppercase leading-tight -mt-0.5">UNIVERSITAS BAITURRAHMAH</span>
                </div>
            </a>

            <!-- Navigation Links (Desktop) -->
            <nav class="hidden lg:flex items-center space-x-1 font-semibold text-sm text-brand-green-950">
                @foreach($navMenus ?? [] as $menu)
                    @php
                        $menuUrl     = $menu->type === 'page' && $menu->page
                                        ? '/page/' . $menu->page->slug
                                        : ($menu->external_url ?? '#');
                        $isExternal  = $menu->type === 'external'; // Hanya external yang asli yang membuka tab baru
                        $hasChildren = $menu->children->isNotEmpty();

                        // Hitung apakah salah satu child sedang aktif
                        $anyChildActive = false;
                        if ($hasChildren) {
                            foreach ($menu->children as $child) {
                                $cUrl = $child->type === 'page' && $child->page
                                    ? '/page/' . $child->page->slug
                                    : ($child->external_url ?? '#');
                                $cClean = ltrim($cUrl, '/');
                                if ($cClean && $cClean !== '#' && request()->is($cClean)) {
                                    $anyChildActive = true;
                                    break;
                                }
                            }
                        }

                        // Menu aktif jika URL-nya sendiri cocok ATAU salah satu child-nya aktif
                        $cleanUrl = ltrim($menuUrl, '/');
                        $isActive = $anyChildActive
                            || ($cleanUrl === ''
                                ? request()->is('/')
                                : ($cleanUrl !== '#' && (request()->is($cleanUrl) || request()->is($cleanUrl . '/*'))));
                    @endphp

                    @if ($hasChildren)
                        {{-- Parent with dropdown --}}
                        <div class="relative group px-3">
                            <button class="flex items-center gap-1 cursor-pointer transition-colors py-2 {{ $isActive ? 'text-brand-green-600' : 'hover:text-brand-green-600' }}">
                                <span>{{ $menu->title }}</span>
                                <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7"></path></svg>
                            </button>
                            <div class="absolute top-full left-0 mt-1 min-w-[180px] rounded-xl bg-white shadow-xl border border-slate-100 opacity-0 invisible group-hover:opacity-100 group-hover:visible transition-all duration-300 p-2 z-50">
                                @foreach($menu->children->sortBy('order') as $child)
                                    @php
                                        $childUrl = $child->type === 'page' && $child->page
                                                    ? '/page/' . $child->page->slug
                                                    : ($child->external_url ?? '#');
                                        $childExt    = $child->type === 'external';
                                        $childClean  = ltrim($childUrl, '/');
                                        $childActive = $childClean && $childClean !== '#' && request()->is($childClean);
                                    @endphp
                                    <a href="{{ $childUrl }}"
                                       {{ $childExt ? 'target="_blank"' : 'wire:navigate' }}
                                       class="block px-4 py-2 text-xs rounded-lg {{ $childActive ? 'bg-brand-green-50 text-brand-green-900 font-bold' : 'hover:bg-brand-green-50 hover:text-brand-green-900 transition-colors' }}">
                                        {{ $child->title }}
                                    </a>
                                @endforeach
                            </div>
                        </div>
                    @else
                        {{-- Plain link --}}
                        <a href="{{ $menuUrl }}"
                           {{ $isExternal ? 'target="_blank"' : 'wire:navigate' }}
                           class="px-3 py-2 {{ $isActive ? 'text-brand-green-600 relative after:absolute after:bottom-0 after:left-3 after:right-3 after:h-[2px] after:bg-brand-green-500' : 'hover:text-brand-green-600 transition-colors' }}">
                            {{ $menu->title }}
                        </a>
                    @endif
                @endforeach
            </nav>

            <!-- Action Button & Mobile Nav Trigger -->
            <div class="flex items-center space-x-4">
                <!-- Call to Action WhatsApp -->
                <a href="https://wa.me/{{ setting('whatsapp_number', '628116313712') }}?text={{ urlencode('Halo Bidang Kemahasiswaan Universitas Baiturrahmah, saya ingin menanyakan informasi layanan kemahasiswaan.') }}" target="_blank" class="hidden md:flex items-center whitespace-nowrap space-x-2 bg-gradient-to-r from-brand-green-900 to-brand-green-950 hover:from-brand-green-950 hover:to-brand-green-900 text-white font-semibold text-xs px-4.5 py-2.5 rounded-full shadow-lg hover:shadow-xl transition-all duration-300 transform hover:-translate-y-0.5 cursor-pointer border border-brand-gold-500/20 hover:border-brand-gold-500/50 no-underline">
                    <svg class="w-3.5 h-3.5 text-brand-gold-500 animate-bounce" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 5a2 2 0 012-2h3.28a1 1 0 01.94.725l.548 2.2a1 1 0 01-.321.988l-1.305.98a10.582 10.582 0 004.872 4.872l.98-1.305a1 1 0 01.988-.321l2.2.548a1 1 0 01.725.94V19a2 2 0 01-2 2h-1C9.716 21 3 14.284 3 6V5z"></path></svg>
                    <span>Hubungi Kami</span>
                </a>

                <!-- Mobile Menu Button -->
                <button id="mobile-menu-btn" class="lg:hidden p-2 rounded-lg text-brand-green-950 hover:bg-brand-green-50 focus:outline-none transition-colors">
                    <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 6h16M4 12h16m-7 6h7"></path></svg>
                </button>
            </div>
        </div>

        <!-- Mobile Menu Dropdown (Dinamis) -->
        <div id="mobile-menu" class="hidden lg:hidden border-t border-slate-100 bg-white shadow-inner max-h-[80vh] overflow-y-auto">
            <div class="px-4 py-3 space-y-1 text-sm font-semibold text-brand-green-950">
                @foreach($navMenus ?? [] as $menu)
                    @php
                        $menuUrl    = $menu->type === 'page' && $menu->page ? '/page/' . $menu->page->slug : ($menu->external_url ?? '#');
                        $isExternal = $menu->type === 'external';
                    @endphp

                    @if ($menu->children->isNotEmpty())
                        <div class="pt-2">
                            <span class="block py-1 text-slate-400 text-xs font-bold uppercase tracking-wider">{{ $menu->title }}</span>
                            @foreach($menu->children->sortBy('order') as $child)
                                @php
                                    $childUrl = $child->type === 'page' && $child->page ? '/page/' . $child->page->slug : ($child->external_url ?? '#');
                                    $childExt = $child->type === 'external';
                                @endphp
                                <a href="{{ $childUrl }}" {{ $childExt ? 'target="_blank"' : 'wire:navigate' }}
                                   class="block py-2 pl-4 text-xs hover:text-brand-green-600 no-underline">{{ $child->title }}</a>
                            @endforeach
                        </div>
                        <hr class="border-slate-100">
                    @else
                        <a href="{{ $menuUrl }}" {{ $isExternal ? 'target="_blank"' : 'wire:navigate' }}
                           class="block py-2 hover:text-brand-green-600 no-underline">{{ $menu->title }}</a>
                    @endif
                @endforeach

                <!-- WhatsApp Contact Link for Mobile -->
                <hr class="border-slate-100 mt-2">
                <a href="https://wa.me/{{ setting('whatsapp_number', '628116313712') }}" target="_blank" class="block py-2 text-brand-gold-600 hover:text-brand-gold-500 font-bold flex items-center space-x-1.5 no-underline">
                    <svg class="w-4 h-4 text-brand-gold-500 animate-bounce" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 19l9 2-9-18-9 18 9-2zm0 0v-8"></path></svg>
                    <span>Hubungi Kami (WhatsApp)</span>
                </a>

                <!-- Language Toggle Mobile -->
                <hr class="border-slate-100">
                <div class="flex items-center justify-between py-2.5">
                    <span class="text-slate-450 text-xs font-bold uppercase tracking-wider">Bahasa / Language</span>
                    <button onclick="toggleLanguage()" id="lang-switch-mobile" class="relative inline-flex items-center h-6 w-14 rounded-full transition-colors duration-300 focus:outline-none cursor-pointer p-0.5 bg-slate-100 border border-slate-200">
                        <span id="lang-switch-knob-mobile" class="pointer-events-none inline-block h-4.5 w-4.5 rounded-full bg-white shadow-md transform transition-transform duration-300 ease-in-out translate-x-0"></span>
                        <span id="lang-switch-text-mobile" class="pointer-events-none absolute text-[10px] font-black uppercase tracking-wider select-none transition-all duration-300 text-slate-450 right-2">ID</span>
                    </button>
                </div>
            </div>
        </div>
    </div>
</header>

<style>
/* Transition smooth colors for header elements */
#main-header,
#navbar-bg,
#navbar-bg *,
#main-header * {
    transition: all 0.3s ease-in-out;
}

/* Scrolled styling state */
#main-header.scrolled #navbar-bg {
    background-color: #022c22 !important; /* bg-brand-green-950 */
    box-shadow: 0 10px 15px -3px rgba(0, 0, 0, 0.2) !important;
    border-bottom: 1px solid rgba(245, 158, 11, 0.1) !important; /* subtle gold border bottom */
}

/* Logo and text */
#main-header.scrolled a.group span.text-brand-green-950 {
    color: #ffffff !important;
}
#main-header.scrolled a.group:hover span.text-brand-green-950 {
    color: #edc97f !important; /* gold #edc97f */
}
#main-header.scrolled a.group span.text-brand-green-900 {
    color: #cbd5e1 !important; /* slate-300 */
}

/* Nav links default and dropdown buttons */
#main-header.scrolled nav > a,
#main-header.scrolled nav button {
    color: #e2e8f0 !important; /* slate-200 */
}
#main-header.scrolled nav > a:hover,
#main-header.scrolled nav button:hover {
    color: #edc97f !important; /* gold #edc97f */
}

/* Active status color override for scrolled navbar */
#main-header.scrolled nav > a.text-brand-green-600,
#main-header.scrolled nav button.text-brand-green-600 {
    color: #edc97f !important; /* gold #edc97f */
}
#main-header.scrolled nav > a.text-brand-green-600::after {
    background-color: #edc97f !important; /* gold #edc97f indicator */
}

/* CTA WhatsApp Button */
#main-header.scrolled .flex.items-center.space-x-4 a {
    background: #edc97f !important; /* gold #edc97f background */
    color: #022c22 !important; /* brand-green-950 text */
    border-color: transparent !important;
    box-shadow: 0 4px 6px -1px rgba(0, 0, 0, 0.1), 0 2px 4px -1px rgba(0, 0, 0, 0.06) !important;
}
#main-header.scrolled .flex.items-center.space-x-4 a:hover {
    background: #e0bd72 !important; /* hover slightly darker */
    box-shadow: 0 10px 15px -3px rgba(0, 0, 0, 0.2) !important;
}
#main-header.scrolled .flex.items-center.space-x-4 a svg {
    color: #022c22 !important;
}

/* Mobile trigger menu icon */
#main-header.scrolled #mobile-menu-btn {
    color: #ffffff !important;
}
#main-header.scrolled #mobile-menu-btn:hover {
    background-color: rgba(255, 255, 255, 0.1) !important;
}
</style>
