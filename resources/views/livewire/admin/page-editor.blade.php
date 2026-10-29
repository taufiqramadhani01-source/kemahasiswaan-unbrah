<div>
    <x-slot name="title">{{ $pageId ? 'Edit Halaman' : 'Tambah Halaman' }} - Dasbor Kemahasiswaan</x-slot>

    {{-- CKEditor 5 Styling (Script & CSS sudah dimuat secara lokal di layouts.app) --}}
    <style>
        .ck-block .ck-editor__editable { min-height: 150px !important; font-size: 13px !important; }
        .ck-block .ck-toolbar { border-radius: 12px 12px 0 0 !important; border-color: #e5e2d9 !important; background-color: #faf9f6 !important; }
        .ck-block .ck-editor__editable { border-radius: 0 0 12px 12px !important; border-color: #e5e2d9 !important; }
        .ck-editor__editable_inline:focus { border-color: var(--color-accent) !important; box-shadow: none !important; }
    </style>

    {{-- Breadcrumbs & Header --}}
    <div class="flex flex-col md:flex-row md:items-center justify-between gap-4 mb-8">
        <div>
            <div class="flex items-center gap-2 text-xs text-[#8c887d] font-bold uppercase tracking-wider mb-2">
                <a href="/admin/pages" class="hover:text-[#052c22] transition">Halaman Dinamis</a>
                <span>&rarr;</span>
                <span class="text-[#c8b282]">{{ $pageId ? 'Edit Editor' : 'Pembuat Halaman Baru' }}</span>
            </div>
            <h1 class="text-2xl font-extrabold text-[#052c22] tracking-tight">
                {{ $pageId ? 'Edit Konten Halaman' : 'Buat Halaman Dinamis Baru' }}
            </h1>
            <p class="text-xs text-[#5c5950] mt-0.5">Susun konten terstruktur, letakkan gambar, heading, dan kolom tanpa batasan.</p>
        </div>
        <div class="flex items-center gap-3">
            <a href="/admin/pages" class="px-4 py-2 border border-[#e5e2d9] hover:bg-slate-50 text-[#5c5950] rounded-xl text-xs font-bold transition flex items-center no-underline">
                Kembali
            </a>
            @if ($pageId)
                <a href="/page/{{ $slug }}" target="_blank" class="px-4 py-2 border border-[#052c22] hover:bg-[#faf9f6] text-[#052c22] rounded-xl text-xs font-bold transition flex items-center gap-1 no-underline">
                    Pratinjau 🌐
                </a>
            @else
                <button type="button" disabled title="Simpan halaman terlebih dahulu untuk melihat pratinjau"
                    class="px-4 py-2 border border-slate-200 text-slate-400 bg-slate-50 rounded-xl text-xs font-bold cursor-not-allowed flex items-center gap-1">
                    Pratinjau 🌐
                </button>
            @endif
            <button wire:click="save" class="px-4 py-2 bg-[#052c22] hover:bg-[#073d30] text-[#c8b282] rounded-xl text-xs font-bold transition">
                💾 Simpan Konten Halaman
            </button>
        </div>
    </div>


    {{-- Flash Message --}}
    @if (session()->has('message'))
        <div class="p-4 mb-6 bg-emerald-50 border border-emerald-100 text-emerald-800 text-xs font-bold rounded-xl flex items-center justify-between">
            <span>✅ {{ session('message') }}</span>
            <button onclick="this.parentElement.remove()">✕</button>
        </div>
    @endif

    <div class="grid grid-cols-1 lg:grid-cols-3 gap-6">

        {{-- LEFT PANEL: Metadata & Settings --}}
        <div class="space-y-6">
            <div class="bg-white border border-[#e5e2d9] rounded-2xl shadow-sm p-6 space-y-4">
                <h3 class="text-xs font-bold uppercase tracking-wider text-[#052c22] border-b border-[#faf9f6] pb-2">⚙️ Atribut Halaman</h3>

                {{-- Judul Halaman --}}
                <div>
                    <label class="block text-[10px] font-bold uppercase tracking-wider text-[#8c887d] mb-1.5">Judul Halaman Publik</label>
                    <input type="text" wire:model.live.debounce.400ms="title" placeholder="Contoh: Layanan Kesejahteraan Mahasiswa" 
                        class="w-full px-3 py-2 border border-[#dcd9d0] rounded-xl text-xs focus:outline-none focus:border-[#c8b282] transition @error('title') border-red-300 @enderror">
                    @error('title') <p class="text-red-500 text-[10px] mt-1">{{ $message }}</p> @enderror
                </div>

                {{-- Slug Halaman --}}
                <div>
                    <label class="block text-[10px] font-bold uppercase tracking-wider text-[#8c887d] mb-1.5">Slug URL</label>
                    <div class="flex">
                        <span class="px-3 py-2 bg-[#faf9f6] border border-r-0 border-[#dcd9d0] rounded-l-xl text-xs text-[#8c887d] font-semibold">/page/</span>
                        <input type="text" wire:model="slug" placeholder="layanan-mahasiswa"
                            class="flex-1 px-3 py-2 border border-[#dcd9d0] rounded-r-xl text-xs focus:outline-none focus:border-[#c8b282] transition @error('slug') border-red-300 @enderror">
                    </div>
                    @error('slug') <p class="text-red-500 text-[10px] mt-1">{{ $message }}</p> @enderror
                </div>

                {{-- Status Publik --}}
                <div class="flex items-center gap-3 pt-2">
                    <label class="text-[10px] font-bold uppercase tracking-wider text-[#8c887d]">Status Publik</label>
                    <button type="button" wire:click="$toggle('is_published')"
                        class="relative w-10 h-5 rounded-full transition-colors duration-200 {{ $is_published ? 'bg-[#052c22]' : 'bg-[#e5e2d9]' }}">
                        <span class="absolute top-0.5 left-0.5 w-4 h-4 bg-white rounded-full shadow transition-transform duration-200 {{ $is_published ? 'translate-x-5' : 'translate-x-0' }}"></span>
                    </button>
                    <span class="text-[10px] font-bold text-[#5c5950]">{{ $is_published ? 'Publik' : 'Draft' }}</span>
                </div>
            </div>

            {{-- Info Panel --}}
            <div class="bg-white border border-[#e5e2d9] rounded-2xl shadow-sm p-6">
                <h3 class="text-xs font-bold uppercase tracking-wider text-[#052c22] mb-2">💡 Tips Penyusunan Blok</h3>
                <ul class="text-[10px] text-[#5c5950] space-y-2 list-disc pl-4">
                    <li>Gunakan blok **Hero / Banner** di urutan teratas sebagai header halaman utama.</li>
                    <li>Gunakan blok **Dua Kolom** untuk konten berdampingan (contoh: Visi di kiri, Misi di kanan).</li>
                    <li>Gunakan **Divider** untuk membatasi bab/bagian halaman agar tidak monoton.</li>
                </ul>
            </div>
        </div>

        {{-- RIGHT PANEL: Page Block Builder --}}
        <div class="lg:col-span-2 space-y-4">
            <div class="bg-white border border-[#e5e2d9] rounded-2xl shadow-sm p-6">
                <div class="flex justify-between items-center mb-6">
                    <h3 class="text-xs font-bold uppercase tracking-wider text-[#052c22]">🧩 Blok Konten Halaman</h3>
                    <span class="text-[10px] px-2.5 py-0.5 rounded-full bg-[#faf9f6] text-[#052c22] border border-[#e5e2d9] font-bold">Total Blok: {{ count($blocks) }}</span>
                </div>

                {{-- Blocks Stack --}}
                <div class="space-y-4">
                    @forelse ($blocks as $i => $block)
                        <div class="border border-[#e5e2d9] rounded-2xl bg-white overflow-hidden shadow-sm hover:shadow transition duration-200">
                            
                            {{-- Block Header / Control Panel --}}
                            <div class="flex items-center gap-2 px-4 py-3 bg-[#faf9f6] border-b border-[#e5e2d9]">
                                <span class="text-[9px] font-bold uppercase tracking-widest text-[#8c887d] px-2 py-0.5 rounded-full border border-[#e5e2d9] bg-white">
                                    @switch($block['type'])
                                        @case('heading') 🔤 Heading @break
                                        @case('text')    📝 Teks Rich @break
                                        @case('image')   🖼️ Gambar @break
                                        @case('divider') ─ Divider @break
                                        @case('callout') 💡 Callout @break
                                        @case('hero')    🏔️ Hero Banner @break
                                        @case('two_col') ⬛⬛ Dua Kolom @break
                                        @default {{ ucfirst($block['type']) }}
                                    @endswitch
                                </span>
                                <span class="text-[9px] text-[#8c887d] font-bold">Blok #{{ $i + 1 }}</span>

                                <div class="ml-auto flex items-center gap-1">
                                    @if ($i > 0)
                                        <button wire:click="moveBlockUp({{ $i }})" title="Geser Naik" class="px-2 py-1 text-[9px] border border-[#e5e2d9] rounded-lg hover:bg-white text-[#5c5950] transition">↑</button>
                                    @endif
                                    @if ($i < count($blocks) - 1)
                                        <button wire:click="moveBlockDown({{ $i }})" title="Geser Turun" class="px-2 py-1 text-[9px] border border-[#e5e2d9] rounded-lg hover:bg-white text-[#5c5950] transition">↓</button>
                                    @endif
                                    <button wire:click="removeBlock({{ $i }})" title="Hapus Blok" class="px-2 py-1 text-[9px] border border-red-100 rounded-lg hover:bg-red-50 text-red-500 font-bold transition">✕</button>
                                </div>
                            </div>

                            {{-- Block Content Fields --}}
                            <div class="p-5">
                                @switch($block['type'])

                                    {{-- 1. HEADING --}}
                                    @case('heading')
                                        <div class="flex gap-3">
                                            <select wire:change="updateBlockData({{ $i }}, 'level', $event.target.value)"
                                                class="px-3 py-2 border border-[#dcd9d0] rounded-xl text-xs bg-[#faf9f6] focus:outline-none focus:border-[#c8b282] transition w-24">
                                                @foreach(['h2','h3','h4'] as $level)
                                                    <option value="{{ $level }}" {{ ($block['data']['level'] ?? 'h2') === $level ? 'selected' : '' }}>{{ strtoupper($level) }}</option>
                                                @endforeach
                                            </select>
                                            <input type="text" value="{{ $block['data']['text'] ?? '' }}"
                                                wire:change="updateBlockData({{ $i }}, 'text', $event.target.value)"
                                                placeholder="Tulis sub-judul sub-bab..."
                                                class="flex-1 px-3 py-2 border border-[#dcd9d0] rounded-xl text-sm font-bold focus:outline-none focus:border-[#c8b282] transition">
                                        </div>
                                        @break

                                    {{-- 2. TEXT (CKEDITOR) --}}
                                    @case('text')
                                        <div wire:key="ck-editor-text-{{ $i }}" wire:ignore class="ck-block"
                                             x-data="{ editor: null }"
                                             x-init="(async () => {
                                                 const { ClassicEditor, Essentials, Paragraph, Bold, Italic, Underline, Link, List, Heading, Alignment } = CKEDITOR;
                                                 editor = await ClassicEditor.create($refs.ckText, {
                                                     plugins: [Essentials, Paragraph, Bold, Italic, Underline, Link, List, Heading, Alignment],
                                                     toolbar: ['heading', '|', 'bold', 'italic', 'underline', '|', 'alignment', '|', 'bulletedList', 'numberedList', '|', 'link'],
                                                     initialData: @js($block['data']['html'] ?? '')
                                                 });
                                                 editor.model.document.on('change:data', () => {
                                                     $wire.set('blocks.{{ $i }}.data.html', editor.getData());
                                                 });
                                             })()"
                                             x-destroy="if (editor) editor.destroy()">
                                            <div x-ref="ckText"></div>
                                        </div>
                                        @break

                                    {{-- 3. IMAGE (WITH FILE UPLOAD) --}}
                                    @case('image')
                                        <div class="space-y-4">
                                            <div class="grid grid-cols-1 md:grid-cols-3 gap-4 items-start">
                                                
                                                {{-- Area Upload Gambar --}}
                                                <div class="md:col-span-2">
                                                    <label class="block text-[9px] font-bold uppercase tracking-wider text-[#8c887d] mb-1.5">Pilih / Unggah File Gambar</label>
                                                    <div class="relative border-2 border-dashed border-[#e5e2d9] rounded-2xl p-6 bg-[#faf9f6]/30 hover:bg-[#faf9f6] hover:border-[#c8b282]/50 transition duration-200 flex flex-col items-center justify-center text-center">
                                                        
                                                        <input type="file" wire:model="temp_images.{{ $i }}" id="upload-{{ $i }}"
                                                               class="absolute inset-0 w-full h-full opacity-0 cursor-pointer">
                                                        
                                                        <span class="text-3xl mb-2">🖼️</span>
                                                        <span class="text-xs font-bold text-[#052c22]">Klik untuk mengunggah berkas</span>
                                                        <span class="text-[9px] text-[#8c887d] mt-1">Format: JPG, JPEG, PNG (Maks. 2MB)</span>
                                                        
                                                        {{-- Progress loading upload --}}
                                                        <div wire:loading wire:target="temp_images.{{ $i }}" class="absolute inset-0 bg-white/95 rounded-2xl flex flex-col items-center justify-center">
                                                            <div class="animate-spin rounded-full h-6 w-6 border-b-2 border-[#052c22] mb-2"></div>
                                                            <span class="text-[10px] font-bold text-[#052c22] uppercase tracking-wider">Mengunggah Gambar...</span>
                                                        </div>
                                                    </div>
                                                </div>

                                                {{-- Preview / Tampilan Gambar --}}
                                                <div>
                                                    <label class="block text-[9px] font-bold uppercase tracking-wider text-[#8c887d] mb-1.5">Pratinjau Gambar</label>
                                                    @if (!empty($block['data']['url']))
                                                        <div class="p-2 border border-[#e5e2d9] bg-white rounded-2xl shadow-sm text-center relative group">
                                                            <img src="{{ $block['data']['url'] }}" alt="preview" class="w-full h-28 object-cover rounded-xl border border-[#e5e2d9]">
                                                            <span class="text-[8px] text-[#8c887d] font-mono mt-1.5 block select-all truncate">{{ basename($block['data']['url']) }}</span>
                                                        </div>
                                                    @else
                                                        <div class="border border-dashed border-[#e5e2d9] rounded-2xl h-[142px] flex items-center justify-center text-[#8c887d] bg-[#faf9f6]/20">
                                                            <span class="text-[9px] font-semibold italic">Belum ada gambar</span>
                                                        </div>
                                                    @endif
                                                </div>

                                            </div>
                                            
                                            <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                                                {{-- Caption --}}
                                                <div>
                                                    <label class="block text-[9px] font-bold uppercase tracking-wider text-[#8c887d] mb-1">Keterangan Gambar (Caption)</label>
                                                    <input type="text" value="{{ $block['data']['caption'] ?? '' }}"
                                                        wire:change="updateBlockData({{ $i }}, 'caption', $event.target.value)"
                                                        placeholder="Contoh: Kegiatan pembukaan seminar mahasiswa"
                                                        class="w-full px-3 py-2 border border-[#dcd9d0] rounded-xl text-xs focus:outline-none focus:border-[#c8b282] transition">
                                                </div>
                                                
                                                {{-- Layout Position --}}
                                                <div>
                                                    <label class="block text-[9px] font-bold uppercase tracking-wider text-[#8c887d] mb-1">Posisi Layout</label>
                                                    <select wire:change="updateBlockData({{ $i }}, 'align', $event.target.value)"
                                                        class="w-full px-3 py-2 border border-[#dcd9d0] rounded-xl text-xs bg-[#faf9f6] focus:outline-none focus:border-[#c8b282] transition">
                                                        @foreach(['center' => 'Tengah (Sedang)', 'left' => 'Kiri (Teks melingkar)', 'right' => 'Kanan (Teks melingkar)', 'full' => 'Penuh (Lebar halaman)'] as $val => $label)
                                                            <option value="{{ $val }}" {{ ($block['data']['align'] ?? 'center') === $val ? 'selected' : '' }}>{{ $label }}</option>
                                                        @endforeach
                                                    </select>
                                                </div>
                                            </div>
                                        </div>
                                        @break

                                    {{-- 4. DIVIDER --}}
                                    @case('divider')
                                        <div class="flex items-center gap-2 text-[10px] text-[#8c887d] py-3">
                                            <div class="flex-1 h-px bg-[#e5e2d9]"></div>
                                            <span>Garis Pemisah</span>
                                            <div class="flex-1 h-px bg-[#e5e2d9]"></div>
                                        </div>
                                        @break

                                    {{-- 5. CALLOUT --}}
                                    @case('callout')
                                        <div class="flex gap-2">
                                            <select wire:change="updateBlockData({{ $i }}, 'color', $event.target.value)"
                                                class="px-3 py-2 border border-[#dcd9d0] rounded-xl text-xs bg-[#faf9f6] focus:outline-none focus:border-[#c8b282] transition w-28">
                                                @foreach(['blue' => '🔵 Biru', 'green' => '🟢 Hijau', 'yellow' => '🟡 Kuning', 'red' => '🔴 Merah'] as $val => $label)
                                                    <option value="{{ $val }}" {{ ($block['data']['color'] ?? 'blue') === $val ? 'selected' : '' }}>{{ $label }}</option>
                                                @endforeach
                                            </select>
                                            <input type="text" value="{{ $block['data']['text'] ?? '' }}"
                                                wire:change="updateBlockData({{ $i }}, 'text', $event.target.value)"
                                                placeholder="Tulis pesan callout / informasi penting..."
                                                class="flex-1 px-3 py-2 border border-[#dcd9d0] rounded-xl text-xs focus:outline-none focus:border-[#c8b282] transition">
                                        </div>
                                        @break

                                    {{-- 6. HERO BANNER --}}
                                    @case('hero')
                                        <div class="space-y-4">
                                            {{-- Live preview --}}
                                            <div class="rounded-2xl overflow-hidden p-6 text-{{ $block['data']['align'] ?? 'center' }} shadow-inner" style="background-color: {{ $block['data']['bg_color'] ?? '#052c22' }}">
                                                @if (!empty($block['data']['label']))
                                                    <p class="text-[10px] font-bold uppercase tracking-[0.2em] mb-2" style="color: {{ $block['data']['highlight_color'] ?? '#c8b282' }}">{{ $block['data']['label'] }}</p>
                                                @endif
                                                <h1 class="text-xl font-extrabold text-white leading-tight">
                                                    {{ $block['data']['title'] ?? 'Judul Banner' }}
                                                    @if (!empty($block['data']['highlight']))
                                                        <span style="color: {{ $block['data']['highlight_color'] ?? '#c8b282' }}">{{ $block['data']['highlight'] }}</span>
                                                    @endif
                                                </h1>
                                                @if (!empty($block['data']['subtitle']))
                                                    <p class="text-xs text-white/70 mt-2">{{ $block['data']['subtitle'] }}</p>
                                                @endif
                                            </div>

                                            {{-- Editor inputs --}}
                                            <div class="grid grid-cols-2 gap-3">
                                                <div>
                                                    <label class="block text-[9px] font-bold uppercase tracking-wider text-[#8c887d] mb-1">Label Kecil (Atas)</label>
                                                    <input type="text" value="{{ $block['data']['label'] ?? '' }}"
                                                        wire:change="updateBlockData({{ $i }}, 'label', $event.target.value)"
                                                        placeholder="WARTA & KABAR TERKINI"
                                                        class="w-full px-3 py-2 border border-[#dcd9d0] rounded-lg text-xs focus:outline-none">
                                                </div>
                                                <div>
                                                    <label class="block text-[9px] font-bold uppercase tracking-wider text-[#8c887d] mb-1">Warna Background</label>
                                                    <div class="flex gap-2 items-center">
                                                        <input type="color" value="{{ $block['data']['bg_color'] ?? '#052c22' }}"
                                                            wire:change="updateBlockData({{ $i }}, 'bg_color', $event.target.value)"
                                                            class="w-8 h-8 rounded border border-[#dcd9d0] cursor-pointer p-0.5">
                                                        <input type="text" value="{{ $block['data']['bg_color'] ?? '#052c22' }}"
                                                            wire:change="updateBlockData({{ $i }}, 'bg_color', $event.target.value)"
                                                            class="flex-1 px-2 py-1.5 border border-[#dcd9d0] rounded-lg text-xs font-mono">
                                                    </div>
                                                </div>
                                            </div>
                                            <div class="grid grid-cols-2 gap-3">
                                                <div>
                                                    <label class="block text-[9px] font-bold uppercase tracking-wider text-[#8c887d] mb-1">Judul Utama</label>
                                                    <input type="text" value="{{ $block['data']['title'] ?? '' }}"
                                                        wire:change="updateBlockData({{ $i }}, 'title', $event.target.value)"
                                                        placeholder="Kilas Berita &"
                                                        class="w-full px-3 py-2 border border-[#dcd9d0] rounded-lg text-xs font-bold">
                                                </div>
                                                <div>
                                                    <label class="block text-[9px] font-bold uppercase tracking-wider text-[#8c887d] mb-1">Teks Highlight</label>
                                                    <div class="flex gap-2">
                                                        <input type="text" value="{{ $block['data']['highlight'] ?? '' }}"
                                                            wire:change="updateBlockData({{ $i }}, 'highlight', $event.target.value)"
                                                            placeholder="Publikasi"
                                                            class="flex-1 px-3 py-2 border border-[#dcd9d0] rounded-lg text-xs font-bold">
                                                        <input type="color" value="{{ $block['data']['highlight_color'] ?? '#c8b282' }}"
                                                            wire:change="updateBlockData({{ $i }}, 'highlight_color', $event.target.value)"
                                                            class="w-8 h-8 rounded border border-[#dcd9d0] cursor-pointer p-0.5">
                                                    </div>
                                                </div>
                                            </div>
                                            <div>
                                                <label class="block text-[9px] font-bold uppercase tracking-wider text-[#8c887d] mb-1">Subtitle / Keterangan</label>
                                                <input type="text" value="{{ $block['data']['subtitle'] ?? '' }}"
                                                    wire:change="updateBlockData({{ $i }}, 'subtitle', $event.target.value)"
                                                    placeholder="Tulis sub-deskripsi di bawah banner..."
                                                    class="w-full px-3 py-2 border border-[#dcd9d0] rounded-lg text-xs">
                                            </div>
                                            <div>
                                                <label class="block text-[9px] font-bold uppercase tracking-wider text-[#8c887d] mb-1">Penyelarasan (Alignment)</label>
                                                <div class="flex gap-4 mt-1">
                                                    @foreach(['center' => 'Tengah', 'left' => 'Kiri', 'right' => 'Kanan'] as $val => $lbl)
                                                        <label class="flex items-center gap-1.5 cursor-pointer text-xs">
                                                            <input type="radio" name="hero_align_edit_{{ $i }}" value="{{ $val }}"
                                                                wire:change="updateBlockData({{ $i }}, 'align', '{{ $val }}')"
                                                                {{ ($block['data']['align'] ?? 'center') === $val ? 'checked' : '' }}
                                                                class="accent-[#052c22]">
                                                            {{ $lbl }}
                                                        </label>
                                                    @endforeach
                                                </div>
                                            </div>
                                        </div>
                                        @break

                                    {{-- 7. TWO COLUMN --}}
                                    @case('two_col')
                                        <div class="space-y-4">
                                            {{-- Ratio & BG selector --}}
                                            <div class="flex items-center gap-3">
                                                <label class="text-[9px] font-bold uppercase tracking-wider text-[#8c887d]">Rasio Lebar:</label>
                                                @foreach(['50/50' => '50 : 50', '40/60' => '40 : 60', '60/40' => '60 : 40'] as $val => $lbl)
                                                    <label class="flex items-center gap-1.5 cursor-pointer text-xs">
                                                        <input type="radio" name="ratio_edit_{{ $i }}" value="{{ $val }}"
                                                            wire:change="updateBlockData({{ $i }}, 'ratio', '{{ $val }}')"
                                                            {{ ($block['data']['ratio'] ?? '50/50') === $val ? 'checked' : '' }}
                                                            class="accent-[#052c22]">
                                                        {{ $lbl }}
                                                    </label>
                                                @endforeach
                                            </div>

                                            <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                                                
                                                {{-- Left Col --}}
                                                <div class="space-y-2 p-3.5 rounded-2xl border border-[#e5e2d9]"
                                                     style="background-color: {{ !empty($block['data']['left_bg_color']) ? $block['data']['left_bg_color'] : 'transparent' }}">
                                                    <div class="flex justify-between items-center mb-1">
                                                        <span class="text-[9px] font-bold text-[#8c887d] uppercase tracking-wider">KOLOM KIRI</span>
                                                        <div class="flex items-center gap-1.5">
                                                            <span class="text-[9px] text-[#8c887d]">BG:</span>
                                                            <input type="color" value="{{ $block['data']['left_bg_color'] ?? '' }}"
                                                                wire:change="updateBlockData({{ $i }}, 'left_bg_color', $event.target.value)"
                                                                class="w-6 h-6 rounded border border-[#dcd9d0] cursor-pointer p-0.5">
                                                            <button type="button" wire:click="updateBlockData({{ $i }}, 'left_bg_color', '')" class="text-[8px] border px-1 rounded bg-white hover:bg-slate-50">✕</button>

                                                            <span class="text-[9px] text-[#8c887d] ml-1">Teks:</span>
                                                            <input type="color" value="{{ $block['data']['left_text_color'] ?? '#1a2421' }}"
                                                                wire:change="updateBlockData({{ $i }}, 'left_text_color', $event.target.value)"
                                                                class="w-6 h-6 rounded border border-[#dcd9d0] cursor-pointer p-0.5">
                                                        </div>
                                                    </div>
                                                    <input type="text" value="{{ $block['data']['left_heading'] ?? '' }}"
                                                        wire:change="updateBlockData({{ $i }}, 'left_heading', $event.target.value)"
                                                        placeholder="Judul Kolom Kiri"
                                                        class="w-full px-3 py-1.5 border border-[#dcd9d0] rounded-xl text-xs font-bold"
                                                        style="color: {{ $block['data']['left_text_color'] ?? '#1a2421' }}">
                                                    
                                                    {{-- CKEditor Left --}}
                                                    <div wire:key="ck-two-left-edit-{{ $i }}" wire:ignore class="ck-block"
                                                         x-data="{ ckL: null }"
                                                         x-init="(async () => {
                                                             const { ClassicEditor, Essentials, Paragraph, Bold, Italic, Underline, List, Link } = CKEDITOR;
                                                             ckL = await ClassicEditor.create($refs.ckTwoLeft, {
                                                                 plugins: [Essentials, Paragraph, Bold, Italic, Underline, List, Link],
                                                                 toolbar: ['bold', 'italic', 'underline', '|', 'bulletedList', 'numberedList', '|', 'link'],
                                                                 initialData: @js($block['data']['left_content'] ?? '')
                                                             });
                                                             ckL.model.document.on('change:data', () => {
                                                                 $wire.set('blocks.{{ $i }}.data.left_content', ckL.getData());
                                                             });
                                                         })()"
                                                         x-destroy="if (ckL) ckL.destroy()">
                                                        <div x-ref="ckTwoLeft"></div>
                                                    </div>
                                                </div>

                                                {{-- Right Col --}}
                                                <div class="space-y-2 p-3.5 rounded-2xl border border-[#e5e2d9]"
                                                     style="background-color: {{ !empty($block['data']['right_bg_color']) ? $block['data']['right_bg_color'] : 'transparent' }}">
                                                    <div class="flex justify-between items-center mb-1">
                                                        <span class="text-[9px] font-bold text-[#8c887d] uppercase tracking-wider">KOLOM KANAN</span>
                                                        <div class="flex items-center gap-1.5">
                                                            <span class="text-[9px] text-[#8c887d]">BG:</span>
                                                            <input type="color" value="{{ $block['data']['right_bg_color'] ?? '' }}"
                                                                wire:change="updateBlockData({{ $i }}, 'right_bg_color', $event.target.value)"
                                                                class="w-6 h-6 rounded border border-[#dcd9d0] cursor-pointer p-0.5">
                                                            <button type="button" wire:click="updateBlockData({{ $i }}, 'right_bg_color', '')" class="text-[8px] border px-1 rounded bg-white hover:bg-slate-50">✕</button>

                                                            <span class="text-[9px] text-[#8c887d] ml-1">Teks:</span>
                                                            <input type="color" value="{{ $block['data']['right_text_color'] ?? '#1a2421' }}"
                                                                wire:change="updateBlockData({{ $i }}, 'right_text_color', $event.target.value)"
                                                                class="w-6 h-6 rounded border border-[#dcd9d0] cursor-pointer p-0.5">
                                                        </div>
                                                    </div>
                                                    <input type="text" value="{{ $block['data']['right_heading'] ?? '' }}"
                                                        wire:change="updateBlockData({{ $i }}, 'right_heading', $event.target.value)"
                                                        placeholder="Judul Kolom Kanan"
                                                        class="w-full px-3 py-1.5 border border-[#dcd9d0] rounded-xl text-xs font-bold"
                                                        style="color: {{ $block['data']['right_text_color'] ?? '#1a2421' }}">
                                                    
                                                    {{-- CKEditor Right --}}
                                                    <div wire:key="ck-two-right-edit-{{ $i }}" wire:ignore class="ck-block"
                                                         x-data="{ ckR: null }"
                                                         x-init="(async () => {
                                                             const { ClassicEditor, Essentials, Paragraph, Bold, Italic, Underline, List, Link } = CKEDITOR;
                                                             ckR = await ClassicEditor.create($refs.ckTwoRight, {
                                                                 plugins: [Essentials, Paragraph, Bold, Italic, Underline, List, Link],
                                                                 toolbar: ['bold', 'italic', 'underline', '|', 'bulletedList', 'numberedList', '|', 'link'],
                                                                 initialData: @js($block['data']['right_content'] ?? '')
                                                             });
                                                             ckR.model.document.on('change:data', () => {
                                                                 $wire.set('blocks.{{ $i }}.data.right_content', ckR.getData());
                                                             });
                                                         })()"
                                                         x-destroy="if (ckR) ckR.destroy()">
                                                        <div x-ref="ckTwoRight"></div>
                                                    </div>
                                                </div>

                                            </div>
                                        </div>
                                        @break

                                    {{-- 8. ACCORDION (FAQ) --}}
                                    @case('accordion')
                                        <div class="space-y-4">
                                            <div class="space-y-3">
                                                @foreach($block['data']['items'] ?? [] as $subIdx => $item)
                                                    <div class="p-3 border border-[#e5e2d9] rounded-xl space-y-2 bg-[#faf9f6]/30">
                                                        <div class="flex justify-between items-center">
                                                            <span class="text-[9px] font-bold text-[#8c887d]">ITEM #{{ $subIdx + 1 }}</span>
                                                            <button type="button" wire:click="removeNestedItem({{ $i }}, 'items', {{ $subIdx }})"
                                                                class="text-[8px] text-red-500 font-bold border border-red-100 hover:bg-red-50 px-1.5 py-0.5 rounded">Hapus Item</button>
                                                        </div>
                                                        <input type="text" placeholder="Judul Akordion (Pertanyaan)..."
                                                            wire:model="blocks.{{ $i }}.data.items.{{ $subIdx }}.title"
                                                            class="w-full px-3 py-1.5 border border-[#dcd9d0] rounded-lg text-xs font-bold">
                                                        
                                                        {{-- CKEditor untuk isi akordion --}}
                                                        <div wire:key="ck-acc-{{ $i }}-{{ $subIdx }}" wire:ignore class="ck-block"
                                                             x-data="{ ckAcc: null }"
                                                             x-init="(async () => {
                                                                 const { ClassicEditor, Essentials, Paragraph, Bold, Italic, List, Link } = CKEDITOR;
                                                                 ckAcc = await ClassicEditor.create($refs.ckAccRef, {
                                                                     plugins: [Essentials, Paragraph, Bold, Italic, List, Link],
                                                                     toolbar: ['bold', 'italic', '|', 'bulletedList', 'numberedList', '|', 'link'],
                                                                     initialData: @js($item['content'] ?? '')
                                                                 });
                                                                 ckAcc.model.document.on('change:data', () => {
                                                                     $wire.set('blocks.{{ $i }}.data.items.{{ $subIdx }}.content', ckAcc.getData());
                                                                 });
                                                             })()"
                                                             x-destroy="if (ckAcc) ckAcc.destroy()">
                                                            <div x-ref="ckAccRef"></div>
                                                        </div>
                                                    </div>
                                                @endforeach
                                            </div>
                                            <button type="button" wire:click="addNestedItem({{ $i }}, 'items', {{ json_encode(['title' => '', 'content' => '']) }})"
                                                class="px-3 py-1.5 border border-[#dcd9d0] hover:bg-slate-50 text-[10px] font-bold rounded-lg text-[#052c22] transition">
                                                + Tambah Item FAQ / Tanya Jawab
                                            </button>
                                        </div>
                                        @break

                                    {{-- 9. DOWNLOADS --}}
                                    @case('downloads')
                                        <div class="space-y-4">
                                            <div class="space-y-2">
                                                @foreach($block['data']['items'] ?? [] as $subIdx => $item)
                                                    <div class="flex flex-wrap sm:flex-nowrap gap-2 items-center p-3 border border-[#e5e2d9] rounded-xl bg-white">
                                                        <select wire:model="blocks.{{ $i }}.data.items.{{ $subIdx }}.type"
                                                            class="px-2 py-1.5 border border-[#dcd9d0] rounded-lg text-xs bg-[#faf9f6] w-24">
                                                            <option value="pdf">📄 PDF</option>
                                                            <option value="link">🔗 Link URL</option>
                                                        </select>
                                                        <input type="text" placeholder="Label tombol download (Contoh: Panduan KIP-K.pdf)"
                                                            wire:model="blocks.{{ $i }}.data.items.{{ $subIdx }}.label"
                                                            class="flex-1 min-w-[200px] px-3 py-1.5 border border-[#dcd9d0] rounded-lg text-xs font-semibold">
                                                        <input type="text" placeholder="URL Link / Path File..."
                                                            wire:model="blocks.{{ $i }}.data.items.{{ $subIdx }}.url"
                                                            class="flex-1 min-w-[200px] px-3 py-1.5 border border-[#dcd9d0] rounded-lg text-xs font-mono">
                                                        <button type="button" wire:click="removeNestedItem({{ $i }}, 'items', {{ $subIdx }})"
                                                            class="text-red-500 font-bold border border-red-100 hover:bg-red-50 p-1.5 rounded-lg text-xs">✕</button>
                                                    </div>
                                                @endforeach
                                            </div>
                                            <button type="button" wire:click="addNestedItem({{ $i }}, 'items', {{ json_encode(['label' => '', 'url' => '#', 'type' => 'pdf']) }})"
                                                class="px-3 py-1.5 border border-[#dcd9d0] hover:bg-slate-50 text-[10px] font-bold rounded-lg text-[#052c22] transition">
                                                + Tambah Link / Tombol Unduhan
                                            </button>
                                        </div>
                                        @break

                                    {{-- 10. CARD GRID (GALERI/FACILITAS/ALUR) --}}
                                    @case('card_grid')
                                        <div class="space-y-4">
                                            <div class="flex items-center gap-3">
                                                <label class="text-[9px] font-bold uppercase tracking-wider text-[#8c887d]">Jumlah Kolom Grid:</label>
                                                <select wire:change="updateBlockData({{ $i }}, 'columns', $event.target.value)"
                                                    class="px-3 py-1.5 border border-[#dcd9d0] rounded-xl text-xs bg-[#faf9f6]">
                                                    <option value="2" {{ ($block['data']['columns'] ?? '3') == '2' ? 'selected' : '' }}>2 Kolom</option>
                                                    <option value="3" {{ ($block['data']['columns'] ?? '3') == '3' ? 'selected' : '' }}>3 Kolom (Rekomendasi)</option>
                                                    <option value="4" {{ ($block['data']['columns'] ?? '3') == '4' ? 'selected' : '' }}>4 Kolom</option>
                                                </select>
                                            </div>

                                            <div class="space-y-3">
                                                @foreach($block['data']['items'] ?? [] as $subIdx => $item)
                                                    <div class="p-3 border border-[#e5e2d9] rounded-xl space-y-2 bg-[#faf9f6]/30">
                                                        <div class="flex justify-between items-center">
                                                            <span class="text-[9px] font-bold text-[#8c887d]">KARTU #{{ $subIdx + 1 }}</span>
                                                            <button type="button" wire:click="removeNestedItem({{ $i }}, 'items', {{ $subIdx }})"
                                                                class="text-[8px] text-red-500 font-bold border border-red-100 hover:bg-red-50 px-1.5 py-0.5 rounded">Hapus Kartu</button>
                                                        </div>
                                                        <div class="grid grid-cols-2 gap-2">
                                                            <input type="text" placeholder="Judul Kartu..."
                                                                wire:model="blocks.{{ $i }}.data.items.{{ $subIdx }}.title"
                                                                class="px-3 py-1.5 border border-[#dcd9d0] rounded-lg text-xs font-bold">
                                                            <input type="text" placeholder="Link URL Tujuan (Opsional)..."
                                                                wire:model="blocks.{{ $i }}.data.items.{{ $subIdx }}.link"
                                                                class="px-3 py-1.5 border border-[#dcd9d0] rounded-lg text-xs">
                                                        </div>
                                                        <input type="text" placeholder="URL Gambar Ilustrasi..."
                                                            wire:model="blocks.{{ $i }}.data.items.{{ $subIdx }}.image"
                                                            class="w-full px-3 py-1.5 border border-[#dcd9d0] rounded-lg text-xs">
                                                        <textarea placeholder="Deskripsi singkat..." rows="2"
                                                            wire:model="blocks.{{ $i }}.data.items.{{ $subIdx }}.desc"
                                                            class="w-full px-3 py-1.5 border border-[#dcd9d0] rounded-lg text-xs"></textarea>
                                                    </div>
                                                @endforeach
                                            </div>
                                            <button type="button" wire:click="addNestedItem({{ $i }}, 'items', {{ json_encode(['title' => '', 'desc' => '', 'image' => '', 'link' => '']) }})"
                                                class="px-3 py-1.5 border border-[#dcd9d0] hover:bg-slate-50 text-[10px] font-bold rounded-lg text-[#052c22] transition">
                                                + Tambah Item Kartu Grid
                                            </button>
                                        </div>
                                        @break

                                    {{-- 11. CUSTOM HTML/JS CODE --}}
                                    @case('custom_html')
                                        <div class="space-y-2">
                                            <label class="block text-[9px] font-bold uppercase tracking-wider text-[#8c887d]">Salin / Tempel Kode Kodingan Murni (HTML / iFrame / Widget JS)</label>
                                            <textarea rows="6" 
                                                wire:change="updateBlockData({{ $i }}, 'code', $event.target.value)"
                                                placeholder="<iframe src='https://www.google.com/maps/embed...' width='600' height='450'></iframe>"
                                                class="w-full p-4 border border-[#dcd9d0] rounded-2xl text-xs font-mono bg-slate-900 text-slate-100 focus:outline-none focus:border-[#c8b282] transition resize-y">{{ $block['data']['code'] ?? '' }}</textarea>
                                            <p class="text-[9px] text-amber-600 font-bold">⚠️ Perhatian: Kodingan HTML/JS yang Anda masukkan akan dijalankan langsung di halaman publik. Pastikan kode bersumber tepercaya.</p>
                                        </div>
                                        @break

                                @endswitch
                            </div>
                        </div>
                    @empty
                        <div class="p-12 text-center border-2 border-dashed border-[#e5e2d9] rounded-2xl text-[#8c887d]">
                            <p class="text-3xl mb-2">🧩</p>
                            <p class="text-xs font-bold">Belum ada blok konten.</p>
                            <p class="text-[10px] mt-1">Pilih tipe blok di bawah lalu klik "+ Tambah Blok" untuk menyusun struktur.</p>
                        </div>
                    @endforelse
                </div>

                {{-- Add Block Controller --}}
                <div class="mt-6 pt-6 border-t border-[#e5e2d9] flex flex-col sm:flex-row items-center gap-3">
                    <div class="flex items-center gap-2 w-full sm:w-auto">
                        <span class="text-[10px] font-bold uppercase tracking-wider text-[#8c887d]">Jenis Blok:</span>
                        <select wire:model="newBlockType" class="flex-1 sm:flex-none px-3 py-2 border border-[#dcd9d0] rounded-xl text-xs bg-[#faf9f6] focus:outline-none focus:border-[#c8b282] transition">
                            <optgroup label="Dasar">
                                <option value="text">📝 Teks Rich (CKEditor)</option>
                                <option value="heading">🔤 Heading Judul (H2-H4)</option>
                                <option value="image">🖼️ Gambar (Dengan Upload)</option>
                                <option value="divider">─ Divider Pemisah</option>
                                <option value="callout">💡 Callout Pengumuman</option>
                                <option value="custom_html">💻 Kodingan Murni (HTML/Embed)</option>
                            </optgroup>
                            <optgroup label="Layout">
                                <option value="hero">🏔️ Hero Banner Section</option>
                                <option value="two_col">⬛⬛ Dua Kolom (Visi Misi)</option>
                                <option value="accordion">📂 Akordion / FAQ List</option>
                                <option value="downloads">📥 Link Tombol & Unduhan File</option>
                                <option value="card_grid">👥 Kartu Grid (Fasilitas & Profil)</option>
                            </optgroup>
                        </select>
                    </div>
                    <button wire:click="addBlock" class="w-full sm:w-auto px-4 py-2 bg-[#052c22] hover:bg-[#073d30] text-[#c8b282] rounded-xl text-xs font-bold transition">
                        + Tambah Blok Konten
                    </button>


                </div>
            </div>
        </div>

    </div>
</div>
