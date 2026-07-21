<div>
    @section('title', 'Prestasi Mahasiswa')

    <!-- HERO BANNER -->
    <section class="relative bg-gradient-to-br from-brand-green-950 to-brand-green-900 py-16 text-white overflow-hidden shadow-xl">
        <div class="max-w-[1440px] mx-auto px-4 sm:px-8 lg:px-12 text-center relative z-10 space-y-4">
            <span class="inline-block bg-brand-gold-500/10 text-brand-gold-400 border border-brand-gold-500/20 text-xs font-bold tracking-widest uppercase px-4 py-1.5 rounded-full">Hall of Fame</span>
            <h1 class="text-3xl sm:text-5xl md:text-6xl font-bold font-display tracking-tight text-white leading-tight drop-shadow">
                Prestasi Mahasiswa<br class="hidden sm:inline">
                <span class="bg-gradient-to-r from-brand-gold-500 to-brand-gold-100 bg-clip-text text-transparent">Universitas Baiturrahmah</span>
            </h1>
            <p class="text-base md:text-lg text-slate-300/90 max-w-2xl mx-auto font-light leading-relaxed">
                Apresiasi setinggi-tingginya kepada mahasiswa dan mahasiswi Universitas Baiturrahmah yang telah mengharumkan nama almamater di kancah Regional, Nasional, maupun Internasional.
            </p>
        </div>
    </section>

    <!-- CONTENT & FILTER SECTION -->
    <section class="py-16 bg-slate-50 min-h-[600px]">
        <div class="max-w-[1440px] mx-auto px-4 sm:px-8 lg:px-12 space-y-10">
            
            <!-- FILTER PANEL (Modern Sleek Panel) -->
            <div class="bg-white rounded-3xl p-6 border border-slate-100 shadow-sm grid grid-cols-1 md:grid-cols-4 gap-4 items-end">
                <!-- Search -->
                <div class="md:col-span-2 space-y-2">
                    <label class="block text-[10px] font-bold uppercase tracking-wider text-slate-400">Pencarian Nama / Prestasi</label>
                    <div class="relative">
                        <span class="absolute inset-y-0 left-0 flex items-center pl-3 text-slate-400 text-sm">🔍</span>
                        <input type="text" wire:model.live.debounce.300ms="search" placeholder="Cari nama mahasiswa atau nama lomba..."
                            class="w-full pl-9 pr-4 py-2.5 border border-slate-200 rounded-xl text-xs bg-slate-50/50 focus:outline-none focus:border-brand-green-500 focus:bg-white transition-all text-slate-700">
                    </div>
                </div>

                <!-- Filter Tingkat -->
                <div class="space-y-2">
                    <label class="block text-[10px] font-bold uppercase tracking-wider text-slate-400">Tingkat Prestasi</label>
                    <select wire:model.live="filterLevel" 
                        class="w-full px-4 py-2.5 border border-slate-200 rounded-xl text-xs bg-slate-50/50 focus:outline-none focus:border-brand-green-500 focus:bg-white transition-all text-slate-700 font-medium">
                        <option value="">Semua Tingkat</option>
                        @foreach($levels as $lvl)
                            <option value="{{ $lvl }}">{{ $lvl }}</option>
                        @endforeach
                    </select>
                </div>

                <!-- Filter Tahun -->
                <div class="space-y-2">
                    <label class="block text-[10px] font-bold uppercase tracking-wider text-slate-400">Tahun Perolehan</label>
                    <select wire:model.live="filterYear" 
                        class="w-full px-4 py-2.5 border border-slate-200 rounded-xl text-xs bg-slate-50/50 focus:outline-none focus:border-brand-green-500 focus:bg-white transition-all text-slate-700 font-medium">
                        <option value="">Semua Tahun</option>
                        @foreach($years as $yr)
                            <option value="{{ $yr }}">{{ $yr }}</option>
                        @endforeach
                    </select>
                </div>
            </div>

            <!-- PRESTASI GRID -->
            @if($prestasis->count() > 0)
                <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-3 xl:grid-cols-4 gap-6">
                    @foreach($prestasis as $prestasi)
                        <div class="bg-white rounded-3xl border border-slate-100 shadow-sm hover:shadow-lg transition-all duration-300 overflow-hidden flex flex-col justify-between group">
                            <div class="p-6 space-y-4">
                                <!-- Badge Tingkat -->
                                <div class="flex items-center justify-between">
                                    <span class="inline-block bg-brand-green-50 text-brand-green-900 border border-brand-green-100 text-[9px] font-bold uppercase tracking-wider px-2.5 py-1 rounded-full">
                                        🏆 {{ $prestasi->level }}
                                    </span>
                                    <span class="text-xs font-bold text-slate-400">
                                        📅 {{ $prestasi->year }}
                                    </span>
                                </div>

                                <!-- Nama Prestasi -->
                                <div class="space-y-1">
                                    <h3 class="font-bold text-sm text-slate-900 group-hover:text-brand-green-700 transition-colors leading-snug line-clamp-2">
                                        {{ $prestasi->title }}
                                    </h3>
                                    <p class="text-[11px] text-brand-gold-600 font-bold uppercase tracking-wider">
                                        👤 {{ $prestasi->student_name }}
                                    </p>
                                </div>
                            </div>

                            <!-- Footer Card: Tombol Piagam/Dokumen -->
                            <div class="px-6 py-4 bg-slate-50/80 border-t border-slate-100 flex items-center justify-between">
                                <span class="text-[10px] text-slate-400 font-medium">Universitas Baiturrahmah</span>
                                @if($prestasi->certificate_path)
                                    <a href="{{ $prestasi->certificate_path }}" target="_blank"
                                       class="text-[10px] font-bold text-brand-green-900 hover:text-brand-green-950 flex items-center gap-1 transition no-underline">
                                        Lihat Sertifikat 📁
                                    </a>
                                @else
                                    <span class="text-[10px] text-slate-400 italic">Piagam Arsip</span>
                                @endif
                            </div>
                        </div>
                    @endforeach
                </div>

                <!-- Pagination -->
                <div class="bg-white rounded-2xl p-4 border border-slate-100 shadow-sm">
                    {{ $prestasis->links() }}
                </div>
            @else
                <!-- Empty State -->
                <div class="bg-white rounded-3xl p-16 text-center border border-slate-100 shadow-sm max-w-xl mx-auto space-y-4">
                    <div class="text-4xl">🏆</div>
                    <h3 class="font-bold text-sm text-slate-900">Prestasi Tidak Ditemukan</h3>
                    <p class="text-xs text-slate-500 leading-relaxed font-light">Tidak ada data prestasi mahasiswa yang sesuai dengan kata kunci pencarian atau filter yang Anda pilih saat ini.</p>
                    <button wire:click="$set('search', ''); $set('filterLevel', ''); $set('filterYear', '');"
                        class="px-4 py-2 bg-brand-green-900 hover:bg-brand-green-950 text-white font-bold text-xs rounded-xl transition">
                        Reset Filter
                    </button>
                </div>
            @endif

        </div>
    </section>
</div>
