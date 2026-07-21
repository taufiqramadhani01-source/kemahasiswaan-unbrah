<div>
    @section('title', 'Dokumen & Unduhan - Kemahasiswaan UNBRAH')

    <!-- HERO SECTION -->
    <section class="relative bg-gradient-to-br from-brand-green-950 to-brand-green-900 py-12 text-white overflow-hidden shadow-xl">
        <div class="max-w-[1440px] mx-auto px-4 sm:px-8 lg:px-12 text-center relative z-10 space-y-3">
            <h1 class="text-3xl sm:text-5xl md:text-6xl font-bold font-display tracking-tight text-white leading-tight drop-shadow">
                Dokumen & Unduhan <br class="hidden sm:inline">
                <span class="bg-gradient-to-r from-brand-gold-500 to-brand-gold-100 bg-clip-text text-transparent">Pusat Berkas Mahasiswa</span>
            </h1>
        </div>
    </section>

    <!-- MAIN CONTENT SECTION -->
    <section class="py-16 bg-slate-50 min-h-[700px]">
        <div class="max-w-[1440px] mx-auto px-4 sm:px-8 lg:px-12 space-y-12">

            <!-- Controls: Search + Kategori Tab -->
            <div class="space-y-5">
                <!-- Search bar -->
                <div class="relative max-w-xl mx-auto">
                    <input type="text" wire:model.live.debounce.300ms="search" placeholder="Cari berkas dokumen (contoh: Panduan, Pengajuan, SK)..."
                        class="w-full bg-white border border-slate-200 focus:border-brand-green-500 text-slate-800 text-sm pl-12 pr-4 py-3.5 rounded-2xl shadow-sm outline-none transition-all duration-300">
                    <div class="absolute left-4 top-4 text-slate-400">
                        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z"></path></svg>
                    </div>
                </div>

                <!-- Tab Kategori -->
                <div class="flex flex-wrap justify-center gap-2">
                    @foreach($kategoris as $kat)
                        <button wire:click="$set('activeKategori', '{{ $kat->slug }}')"
                            class="px-5 py-2.5 rounded-full text-xs font-bold whitespace-nowrap transition duration-200 cursor-pointer
                                {{ $activeKategori === $kat->slug ? 'bg-brand-green-900 text-white shadow-md' : 'bg-white text-slate-600 border border-slate-200 hover:border-brand-green-400 hover:text-brand-green-900' }}">
                            {{ $kat->name }}
                        </button>
                    @endforeach
                </div>
            </div>

            <!-- Content Area -->
            @php
                $currentKat = $allKategoris->firstWhere('slug', $activeKategori);
                $activeLabel = $currentKat ? $currentKat->name : 'Semua Dokumen';
            @endphp
            <div class="grid grid-cols-1 lg:grid-cols-12 gap-8 items-start">

                <!-- Left: Category Detail Card -->
                <div class="lg:col-span-4 bg-gradient-to-br from-brand-green-900 to-brand-green-950 p-8 rounded-3xl text-white shadow-lg border border-brand-gold-500/20 relative overflow-hidden group">
                    <div class="absolute -right-12 -bottom-12 w-32 h-32 bg-brand-gold-500/10 rounded-full group-hover:scale-110 transition-transform duration-500"></div>
                    <span class="px-2.5 py-0.5 text-[10px] font-bold uppercase tracking-wider bg-brand-gold-500/20 text-brand-gold-400 rounded-full border border-brand-gold-500/30 inline-block mb-4">
                        Kategori Dokumen
                    </span>
                    <h3 class="text-2xl font-bold font-display text-white mb-3">{{ $activeLabel }}</h3>
                    <p class="text-slate-300 text-xs font-light leading-relaxed mb-6">
                        Berkas dan dokumen resmi kemahasiswaan Universitas Baiturrahmah terkait {{ $activeLabel }}.
                    </p>
                    <div class="border-t border-brand-green-800/40 pt-4 text-xs font-light text-slate-400 space-y-1">
                        <span>Status: Terbit / Berlaku</span>
                        <span class="block">Jumlah Berkas: {{ $dokumens->count() }} dokumen</span>
                    </div>
                </div>

                <!-- Right: Documents List -->
                <div class="lg:col-span-8 space-y-4 font-display">
                    @forelse($dokumens as $dok)
                        @php
                            $isLink = $dok->tipe_file === 'link';
                            $iconBg = $isLink ? 'bg-blue-50 text-blue-600' : 'bg-red-50 text-red-600';
                            $iconBgHover = $isLink ? 'group-hover:bg-blue-500 group-hover:text-white' : 'group-hover:bg-red-500 group-hover:text-white';
                        @endphp
                        <div class="bg-white p-5 rounded-2xl border border-slate-100 shadow-sm hover:border-brand-green-300 transition-all flex justify-between items-center group">
                            <div class="flex items-center space-x-4">
                                <div class="p-3 {{ $iconBg }} {{ $iconBgHover }} rounded-xl shrink-0 transition-all duration-300">
                                    @if($isLink)
                                        <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13.828 10.172a4 4 0 00-5.656 0l-4 4a4 4 0 105.656 5.656l1.102-1.101m-.758-4.899a4 4 0 005.656 0l4-4a4 4 0 00-5.656-5.656l-1.1 1.1"></path></svg>
                                    @else
                                        <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"></path></svg>
                                    @endif
                                </div>
                                <div>
                                    <h5 class="text-sm font-bold text-slate-800 group-hover:text-brand-green-900 transition-colors">{{ $dok->judul }}</h5>
                                    <p class="text-[11px] text-slate-500 font-light mt-0.5">
                                        Format: {{ strtoupper($dok->tipe_file) }}
                                        @if($dok->ukuran_file) | Ukuran: {{ $dok->ukuran_file }} @endif
                                    </p>
                                </div>
                            </div>
                            @if($dok->url_file)
                                <a href="{{ $dok->url_file }}" target="_blank"
                                   class="px-4 py-2 bg-slate-50 text-brand-green-900 border border-brand-green-900/10 font-bold text-xs rounded-xl hover:bg-brand-green-900 hover:text-white transition-all whitespace-nowrap">
                                    {{ $isLink ? 'Buka Link' : 'Unduh' }}
                                </a>
                            @else
                                <span class="px-4 py-2 bg-slate-100 text-slate-400 font-bold text-xs rounded-xl whitespace-nowrap">Segera Hadir</span>
                            @endif
                        </div>
                    @empty
                        <div class="text-center py-16 bg-white rounded-3xl border border-slate-100 shadow-sm text-slate-400">
                            <div class="text-5xl mb-4">📂</div>
                            <p class="font-bold text-sm">Belum ada dokumen dalam kategori ini.</p>
                            <p class="text-xs mt-1">Dokumen akan ditambahkan segera oleh admin kemahasiswaan.</p>
                        </div>
                    @endforelse
                </div>
            </div>

        </div>
    </section>
</div>
