<!-- FOOTER SECTION -->
<footer class="bg-brand-green-950 text-slate-400 pt-20 border-t border-brand-green-900">
    <div class="max-w-[1440px] mx-auto px-4 sm:px-8 lg:px-12">
        <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-12 gap-12 pb-16">

            <!-- Column 1: Brand/Logo + Tagline + Kontak (Col span 4) -->
            <div class="lg:col-span-4 space-y-6">
                <a href="/" class="flex items-center space-x-2.5 group">
                    <img src="/images/logo_unbrah.png"
                         alt="Logo Universitas Baiturrahmah"
                         class="w-18 h-18 object-contain filter brightness-95">
                    <div class="flex flex-col justify-center translate-y-[2px]">
                        <span class="text-2xl md:text-3xl font-bold tracking-tight text-white font-display group-hover:text-brand-gold-500 transition-colors leading-tight">KEMAHASISWAAN</span>
                        <span class="text-[13.5px] md:text-[15.5px] font-bold tracking-wider text-slate-400 uppercase leading-tight -mt-0.5">UNIVERSITAS BAITURRAHMAH</span>
                    </div>
                </a>

                <p class="text-xs text-slate-400 leading-relaxed font-light">
                    {{ setting('footer_tagline', 'Membina potensi, karakter, kreativitas, dan kesejahteraan mahasiswa Universitas Baiturrahmah untuk menghasilkan lulusan yang kompeten, beretika luhur, dan siap berkontribusi bagi bangsa.') }}
                </p>

                <!-- Contacts -->
                <div class="space-y-3 text-xs text-slate-300">
                    <div class="flex items-start space-x-3">
                        <svg class="w-4 h-4 text-brand-gold-500 mt-0.5 shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17.657 16.657L13.414 20.9a1.998 1.998 0 01-2.827 0l-4.244-4.243a8 8 0 1111.314 0z"></path><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 11a3 3 0 11-6 0 3 3 0 016 0z"></path></svg>
                        <span class="font-light">{{ setting('contact_address', 'Jl. Raya By Pass KM 15, Aie Pacah, Kec. Koto Tangah, Kota Padang, Sumatera Barat 25176') }}</span>
                    </div>
                    <div class="flex items-center space-x-3">
                        <svg class="w-4 h-4 text-brand-gold-500 shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 5a2 2 0 012-2h3.28a1 1 0 01.94.725l.548 2.2a1 1 0 01-.321.988l-1.305.98a10.582 10.582 0 004.872 4.872l.98-1.305a1 1 0 01.988-.321l2.2.548a1 1 0 01.725.94V19a2 2 0 01-2 2h-1C9.716 21 3 14.284 3 6V5z"></path></svg>
                        <span class="font-light">{{ setting('contact_phone', '(0751) 4641913') }}</span>
                    </div>
                    <div class="flex items-center space-x-3">
                        <svg class="w-4 h-4 text-brand-gold-500 shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 8l7.89 5.26a2 2 0 002.22 0L21 8M5 19h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v10a2 2 0 002 2z"></path></svg>
                        <span class="font-light">{{ setting('contact_email', 'fk@unbrah.ac.id') }}</span>
                    </div>
                </div>
            </div>

            <!-- Column 2: Navigasi Pintas dari Menu DB (Col span 2) -->
            @php
                $footerMenus = \App\Models\Menu::whereNull('parent_id')->orderBy('order')->limit(7)->get();
            @endphp
            <div class="lg:col-span-2 lg:col-start-6 space-y-6">
                <h4 class="text-white font-bold font-display text-sm tracking-wider uppercase">Navigasi Pintas</h4>
                <ul class="space-y-3 text-xs">
                    @forelse ($footerMenus as $m)
                        @php
                            $mUrl = $m->type === 'page' && $m->page ? '/' . $m->page->slug : ($m->external_url ?? '#');
                        @endphp
                        <li>
                            <a href="{{ $mUrl }}" class="hover:text-white transition-colors">{{ $m->title }}</a>
                        </li>
                    @empty
                        <li><a href="/" class="hover:text-white transition-colors">Beranda</a></li>
                        <li><a href="/profil" class="hover:text-white transition-colors">Profil Kemahasiswaan</a></li>
                        <li><a href="/layanan" class="hover:text-white transition-colors">Layanan Mahasiswa</a></li>
                        <li><a href="/ormawa" class="hover:text-white transition-colors">Organisasi Mahasiswa</a></li>
                        <li><a href="/berita" class="hover:text-white transition-colors">Kilas Berita</a></li>
                    @endforelse
                </ul>
            </div>

            <!-- Column 3: Layanan Utama (Col span 3) — Statis karena ini link tab spesifik -->
            <div class="lg:col-span-3 space-y-6">
                <h4 class="text-white font-bold font-display text-sm tracking-wider uppercase">Layanan Utama</h4>
                <ul class="space-y-3 text-xs">
                    <li><a href="/layanan?tab=beasiswa" class="hover:text-white transition-colors">Layanan Beasiswa</a></li>
                    <li><a href="/layanan?tab=psikologi" class="hover:text-white transition-colors">Layanan Bimbingan & Psikologi</a></li>
                    <li><a href="/layanan?tab=p2k2" class="hover:text-white transition-colors">Layanan Karir & Alumni (P2K2)</a></li>
                    <li><a href="/layanan?tab=ppktp" class="hover:text-white transition-colors">Satgas Pencegahan Kekerasan (PPKTP)</a></li>
                    <li><a href="/dokumen" class="hover:text-white transition-colors">Unduh Dokumen & Pedoman</a></li>
                </ul>
            </div>

            <!-- Column 4: Tautan Resmi (dari setting DB) (Col span 2) -->
            @php
                $externalLinks = json_decode(
                    setting('footer_external_links', json_encode([
                        ['label' => 'Univ. Baiturrahmah', 'url' => 'https://unbrah.ac.id'],
                        ['label' => 'Kemendikbudristek',  'url' => 'https://kemdikbud.go.id'],
                        ['label' => 'RSI Siti Rahmah',    'url' => 'https://rsisilirahmah.co.id'],
                    ])),
                    true
                ) ?? [];
            @endphp
            <div class="lg:col-span-2 space-y-6">
                <h4 class="text-white font-bold font-display text-sm tracking-wider uppercase">Tautan Resmi</h4>
                <ul class="space-y-3 text-xs">
                    @foreach($externalLinks as $extLink)
                        @if(!empty($extLink['label']))
                            <li>
                                <a href="{{ $extLink['url'] }}" target="_blank" class="hover:text-white transition-colors">
                                    {{ $extLink['label'] }}
                                </a>
                            </li>
                        @endif
                    @endforeach
                </ul>
            </div>

        </div>

        <!-- Bottom Copyright bar -->
        <div class="border-t border-brand-green-900/60 py-8 flex flex-col sm:flex-row justify-between items-center text-[10px] text-slate-500">
            <p>{{ setting('footer_copyright', '© ' . date('Y') . ' Bidang Kemahasiswaan Universitas Baiturrahmah. All rights reserved. | Dirancang dengan ❤️') }}</p>
            <div class="flex space-x-6 mt-4 sm:mt-0 font-medium">
                <a href="#" class="hover:text-brand-gold-500 transition-colors">Kebijakan Privasi</a>
                <a href="#" class="hover:text-brand-gold-500 transition-colors">Ketentuan Layanan</a>
                <a href="#" class="hover:text-brand-gold-500 transition-colors">Sitemap</a>
            </div>
        </div>
    </div>
</footer>
