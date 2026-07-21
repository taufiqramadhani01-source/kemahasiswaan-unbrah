<div>
    <x-slot name="title">{{ $postId ? 'Edit Berita' : 'Tulis Berita Baru' }} - Dasbor Kemahasiswaan</x-slot>

    <style>
        .ck-block .ck-editor__editable { min-height: 380px !important; font-size: 13px !important; }
        .ck-block .ck-toolbar { border-radius: 12px 12px 0 0 !important; border-color: #e5e2d9 !important; background-color: #faf9f6 !important; }
        .ck-block .ck-editor__editable { border-radius: 0 0 12px 12px !important; border-color: #e5e2d9 !important; }
    </style>

    {{-- Breadcrumbs & Header --}}
    <div class="flex flex-col md:flex-row md:items-center justify-between gap-4 mb-8">
        <div>
            <div class="flex items-center gap-2 text-xs text-[#8c887d] font-bold uppercase tracking-wider mb-2">
                <a href="/admin/posts" class="hover:text-[#052c22] transition">Warta & Berita</a>
                <span>&rarr;</span>
                <span class="text-[#c8b282]">{{ $postId ? 'Edit Artikel' : 'Tulis Artikel Baru' }}</span>
            </div>
            <h1 class="text-2xl font-extrabold text-[#052c22] tracking-tight">
                {{ $postId ? 'Edit Konten Berita' : 'Buat Artikel Berita Baru' }}
            </h1>
            <p class="text-xs text-[#5c5950] mt-0.5">Tulis berita seputar kemahasiswaan, prestasi, dan pengumuman kampus.</p>
        </div>
        <div class="flex items-center gap-3">
            <a href="/admin/posts" class="px-4 py-2 border border-[#e5e2d9] hover:bg-slate-50 text-[#5c5950] rounded-xl text-xs font-bold transition flex items-center no-underline">
                Kembali
            </a>
            @if ($postId)
                <a href="/berita/{{ $slug }}" target="_blank" class="px-4 py-2 border border-[#052c22] hover:bg-[#faf9f6] text-[#052c22] rounded-xl text-xs font-bold transition flex items-center gap-1 no-underline">
                    Lihat Hasil Berita 🌐
                </a>
            @endif
            <button wire:click="save" class="px-4 py-2 bg-[#052c22] hover:bg-[#073d30] text-[#c8b282] rounded-xl text-xs font-bold transition">
                💾 Simpan & Terbitkan Berita
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

        {{-- LEFT PANEL: Main Title and Content Editor --}}
        <div class="lg:col-span-2 space-y-6">
            <div class="bg-white border border-[#e5e2d9] rounded-2xl p-6 shadow-sm space-y-6">
                <!-- Judul Berita -->
                <div>
                    <label class="block text-[10px] font-bold uppercase tracking-wider text-[#8c887d] mb-1.5">Judul Berita</label>
                    <input type="text" wire:model.live.debounce.250ms="title" placeholder="Masukkan judul berita yang menarik..." 
                        class="w-full px-4 py-3 border border-[#dcd9d0] rounded-xl text-sm font-bold focus:outline-none focus:border-[#c8b282] bg-[#faf9f6] text-[#052c22]">
                    @error('title') <span class="text-red-500 text-[10px] block mt-1 font-bold">{{ $message }}</span> @enderror
                </div>

                <!-- Slug Berita -->
                <div>
                    <label class="block text-[10px] font-bold uppercase tracking-wider text-[#8c887d] mb-1.5">Slug URL (Dihasilkan Otomatis)</label>
                    <div class="flex">
                        <span class="inline-flex items-center px-3 rounded-l-xl border border-r-0 border-[#dcd9d0] bg-slate-100 text-[#8c887d] text-xs font-mono">
                            /berita/
                        </span>
                        <input type="text" wire:model="slug" placeholder="slug-berita-ini"
                            class="flex-1 px-4 py-2 border border-[#dcd9d0] rounded-r-xl text-xs focus:outline-none focus:border-[#c8b282] bg-[#faf9f6] text-slate-600 font-mono">
                    </div>
                    @error('slug') <span class="text-red-500 text-[10px] block mt-1 font-bold">{{ $message }}</span> @enderror
                </div>

                <!-- Rich Text Editor (CKEditor 5) dengan Fitur Upload Gambar -->
                <div class="ck-block" wire:ignore
                    x-data="{
                        value: @entangle('content').live,
                        initEditor() {
                            if (window.CKEDITOR) {
                                const { 
                                    ClassicEditor, Essentials, Bold, Italic, Paragraph, Link, List, Heading, Alignment,
                                    Image, ImageToolbar, ImageCaption, ImageStyle, ImageResize, ImageInsert, ImageUpload, Base64UploadAdapter,
                                    Table, TableToolbar, MediaEmbed, SourceEditing
                                } = window.CKEDITOR;

                                ClassicEditor.create(this.$refs.editorContainer, {
                                    plugins: [ 
                                        Essentials, Bold, Italic, Paragraph, Link, List, Heading, Alignment,
                                        Image, ImageToolbar, ImageCaption, ImageStyle, ImageResize, ImageInsert, ImageUpload, Base64UploadAdapter,
                                        Table, TableToolbar, MediaEmbed, SourceEditing
                                    ],
                                    toolbar: [
                                        'undo', 'redo', '|',
                                        'heading', '|',
                                        'bold', 'italic', '|',
                                        'alignment', '|',
                                        'bulletedList', 'numberedList', '|',
                                        'link', 'insertImage', 'insertTable', 'mediaEmbed', '|',
                                        'sourceEditing'
                                    ],
                                    image: {
                                        toolbar: [
                                            'imageStyle:inline',
                                            'imageStyle:wrapText',
                                            'imageStyle:breakText',
                                            '|',
                                            'toggleImageCaption',
                                            'imageTextAlternative',
                                            '|',
                                            'resizeImage'
                                        ],
                                        resizeOptions: [
                                            { name: 'resizeImage:original', value: null, label: 'Ukuran Asli' },
                                            { name: 'resizeImage:25',       value: '25',  label: '25%' },
                                            { name: 'resizeImage:50',       value: '50',  label: '50%' },
                                            { name: 'resizeImage:75',       value: '75',  label: '75%' }
                                        ]
                                    },
                                    initialData: this.value || ''
                                }).then(editor => {
                                    editor.model.document.on('change:data', () => {
                                        this.value = editor.getData();
                                    });
                                }).catch(err => console.error('Gagal menginisialisasi editor:', err));
                            } else {
                                console.error('CKEDITOR object belum terdeteksi');
                            }
                        }
                    }"
                    x-init="initEditor()">
                    <label class="block text-[10px] font-bold uppercase tracking-wider text-[#8c887d] mb-1.5 font-display font-bold">Isi Berita Lengkap</label>
                    <div x-ref="editorContainer"></div>
                </div>
                @error('content') <span class="text-red-500 text-[10px] block mt-1 font-bold">{{ $message }}</span> @enderror
            </div>
        </div>

        {{-- RIGHT PANEL: Metadata, Featured Image, Status --}}
        <div class="space-y-6">
            
            <!-- Publish Status Panel -->
            <div class="bg-white border border-[#e5e2d9] rounded-2xl p-6 shadow-sm space-y-4">
                <h3 class="font-extrabold text-xs uppercase text-[#052c22] tracking-wider border-b border-[#e5e2d9] pb-3 mb-2">Penerbitan</h3>
                
                <!-- Status Switcher -->
                <div class="flex items-center justify-between p-3 bg-[#faf9f6] rounded-xl border border-[#e5e2d9]">
                    <span class="text-xs font-bold text-[#052c22]">Status Terbit</span>
                    <label class="relative inline-flex items-center cursor-pointer">
                        <input type="checkbox" wire:model="is_published" class="sr-only peer">
                        <div class="w-9 h-5 bg-slate-200 peer-focus:outline-none rounded-full peer peer-checked:after:translate-x-full peer-checked:after:border-white after:content-[''] after:absolute after:top-[2px] after:left-[2px] after:bg-white after:border-slate-300 after:border after:rounded-full after:h-4 after:w-4 after:transition-all peer-checked:bg-emerald-600"></div>
                    </label>
                </div>

                <!-- Waktu Publikasi -->
                <div>
                    <label class="block text-[10px] font-bold uppercase tracking-wider text-[#8c887d] mb-1.5">Jadwal Publikasi</label>
                    <input type="datetime-local" wire:model="published_at" 
                        class="w-full px-3 py-2 border border-[#dcd9d0] rounded-xl text-xs bg-[#faf9f6] focus:outline-none focus:border-[#c8b282] text-slate-700 font-medium">
                </div>

                <!-- Kutipan Berita (Excerpt) -->
                <div>
                    <label class="block text-[10px] font-bold uppercase tracking-wider text-[#8c887d] mb-1.5">Kutipan Singkat (Excerpt)</label>
                    <textarea wire:model="excerpt" rows="3" placeholder="Tuliskan ringkasan 1-2 kalimat untuk pratinjau berita..."
                        class="w-full px-3 py-2 border border-[#dcd9d0] rounded-xl text-xs bg-[#faf9f6] focus:outline-none focus:border-[#c8b282] text-slate-600 font-light leading-relaxed"></textarea>
                    <p class="text-[9px] text-[#8c887d] mt-1">Maksimal 500 karakter.</p>
                </div>
            </div>

            <!-- Author & Tags Panel -->
            <div class="bg-white border border-[#e5e2d9] rounded-2xl p-6 shadow-sm space-y-4">
                <h3 class="font-extrabold text-xs uppercase text-[#052c22] tracking-wider border-b border-[#e5e2d9] pb-3 mb-2">Penulis & Tag</h3>

                <!-- Author -->
                <div>
                    <label class="block text-[10px] font-bold uppercase tracking-wider text-[#8c887d] mb-1.5">Nama Penulis / Editor</label>
                    <input type="text" wire:model="author" placeholder="misal: Humas Kemahasiswaan"
                        class="w-full px-3 py-2 border border-[#dcd9d0] rounded-xl text-xs bg-[#faf9f6] focus:outline-none focus:border-[#c8b282] text-slate-700">
                </div>

                <!-- Tag -->
                <div>
                    <label class="block text-[10px] font-bold uppercase tracking-wider text-[#8c887d] mb-1.5">Kategori / Tag</label>
                    <input type="text" wire:model="tag" placeholder="misal: Pengumuman, Lomba, Beasiswa"
                        class="w-full px-3 py-2 border border-[#dcd9d0] rounded-xl text-xs bg-[#faf9f6] focus:outline-none focus:border-[#c8b282] text-slate-700">
                    <p class="text-[9px] text-[#8c887d] mt-1">Pisahkan dengan koma jika lebih dari satu.</p>
                </div>
            </div>

            <!-- Featured Image Panel -->
            <div class="bg-white border border-[#e5e2d9] rounded-2xl p-6 shadow-sm space-y-4">
                <h3 class="font-extrabold text-xs uppercase text-[#052c22] tracking-wider border-b border-[#e5e2d9] pb-3 mb-2">Gambar Sampul</h3>

                <!-- Preview Gambar -->
                <div class="relative w-full h-40 bg-[#faf9f6] rounded-xl border border-dashed border-[#dcd9d0] flex items-center justify-center overflow-hidden">
                    @if ($image_file)
                        <img src="{{ $image_file->temporaryUrl() }}" class="w-full h-full object-cover">
                    @elseif ($image_path)
                        <img src="{{ $image_path }}" class="w-full h-full object-cover">
                    @else
                        <div class="text-center text-[#8c887d] space-y-1">
                            <span class="text-2xl block">🖼️</span>
                            <span class="text-[10px] font-bold uppercase tracking-wider">Belum Ada Gambar</span>
                        </div>
                    @endif
                </div>

                <!-- Input File -->
                <div>
                    <input type="file" wire:model="image_file" class="w-full text-xs text-slate-500 file:mr-3 file:py-1.5 file:px-3 file:rounded-lg file:border file:border-[#e5e2d9] file:text-[11px] file:font-bold file:bg-[#faf9f6] file:text-[#052c22] hover:file:bg-[#e5e2d9]/30 file:cursor-pointer">
                    <p class="text-[9px] text-[#8c887d] mt-1.5">Rekomendasi rasio landscape (16:9), maksimal 2MB.</p>
                </div>
            </div>

        </div>

    </div>
</div>
