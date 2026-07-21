<div>
    <x-slot name="title">Kelola Ormawa - Dasbor Kemahasiswaan</x-slot>

    <!-- Page Header -->
    <div class="flex flex-col md:flex-row md:items-center justify-between gap-4 mb-8">
        <div>
            <span class="px-3 py-1 rounded-full border border-[#c8b282]/50 bg-[#faf9f6] text-[#c8b282] text-[10px] font-bold uppercase tracking-widest inline-block mb-3">Kemahasiswaan</span>
            <h1 class="text-2xl font-extrabold text-[#052c22] tracking-tight">Organisasi Mahasiswa (Ormawa)</h1>
            <p class="text-xs text-[#5c5950] mt-1">Kelola data lembaga eksekutif, legislatif, dan UKM tingkat Universitas Baiturrahmah.</p>
        </div>
        <button wire:click="openForm" class="px-4 py-2 bg-[#052c22] hover:bg-[#073d30] text-[#c8b282] rounded-xl text-xs font-bold uppercase tracking-wider transition self-start md:self-auto">
            + Tambah Ormawa
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
        <!-- Search bar -->
        <div class="p-4 border-b border-[#e5e2d9] bg-[#faf9f6]">
            <input type="text" wire:model.live.debounce.300ms="search" placeholder="Cari berdasarkan nama ormawa..." 
                class="w-full max-w-md px-4 py-2 border border-[#dcd9d0] rounded-xl text-xs bg-white focus:outline-none focus:border-[#c8b282] transition">
        </div>

        <table class="w-full text-left border-collapse">
            <thead>
                <tr class="bg-[#faf9f6] text-[10px] font-bold uppercase tracking-wider text-[#8c887d] border-b border-[#e5e2d9]">
                    <th class="p-4 w-16 text-center">Logo</th>
                    <th class="p-4">Nama Organisasi</th>
                    <th class="p-4">Slug</th>
                    <th class="p-4">Struktur</th>
                    <th class="p-4 w-32 text-right">Aksi</th>
                </tr>
            </thead>
            <tbody class="divide-y divide-[#e5e2d9]">
                @forelse ($ormawas as $ormawa)
                    <tr class="text-xs text-[#1a2421] hover:bg-[#faf9f6]/40 transition">
                        <td class="p-4 text-center">
                            {{-- Logo --}}
                            @if ($ormawa->logo_path)
                                <img src="{{ $ormawa->logo_path }}" alt="Logo" class="w-10 h-10 rounded-xl object-contain mx-auto border border-[#e5e2d9] bg-white p-1" title="Logo Organisasi">
                            @else
                                <div class="w-10 h-10 rounded-xl bg-[#faf9f6] border border-[#e5e2d9] flex items-center justify-center font-bold text-[#8c887d] text-[10px] uppercase mx-auto" title="Tanpa Logo">L</div>
                            @endif
                        </td>

                        <td class="p-4 font-bold text-[#052c22]">
                            {{ $ormawa->name }}
                        </td>
                        <td class="p-4 text-[#8c887d] font-mono">{{ $ormawa->slug }}</td>
                        <td class="p-4">
                            <span class="px-2 py-0.5 rounded-full bg-[#faf9f6] border border-[#e5e2d9] text-[#5c5950] font-semibold text-[10px]">
                                {{ count($ormawa->structure ?? []) }} Anggota Pengurus
                            </span>
                        </td>
                        <td class="p-4 text-right space-x-1">
                            <button wire:click="openForm({{ $ormawa->id }})" class="p-1.5 text-blue-600 hover:bg-blue-50 rounded-lg transition" title="Edit">
                                ✏️
                            </button>
                            <button onclick="confirm('Hapus organisasi ini?') || event.stopImmediatePropagation()" wire:click="delete({{ $ormawa->id }})" class="p-1.5 text-red-600 hover:bg-red-50 rounded-lg transition" title="Hapus">
                                🗑️
                            </button>
                        </td>
                    </tr>
                @empty
                    <tr>
                        <td colspan="5" class="p-8 text-center text-[#8c887d]">Belum ada organisasi mahasiswa.</td>
                    </tr>
                @endforelse
            </tbody>
        </table>
        
        <!-- Pagination -->
        <div class="p-4 border-t border-[#e5e2d9]">
            {{ $ormawas->links() }}
        </div>
    </div>

    <!-- Add/Edit Modal (Light Warm Theme matching reference UI) -->
    @if ($isFormOpen)
        <div class="fixed inset-0 bg-[#052c22]/30 backdrop-blur-sm flex items-center justify-center p-4 z-50 animate-fade-in">
            <div class="bg-white border border-[#e5e2d9] rounded-2xl w-full max-w-2xl overflow-hidden shadow-xl max-h-[90vh] flex flex-col">
                
                <!-- Modal Header -->
                <div class="p-6 border-b border-[#e5e2d9] flex justify-between items-center bg-[#faf9f6]">
                    <h2 class="font-extrabold text-base text-[#052c22] uppercase tracking-wide">
                        {{ $ormawaId ? 'Edit Organisasi' : 'Tambah Organisasi Baru' }}
                    </h2>
                    <button wire:click="closeForm" class="text-[#8c887d] hover:text-[#052c22] text-sm">✕</button>
                </div>

                <!-- Modal Body (Scrollable) -->
                <div class="p-6 overflow-y-auto flex-1 space-y-6">
                    <!-- Name & Slug -->
                    <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                        <div>
                            <label class="block text-[10px] font-bold uppercase tracking-wider text-[#8c887d] mb-1.5">Nama Organisasi</label>
                            <input type="text" wire:model.live="name" placeholder="misal: Badan Eksekutif Mahasiswa" 
                                class="w-full px-4 py-2 border border-[#dcd9d0] rounded-xl text-xs focus:outline-none focus:border-[#c8b282] bg-[#faf9f6]">
                        </div>
                        <div>
                            <label class="block text-[10px] font-bold uppercase tracking-wider text-[#8c887d] mb-1.5">Slug URL (Otomatis)</label>
                            <input type="text" wire:model="slug" placeholder="misal: bem" readonly
                                class="w-full px-4 py-2 border border-[#e5e2d9] rounded-xl text-xs bg-slate-50 text-[#8c887d] focus:outline-none">
                        </div>
                    </div>

                    <!-- Logo Upload -->
                    <div>
                        <label class="block text-[10px] font-bold uppercase tracking-wider text-[#8c887d] mb-1.5">Logo Organisasi</label>
                        <div class="flex items-center gap-4">
                            @if ($logo_file)
                                <img src="{{ $logo_file->temporaryUrl() }}" class="w-16 h-16 rounded-xl object-contain border border-[#e5e2d9] p-1 bg-white">
                            @elseif ($logo_path)
                                <img src="{{ $logo_path }}" class="w-16 h-16 rounded-xl object-contain border border-[#e5e2d9] p-1 bg-white">
                            @else
                                <div class="w-16 h-16 rounded-xl bg-[#faf9f6] border border-[#e5e2d9] flex items-center justify-center text-xs text-[#8c887d]">No Logo</div>
                            @endif

                            <div class="flex-1">
                                <input type="file" wire:model="logo_file" class="text-xs text-[#5c5950] file:mr-4 file:py-2 file:px-4 file:rounded-xl file:border file:border-[#e5e2d9] file:text-xs file:font-bold file:bg-[#faf9f6] file:text-[#052c22] hover:file:bg-[#e5e2d9]/30 file:cursor-pointer">
                                <p class="text-[10px] text-[#8c887d] mt-1">Format PNG, JPG maks 1MB.</p>
                            </div>
                        </div>
                    </div>



                    <!-- Description -->
                    <div>
                        <label class="block text-[10px] font-bold uppercase tracking-wider text-[#8c887d] mb-1.5">Deskripsi Singkat</label>
                        <textarea wire:model="description" rows="3" placeholder="Tuliskan visi singkat atau deskripsi ormawa..." 
                            class="w-full px-4 py-2 border border-[#dcd9d0] rounded-xl text-xs focus:outline-none focus:border-[#c8b282] bg-[#faf9f6]"></textarea>
                    </div>

                    <!-- Structure Member (JSON) -->
                    <div class="border-t border-[#e5e2d9] pt-6">
                        <div class="flex justify-between items-center mb-4">
                            <h3 class="font-bold text-xs uppercase text-[#052c22] tracking-wider">Susunan Pengurus Inti</h3>
                            <button type="button" wire:click="addStructureItem" class="text-[10px] font-bold text-[#c8b282] hover:text-[#b49d6c]">+ Tambah Jabatan</button>
                        </div>
                        
                        <div class="space-y-3">
                            @foreach ($structure as $index => $item)
                                <div class="flex items-center gap-3 bg-[#faf9f6] p-3 rounded-xl border border-[#e5e2d9]">
                                    
                                    {{-- Preview Foto Pengurus --}}
                                    <div class="shrink-0">
                                        @if (!empty($item['photo']))
                                            <img src="{{ $item['photo'] }}" class="w-10 h-10 rounded-full object-cover border border-[#e5e2d9]">
                                        @else
                                            <div class="w-10 h-10 rounded-full bg-[#e5e2d9] border border-[#dcd9d0] flex items-center justify-center text-[10px] text-[#8c887d] font-bold">Foto</div>
                                        @endif
                                    </div>

                                    {{-- Fields --}}
                                    <div class="flex-1 grid grid-cols-1 md:grid-cols-3 gap-3">
                                        <div>
                                            <input type="text" wire:model="structure.{{ $index }}.role" placeholder="Jabatan (misal: Presiden)" 
                                                class="w-full px-3 py-1.5 border border-[#dcd9d0] rounded-lg text-xs bg-white focus:outline-none focus:border-[#c8b282]">
                                        </div>
                                        <div>
                                            <input type="text" wire:model="structure.{{ $index }}.name" placeholder="Nama Lengkap" 
                                                class="w-full px-3 py-1.5 border border-[#dcd9d0] rounded-lg text-xs bg-white focus:outline-none focus:border-[#c8b282]">
                                        </div>
                                        
                                        {{-- File Upload Foto Profil Pengurus --}}
                                        <div class="flex items-center gap-2">
                                            <input type="file" wire:model.live="member_photos.{{ $index }}"
                                                class="w-full text-[10px] text-slate-500 file:mr-2 file:py-1 file:px-2.5 file:rounded-lg file:border-0 file:text-[10px] file:font-bold file:bg-[#e5e2d9]/40 file:text-[#052c22] hover:file:bg-[#e5e2d9]/70 cursor-pointer">
                                        </div>
                                    </div>
                                    <button type="button" wire:click="removeStructureItem({{ $index }})" class="text-red-500 hover:text-red-700 text-xs">🗑️</button>
                                </div>
                            @endforeach
                        </div>
                    </div>

                    <!-- Focus Items (JSON Array of Strings) -->
                    <div class="border-t border-[#e5e2d9] pt-6">
                        <div class="flex justify-between items-center mb-4">
                            <div>
                                <h3 class="font-bold text-xs uppercase text-[#052c22] tracking-wider">Fokus Program & Aktivitas</h3>
                                <p class="text-[9px] text-[#8c887d]">Poin-poin program kerja unggulan ormawa yang tampil di halaman depan.</p>
                            </div>
                            <button type="button" wire:click="addFocusItem" class="text-[10px] font-bold text-[#c8b282] hover:text-[#b49d6c]">+ Tambah Poin</button>
                        </div>
                        
                        <div class="space-y-2">
                            @foreach ($focus_items as $fIndex => $fItem)
                                <div class="flex items-center gap-2">
                                    <input type="text" wire:model="focus_items.{{ $fIndex }}" placeholder="Contoh: Mengawal advokasi hak mahasiswa" 
                                        class="flex-1 px-3 py-1.5 border border-[#dcd9d0] rounded-lg text-xs bg-white focus:outline-none focus:border-[#c8b282]">
                                    <button type="button" wire:click="removeFocusItem({{ $fIndex }})" class="text-red-500 hover:text-red-700 text-xs">✕</button>
                                </div>
                            @endforeach
                            @if(count($focus_items) === 0)
                                <p class="text-center text-[10px] text-slate-400 py-3 bg-[#faf9f6] rounded-xl border border-dashed border-[#e5e2d9]">Belum ada poin fokus. Klik "+ Tambah Poin" untuk mulai mengisi.</p>
                            @endif
                        </div>
                    </div>
                </div>

                <!-- Modal Footer -->
                <div class="p-6 border-t border-[#e5e2d9] flex justify-end gap-3 bg-[#faf9f6]">
                    <button type="button" wire:click="closeForm" class="px-4 py-2 border border-[#e5e2d9] hover:bg-slate-100 text-[#5c5950] rounded-xl text-xs font-bold transition">Batal</button>
                    <button type="button" wire:click="save" class="px-4 py-2 bg-[#052c22] hover:bg-[#073d30] text-[#c8b282] rounded-xl text-xs font-bold transition">Simpan Organisasi</button>
                </div>

            </div>
        </div>
    @endif
</div>
