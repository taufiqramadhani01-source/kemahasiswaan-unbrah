<!DOCTYPE html>
<html lang="id" class="scroll-smooth">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>@yield('title', 'Kemahasiswaan Universitas Baiturrahmah') | Kemahasiswaan UNBRAH</title>
    <!-- CSRF Token -->
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <!-- Meta SEO -->
    <meta name="description" content="Website Resmi Bidang Kemahasiswaan & Alumni Universitas Baiturrahmah. Portal informasi pelayanan beasiswa, organisasi mahasiswa (Ormawa), kegiatan & prestasi mahasiswa, bimbingan konseling, dan karir.">
    <meta name="author" content="Universitas Baiturrahmah">
    
    <!-- Favicon -->
    <link rel="icon" type="image/png" href="/images/logo_unbrah.png">
    
    @vite(['resources/css/app.css', 'resources/js/app.js'])
    
    <!-- SweetAlert2 (Local Offline) -->
    <script src="/js/sweetalert2.js"></script>
    
    @yield('styles')
    @livewireStyles
    
    <!-- Dynamic CSS Variables from Database Settings (Warna Tema & Gradasi) -->
    @php
        $primaryColor   = setting('color_primary', '#001a0b');     // Default Green 955
        $secondaryColor = setting('color_secondary', '#003316');   // Default Green 900
        $accentColor    = setting('color_accent', '#EDC97F');      // Default Gold 500
        $accentHover    = setting('color_accent_hover', '#dcb360'); // Default Gold 600
        $isGradient     = setting('theme_use_gradient', 'true') === 'true';
    @endphp
    <style>
        :root {
            /* Override warna Tailwind */
            --color-brand-green-950: {{ $primaryColor }} !important;
            --color-brand-green-900: {{ $secondaryColor }} !important;
            --color-brand-gold-500: {{ $accentColor }} !important;
            --color-brand-gold-600: {{ $accentHover }} !important;
            
            /* Logic Gradasi vs Solid untuk Background Header/Footer */
            --header-bg: {{ $isGradient ? "linear-gradient(to bottom right, $secondaryColor, $primaryColor)" : $primaryColor }} !important;
        }

        /* Terapkan header-bg HANYA ke header & footer, bukan ke section konten */
        #top-bar,
        #main-header,
        footer.bg-brand-green-950,
        .bg-gradient-to-br.from-brand-green-950.to-brand-green-900,
        .bg-gradient-to-br.from-brand-green-900.to-brand-green-955,
        .bg-gradient-to-br.from-brand-green-900.to-brand-green-950 {
            background: var(--header-bg) !important;
        }
        /* Pastikan section konten (slider, agenda, dll) tidak ikut override */
        section.bg-brand-green-950 {
            background-color: var(--color-brand-green-950) !important;
            background-image: none;
        }
    </style>

    <!-- Google Translate Custom Styling Override -->
    <style>
        .goog-te-banner-frame.skiptranslate,
        .goog-te-banner-frame,
        iframe.goog-te-banner-frame {
            display: none !important;
        }
        body {
            top: 0px !important;
        }
        .goog-tooltip,
        .goog-tooltip:hover {
            display: none !important;
        }
        .goog-text-highlight {
            background-color: transparent !important;
            border: none !important;
            box-shadow: none !important;
        }
    </style>
</head>
<body class="bg-slate-50 text-slate-800 font-sans antialiased overflow-x-hidden">

    @php
        $navMenus = \App\Models\Menu::with(['children.page', 'page'])
            ->whereNull('parent_id')
            ->orderBy('order')
            ->get();
    @endphp
    @include('layouts.header', ['navMenus' => $navMenus])

    <!-- MAIN FRONTEND CONTENT -->
    @if (isset($slot))
        {{ $slot }}
    @else
        @yield('content')
    @endif

    @include('layouts.footer')



    <!-- JS Scripts (Shared Globally) -->
    <script>
        // Navbar scroll effect
        const mainHeader = document.getElementById('main-header');
        const navbarBg = document.getElementById('navbar-bg');
        window.addEventListener('scroll', () => {
            if (window.scrollY > 50) {
                mainHeader.classList.add('scrolled');
                navbarBg.classList.remove('glass-light');
                navbarBg.classList.add('shadow-lg');
            } else {
                mainHeader.classList.remove('scrolled');
                navbarBg.classList.add('glass-light');
                navbarBg.classList.remove('shadow-lg');
            }
        });

        // Mobile menu toggle
        const mobileMenuBtn = document.getElementById('mobile-menu-btn');
        const mobileMenu = document.getElementById('mobile-menu');
        if (mobileMenuBtn && mobileMenu) {
            mobileMenuBtn.addEventListener('click', () => {
                mobileMenu.classList.toggle('hidden');
            });
        }

        // Custom DOM success/error popup modal helper (Identical layout to admin/informasi-publik-berkala)
        function showSwalAlert(title, text, icon = 'success') {
            const existing = document.getElementById("js-success-popup-modal");
            if (existing) existing.remove();

            const modal = document.createElement("div");
            modal.id = "js-success-popup-modal";
            modal.className = "fixed inset-0 z-[100] overflow-y-auto flex items-center justify-center p-4 font-sans";
            
            let iconHtml = '';
            if (icon === 'success') {
                iconHtml = `
                    <div class="w-16 h-16 rounded-full bg-emerald-500/10 flex items-center justify-center mb-5 relative shrink-0">
                        <span class="animate-ping absolute inline-flex h-full w-full rounded-full bg-emerald-500/10 opacity-75"></span>
                        <div class="w-10 h-10 rounded-full bg-[#10b981] flex items-center justify-center text-white relative z-10">
                            <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="3.5" d="M5 13l4 4L19 7"></path>
                            </svg>
                        </div>
                    </div>
                `;
            } else if (icon === 'error') {
                iconHtml = `
                    <div class="w-16 h-16 rounded-full bg-red-500/10 flex items-center justify-center mb-5 relative shrink-0">
                        <span class="animate-ping absolute inline-flex h-full w-full rounded-full bg-red-500/10 opacity-75"></span>
                        <div class="w-10 h-10 rounded-full bg-[#ef4444] flex items-center justify-center text-white relative z-10">
                            <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="3.5" d="M6 18L18 6M6 6l12 12"></path>
                            </svg>
                        </div>
                    </div>
                `;
            } else { // warning
                iconHtml = `
                    <div class="w-16 h-16 rounded-full bg-amber-500/10 flex items-center justify-center mb-5 relative shrink-0">
                        <span class="animate-ping absolute inline-flex h-full w-full rounded-full bg-amber-500/10 opacity-75"></span>
                        <div class="w-10 h-10 rounded-full bg-[#f59e0b] flex items-center justify-center text-white relative z-10">
                            <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="3.5" d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-3L13.732 4c-.77-1.333-2.694-1.333-3.464 0L3.34 16c-.77 1.333.192 3 1.732 3z"></path>
                            </svg>
                        </div>
                    </div>
                `;
            }

            modal.innerHTML = `
                <div class="fixed inset-0 bg-slate-950/80 backdrop-blur-md transition-opacity duration-300" onclick="closeJsSuccessPopupModal()"></div>
                <div class="relative transform overflow-hidden rounded-[28px] bg-gradient-to-br from-brand-green-900 to-brand-green-950 border border-brand-green-800/50 text-center shadow-2xl p-8 max-w-sm w-[90%] transition-all duration-300 scale-100 opacity-100 z-10 flex flex-col items-center">
                    ${iconHtml}
                    <h3 class="text-base font-bold text-white tracking-wide font-display leading-tight">${title}</h3>
                    <p class="text-[11px] text-emerald-100/75 mt-2.5 mb-6 leading-relaxed max-w-xs text-center">
                        ${text}
                    </p>
                    <button onclick="closeJsSuccessPopupModal()" class="w-full py-3 bg-white hover:bg-slate-100 active:scale-[0.98] text-brand-green-950 text-xs font-bold rounded-full transition-all duration-300 shadow-md cursor-pointer border-none outline-none text-center block">
                        Selesai
                    </button>
                </div>
            `;
            document.body.appendChild(modal);
        }

        function closeJsSuccessPopupModal() {
            const modal = document.getElementById("js-success-popup-modal");
            if (modal) {
                modal.classList.add("pointer-events-none");
                modal.style.transition = "opacity 200ms ease-out";
                modal.style.opacity = "0";
                setTimeout(() => modal.remove(), 200);
            }
        }

    </script>
    <!-- Google Translate Element Container (Hidden) -->
    <div id="google_translate_element" class="hidden animate-none" style="display:none;"></div>

    <!-- Google Translate Initialization Script -->
    <script type="text/javascript">
        function googleTranslateElementInit() {
            new google.translate.TranslateElement({
                pageLanguage: 'id',
                includedLanguages: 'id,en',
                layout: google.translate.TranslateElement.InlineLayout.SIMPLE,
                autoDisplay: false
            }, 'google_translate_element');
        }
    </script>
    <script type="text/javascript" src="//translate.google.com/translate_a/element.js?cb=googleTranslateElementInit"></script>

    <!-- Language Toggle Action Script -->
    <script type="text/javascript">
        function changeLanguage(langCode) {
            var cookieValue = "/id/" + langCode;
            document.cookie = "googtrans=" + cookieValue + "; path=/";
            document.cookie = "googtrans=" + cookieValue + "; path=/; domain=" + window.location.hostname;
            
            var hostParts = window.location.hostname.split('.');
            if (hostParts.length > 2) {
                var rootDomain = "." + hostParts.slice(-2).join('.');
                document.cookie = "googtrans=" + cookieValue + "; path=/; domain=" + rootDomain;
            }
            
            window.location.reload();
        }

        function toggleLanguage() {
            var cookies = document.cookie.split(';');
            var currentLang = 'id';
            for (var i = 0; i < cookies.length; i++) {
                var c = cookies[i].trim();
                if (c.indexOf('googtrans=') === 0) {
                    var val = c.substring('googtrans='.length, c.length);
                    if (val.includes('/en')) {
                        currentLang = 'en';
                    }
                    break;
                }
            }
            var nextLang = (currentLang === 'id') ? 'en' : 'id';
            changeLanguage(nextLang);
        }

        document.addEventListener("DOMContentLoaded", function() {
            var cookies = document.cookie.split(';');
            var currentLang = 'id';
            for (var i = 0; i < cookies.length; i++) {
                var c = cookies[i].trim();
                if (c.indexOf('googtrans=') === 0) {
                    var val = c.substring('googtrans='.length, c.length);
                    if (val.includes('/en')) {
                        currentLang = 'en';
                    }
                    break;
                }
            }

            var switchBtn = document.getElementById('lang-switch');
            var switchKnob = document.getElementById('lang-switch-knob');
            var switchText = document.getElementById('lang-switch-text');

            var switchBtnMobile = document.getElementById('lang-switch-mobile');
            var switchKnobMobile = document.getElementById('lang-switch-knob-mobile');
            var switchTextMobile = document.getElementById('lang-switch-text-mobile');

            if (currentLang === 'en') {
                if (switchBtn) {
                    switchBtn.classList.remove('bg-brand-green-900', 'border-brand-green-800');
                    switchBtn.classList.add('bg-brand-gold-500', 'border-brand-gold-400');
                }
                if (switchKnob) {
                    switchKnob.classList.remove('translate-x-0');
                    switchKnob.classList.add('translate-x-[32px]');
                }
                if (switchText) {
                    switchText.innerText = 'EN';
                    switchText.classList.remove('right-2', 'text-brand-gold-500');
                    switchText.classList.add('left-2', 'text-brand-green-950');
                }

                if (switchBtnMobile) {
                    switchBtnMobile.classList.remove('bg-slate-100', 'border-slate-200');
                    switchBtnMobile.classList.add('bg-brand-green-900', 'border-brand-green-850');
                }
                if (switchKnobMobile) {
                    switchKnobMobile.classList.remove('translate-x-0');
                    switchKnobMobile.classList.add('translate-x-[32px]');
                }
                if (switchTextMobile) {
                    switchTextMobile.innerText = 'EN';
                    switchTextMobile.classList.remove('right-2', 'text-slate-450');
                    switchTextMobile.classList.add('left-2', 'text-brand-gold-500');
                }
            } else {
                if (switchBtn) {
                    switchBtn.classList.add('bg-brand-green-900', 'border-brand-green-800');
                    switchBtn.classList.remove('bg-brand-gold-500', 'border-brand-gold-400');
                }
                if (switchKnob) {
                    switchKnob.classList.add('translate-x-0');
                    switchKnob.classList.remove('translate-x-[32px]');
                }
                if (switchText) {
                    switchText.innerText = 'ID';
                    switchText.classList.add('right-2', 'text-brand-gold-500');
                    switchText.classList.remove('left-2', 'text-brand-green-950');
                }

                if (switchBtnMobile) {
                    switchBtnMobile.classList.add('bg-slate-100', 'border-slate-200');
                    switchBtnMobile.classList.remove('bg-brand-green-900', 'border-brand-green-850');
                }
                if (switchKnobMobile) {
                    switchKnobMobile.classList.add('translate-x-0');
                    switchKnobMobile.classList.remove('translate-x-[32px]');
                }
                if (switchTextMobile) {
                    switchTextMobile.innerText = 'ID';
                    switchTextMobile.classList.add('right-2', 'text-slate-450');
                    switchTextMobile.classList.remove('left-2', 'text-brand-gold-500');
                }
            }
        });

        // Toggle Accordion untuk Halaman Informasi/Dokumen
        function toggleAccordion(id) {
            const content = document.getElementById('content-' + id);
            const arrow = document.getElementById('arrow-' + id);
            if (content && arrow) {
                if (content.classList.contains('hidden')) {
                    content.classList.remove('hidden');
                    arrow.classList.add('rotate-180');
                } else {
                    content.classList.add('hidden');
                    arrow.classList.remove('rotate-180');
                }
            }
        }

        // Live search filter untuk Accordion Dokumen
        document.addEventListener('DOMContentLoaded', () => {
            const searchInput = document.getElementById('doc-search');
            if (searchInput) {
                searchInput.addEventListener('input', (e) => {
                    const query = e.target.value.toLowerCase().trim();
                    const docItems = document.querySelectorAll('.doc-item');
                    
                    docItems.forEach(item => {
                        const titleEl = item.querySelector('.font-bold');
                        const descEl = item.querySelector('p');
                        const files = item.querySelectorAll('a');
                        
                        let textToSearch = '';
                        if (titleEl) textToSearch += titleEl.textContent.toLowerCase() + ' ';
                        if (descEl) textToSearch += descEl.textContent.toLowerCase() + ' ';
                        files.forEach(f => {
                            textToSearch += f.textContent.toLowerCase() + ' ';
                        });
                        
                        if (textToSearch.includes(query)) {
                            item.classList.remove('hidden');
                        } else {
                            item.classList.add('hidden');
                        }
                    });
                });
            }
        });
    </script>

    @stack('scripts')
    @livewireScripts
</body>
</html>
