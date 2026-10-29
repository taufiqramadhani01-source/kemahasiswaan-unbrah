<div>
    <!-- Hero Header -->
    <section class="relative bg-gradient-to-br from-brand-green-950 to-brand-green-900 py-16 text-white overflow-hidden shadow-xl">
        <div class="absolute inset-0 opacity-5 pointer-events-none" style="background-image:radial-gradient(circle at 80% 20%, #c8b282 0%, transparent 60%)"></div>
        <div class="max-w-7xl mx-auto px-4 sm:px-8 lg:px-12 text-center relative z-10 space-y-3">
            <span class="inline-block px-4 py-1.5 rounded-full border border-brand-gold-500/40 bg-brand-gold-500/10 text-brand-gold-400 text-xs font-bold uppercase tracking-widest">
                Layanan Kesejahteraan Mahasiswa
            </span>
            <h1 class="text-3xl sm:text-5xl md:text-6xl font-bold font-display tracking-tight text-white leading-tight drop-shadow">
                Info Beasiswa<br class="hidden sm:inline">
                <span class="bg-gradient-to-r from-brand-gold-500 to-brand-gold-100 bg-clip-text text-transparent">Universitas Baiturrahmah</span>
            </h1>
            <p class="text-base md:text-lg text-slate-300/90 max-w-2xl mx-auto font-light leading-relaxed">
                Temukan berbagai peluang bantuan pendidikan dan beasiswa prestasi dari Pemerintah (KIP-K), LLDIKTI, Internal Kampus Unbrah, maupun Mitra Swasta.
            </p>
        </div>
    </section>

    <!-- Filter & Search Bar Section -->
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 -mt-7 relative z-10">
        <div class="bg-white border border-[#e5e2d9] rounded-2xl p-4 sm:p-6 shadow-xl flex flex-col md:flex-row gap-4 items-center justify-between">
            <div class="w-full md:w-1/2">
                <label class="block text-[10px] font-bold uppercase tracking-wider text-[#8c887d] mb-1">Cari Beasiswa</label>
                <input type="text" wire:model.live.debounce.300ms="search" placeholder="Cari nama beasiswa atau penyelenggara..." 
                    class="w-full px-4 py-2.5 border border-[#dcd9d0] rounded-xl text-xs bg-[#faf9f6] focus:outline-none focus:border-[#c8b282] transition text-[#052c22]">
            </div>
            <div class="w-full md:w-1/3">
                <label class="block text-[10px] font-bold uppercase tracking-wider text-[#8c887d] mb-1">Penyelenggara / Provider</label>
                <select wire:model.live="providerFilter" class="w-full px-4 py-2.5 border border-[#dcd9d0] rounded-xl text-xs bg-[#faf9f6] focus:outline-none focus:border-[#c8b282] transition text-[#052c22]">
                    <option value="">Semua Penyelenggara</option>
                    <option value="Pemerintah">Pemerintah / Kemdikbud</option>
                    <option value="Internal">Internal Unbrah</option>
                    <option value="LLDIKTI">LLDIKTI</option>
                    <option value="Swasta">Yayasan / Swasta</option>
                </select>
            </div>
        </div>
    </div>

    <!-- Grid List Beasiswa -->
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-12">
        <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-6">
            @forelse ($beasiswas as $beasiswa)
                @php
                    $isOpen = (bool) $beasiswa->is_active;
                @endphp
                <div class="bg-white border border-[#e5e2d9] rounded-2xl p-6 shadow-sm hover:shadow-md transition duration-200 flex flex-col justify-between group">
                    <div>
                        <!-- Header Card: Badge & Status -->
                        <div class="flex items-center justify-between mb-4">
                            <span class="px-3 py-1 bg-[#faf9f6] border border-[#e5e2d9] text-[#052c22] rounded-full text-[10px] font-bold uppercase tracking-wider">
                                🏢 {{ $beasiswa->provider ?? 'Umum' }}
                            </span>
                            
                            @if ($isOpen)
                                <span class="px-2.5 py-1 rounded-full bg-emerald-50 border border-emerald-200 text-emerald-700 text-[10px] font-bold uppercase tracking-wider flex items-center gap-1">
                                    <span class="w-1.5 h-1.5 rounded-full bg-emerald-500 animate-pulse"></span> Buka Pendaftaran
                                </span>
                            @else
                                <span class="px-2.5 py-1 rounded-full bg-slate-100 border border-slate-200 text-slate-500 text-[10px] font-bold uppercase tracking-wider">
                                    Pendaftaran Ditutup
                                </span>
                            @endif
                        </div>

                        <!-- Title -->
                        <h2 class="text-base font-extrabold text-[#052c22] group-hover:text-[#c8b282] transition leading-snug mb-2">
                            {{ $beasiswa->name }}
                        </h2>

                        <!-- Deskripsi Singkat -->
                        @if ($beasiswa->description)
                            <p class="text-xs text-[#5c5950] leading-relaxed mb-4 font-light">
                                {{ $beasiswa->description }}
                            </p>
                        @endif

                        <!-- Syarat & Ketentuan Singkat -->
                        @if ($beasiswa->requirements)
                            <div class="text-xs text-[#5c5950] leading-relaxed line-clamp-3 mb-6 font-light bg-[#faf9f6] p-3 rounded-xl border border-[#e5e2d9]">
                                <strong class="text-[#052c22] font-bold block mb-1 text-[11px]">Persyaratan:</strong>
                                {!! nl2br(e($beasiswa->requirements)) !!}
                            </div>
                        @endif
                    </div>

                    <!-- Footer Action -->
                    <div class="pt-4 border-t border-[#e5e2d9]">
                        @if ($beasiswa->registration_url)
                            <a href="{{ $beasiswa->registration_url }}" target="_blank" class="w-full py-2.5 bg-[#052c22] hover:bg-[#073d30] text-[#c8b282] rounded-xl text-xs font-bold uppercase tracking-wider transition flex items-center justify-center gap-1.5 no-underline">
                                <span>Daftar Beasiswa</span> ↗
                            </a>
                        @else
                            <button disabled class="w-full py-2.5 bg-slate-100 text-slate-400 border border-slate-200 rounded-xl text-xs font-bold uppercase tracking-wider cursor-not-allowed">
                                Informasi di Kemahasiswaan
                            </button>
                        @endif
                    </div>
                </div>
            @empty
                <div class="col-span-full bg-white border border-[#e5e2d9] rounded-2xl p-12 text-center text-[#8c887d]">
                    <span class="text-3xl block mb-2">🎓</span>
                    <p class="font-bold text-xs uppercase tracking-wider text-[#052c22]">Belum ada informasi beasiswa</p>
                    <p class="text-xs text-[#5c5950] mt-1">Gunakan kata kunci lain atau periksa kembali di lain waktu.</p>
                </div>
            @endforelse
        </div>

        <!-- Pagination -->
        <div class="mt-8">
            {{ $beasiswas->links() }}
        </div>
    </div>
</div>
