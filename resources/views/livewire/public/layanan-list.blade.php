<div>
    @section('title', 'Layanan Kemahasiswaan')
    @php $currentTab = $tabs->firstWhere('slug', $activeTab) ?? $tabs->first(); @endphp

    <!-- HERO SECTION -->
    <section class="relative bg-gradient-to-br from-brand-green-950 to-brand-green-900 py-12 text-white overflow-hidden shadow-xl">
        <div class="max-w-[1440px] mx-auto px-4 sm:px-8 lg:px-12 text-center relative z-10 space-y-3">
            <h1 class="text-3xl sm:text-5xl md:text-6xl font-bold font-display tracking-tight text-white leading-tight drop-shadow">
                <span id="layanan-banner-title">{{ $currentTab?->title ?? 'Layanan Mahasiswa' }}</span><br class="hidden sm:inline">
                <span class="bg-gradient-to-r from-brand-gold-500 to-brand-gold-100 bg-clip-text text-transparent">Universitas Baiturrahmah</span>
            </h1>
            <p class="text-base md:text-lg text-slate-300/90 max-w-none mx-auto font-light leading-relaxed">
                Akses informasi beasiswa, bimbingan konseling psikologi, pengembangan karir, sistem prestasi (SAPS), pembinaan mental (Hasimaru), serta pengaduan kekerasan (PPKTP) Universitas Baiturrahmah.
            </p>
        </div>
    </section>

    <!-- TABS DETAIL CONTENT -->
    <section class="py-20 bg-slate-50 min-h-[600px]">
        <div class="max-w-[1440px] mx-auto px-4 sm:px-8 lg:px-12">
            @if($tabs->count() > 1)
                <div class="flex flex-wrap justify-center gap-2.5 mb-12">
                    @foreach($tabs as $tab)
                        <button type="button"
                                wire:click="switchTab('{{ $tab->slug }}')"
                                class="px-4 py-2.5 rounded-xl text-xs font-bold transition-all cursor-pointer flex items-center gap-2 {{ $currentTab?->slug === $tab->slug ? 'bg-brand-green-900 text-white shadow-md ring-2 ring-brand-gold-500/40' : 'bg-white text-slate-600 hover:bg-brand-green-50 hover:text-brand-green-900 border border-slate-200' }}">
                            @if($tab->icon)
                                <span>{{ $tab->icon }}</span>
                            @endif
                            <span>{{ $tab->title }}</span>
                        </button>
                    @endforeach
                </div>
            @endif

            @if($currentTab)
                <div class="grid grid-cols-1 lg:grid-cols-2 gap-16 items-start">
                    {{-- LEFT: Info Card (style dinamis berdasarkan slug) --}}
                    <div class="space-y-6">
                        @php
                            $cardClass = $currentTab->slug === 'ppktp'
                                ? 'bg-gradient-to-br from-red-900 to-red-950 border-red-500/20'
                                : 'bg-gradient-to-br from-brand-green-900 to-brand-green-950 border-brand-gold-500/20';
                            $cardTitleClass = $currentTab->slug === 'ppktp'
                                ? 'text-red-300'
                                : 'text-brand-gold-500';
                        @endphp
                        <div class="{{ $cardClass }} p-8 rounded-3xl text-white shadow-xl border relative overflow-hidden group">
                            <div class="absolute -right-12 -bottom-12 w-32 h-32 bg-white/5 rounded-full group-hover:scale-110 transition-transform duration-500"></div>
                            <h3 class="text-xl font-bold font-display {{ $cardTitleClass }} mb-6">{{ $currentTab->title }}</h3>
                            <div class="space-y-4 text-xs font-light">
                                <div>
                                    <span class="text-[10px] text-slate-400 font-bold uppercase tracking-wider block">Kategori Layanan</span>
                                    <span class="text-sm font-bold text-white mt-0.5 block">{{ $currentTab->category ?? 'Kemahasiswaan Universitas Baiturrahmah' }}</span>
                                </div>
                                <div class="border-t border-white/10 pt-3">
                                    <span class="text-[10px] text-slate-400 font-bold uppercase tracking-wider block">Status</span>
                                    <span class="text-sm font-bold text-brand-gold-400 mt-0.5 block">{{ $currentTab->is_active ? 'Aktif & Tersedia' : 'Tidak Aktif' }}</span>
                                </div>
                                <div class="border-t border-white/10 pt-3">
                                    <span class="text-[10px] text-slate-400 font-bold uppercase tracking-wider block">Tarif Layanan</span>
                                    <span class="text-sm font-bold text-white mt-0.5 block">{{ $currentTab->fee ?? 'Gratis Bagi Mahasiswa Aktif UNBRAH' }}</span>
                                </div>
                            </div>
                        </div>
                    </div>

                    {{-- RIGHT: Konten Dinamis dari Database --}}
                    <div class="space-y-8">
                        <div class="space-y-4">
                            <h3 class="text-2xl font-bold font-display text-slate-900">{{ $currentTab->title }}</h3>
                            <div class="w-16 h-1 bg-brand-green-600 rounded"></div>
                        </div>
                        <div class="ck-content text-slate-600 text-sm leading-relaxed text-justify font-light space-y-4 prose max-w-none">
                            {!! $currentTab->content !!}
                        </div>
                        <div class="pt-2 flex flex-wrap gap-3">
                            @php
                                $waNum = !empty($currentTab->whatsapp_number) ? preg_replace('/[^0-9]/', '', $currentTab->whatsapp_number) : setting('whatsapp_number', '628116313712');
                            @endphp
                            <a href="https://wa.me/{{ $waNum }}?text={{ urlencode('Halo, saya ingin bertanya tentang layanan ' . ($currentTab->title ?? 'kemahasiswaan') . ' Universitas Baiturrahmah.') }}"
                               target="_blank"
                               class="inline-block px-6 py-3 bg-brand-green-900 hover:bg-brand-green-950 text-white font-bold text-xs rounded-xl shadow-md transition-all cursor-pointer no-underline">
                                Hubungi Tim (WhatsApp)
                            </a>
                        </div>

                        {{-- Jika tab yang aktif adalah Beasiswa, tampilkan daftar program beasiswa terperinci --}}
                        @if($activeTab === 'beasiswa' && !empty($beasiswas))
                            <div class="border-t border-slate-200 pt-8 mt-8 space-y-6">
                                <h4 class="text-lg font-bold font-display text-brand-green-950">Daftar Program Beasiswa Tersedia</h4>
                                <div class="grid grid-cols-1 gap-4">
                                    @foreach($beasiswas as $beasiswa)
                                        <div class="bg-white rounded-2xl p-5 border border-slate-100 shadow-sm hover:shadow-md transition-all flex flex-col md:flex-row justify-between items-start md:items-center gap-4">
                                            <div class="space-y-2">
                                                <div class="flex items-center gap-2 flex-wrap">
                                                    <h5 class="text-sm font-bold text-slate-900">{{ $beasiswa->name }}</h5>
                                                    @if($beasiswa->is_active)
                                                        <span class="inline-flex items-center px-2 py-0.5 rounded-full text-[9px] font-bold bg-emerald-50 text-emerald-700 border border-emerald-200">
                                                            <span class="w-1.5 h-1.5 bg-emerald-500 rounded-full mr-1 animate-pulse"></span>
                                                            Pendaftaran Dibuka
                                                        </span>
                                                    @else
                                                        <span class="inline-flex items-center px-2 py-0.5 rounded-full text-[9px] font-bold bg-rose-50 text-rose-700 border border-rose-200">
                                                            <span class="w-1.5 h-1.5 bg-rose-400 rounded-full mr-1"></span>
                                                            Pendaftaran Ditutup
                                                        </span>
                                                    @endif
                                                </div>
                                                <p class="text-[10px] text-brand-green-700 font-bold uppercase tracking-wider">Penyelenggara: {{ $beasiswa->provider ?? 'Universitas Baiturrahmah' }}</p>
                                                @if($beasiswa->description)
                                                    <p class="text-xs text-slate-500 font-light leading-relaxed max-w-xl">{{ $beasiswa->description }}</p>
                                                @endif
                                                @if($beasiswa->requirements)
                                                    <div class="bg-[#faf9f6] p-3 rounded-lg border border-[#e5e2d9]/60 text-[11px] text-slate-600 font-light max-w-xl">
                                                        <strong class="text-brand-green-950 font-bold block mb-1">Persyaratan Utama:</strong>
                                                        {!! nl2br(e($beasiswa->requirements)) !!}
                                                    </div>
                                                @endif
                                            </div>
                                            <div class="shrink-0">
                                                @if($beasiswa->is_active && $beasiswa->registration_url)
                                                    <a href="{{ $beasiswa->registration_url }}" target="_blank"
                                                       class="inline-block px-5 py-2.5 bg-brand-gold-500 hover:bg-brand-gold-600 text-brand-green-950 font-bold text-xs rounded-lg shadow transition no-underline">
                                                        Daftar Sekarang &rarr;
                                                    </a>
                                                @elseif($beasiswa->is_active)
                                                    <span class="inline-block px-4 py-2 bg-slate-100 text-slate-400 text-[10px] font-bold rounded-lg border border-slate-200">
                                                        Silakan Hubungi Admin
                                                    </span>
                                                @else
                                                    <button disabled class="inline-block px-4 py-2 bg-slate-50 text-slate-300 text-[10px] font-bold rounded-lg border border-slate-100 cursor-not-allowed">
                                                        Tutup
                                                    </button>
                                                @endif
                                            </div>
                                        </div>
                                    @endforeach
                                </div>
                            </div>
                        @endif
                    </div>
                </div>
            @else
                <div class="text-center py-16 text-slate-500">
                    <p class="text-xl">Pilih layanan pada menu di atas.</p>
                </div>
            @endif
        </div>
    </section>
</div>
