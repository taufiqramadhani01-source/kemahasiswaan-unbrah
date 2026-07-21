<div>
    <x-slot name="title">Kelola Dokumen & Unduhan - Dasbor Kemahasiswaan</x-slot>

    <!-- Page Header -->
    <div class="flex flex-col md:flex-row md:items-center justify-between gap-4 mb-8">
        <div>
            <h1 class="text-2xl font-extrabold text-[#052c22] tracking-tight">Dokumen & Unduhan</h1>
            <p class="text-xs text-[#5c5950] mt-1">Kelola berkas unduhan dan dokumen panduan mahasiswa secara dinamis berdasarkan kategori.</p>
        </div>
        <button wire:click="openForm" class="px-4 py-2 bg-[#052c22] hover:bg-[#073d30] text-[#c8b282] rounded-xl text-xs font-bold uppercase tracking-wider transition self-start md:self-auto">
            + Tambah Dokumen
        </button>
    </div>

    <!-- Flash Message -->
    @if (session()->has('message'))
        <div class="p-4 mb-6 bg-emerald-50 border border-emerald-100 text-emerald-800 text-xs font-bold rounded-xl flex items-center justify-between">
            <span>{{ session('message') }}</span>
            <button class="text-emerald-500 hover:text-emerald-800" onclick="this.parentElement.remove()">✕</button>
        </div>
    @endif

    <!-- Filter & Search Bar -->
    <div class="bg-white border border-[#e5e2d9] rounded-2xl shadow-sm p-4 mb-6 flex flex-col md:flex-row gap-3 items-center">
        <input type="text" wire:model.live.debounce.300ms="search" placeholder="Cari judul dokumen..."
            class="w-full md:max-w-xs px-4 py-2 border border-[#dcd9d0] rounded-xl text-xs bg-[#faf9f6] focus:outline-none focus:border-[#c8b282] transition">
        <div class="flex flex-wrap gap-2">
            <button wire:click="$set('filterKategori', '')" class="px-3 py-1.5 rounded-lg text-[10px] font-bold transition {{ !$filterKategori ? 'bg-[#052c22] text-[#c8b282]' : 'bg-[#faf9f6] text-[#5c5950] border border-[#dcd9d0] hover:border-[#c8b282]' }}">Semua</button>
            @foreach($kategoris as $kat)
                <button wire:click="$set('filterKategori', '{{ $kat->id }}')" class="px-3 py-1.5 rounded-lg text-[10px] font-bold transition {{ $filterKategori == $kat->id ? 'bg-[#052c22] text-[#c8b282]' : 'bg-[#faf9f6] text-[#5c5950] border border-[#dcd9d0] hover:border-[#c8b282]' }}">{{ $kat->name }}</button>
            @endforeach
        </div>
    </div>

    <!-- Data List Panel -->
    <div class="bg-white border border-[#e5e2d9] rounded-2xl shadow-sm overflow-hidden">
        <table class="w-full text-left border-collapse">
            <thead>
                <tr class="bg-[#faf9f6] text-[10px] font-bold uppercase tracking-wider text-[#8c887d] border-b border-[#e5e2d9]">
                    <th class="p-4">Judul Dokumen</th>
                    <th class="p-4">Kategori</th>
                    <th class="p-4">Tipe</th>
                    <th class="p-4">Ukuran</th>
                    <th class="p-4">Status</th>
                    <th class="p-4 text-right">Aksi</th>
                </tr>
            </thead>
            <tbody class="divide-y divide-[#e5e2d9]">
                @forelse ($dokumens as $dok)
                    <tr class="text-xs text-[#1a2421] hover:bg-[#faf9f6]/40 transition">
                        <td class="p-4">
                            <div class="font-bold text-[#052c22]">{{ $dok->judul }}</div>
                            @if($dok->url_file)
                                <a href="{{ $dok->url_file }}" target="_blank" class="text-[9px] text-blue-500 hover:underline mt-0.5 block truncate max-w-[250px]">{{ $dok->url_file }}</a>
                            @endif
                        </td>
                        <td class="p-4">
                            <span class="px-2.5 py-0.5 rounded-full text-[9px] font-bold uppercase tracking-wider bg-[#faf9f6] border border-[#e5e2d9] text-[#5c5950]">
                                {{ $dok->kategoriRelation->name ?? 'Tanpa Kategori' }}
                            </span>
                        </td>
                        <td class="p-4 font-mono font-bold text-[#8c887d] uppercase">{{ $dok->tipe_file }}</td>
                        <td class="p-4 text-[#8c887d]">{{ $dok->ukuran_file ?: '—' }}</td>
                        <td class="p-4">
                            <button wire:click="toggleActive({{ $dok->id }})"
                                class="px-2.5 py-0.5 rounded-full text-[9px] font-bold uppercase tracking-wider border transition
                                {{ $dok->is_active ? 'bg-emerald-50 border-emerald-200 text-emerald-700' : 'bg-red-50 border-red-200 text-red-700' }}">
                                {{ $dok->is_active ? 'Aktif' : 'Non-aktif' }}
                            </button>
                        </td>
                        <td class="p-4 text-right space-x-1">
                            <button wire:click="openForm({{ $dok->id }})" class="p-1.5 text-blue-600 hover:bg-blue-50 rounded-lg transition" title="Edit">✏️</button>
                            <button onclick="confirm('Hapus dokumen ini?') || event.stopImmediatePropagation()" wire:click="delete({{ $dok->id }})" class="p-1.5 text-red-600 hover:bg-red-50 rounded-lg transition" title="Hapus">🗑️</button>
                        </td>
                    </tr>
                @empty
                    <tr>
                        <td colspan="6" class="p-10 text-center">
                            <div class="text-3xl mb-2">📥</div>
                            <p class="text-[#8c887d] text-xs font-bold">Belum ada dokumen yang ditambahkan.</p>
                            <p class="text-[#8c887d] text-[10px] mt-1">Klik <strong>"+ Tambah Dokumen"</strong> untuk mulai.</p>
                        </td>
                    </tr>
                @endforelse
            </tbody>
        </table>
    </div>

    <!-- Add/Edit Modal -->
    @if ($isFormOpen)
        <div class="fixed inset-0 bg-[#052c22]/30 backdrop-blur-sm flex items-center justify-center p-4 z-50">
            <div class="bg-white border border-[#e5e2d9] rounded-2xl w-full max-w-2xl overflow-hidden shadow-xl max-h-[90vh] flex flex-col">

                <!-- Modal Header -->
                <div class="p-6 border-b border-[#e5e2d9] flex justify-between items-center bg-[#faf9f6]">
                    <h2 class="font-extrabold text-base text-[#052c22] uppercase tracking-wide">
                        {{ $dokumenId ? 'Edit Dokumen' : 'Tambah Dokumen Baru' }}
                    </h2>
                    <button wire:click="closeForm" class="text-[#8c887d] hover:text-[#052c22]">✕</button>
                </div>

                <!-- Modal Body -->
                <div class="p-6 overflow-y-auto flex-1 space-y-5">

                    <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                        <!-- Kategori -->
                        <div>
                            <div class="flex justify-between items-center mb-1.5">
                                <label class="block text-[10px] font-bold uppercase tracking-wider text-[#8c887d]">Kategori</label>
                                <button wire:click="openKategoriForm" type="button" class="text-[9px] font-bold text-blue-600 hover:underline">+ Kategori Baru</button>
                            </div>
                            <select wire:model="dokumen_kategori_id" class="w-full px-3 py-2 border border-[#dcd9d0] rounded-xl text-xs bg-[#faf9f6] focus:outline-none focus:border-[#c8b282] transition">
                                @foreach($kategoris as $kat)
                                    <option value="{{ $kat->id }}">{{ $kat->name }}</option>
                                @endforeach
                            </select>
                            @error('dokumen_kategori_id') <p class="text-red-500 text-[10px] mt-1">{{ $message }}</p> @enderror
                        </div>

                        <!-- Tipe File -->
                        <div>
                            <label class="block text-[10px] font-bold uppercase tracking-wider text-[#8c887d] mb-1.5">Tipe Berkas</label>
                            <select wire:model="tipe_file" class="w-full px-3 py-2 border border-[#dcd9d0] rounded-xl text-xs bg-[#faf9f6] focus:outline-none focus:border-[#c8b282] transition">
                                <option value="pdf">PDF</option>
                                <option value="docx">Word (DOCX)</option>
                                <option value="xlsx">Excel (XLSX)</option>
                                <option value="link">Link Eksternal</option>
                            </select>
                        </div>
                    </div>

                    <!-- Judul -->
                    <div>
                        <label class="block text-[10px] font-bold uppercase tracking-wider text-[#8c887d] mb-1.5">Judul Dokumen</label>
                        <input type="text" wire:model="judul" placeholder="Contoh: Buku Pedoman Hasimaru 2026"
                            class="w-full px-3 py-2 border border-[#dcd9d0] rounded-xl text-xs bg-white focus:outline-none focus:border-[#c8b282] transition @error('judul') border-red-300 @enderror">
                        @error('judul') <p class="text-red-500 text-[10px] mt-1">{{ $message }}</p> @enderror
                    </div>

                    <!-- Deskripsi -->
                    <div>
                        <label class="block text-[10px] font-bold uppercase tracking-wider text-[#8c887d] mb-1.5">Deskripsi Singkat (Opsional)</label>
                        <textarea wire:model="deskripsi" rows="2" placeholder="Keterangan singkat tentang dokumen ini..."
                            class="w-full px-3 py-2 border border-[#dcd9d0] rounded-xl text-xs bg-white focus:outline-none focus:border-[#c8b282] transition"></textarea>
                    </div>

                    <!-- Upload Berkas Baru -->
                    <div class="p-4 bg-[#faf9f6] rounded-xl border border-[#e5e2d9] space-y-3">
                        <label class="block text-[10px] font-bold uppercase tracking-wider text-[#052c22]">Upload File (Maks. 10MB)</label>
                        <input type="file" wire:model="uploaded_file" class="text-xs file:mr-4 file:py-1.5 file:px-3 file:rounded-lg file:border-0 file:text-xs file:font-semibold file:bg-emerald-50 file:text-emerald-700 hover:file:bg-emerald-100">
                        <div wire:loading wire:target="uploaded_file" class="text-[10px] text-emerald-600">Sedang mengunggah...</div>
                        @error('uploaded_file') <p class="text-red-500 text-[10px] mt-1">{{ $message }}</p> @enderror
                        <p class="text-[9px] text-[#8c887d]">Mengunggah file akan otomatis mengisi URL, tipe, dan ukuran berkas.</p>
                    </div>

                    <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                        <!-- URL / Link File -->
                        <div>
                            <label class="block text-[10px] font-bold uppercase tracking-wider text-[#8c887d] mb-1.5">URL / Link File (Jika tidak upload)</label>
                            <input type="text" wire:model="url_file" placeholder="https://drive.google.com/..."
                                class="w-full px-3 py-2 border border-[#dcd9d0] rounded-xl text-xs bg-white focus:outline-none focus:border-[#c8b282] transition">
                            <p class="text-[9px] text-[#8c887d] mt-1">Tempel link Google Drive, OneDrive, atau URL langsung ke file.</p>
                        </div>

                        <!-- Ukuran File -->
                        <div>
                            <label class="block text-[10px] font-bold uppercase tracking-wider text-[#8c887d] mb-1.5">Ukuran File (Manual jika tidak upload)</label>
                            <input type="text" wire:model="ukuran_file" placeholder="Contoh: 4.8 MB"
                                class="w-full px-3 py-2 border border-[#dcd9d0] rounded-xl text-xs bg-white focus:outline-none focus:border-[#c8b282] transition">
                        </div>
                    </div>

                    <div class="flex items-center gap-4">
                        <label class="flex items-center gap-2 cursor-pointer text-xs font-bold text-[#5c5950]">
                            <input type="checkbox" wire:model="is_active" class="rounded border-[#dcd9d0] text-[#052c22] focus:ring-[#c8b282]">
                            Dokumen Aktif (Tampil di Halaman Publik)
                        </label>
                    </div>
                </div>

                <!-- Modal Footer -->
                <div class="p-6 border-t border-[#e5e2d9] bg-[#faf9f6] flex justify-end gap-3">
                    <button wire:click="closeForm" class="px-5 py-2 rounded-xl text-xs font-bold text-[#5c5950] bg-white border border-[#dcd9d0] hover:bg-gray-50 transition">Batal</button>
                    <button wire:click="save" class="px-5 py-2 rounded-xl text-xs font-bold text-[#c8b282] bg-[#052c22] hover:bg-[#073d30] transition">Simpan Dokumen</button>
                </div>
            </div>
        </div>
    @endif

    <!-- Modal Inline Kategori Baru -->
    @if ($isKategoriFormOpen)
        <div class="fixed inset-0 bg-[#052c22]/50 backdrop-blur-sm flex items-center justify-center p-4 z-[60]">
            <div class="bg-white border border-[#e5e2d9] rounded-2xl w-full max-w-sm overflow-hidden shadow-2xl">
                <div class="p-5 border-b border-[#e5e2d9] bg-[#faf9f6] flex justify-between items-center">
                    <h3 class="font-extrabold text-xs text-[#052c22] uppercase tracking-wide">Tambah Kategori Baru</h3>
                    <button wire:click="$set('isKategoriFormOpen', false)" class="text-[#8c887d] hover:text-[#052c22]">✕</button>
                </div>
                <div class="p-5 space-y-4">
                    <div>
                        <label class="block text-[10px] font-bold uppercase tracking-wider text-[#8c887d] mb-1.5">Nama Kategori</label>
                        <input type="text" wire:model="kategoriName" placeholder="Contoh: Surat Edaran"
                            class="w-full px-3 py-2 border border-[#dcd9d0] rounded-xl text-xs bg-white focus:outline-none focus:border-[#c8b282] transition">
                        @error('kategoriName') <p class="text-red-500 text-[10px] mt-1">{{ $message }}</p> @enderror
                    </div>
                </div>
                <div class="p-5 border-t border-[#e5e2d9] bg-[#faf9f6] flex justify-end gap-2">
                    <button wire:click="$set('isKategoriFormOpen', false)" class="px-4 py-2 border border-[#dcd9d0] text-[#5c5950] rounded-lg text-[10px] font-bold bg-white hover:bg-gray-50 transition">Batal</button>
                    <button wire:click="saveKategori" class="px-4 py-2 bg-[#052c22] text-[#c8b282] rounded-lg text-[10px] font-bold hover:bg-[#073d30] transition">Simpan Kategori</button>
                </div>
            </div>
        </div>
    @endif
</div>
