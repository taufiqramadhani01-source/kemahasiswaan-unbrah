<div>
    <x-slot name="title">Pengaturan Situs - Dasbor Kemahasiswaan</x-slot>

    {{-- Flash Message --}}
    @if (session()->has('message'))
        <div class="p-4 mb-6 bg-emerald-50 border border-emerald-100 text-emerald-800 text-xs font-bold rounded-xl flex items-center justify-between">
            <span>✅ {{ session('message') }}</span>
            <button onclick="this.parentElement.remove()">✕</button>
        </div>
    @endif

    {{-- Page Header --}}
    <div class="flex flex-col md:flex-row md:items-center justify-between gap-4 mb-8">
        <div>
            <span class="px-3 py-1 rounded-full border border-[#c8b282]/50 bg-[#faf9f6] text-[#c8b282] text-[10px] font-bold uppercase tracking-widest inline-block mb-3">Pengaturan</span>
            <h1 class="text-2xl font-extrabold text-[#052c22] tracking-tight">Pengaturan Website</h1>
            <p class="text-xs text-[#5c5950] mt-1">Ubah warna dasar dan identitas situs utama secara terpadu.</p>
        </div>
        <div class="flex gap-3">
            <button wire:click="resetDefaults" class="px-4 py-2 border border-[#e5e2d9] hover:bg-[#faf9f6] text-[#5c5950] rounded-xl text-xs font-bold transition">
                ↺ Reset Default
            </button>
            <button wire:click="save" class="px-4 py-2 bg-[#052c22] hover:bg-[#073d30] text-[#c8b282] rounded-xl text-xs font-bold transition">
                💾 Simpan Perubahan
            </button>
        </div>
    </div>

    {{-- CONTENT --}}
    <div class="grid grid-cols-1 lg:grid-cols-3 gap-6">

        {{-- ===== KIRI: Color Pickers & Identity ===== --}}
        <div class="lg:col-span-2 space-y-6">

            {{-- === PANEL: Palet Warna === --}}
            <div class="bg-white border border-[#e5e2d9] rounded-2xl shadow-sm overflow-hidden">
                <div class="p-5 border-b border-[#e5e2d9] bg-[#faf9f6]">
                    <h2 class="text-xs font-bold uppercase tracking-wider text-[#052c22]">🎨 Palet Warna Situs</h2>
                    <p class="text-[10px] text-[#8c887d] mt-0.5">Warna-warna ini akan diterapkan ke seluruh halaman frontend dan backoffice secara dinamis.</p>
                </div>

                <div class="p-6 space-y-5">
                    @php
                        $colorFields = [
                            ['field' => 'color_primary', 'label' => 'Warna Utama (Primary)', 'desc' => 'Digunakan untuk tombol, header sidebar, aksen utama.', 'preview_class' => 'bg-[#052c22]'],
                            ['field' => 'color_accent',  'label' => 'Warna Aksen / Highlight', 'desc' => 'Digunakan untuk highlight teks, badge, border aktif, CTA.', 'preview_class' => 'bg-[#c8b282]'],
                            ['field' => 'color_bg',      'label' => 'Background Halaman', 'desc' => 'Warna dasar latar halaman utama.', 'preview_class' => 'bg-[#fcfbf9]'],
                            ['field' => 'color_text',    'label' => 'Warna Teks Utama', 'desc' => 'Warna teks paragraf dan konten umum.', 'preview_class' => 'bg-[#1a2421]'],
                            ['field' => 'color_sidebar', 'label' => 'Warna Sidebar', 'desc' => 'Background panel navigasi sidebar.', 'preview_class' => 'bg-white border border-[#e5e2d9]'],
                        ];
                    @endphp

                    @foreach ($colorFields as $cf)
                        <div class="flex items-center gap-4">
                            <div class="w-10 h-10 rounded-xl border border-[#e5e2d9] shadow-sm shrink-0"
                                style="background-color: {{ $this->{$cf['field']} }}"></div>

                            <div class="flex-1 min-w-0">
                                <label class="block text-xs font-bold text-[#1a2421]">{{ $cf['label'] }}</label>
                                <p class="text-[10px] text-[#8c887d] mt-0.5">{{ $cf['desc'] }}</p>
                            </div>

                            <div class="flex items-center gap-2 shrink-0">
                                <input type="color"
                                    wire:model.live="{{ $cf['field'] }}"
                                    class="w-10 h-10 rounded-lg border border-[#dcd9d0] cursor-pointer p-0.5 bg-white">
                                <input type="text"
                                    wire:model.live="{{ $cf['field'] }}"
                                    maxlength="7"
                                    placeholder="#000000"
                                    class="w-24 px-3 py-2 border border-[#dcd9d0] rounded-lg text-xs font-mono focus:outline-none focus:border-[#c8b282] transition bg-[#faf9f6]">
                            </div>
                        </div>
                        @if (!$loop->last)
                            <div class="h-px bg-[#f0ede6]"></div>
                        @endif
                    @endforeach

                    {{-- Toggle Gradasi --}}
                    <div class="h-px bg-[#f0ede6] my-4"></div>
                    <div class="flex items-center justify-between gap-4">
                        <div class="flex-1 min-w-0">
                            <label class="block text-xs font-bold text-[#1a2421]">Gunakan Efek Gradasi</label>
                            <p class="text-[10px] text-[#8c887d] mt-0.5">Jika aktif, tombol-tombol utama dan banner header akan menggunakan gradasi warna. Jika dinonaktifkan, akan menggunakan warna solid polos.</p>
                        </div>
                        <div class="shrink-0 flex items-center gap-2">
                            <button type="button" wire:click="$toggle('theme_use_gradient')"
                                class="relative w-10 h-5 rounded-full transition-colors duration-200 {{ $theme_use_gradient ? 'bg-[#052c22]' : 'bg-[#e5e2d9]' }}">
                                <span class="absolute top-0.5 left-0.5 w-4 h-4 bg-white rounded-full shadow transition-transform duration-200 {{ $theme_use_gradient ? 'translate-x-5' : 'translate-x-0' }}"></span>
                            </button>
                            <span class="text-[10px] font-bold text-[#5c5950] w-8">{{ $theme_use_gradient ? 'Aktif' : 'Mati' }}</span>
                        </div>
                    </div>
                </div>
            </div>

            {{-- === PANEL: Identitas Situs === --}}
            <div class="bg-white border border-[#e5e2d9] rounded-2xl shadow-sm overflow-hidden">
                <div class="p-5 border-b border-[#e5e2d9] bg-[#faf9f6]">
                    <h2 class="text-xs font-bold uppercase tracking-wider text-[#052c22]">🏛️ Identitas Situs</h2>
                    <p class="text-[10px] text-[#8c887d] mt-0.5">Nama, tagline, dan informasi dasar situs yang tampil di berbagai bagian halaman.</p>
                </div>
                <div class="p-6 space-y-4">
                    <div>
                        <label class="block text-[10px] font-bold uppercase tracking-wider text-[#8c887d] mb-1.5">Nama Situs</label>
                        <input type="text" wire:model="site_name" placeholder="Kemahasiswaan Unbrah"
                            class="w-full px-3 py-2 border border-[#dcd9d0] rounded-xl text-xs focus:outline-none focus:border-[#c8b282] transition">
                    </div>
                    <div>
                        <label class="block text-[10px] font-bold uppercase tracking-wider text-[#8c887d] mb-1.5">Tagline / Sub-judul</label>
                        <input type="text" wire:model="site_tagline" placeholder="Universitas Baiturrahmah"
                            class="w-full px-3 py-2 border border-[#dcd9d0] rounded-xl text-xs focus:outline-none focus:border-[#c8b282] transition">
                    </div>
                    <div>
                        <label class="block text-[10px] font-bold uppercase tracking-wider text-[#8c887d] mb-1.5">Teks Footer</label>
                        <input type="text" wire:model="site_footer" placeholder="© 2025 Universitas Baiturrahmah..."
                            class="w-full px-3 py-2 border border-[#dcd9d0] rounded-xl text-xs focus:outline-none focus:border-[#c8b282] transition">
                    </div>
                </div>
            </div>
        </div>

        {{-- ===== KANAN: Live Preview ===== --}}
        <div class="space-y-4">
            <div class="bg-white border border-[#e5e2d9] rounded-2xl shadow-sm overflow-hidden sticky top-6">
                <div class="p-4 border-b border-[#e5e2d9] bg-[#faf9f6]">
                    <h2 class="text-xs font-bold uppercase tracking-wider text-[#052c22]">👁️ Live Preview</h2>
                    <p class="text-[10px] text-[#8c887d] mt-0.5">Pratinjau tampilan dengan warna yang dipilih.</p>
                </div>

                <div class="p-4 overflow-hidden">
                    <div class="rounded-xl overflow-hidden border border-[#e5e2d9] text-[10px]">
                        <div class="flex" style="min-height: 200px;">
                            <div class="w-16 p-2 flex flex-col gap-1.5 shrink-0" style="background-color: {{ $color_sidebar }}; border-right: 1px solid #e5e2d9;">
                                <div class="w-6 h-6 rounded-lg mx-auto mb-1" style="background-color: {{ $color_primary }}"></div>
                                @foreach(range(1,5) as $n)
                                    <div class="h-2 rounded-full {{ $n === 1 ? 'w-full' : 'w-3/4' }}"
                                        style="background-color: {{ $n === 1 ? $color_primary : '#e5e2d9' }}; opacity: {{ $n === 1 ? 1 : 0.6 }}"></div>
                                @endforeach
                            </div>
                            <div class="flex-1 p-3 space-y-2" style="background-color: {{ $color_bg }};">
                                <div class="h-5 rounded-lg flex items-center px-2 gap-1.5" style="background-color: white; border: 1px solid #e5e2d9;">
                                    <div class="w-1.5 h-1.5 rounded-full" style="background-color: {{ $color_primary }}"></div>
                                    <div class="h-1.5 w-12 rounded-full" style="background-color: {{ $color_primary }}; opacity:0.3"></div>
                                </div>
                                <div class="flex-1 space-y-2">
                                    <div class="h-12 rounded-lg p-2 space-y-1" style="background-color: white; border: 1px solid #e5e2d9;">
                                        <div class="h-2 w-8 rounded-full" style="background-color: {{ $color_accent }}"></div>
                                        <div class="h-1.5 w-full rounded-full opacity-30" style="background-color: {{ $color_text }}"></div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
