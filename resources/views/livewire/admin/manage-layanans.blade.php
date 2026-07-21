<div>
    <x-slot name="title">Kelola Layanan Mahasiswa - Dasbor Kemahasiswaan</x-slot>

    <!-- Page Header -->
    <div class="flex flex-col md:flex-row md:items-center justify-between gap-4 mb-8">
        <div>
            <span class="px-3 py-1 rounded-full border border-[#c8b282]/50 bg-[#faf9f6] text-[#c8b282] text-[10px] font-bold uppercase tracking-widest inline-block mb-3">Kemahasiswaan</span>
            <h1 class="text-2xl font-extrabold text-[#052c22] tracking-tight">Layanan Mahasiswa</h1>
            <p class="text-xs text-[#5c5950] mt-1">Kelola konten dan deskripsi layanan kemahasiswaan (Beasiswa, Psikologi, P2K2, dll).</p>
        </div>
        <button wire:click="openForm" class="px-4 py-2 bg-[#052c22] hover:bg-[#073d30] text-[#c8b282] rounded-xl text-xs font-bold uppercase tracking-wider transition self-start md:self-auto">
            + Tambah Layanan
        </button>
    </div>

    <!-- Flash Message -->
    @if (session()->has('message'))
        <div class="p-4 mb-6 bg-emerald-50 border border-emerald-100 text-emerald-800 text-xs font-bold rounded-xl flex items-center justify-between">
            <span>{{ session('message') }}</span>
            <button class="text-emerald-500 hover:text-emerald-800" onclick="this.parentElement.remove()">✕</button>
        </div>
    @endif

    <!-- Data List Panel -->
    <div class="bg-white border border-[#e5e2d9] rounded-2xl shadow-sm overflow-hidden">
        <div class="p-4 border-b border-[#e5e2d9] bg-[#faf9f6]">
            <h2 class="text-xs font-bold uppercase tracking-wider text-[#052c22]">Daftar Layanan Tersedia</h2>
        </div>

        <table class="w-full text-left border-collapse">
            <thead>
                <tr class="bg-[#faf9f6] text-[10px] font-bold uppercase tracking-wider text-[#8c887d] border-b border-[#e5e2d9]">
                    <th class="p-4 w-16 text-center">Urutan</th>
                    <th class="p-4">Ikon</th>
                    <th class="p-4">Nama Layanan</th>
                    <th class="p-4">Status</th>
                    <th class="p-4 text-right">Aksi</th>
                </tr>
            </thead>
            <tbody class="divide-y divide-[#e5e2d9]">
                @forelse ($layanans as $layanan)
                    <tr class="text-xs text-[#1a2421] hover:bg-[#faf9f6]/40 transition">
                        <td class="p-4 text-center">
                            <div class="flex flex-col items-center gap-1">
                                <button wire:click="moveUp({{ $layanan->id }})" class="text-[#8c887d] hover:text-[#052c22]">▲</button>
                                <span class="font-bold text-[#5c5950]">{{ $layanan->order }}</span>
                                <button wire:click="moveDown({{ $layanan->id }})" class="text-[#8c887d] hover:text-[#052c22]">▼</button>
                            </div>
                        </td>
                        <td class="p-4 text-xl">
                            {{ $layanan->icon ?: '📌' }}
                        </td>
                        <td class="p-4">
                            <div class="font-bold text-[#052c22] text-sm">{{ $layanan->title }}</div>
                            <div class="text-[10px] text-gray-500 mt-1">Slug: /layanan?tab={{ $layanan->slug }}</div>
                        </td>
                        <td class="p-4">
                            <button wire:click="toggleActive({{ $layanan->id }})" 
                                class="px-2.5 py-0.5 rounded-full text-[9px] font-bold uppercase tracking-wider border transition
                                {{ $layanan->is_active ? 'bg-emerald-50 border-emerald-200 text-emerald-700' : 'bg-red-50 border-red-200 text-red-700' }}">
                                {{ $layanan->is_active ? 'Aktif' : 'Non-aktif' }}
                            </button>
                        </td>
                        <td class="p-4 text-right space-x-1">
                            <button wire:click="openForm({{ $layanan->id }})" class="p-1.5 text-blue-600 hover:bg-blue-50 rounded-lg transition" title="Edit">
                                ✏️
                            </button>
                            <button onclick="confirm('Hapus layanan ini?') || event.stopImmediatePropagation()" wire:click="delete({{ $layanan->id }})" class="p-1.5 text-red-600 hover:bg-red-50 rounded-lg transition" title="Hapus">
                                🗑️
                            </button>
                        </td>
                    </tr>
                @empty
                    <tr>
                        <td colspan="5" class="p-8 text-center text-[#8c887d]">Belum ada layanan mahasiswa yang diinputkan.</td>
                    </tr>
                @endforelse
            </tbody>
        </table>
    </div>

    <!-- Add/Edit Modal (Light Warm Theme) -->
    @if ($isFormOpen)
        <div class="fixed inset-0 bg-[#052c22]/30 backdrop-blur-sm flex items-center justify-center p-4 z-50 animate-fade-in">
            <div class="bg-white border border-[#e5e2d9] rounded-2xl w-full max-w-3xl overflow-hidden shadow-xl max-h-[90vh] flex flex-col">
                
                <!-- Modal Header -->
                <div class="p-6 border-b border-[#e5e2d9] flex justify-between items-center bg-[#faf9f6]">
                    <h2 class="font-extrabold text-base text-[#052c22] uppercase tracking-wide">
                        {{ $layananId ? 'Edit Layanan' : 'Tambah Layanan Baru' }}
                    </h2>
                    <button wire:click="closeForm" class="text-[#8c887d] hover:text-[#052c22] text-sm">✕</button>
                </div>

                <!-- Modal Body (Scrollable) -->
                <div class="p-6 overflow-y-auto flex-1 space-y-6">
                    <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                        <div>
                            <label class="block text-[10px] font-bold uppercase tracking-wider text-[#8c887d] mb-1.5">Nama Layanan</label>
                            <input type="text" wire:model.live="title" placeholder="Contoh: Beasiswa Mahasiswa" 
                                class="w-full px-4 py-2 border border-[#dcd9d0] rounded-xl text-xs focus:outline-none focus:border-[#c8b282] bg-white @error('title') border-red-300 @enderror">
                            @error('title') <p class="text-red-500 text-[10px] mt-1">{{ $message }}</p> @enderror
                        </div>
                        <div>
                            <label class="block text-[10px] font-bold uppercase tracking-wider text-[#8c887d] mb-1.5">Slug (Otomatis)</label>
                            <input type="text" wire:model="slug" placeholder="beasiswa-mahasiswa" 
                                class="w-full px-4 py-2 border border-[#dcd9d0] rounded-xl text-xs focus:outline-none focus:border-[#c8b282] bg-[#faf9f6] @error('slug') border-red-300 @enderror">
                            @error('slug') <p class="text-red-500 text-[10px] mt-1">{{ $message }}</p> @enderror
                        </div>
                    </div>

                    <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                        <div>
                            <label class="block text-[10px] font-bold uppercase tracking-wider text-[#8c887d] mb-1.5">Kategori Layanan</label>
                            <input type="text" wire:model="category" placeholder="Contoh: Kemahasiswaan Universitas Baiturrahmah" 
                                class="w-full px-4 py-2 border border-[#dcd9d0] rounded-xl text-xs focus:outline-none focus:border-[#c8b282] bg-white @error('category') border-red-300 @enderror">
                            @error('category') <p class="text-red-500 text-[10px] mt-1">{{ $message }}</p> @enderror
                        </div>
                        <div>
                            <label class="block text-[10px] font-bold uppercase tracking-wider text-[#8c887d] mb-1.5">Tarif Layanan</label>
                            <input type="text" wire:model="fee" placeholder="Contoh: Gratis / Rp 50.000" 
                                class="w-full px-4 py-2 border border-[#dcd9d0] rounded-xl text-xs focus:outline-none focus:border-[#c8b282] bg-white @error('fee') border-red-300 @enderror">
                            @error('fee') <p class="text-red-500 text-[10px] mt-1">{{ $message }}</p> @enderror
                        </div>
                    </div>
                    <div class="grid grid-cols-1 md:grid-cols-3 gap-4">
                        <div>
                            <label class="block text-[10px] font-bold uppercase tracking-wider text-[#8c887d] mb-1.5">Ikon (Emoji / Teks Singkat)</label>
                            <input type="text" wire:model="icon" placeholder="Contoh: 🎓 atau 🧠" 
                                class="w-full px-4 py-2 border border-[#dcd9d0] rounded-xl text-xs focus:outline-none focus:border-[#c8b282] bg-white @error('icon') border-red-300 @enderror">
                            @error('icon') <p class="text-red-500 text-[10px] mt-1">{{ $message }}</p> @enderror
                        </div>
                        <div>
                            <label class="block text-[10px] font-bold uppercase tracking-wider text-[#8c887d] mb-1.5">No. WhatsApp Kontak (Opsional)</label>
                            <input type="text" wire:model="whatsapp_number" placeholder="Contoh: 628123456789" 
                                class="w-full px-4 py-2 border border-[#dcd9d0] rounded-xl text-xs focus:outline-none focus:border-[#c8b282] bg-white @error('whatsapp_number') border-red-300 @enderror">
                            <p class="text-[9px] text-[#8c887d] mt-1">Kosongkan = pakai WhatsApp Kemahasiswaan utama.</p>
                            @error('whatsapp_number') <p class="text-red-500 text-[10px] mt-1">{{ $message }}</p> @enderror
                        </div>
                        <div class="flex items-center mt-5">
                            <label class="flex items-center gap-2 cursor-pointer text-xs font-bold text-[#5c5950]">
                                <input type="checkbox" wire:model="is_active" class="rounded border-[#dcd9d0] text-[#052c22] focus:ring-[#c8b282]">
                                Layanan Aktif (Ditampilkan)
                            </label>
                        </div>
                    </div>


                    <!-- Visual Rich Text Editor (CKEditor 5 Lokal) dengan Fitur Upload Gambar -->
                    <div wire:ignore
                        x-data="{
                            value: @entangle('content').live,
                            initEditor() {
                                if (window.CKEDITOR) {
                                    const { 
                                        ClassicEditor, Essentials, Bold, Italic, Paragraph, Link, List, Heading, Alignment,
                                        Image, ImageToolbar, ImageCaption, ImageStyle, ImageResize, ImageInsert, ImageUpload, Base64UploadAdapter
                                    } = window.CKEDITOR;

                                    ClassicEditor.create(this.$refs.layananEditor, {
                                        plugins: [ 
                                            Essentials, Bold, Italic, Paragraph, Link, List, Heading, Alignment,
                                            Image, ImageToolbar, ImageCaption, ImageStyle, ImageResize, ImageInsert, ImageUpload, Base64UploadAdapter
                                        ],
                                        toolbar: [
                                            'undo', 'redo', '|',
                                            'heading', '|',
                                            'bold', 'italic', '|',
                                            'alignment', '|',
                                            'bulletedList', 'numberedList', '|',
                                            'link', 'insertImage'
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
                                            ]
                                        },
                                        initialData: this.value || ''
                                    }).then(editor => {
                                        editor.model.document.on('change:data', () => {
                                            this.value = editor.getData();
                                        });
                                    }).catch(err => console.error(err));
                                }
                            }
                        }"
                        x-init="initEditor()">
                        <label class="block text-[10px] font-bold uppercase tracking-wider text-[#8c887d] mb-1.5 font-display">Isi Konten Layanan (Visual Editor)</label>
                        <div x-ref="layananEditor" style="min-height: 320px;"></div>
                    </div>
                    @error('content') <p class="text-red-500 text-[10px] mt-1">{{ $message }}</p> @enderror
                </div>

                <!-- Modal Footer -->
                <div class="p-6 border-t border-[#e5e2d9] bg-[#faf9f6] flex justify-end gap-3">
                    <button wire:click="closeForm" class="px-5 py-2 rounded-xl text-xs font-bold text-[#5c5950] bg-white border border-[#dcd9d0] hover:bg-gray-50 transition">
                        Batal
                    </button>
                    <button wire:click="save" class="px-5 py-2 rounded-xl text-xs font-bold text-[#c8b282] bg-[#052c22] hover:bg-[#073d30] transition">
                        Simpan Data
                    </button>
                </div>
            </div>
        </div>
    @endif
</div>

<style>
    /* Perbesar area tulis CKEditor di modal Kelola Layanan */
    .ck-editor__editable {
        min-height: 300px !important;
    }
</style>
