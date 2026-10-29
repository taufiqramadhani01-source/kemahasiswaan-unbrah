<div class="max-w-[1440px] mx-auto py-8 px-4 space-y-6">

    {{-- Header --}}
    <div class="flex items-center justify-between">
        <div>
            <h1 class="text-xl font-bold text-[#1a2421] font-display tracking-tight">Manajemen Beranda</h1>
            <p class="text-xs text-[#8c887d] mt-0.5">Kelola seluruh konten halaman utama, top bar, dan footer website.</p>
        </div>
        <button wire:click="save" wire:loading.attr="disabled"
            class="flex items-center gap-2 bg-[#052c22] hover:bg-[#0a3d30] text-white text-xs font-semibold px-5 py-2.5 rounded-xl shadow-md transition cursor-pointer">
            <svg wire:loading wire:target="save" class="animate-spin w-3.5 h-3.5" fill="none" viewBox="0 0 24 24"><circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"/><path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8v8z"/></svg>
            <span>Simpan Semua</span>
        </button>
    </div>

    @if (session()->has('message'))
        <div class="flex items-center gap-3 bg-emerald-50 border border-emerald-200 text-emerald-800 text-xs font-medium px-4 py-3 rounded-xl">
            <svg class="w-4 h-4 text-emerald-500 shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"/></svg>
            {{ session('message') }}
        </div>
    @endif

    @if ($errors->any())
        <div class="bg-red-50 border border-red-200 text-red-700 text-xs px-4 py-3 rounded-xl space-y-1">
            <p class="font-bold">⚠️ Terdapat kesalahan input:</p>
            <ul class="list-disc list-inside text-[11px]">
                @foreach ($errors->all() as $err)
                    <li>{{ $err }}</li>
                @endforeach
            </ul>
        </div>
    @endif

    {{-- Tab Navigation --}}
    <div class="flex gap-1 border-b border-[#e5e2d9] overflow-x-auto">
        @foreach([
            ['key'=>'slides',   'icon'=>'🖼️',  'label'=>'Banner Slider'],
            ['key'=>'sambutan', 'icon'=>'👤',  'label'=>'Sambutan WR III'],
            ['key'=>'layanan',  'icon'=>'🃏',  'label'=>'Kartu Layanan'],
            ['key'=>'about',    'icon'=>'📖',  'label'=>'Profil & Video'],
            ['key'=>'topbar',   'icon'=>'📋',  'label'=>'Top Bar'],
            ['key'=>'footer',   'icon'=>'📄',  'label'=>'Footer'],
        ] as $tab)
            <button wire:click="switchSection('{{ $tab['key'] }}')"
                class="pb-3 px-4 text-xs font-bold flex items-center gap-1.5 border-b-2 transition-all whitespace-nowrap cursor-pointer
                    {{ $activeSection === $tab['key']
                        ? 'border-[#052c22] text-[#052c22]'
                        : 'border-transparent text-[#8c887d] hover:text-[#1a2421]' }}">
                {{ $tab['icon'] }} {{ $tab['label'] }}
            </button>
        @endforeach
    </div>

    <div class="grid grid-cols-1 lg:grid-cols-12 gap-8 items-start">
        
        {{-- ===== KIRI: Form Inputs ===== --}}
        <div class="lg:col-span-8 space-y-6">

            {{-- TAB: BANNER / SLIDES --}}
            @if ($activeSection === 'slides')
                <div class="space-y-6">
                    @foreach ($slides as $i => $slide)
                        <div class="bg-white border border-[#e5e2d9] rounded-2xl overflow-hidden shadow-sm">
                            <div class="flex items-center gap-3 px-5 py-3 bg-[#faf9f6] border-b border-[#e5e2d9]">
                                <span class="w-5 h-5 rounded-full bg-[#052c22] text-white text-[10px] font-bold flex items-center justify-center">{{ $i + 1 }}</span>
                                <span class="text-xs font-bold text-[#1a2421]">Slide Banner #{{ $i + 1 }}</span>
                            </div>
                            <div class="p-5 grid grid-cols-1 md:grid-cols-2 gap-4">
                                <div class="md:col-span-2">
                                    <label class="block text-[10px] font-bold text-[#5c5950] uppercase tracking-wider mb-2">Foto Banner</label>
                                    <div class="flex items-center gap-4">
                                        @php
                                            $slidePreviewUrl = !empty($slide_photo_files[$i]) && method_exists($slide_photo_files[$i], 'temporaryUrl')
                                                ? $slide_photo_files[$i]->temporaryUrl()
                                                : ($slide['image'] ?? '');
                                        @endphp
                                        <img src="{{ $slidePreviewUrl }}" class="w-36 h-20 object-cover rounded-xl border border-[#e5e2d9]">
                                        <div>
                                            <input type="file" wire:model="slide_photo_files.{{ $i }}" accept="image/*" class="text-[11px] text-[#5c5950]">
                                            <p class="text-[10px] text-[#b0aa9f] mt-1">Disarankan resolusi landscape tinggi (contoh: 1920x800 px).</p>
                                            @error("slide_photo_files.{$i}") <p class="text-red-500 text-[10px] mt-1">{{ $message }}</p> @enderror
                                        </div>
                                    </div>
                                </div>
                                @foreach([
                                    ['badge','Badge Kategori (Kecil di Atas)'],
                                    ['title_line1','Judul Baris 1'],
                                    ['title_line2','Judul Baris 2 (Warna Emas)'],
                                    ['button1_text','Teks Tombol 1'],
                                    ['button1_link','Link Tombol 1'],
                                    ['button2_text','Teks Tombol 2'],
                                    ['button2_link','Link Tombol 2']
                                ] as [$f,$l])
                                <div>
                                    <label class="block text-[10px] font-bold text-[#5c5950] uppercase tracking-wider mb-1">{{ $l }}</label>
                                    <input type="text" wire:model.live="slides.{{ $i }}.{{ $f }}"
                                        class="w-full text-xs border border-[#e5e2d9] rounded-lg px-3 py-2 focus:outline-none focus:border-[#052c22] bg-[#faf9f6]">
                                </div>
                                @endforeach
                                <div class="md:col-span-2">
                                    <label class="block text-[10px] font-bold text-[#5c5950] uppercase tracking-wider mb-1">Deskripsi Ringkas</label>
                                    <textarea wire:model.live="slides.{{ $i }}.description" rows="2"
                                        class="w-full text-xs border border-[#e5e2d9] rounded-lg px-3 py-2 focus:outline-none focus:border-[#052c22] bg-[#faf9f6] resize-none"></textarea>
                                </div>
                            </div>
                        </div>
                    @endforeach
                </div>

            {{-- TAB: SAMBUTAN WR III --}}
            @elseif ($activeSection === 'sambutan')
                <div class="bg-white border border-[#e5e2d9] rounded-2xl shadow-sm p-6 space-y-5">
                    <div class="flex items-start gap-5 pb-5 border-b border-[#f0ede7]">
                        <div class="shrink-0 text-center">
                            @php
                                $wr3PreviewUrl = !empty($wr3_photo_file) && method_exists($wr3_photo_file, 'temporaryUrl')
                                    ? $wr3_photo_file->temporaryUrl()
                                    : $wr3_photo_path;
                            @endphp
                            <img src="{{ $wr3PreviewUrl }}" class="w-24 h-28 object-cover rounded-xl border border-[#e5e2d9] mx-auto">
                            <div class="mt-3">
                                <input type="file" wire:model="wr3_photo_file" accept="image/*" class="text-[10px] text-[#5c5950] max-w-[130px]">
                                @error('wr3_photo_file') <p class="text-red-500 text-[10px] mt-1">{{ $message }}</p> @enderror
                            </div>
                        </div>
                        <div class="flex-1 space-y-4">
                            <div>
                                <label class="block text-[10px] font-bold text-[#5c5950] uppercase tracking-wider mb-1">Nama Wakil Rektor III & Gelar</label>
                                <input type="text" wire:model.live="wr3_name"
                                    class="w-full text-xs border border-[#e5e2d9] rounded-lg px-3 py-2 focus:outline-none focus:border-[#052c22] bg-[#faf9f6]">
                                @error('wr3_name') <p class="text-red-500 text-[10px] mt-1">{{ $message }}</p> @enderror
                            </div>
                            <div>
                                <label class="block text-[10px] font-bold text-[#5c5950] uppercase tracking-wider mb-1">Motto Utama / Quote Singkat</label>
                                <input type="text" wire:model.live="wr3_quote"
                                    class="w-full text-xs border border-[#e5e2d9] rounded-lg px-3 py-2 focus:outline-none focus:border-[#052c22] bg-[#faf9f6]">
                            </div>
                        </div>
                    </div>
                    <div>
                        <label class="block text-[10px] font-bold text-[#5c5950] uppercase tracking-wider mb-1">Sambutan Paragraf 1</label>
                        <textarea wire:model.live="wr3_para1" rows="4"
                            class="w-full text-xs border border-[#e5e2d9] rounded-lg px-3 py-2 focus:outline-none focus:border-[#052c22] bg-[#faf9f6] resize-none"></textarea>
                    </div>
                    <div>
                        <label class="block text-[10px] font-bold text-[#5c5950] uppercase tracking-wider mb-1">Sambutan Paragraf 2</label>
                        <textarea wire:model.live="wr3_para2" rows="4"
                            class="w-full text-xs border border-[#e5e2d9] rounded-lg px-3 py-2 focus:outline-none focus:border-[#052c22] bg-[#faf9f6] resize-none"></textarea>
                    </div>
                </div>

            {{-- TAB: KARTU LAYANAN --}}
            @elseif ($activeSection === 'layanan')
                <div class="space-y-4">
                    <p class="text-[11px] text-[#8c887d] bg-[#faf9f6] border border-[#e5e2d9] rounded-xl px-4 py-3">
                        💡 <b>6 Kartu Layanan Cepat</b> tampil tepat di bawah banner beranda. Pilih layanan dari daftar yang tersedia — judul dan link akan terisi otomatis.
                    </p>

                    @if(count($availableLayanans) === 0)
                        <div class="bg-amber-50 border border-amber-200 rounded-xl px-4 py-3 text-xs text-amber-700">
                            ⚠️ Belum ada data layanan. Silakan tambah layanan terlebih dahulu di menu <a href="/admin/layanans" class="underline font-semibold">Kelola Layanan</a>.
                        </div>
                    @endif

                    <div class="bg-white border border-[#e5e2d9] rounded-2xl shadow-sm overflow-hidden">
                        {{-- Header Kolom --}}
                        <div class="grid grid-cols-12 gap-3 px-4 py-2 bg-[#f5f3ef] border-b border-[#e5e2d9] text-[10px] font-bold uppercase tracking-wider text-[#8c887d]">
                            <div class="col-span-1 text-center">#</div>
                            <div class="col-span-4">Pilih Layanan</div>
                            <div class="col-span-3">Label Kecil (Atas)</div>
                            <div class="col-span-4">Preview Judul & URL</div>
                        </div>

                        {{-- Baris per Kartu --}}
                        @foreach ($layanan_cards as $i => $card)
                            <div class="grid grid-cols-12 gap-3 px-4 py-3 items-center {{ $i % 2 === 0 ? 'bg-white' : 'bg-[#faf9f6]' }} border-b border-[#f0ede7] last:border-0">
                                {{-- Nomor --}}
                                <div class="col-span-1 flex justify-center">
                                    <span class="w-6 h-6 rounded-full bg-[#052c22] text-white text-[10px] font-bold flex items-center justify-center">{{ $i + 1 }}</span>
                                </div>

                                {{-- Dropdown Layanan --}}
                                <div class="col-span-4">
                                    <select wire:change="selectLayananCard({{ $i }}, $event.target.value)"
                                        class="w-full text-xs border border-[#e5e2d9] rounded-lg px-3 py-2 focus:outline-none focus:border-[#052c22] bg-white">
                                        <option value="">— Tidak dipilih —</option>
                                        @foreach ($availableLayanans as $layanan)
                                            <option value="{{ $layanan['id'] }}" {{ ($card['layanan_id'] ?? null) == $layanan['id'] ? 'selected' : '' }}>
                                                {{ $layanan['icon'] ?? '' }} {{ $layanan['title'] }}
                                            </option>
                                        @endforeach
                                    </select>
                                </div>

                                {{-- Label Kecil --}}
                                <div class="col-span-3">
                                    <input type="text"
                                        wire:model.live="layanan_cards.{{ $i }}.label"
                                        class="w-full text-xs border border-[#e5e2d9] rounded-lg px-3 py-2 focus:outline-none focus:border-[#052c22] bg-white"
                                        placeholder="Layanan 0{{ $i + 1 }}">
                                </div>

                                {{-- Preview --}}
                                <div class="col-span-4">
                                    @if (!empty($card['title']))
                                        <div class="text-[11px] font-semibold text-[#052c22] leading-tight truncate">{{ $card['title'] }}</div>
                                        <div class="text-[10px] text-[#8c887d] font-mono truncate mt-0.5">{{ $card['url'] ?? '#' }}</div>
                                    @else
                                        <span class="text-[10px] italic text-[#c8c4bc]">Belum dipilih</span>
                                    @endif
                                </div>
                            </div>
                        @endforeach
                    </div>

                    <p class="text-[10px] text-[#b0ac9f] px-1">
                        💾 Klik <b>Simpan Semua Pengaturan</b> di bawah setelah memilih layanan.
                    </p>
                </div>


            {{-- TAB: PROFIL & VIDEO --}}
            @elseif ($activeSection === 'about')
                <div class="space-y-5">
                    <div class="bg-white border border-[#e5e2d9] rounded-2xl shadow-sm p-5 space-y-4">
                        <h3 class="text-[10px] font-bold text-[#5c5950] uppercase tracking-wider pb-2 border-b border-[#f0ede7]">📖 Deskripsi Profil</h3>
                        <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                            <div>
                                <label class="block text-[10px] font-semibold text-[#8c887d] mb-1">Sub-label Atas</label>
                                <input type="text" wire:model.live="about_label"
                                    class="w-full text-xs border border-[#e5e2d9] rounded-lg px-3 py-2 focus:outline-none focus:border-[#052c22] bg-[#faf9f6]">
                            </div>
                            <div>
                                <label class="block text-[10px] font-semibold text-[#8c887d] mb-1">Judul Utama</label>
                                <input type="text" wire:model.live="about_title"
                                    class="w-full text-xs border border-[#e5e2d9] rounded-lg px-3 py-2 focus:outline-none focus:border-[#052c22] bg-[#faf9f6]">
                            </div>
                        </div>
                        <div>
                            <label class="block text-[10px] font-semibold text-[#8c887d] mb-1">Paragraf Deskripsi Profil</label>
                            <textarea wire:model.live="about_description" rows="4"
                                class="w-full text-xs border border-[#e5e2d9] rounded-lg px-3 py-2 focus:outline-none focus:border-[#052c22] bg-[#faf9f6] resize-none"></textarea>
                        </div>
                        <div class="grid grid-cols-1 md:grid-cols-2 gap-4 pt-2">
                            <div class="space-y-2 p-3 bg-[#faf9f6] border border-[#e5e2d9] rounded-xl">
                                <p class="text-[10px] font-bold text-[#1a2421]">Poin Keunggulan 1</p>
                                <input type="text" wire:model.live="about_point1_title"
                                    class="w-full text-xs border border-[#e5e2d9] rounded-lg px-3 py-2 focus:outline-none focus:border-[#052c22] bg-white">
                                <input type="text" wire:model.live="about_point1_desc"
                                    class="w-full text-xs border border-[#e5e2d9] rounded-lg px-3 py-2 focus:outline-none focus:border-[#052c22] bg-white" placeholder="Keterangan singkat">
                            </div>
                            <div class="space-y-2 p-3 bg-[#faf9f6] border border-[#e5e2d9] rounded-xl">
                                <p class="text-[10px] font-bold text-[#1a2421]">Poin Keunggulan 2</p>
                                <input type="text" wire:model.live="about_point2_title"
                                    class="w-full text-xs border border-[#e5e2d9] rounded-lg px-3 py-2 focus:outline-none focus:border-[#052c22] bg-white">
                                <input type="text" wire:model.live="about_point2_desc"
                                    class="w-full text-xs border border-[#e5e2d9] rounded-lg px-3 py-2 focus:outline-none focus:border-[#052c22] bg-white" placeholder="Keterangan singkat">
                            </div>
                        </div>
                    </div>

                    <div class="bg-white border border-[#e5e2d9] rounded-2xl shadow-sm p-5 space-y-4">
                        <h3 class="text-[10px] font-bold text-[#5c5950] uppercase tracking-wider pb-2 border-b border-[#f0ede7]">▶️ Profil Video</h3>
                        <div>
                            <label class="block text-[10px] font-semibold text-[#8c887d] mb-1">URL YouTube Video</label>
                            <input type="text" wire:model.live="video_youtube_url"
                                class="w-full text-xs border border-[#e5e2d9] rounded-lg px-3 py-2 focus:outline-none focus:border-[#052c22] bg-[#faf9f6]">
                        </div>
                        <div>
                            <label class="block text-[10px] font-semibold text-[#8c887d] mb-1">Keterangan Singkat Video</label>
                            <input type="text" wire:model.live="video_title"
                                class="w-full text-xs border border-[#e5e2d9] rounded-lg px-3 py-2 focus:outline-none focus:border-[#052c22] bg-[#faf9f6]">
                        </div>
                    </div>
                </div>

            {{-- TAB: TOP BAR --}}
            @elseif ($activeSection === 'topbar')
                <div class="bg-white border border-[#e5e2d9] rounded-2xl shadow-sm p-6 space-y-5">
                    <h3 class="text-[10px] font-bold text-[#5c5950] uppercase tracking-wider pb-2 border-b border-[#f0ede7]">📞 Kontak Header</h3>
                    <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                        <div>
                            <label class="block text-[10px] font-semibold text-[#8c887d] mb-1">Telepon Kantor</label>
                            <input type="text" wire:model.live="contact_phone"
                                class="w-full text-xs border border-[#e5e2d9] rounded-lg px-3 py-2 focus:outline-none focus:border-[#052c22] bg-[#faf9f6]">
                        </div>
                        <div>
                            <label class="block text-[10px] font-semibold text-[#8c887d] mb-1">Email Kantor</label>
                            <input type="email" wire:model.live="contact_email"
                                class="w-full text-xs border border-[#e5e2d9] rounded-lg px-3 py-2 focus:outline-none focus:border-[#052c22] bg-[#faf9f6]">
                        </div>
                        <div>
                            <label class="block text-[10px] font-semibold text-[#8c887d] mb-1">Jam Pelayanan</label>
                            <input type="text" wire:model.live="contact_hours"
                                class="w-full text-xs border border-[#e5e2d9] rounded-lg px-3 py-2 focus:outline-none focus:border-[#052c22] bg-[#faf9f6]">
                        </div>
                        <div>
                            <label class="block text-[10px] font-semibold text-[#8c887d] mb-1">WhatsApp Hubungi Kami (Floating & CTA)</label>
                            <input type="text" wire:model.live="whatsapp_number"
                                class="w-full text-xs border border-[#e5e2d9] rounded-lg px-3 py-2 focus:outline-none focus:border-[#052c22] bg-[#faf9f6]"
                                placeholder="628116313712">
                        </div>
                    </div>

                    <h3 class="text-[10px] font-bold text-[#5c5950] uppercase tracking-wider pt-4 pb-2 border-b border-[#f0ede7]">🔗 Menu Link Cepat (Pojok Kanan Atas)</h3>
                    <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                        <div class="p-3 bg-[#faf9f6] border border-[#e5e2d9] rounded-xl space-y-2">
                            <p class="text-[9px] font-bold uppercase text-[#052c22]">Link Pertama</p>
                            <input type="text" wire:model.live="topbar_link1_label" class="w-full text-xs border border-[#e5e2d9] rounded-lg px-3 py-1.5 bg-white">
                            <input type="text" wire:model.live="topbar_link1_url" class="w-full text-xs border border-[#e5e2d9] rounded-lg px-3 py-1.5 bg-white" placeholder="/url">
                        </div>
                        <div class="p-3 bg-[#faf9f6] border border-[#e5e2d9] rounded-xl space-y-2">
                            <p class="text-[9px] font-bold uppercase text-[#052c22]">Link Kedua</p>
                            <input type="text" wire:model.live="topbar_link2_label" class="w-full text-xs border border-[#e5e2d9] rounded-lg px-3 py-1.5 bg-white">
                            <input type="text" wire:model.live="topbar_link2_url" class="w-full text-xs border border-[#e5e2d9] rounded-lg px-3 py-1.5 bg-white" placeholder="/url">
                        </div>
                    </div>
                </div>

            {{-- TAB: FOOTER --}}
            @elseif ($activeSection === 'footer')
                <div class="space-y-5">
                    <div class="bg-white border border-[#e5e2d9] rounded-2xl shadow-sm p-6 space-y-4">
                        <h3 class="text-[10px] font-bold text-[#5c5950] uppercase tracking-wider pb-2 border-b border-[#f0ede7]">🏢 Deskripsi & Alamat</h3>
                        <div>
                            <label class="block text-[10px] font-semibold text-[#8c887d] mb-1">Tagline Singkat Footer</label>
                            <input type="text" wire:model.live="footer_tagline" class="w-full text-xs border border-[#e5e2d9] rounded-lg px-3 py-2 bg-[#faf9f6]">
                        </div>
                        <div>
                            <label class="block text-[10px] font-semibold text-[#8c887d] mb-1">Alamat Lengkap Kantor</label>
                            <textarea wire:model.live="contact_address" rows="3" class="w-full text-xs border border-[#e5e2d9] rounded-lg px-3 py-2 bg-[#faf9f6] resize-none"></textarea>
                        </div>
                        <div>
                            <label class="block text-[10px] font-semibold text-[#8c887d] mb-1">Hak Cipta (Copyright)</label>
                            <input type="text" wire:model.live="footer_copyright" class="w-full text-xs border border-[#e5e2d9] rounded-lg px-3 py-2 bg-[#faf9f6]">
                        </div>
                    </div>

                    <div class="bg-white border border-[#e5e2d9] rounded-2xl shadow-sm p-6 space-y-4">
                        <h3 class="text-[10px] font-bold text-[#5c5950] uppercase tracking-wider pb-2 border-b border-[#f0ede7]">🔗 Tautan Eksternal Footer (Maks. 5)</h3>
                        <div class="space-y-3">
                            @foreach ($footer_external_links as $j => $link)
                                <div class="grid grid-cols-2 gap-3 p-3 border border-[#e5e2d9] rounded-xl bg-[#faf9f6]">
                                    <div>
                                        <label class="block text-[9px] text-[#8c887d] mb-0.5">Nama Link {{ $j+1 }}</label>
                                        <input type="text" wire:model.live="footer_external_links.{{ $j }}.label"
                                            class="w-full text-xs border border-[#e5e2d9] rounded-lg px-3 py-1.5 bg-white">
                                    </div>
                                    <div>
                                        <label class="block text-[9px] text-[#8c887d] mb-0.5">URL Target {{ $j+1 }}</label>
                                        <input type="text" wire:model.live="footer_external_links.{{ $j }}.url"
                                            class="w-full text-xs border border-[#e5e2d9] rounded-lg px-3 py-1.5 bg-white">
                                    </div>
                                </div>
                            @endforeach
                        </div>
                    </div>
                </div>
            @endif
        </div>

        {{-- ===== KANAN: Visual Live Preview Layout ===== --}}
        <div class="lg:col-span-4 sticky top-6">
            <div class="bg-white border border-[#e5e2d9] rounded-2xl shadow-sm overflow-hidden">
                <div class="p-4 border-b border-[#e5e2d9] bg-[#faf9f6]">
                    <h2 class="text-xs font-extrabold uppercase tracking-wider text-[#052c22]">👁️ Pratinjau Tampilan</h2>
                    <p class="text-[10px] text-[#8c887d] mt-0.5">Representasi visual posisi tata letak di halaman beranda.</p>
                </div>

                <div class="p-4 bg-slate-50">
                    
                    {{-- 1. PREVIEW BANNER --}}
                    @if ($activeSection === 'slides')
                        <div class="space-y-4">
                            @foreach ($slides as $idx => $s)
                                @php
                                    $rightSlideImg = !empty($slide_photo_files[$idx]) && method_exists($slide_photo_files[$idx], 'temporaryUrl')
                                        ? $slide_photo_files[$idx]->temporaryUrl()
                                        : ($s['image'] ?? '');
                                @endphp
                                <div class="border border-[#e5e2d9] rounded-xl overflow-hidden shadow-sm bg-white text-[10px]">
                                    <div class="bg-[#faf9f6] px-3 py-1.5 border-b border-[#e5e2d9] flex justify-between items-center text-[9px] font-bold text-[#5c5950]">
                                        <span>PRATINJAU SLIDE #{{ $idx + 1 }}</span>
                                    </div>
                                    <div class="bg-[#052c22] text-white p-4 relative" style="min-height: 120px;">
                                        <div class="absolute inset-0 bg-cover bg-center opacity-30" style="background-image: url('{{ $rightSlideImg }}')"></div>
                                        <div class="relative z-10 space-y-1.5">
                                            <span class="px-2 py-0.5 rounded bg-[#c8b282]/20 text-[#c8b282] border border-[#c8b282]/30 text-[7px] font-bold uppercase tracking-wider inline-block">
                                                {{ $s['badge'] ?: 'BADGE' }}
                                            </span>
                                            <h4 class="font-bold text-[10px] leading-tight">
                                                {{ $s['title_line1'] ?: 'Judul Baris 1' }}<br>
                                                <span class="text-[#c8b282]">{{ $s['title_line2'] ?: 'Judul Baris 2' }}</span>
                                            </h4>
                                            <p class="text-[7px] text-slate-300 font-light truncate max-w-[200px]">
                                                {{ $s['description'] ?: 'Deskripsi...' }}
                                            </p>
                                            <div class="flex gap-1 pt-1">
                                                <span class="px-1.5 py-0.5 bg-[#c8b282] text-[#052c22] text-[6px] font-bold rounded">{{ $s['button1_text'] ?: 'Tombol 1' }}</span>
                                                <span class="px-1.5 py-0.5 bg-white/10 text-white text-[6px] font-bold rounded border border-white/20">{{ $s['button2_text'] ?: 'Tombol 2' }}</span>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            @endforeach
                        </div>

                    {{-- 2. PREVIEW SAMBUTAN --}}
                    @elseif ($activeSection === 'sambutan')
                        @php
                            $rightWr3Img = !empty($wr3_photo_file) && method_exists($wr3_photo_file, 'temporaryUrl')
                                ? $wr3_photo_file->temporaryUrl()
                                : $wr3_photo_path;
                        @endphp
                        <div class="border border-[#e5e2d9] rounded-xl overflow-hidden shadow-sm bg-white p-4 space-y-3">
                            <div class="flex gap-3 items-start">
                                <div class="w-1/3 shrink-0">
                                    <img src="{{ $rightWr3Img }}" class="w-full aspect-[3/4] object-cover rounded-lg border border-slate-200">
                                </div>
                                <div class="w-2/3 space-y-1.5">
                                    <h4 class="font-bold text-[#052c22] text-[10px] leading-tight">{{ $wr3_name ?: 'Nama WR III' }}</h4>
                                    <p class="text-[8px] text-[#c8b282] font-semibold italic">{{ $wr3_quote ?: '"Motto hidup..."' }}</p>
                                    <p class="text-[8px] text-slate-500 font-light line-clamp-3 text-justify">
                                        {{ $wr3_para1 ?: 'Selamat datang di portal informasi kemahasiswaan...' }}
                                    </p>
                                </div>
                            </div>
                            <div class="text-center pt-2 border-t border-[#f0ede6]">
                                <p class="text-[9px] text-[#8c887d] font-bold">Bagian Sambutan Pimpinan (Bawah Slider)</p>
                            </div>
                        </div>

                    {{-- 3. PREVIEW KARTU LAYANAN --}}
                    @elseif ($activeSection === 'layanan')
                        <div class="border border-[#e5e2d9] rounded-xl overflow-hidden shadow-sm bg-white p-4 space-y-3">
                            <div class="grid grid-cols-2 gap-2">
                                @foreach($layanan_cards as $c)
                                    <div class="p-2 border border-[#e5e2d9] rounded-lg bg-[#faf9f6] text-center space-y-0.5">
                                        <span class="text-[7px] text-[#c8b282] font-bold uppercase tracking-wider block">
                                            {{ $c['label'] ?: 'Layanan' }}
                                        </span>
                                        <span class="text-[9px] text-[#052c22] font-bold block truncate">
                                            {{ $c['title'] ?: 'Judul Menu' }}
                                        </span>
                                    </div>
                                @endforeach
                            </div>
                            <div class="text-center pt-2 border-t border-[#f0ede6]">
                                <p class="text-[9px] text-[#8c887d] font-bold">Grid 6 Kartu Menu Cepat Beranda</p>
                            </div>
                        </div>

                    {{-- 4. PREVIEW ABOUT & VIDEO --}}
                    @elseif ($activeSection === 'about')
                        <div class="border border-[#e5e2d9] rounded-xl overflow-hidden shadow-sm bg-white p-4 space-y-4">
                            <div class="space-y-1.5 text-center">
                                <span class="text-[8px] text-[#c8b282] font-extrabold uppercase tracking-wider block">
                                    {{ $about_label ?: 'SUB-LABEL' }}
                                </span>
                                <h4 class="font-bold text-[#052c22] text-xs leading-none">
                                    {{ $about_title ?: 'Judul Profil Beranda' }}
                                </h4>
                            </div>
                            <div class="flex gap-2 items-center bg-[#faf9f6] p-2 border border-[#e5e2d9] rounded-lg">
                                <div class="w-1/2 relative bg-slate-800 aspect-video rounded flex items-center justify-center text-[10px] text-white">
                                    ▶️ Video
                                </div>
                                <div class="w-1/2 space-y-1.5">
                                    <div class="space-y-0.5">
                                        <span class="font-bold text-[8px] text-[#052c22] block">
                                            ✔ {{ $about_point1_title ?: 'Keunggulan 1' }}
                                        </span>
                                        <span class="text-[7px] text-[#8c887d] block truncate">
                                            {{ $about_point1_desc ?: 'Keterangan...' }}
                                        </span>
                                    </div>
                                    <div class="space-y-0.5">
                                        <span class="font-bold text-[8px] text-[#052c22] block">
                                            ✔ {{ $about_point2_title ?: 'Keunggulan 2' }}
                                        </span>
                                        <span class="text-[7px] text-[#8c887d] block truncate">
                                            {{ $about_point2_desc ?: 'Keterangan...' }}
                                        </span>
                                    </div>
                                </div>
                            </div>
                            <div class="text-center pt-2 border-t border-[#f0ede6]">
                                <p class="text-[9px] text-[#8c887d] font-bold">Sekilas Profil & Pemutar Video</p>
                            </div>
                        </div>

                    {{-- 5. PREVIEW TOP BAR --}}
                    @elseif ($activeSection === 'topbar')
                        <div class="border border-[#e5e2d9] rounded-xl overflow-hidden shadow-sm bg-white">
                            <div class="bg-[#052c22] text-slate-300 p-2.5 space-y-1 text-[8px]">
                                <div class="flex justify-between items-center border-b border-white/10 pb-1.5">
                                    <span class="truncate max-w-[130px]">📞 {{ $contact_phone ?: 'Telepon' }}</span>
                                    <span class="text-[#c8b282]">{{ $topbar_link1_label ?: 'Link 1' }}</span>
                                </div>
                                <div class="flex justify-between items-center pt-0.5">
                                    <span class="truncate max-w-[130px]">✉️ {{ $contact_email ?: 'Email' }}</span>
                                    <span class="text-[#c8b282]">{{ $topbar_link2_label ?: 'Link 2' }}</span>
                                </div>
                            </div>
                            <div class="p-3 text-center">
                                <p class="text-[9px] text-[#8c887d] font-bold">Baris Informasi Kontak Teratas (Top Bar)</p>
                            </div>
                        </div>

                    {{-- 6. PREVIEW FOOTER --}}
                    @elseif ($activeSection === 'footer')
                        <div class="border border-[#e5e2d9] rounded-xl overflow-hidden shadow-sm bg-[#052c22] text-slate-400 p-4 space-y-3">
                            <div class="space-y-1">
                                <span class="font-bold text-[9px] text-white tracking-wider block">KEMAHASISWAAN</span>
                                <p class="text-[8px] leading-relaxed text-slate-300">{{ $footer_tagline }}</p>
                                <p class="text-[7px] text-slate-400">📍 {{ $contact_address }}</p>
                            </div>
                            <div class="border-t border-white/10 pt-2 flex justify-between items-center text-[7px]">
                                <span>{{ $footer_copyright }}</span>
                                <div class="flex gap-1.5">
                                    @foreach(array_slice(array_filter($footer_external_links, fn($l)=>!empty($l['label'])), 0, 2) as $el)
                                        <span class="text-[#c8b282]">{{ $el['label'] }}</span>
                                    @endforeach
                                </div>
                            </div>
                        </div>
                    @endif
                </div>
            </div>
        </div>
    </div>

    {{-- Save Bar bawah --}}
    <div class="flex justify-end pt-4 border-t border-[#e5e2d9]">
        <button wire:click="save" wire:loading.attr="disabled"
            class="flex items-center gap-2 bg-[#052c22] hover:bg-[#0a3d30] text-white text-xs font-semibold px-6 py-2.5 rounded-xl shadow-md transition cursor-pointer">
            <svg wire:loading wire:target="save" class="animate-spin w-3.5 h-3.5" fill="none" viewBox="0 0 24 24"><circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"/><path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8v8z"/></svg>
            <span>Simpan Perubahan</span>
        </button>
    </div>
</div>
