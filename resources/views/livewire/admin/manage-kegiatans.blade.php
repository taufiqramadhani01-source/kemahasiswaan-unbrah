<div>
    <x-slot name="title">Kelola Kegiatan - Dasbor Kemahasiswaan</x-slot>

    <!-- Page Header -->
    <div class="flex flex-col md:flex-row md:items-center justify-between gap-4 mb-8">
        <div>
            <span class="px-3 py-1 rounded-full border border-[#c8b282]/50 bg-[#faf9f6] text-[#c8b282] text-[10px] font-bold uppercase tracking-widest inline-block mb-3">Kemahasiswaan</span>
            <h1 class="text-2xl font-extrabold text-[#052c22] tracking-tight">Agenda Kegiatan Mahasiswa</h1>
            <p class="text-xs text-[#5c5950] mt-1">Kelola timeline kegiatan, event olahraga, pelatihan kepemimpinan, dan kompetisi.</p>
        </div>
        <button wire:click="openForm" class="px-4 py-2 bg-[#052c22] hover:bg-[#073d30] text-[#c8b282] rounded-xl text-xs font-bold uppercase tracking-wider transition self-start md:self-auto">
            + Tambah Kegiatan
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
            <input type="text" wire:model.live.debounce.300ms="search" placeholder="Cari berdasarkan judul kegiatan..." 
                class="w-full max-w-md px-4 py-2 border border-[#dcd9d0] rounded-xl text-xs bg-white focus:outline-none focus:border-[#c8b282] transition">
        </div>

        <table class="w-full text-left border-collapse">
            <thead>
                <tr class="bg-[#faf9f6] text-[10px] font-bold uppercase tracking-wider text-[#8c887d] border-b border-[#e5e2d9]">
                    <th class="p-4 w-24">Banner</th>
                    <th class="p-4">Detail Kegiatan</th>
                    <th class="p-4">Penyelenggara</th>
                    <th class="p-4">Waktu & Tempat</th>
                    <th class="p-4 w-32 text-right">Aksi</th>
                </tr>
            </thead>
            <tbody class="divide-y divide-[#e5e2d9]">
                @forelse ($kegiatans as $kegiatan)
                    <tr class="text-xs text-[#1a2421] hover:bg-[#faf9f6]/40 transition">
                        <td class="p-4">
                            @if ($kegiatan->banner_path)
                                <img src="{{ $kegiatan->banner_path }}" alt="Banner" class="w-16 h-10 rounded-lg object-cover border border-[#e5e2d9] bg-slate-100">
                            @else
                                <div class="w-16 h-10 rounded-lg bg-[#faf9f6] border border-[#e5e2d9] flex items-center justify-center font-bold text-[#8c887d] uppercase text-[9px]">
                                    No Image
                                </div>
                            @endif
                        </td>
                        <td class="p-4">
                            <p class="font-bold text-[#052c22]">{{ $kegiatan->title }}</p>
                            <p class="text-[10px] text-[#8c887d] font-mono mt-0.5">{{ $kegiatan->slug }}</p>
                        </td>
                        <td class="p-4">
                            @if ($kegiatan->ormawa)
                                <span class="px-2.5 py-0.5 rounded-full bg-[#052c22]/5 border border-[#052c22]/10 text-[#052c22] font-bold text-[9px] uppercase">
                                    {{ $kegiatan->ormawa->name }}
                                </span>
                            @else
                                <span class="text-[#8c887d] italic text-[10px]">Universitas (Umum)</span>
                            @endif
                        </td>
                        <td class="p-4">
                            <div class="space-y-0.5">
                                <p class="text-[#1a2421] font-semibold">📅 {{ $kegiatan->date ? $kegiatan->date->format('d M Y') : '-' }}</p>
                                <p class="text-[10px] text-[#5c5950]">📍 {{ $kegiatan->location ?? '-' }}</p>
                            </div>
                        </td>
                        <td class="p-4 text-right space-x-1">
                            <button wire:click="openForm({{ $kegiatan->id }})" class="p-1.5 text-blue-600 hover:bg-blue-50 rounded-lg transition" title="Edit">
                                ✏️
                            </button>
                            <button onclick="confirm('Hapus kegiatan ini?') || event.stopImmediatePropagation()" wire:click="delete({{ $kegiatan->id }})" class="p-1.5 text-red-600 hover:bg-red-50 rounded-lg transition" title="Hapus">
                                🗑️
                            </button>
                        </td>
                    </tr>
                @empty
                    <tr>
                        <td colspan="5" class="p-8 text-center text-[#8c887d]">Belum ada agenda kegiatan kemahasiswaan.</td>
                    </tr>
                @endforelse
            </tbody>
        </table>
        
        <!-- Pagination -->
        <div class="p-4 border-t border-[#e5e2d9]">
            {{ $kegiatans->links() }}
        </div>
    </div>

    <!-- Add/Edit Modal (Light Warm Theme) -->
    @if ($isFormOpen)
        <div class="fixed inset-0 bg-[#052c22]/30 backdrop-blur-sm flex items-center justify-center p-4 z-50 animate-fade-in">
            <div class="bg-white border border-[#e5e2d9] rounded-2xl w-full max-w-2xl overflow-hidden shadow-xl max-h-[90vh] flex flex-col">
                
                <!-- Modal Header -->
                <div class="p-6 border-b border-[#e5e2d9] flex justify-between items-center bg-[#faf9f6]">
                    <h2 class="font-extrabold text-base text-[#052c22] uppercase tracking-wide">
                        {{ $kegiatanId ? 'Edit Agenda Kegiatan' : 'Tambah Kegiatan Baru' }}
                    </h2>
                    <button wire:click="closeForm" class="text-[#8c887d] hover:text-[#052c22] text-sm">✕</button>
                </div>

                <!-- Modal Body (Scrollable) -->
                <div class="p-6 overflow-y-auto flex-1 space-y-6">
                    <!-- Title & Slug -->
                    <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                        <div>
                            <label class="block text-[10px] font-bold uppercase tracking-wider text-[#8c887d] mb-1.5">Judul Kegiatan</label>
                            <input type="text" wire:model.live="title" placeholder="misal: Latihan Kepemimpinan LKMM" 
                                class="w-full px-4 py-2 border border-[#dcd9d0] rounded-xl text-xs focus:outline-none focus:border-[#c8b282] bg-[#faf9f6]">
                        </div>
                        <div>
                            <label class="block text-[10px] font-bold uppercase tracking-wider text-[#8c887d] mb-1.5">Slug URL (Otomatis)</label>
                            <input type="text" wire:model="slug" placeholder="misal: lkmm-2026" readonly
                                class="w-full px-4 py-2 border border-[#e5e2d9] rounded-xl text-xs bg-slate-50 text-[#8c887d] focus:outline-none">
                        </div>
                    </div>

                    <!-- Date, Location & Organizer -->
                    <div class="grid grid-cols-1 md:grid-cols-3 gap-4">
                        <div>
                            <label class="block text-[10px] font-bold uppercase tracking-wider text-[#8c887d] mb-1.5">Tanggal Pelaksanaan</label>
                            <input type="date" wire:model="date" 
                                class="w-full px-4 py-2 border border-[#dcd9d0] rounded-xl text-xs focus:outline-none focus:border-[#c8b282] bg-[#faf9f6]">
                        </div>
                        <div>
                            <label class="block text-[10px] font-bold uppercase tracking-wider text-[#8c887d] mb-1.5">Lokasi / Tempat</label>
                            <input type="text" wire:model="location" placeholder="misal: Hall Rektorat Lt. 4" 
                                class="w-full px-4 py-2 border border-[#dcd9d0] rounded-xl text-xs focus:outline-none focus:border-[#c8b282] bg-[#faf9f6]">
                        </div>
                        <div>
                            <label class="block text-[10px] font-bold uppercase tracking-wider text-[#8c887d] mb-1.5">Penyelenggara (Ormawa)</label>
                            <select wire:model="ormawa_id" 
                                class="w-full px-4 py-2.5 border border-[#dcd9d0] rounded-xl text-xs focus:outline-none focus:border-[#c8b282] bg-[#faf9f6] text-[#1a2421]">
                                <option value="">-- Universitas (Umum) --</option>
                                @foreach ($ormawas as $o)
                                    <option value="{{ $o->id }}">{{ $o->name }}</option>
                                @endforeach
                            </select>
                        </div>
                    </div>

                    <!-- Banner Upload -->
                    <div>
                        <label class="block text-[10px] font-bold uppercase tracking-wider text-[#8c887d] mb-1.5">Banner / Poster Kegiatan</label>
                        <div class="flex items-center gap-4">
                            @if ($banner_file)
                                <img src="{{ $banner_file->temporaryUrl() }}" class="w-24 h-16 rounded-xl object-cover border border-[#e5e2d9] p-1 bg-white">
                            @elseif ($banner_path)
                                <img src="{{ $banner_path }}" class="w-24 h-16 rounded-xl object-cover border border-[#e5e2d9] p-1 bg-white">
                            @else
                                <div class="w-24 h-16 rounded-xl bg-[#faf9f6] border border-[#e5e2d9] flex items-center justify-center text-xs text-[#8c887d] uppercase text-[9px]">No Banner</div>
                            @endif

                            <div class="flex-1">
                                <input type="file" wire:model="banner_file" class="text-xs text-[#5c5950] file:mr-4 file:py-2 file:px-4 file:rounded-xl file:border file:border-[#e5e2d9] file:text-xs file:font-bold file:bg-[#faf9f6] file:text-[#052c22] hover:file:bg-[#e5e2d9]/30 file:cursor-pointer">
                                <p class="text-[10px] text-[#8c887d] mt-1">Format PNG, JPG maks 2MB.</p>
                            </div>
                        </div>
                    </div>

                    <!-- Description -->
                    <div>
                        <label class="block text-[10px] font-bold uppercase tracking-wider text-[#8c887d] mb-1.5">Deskripsi Lengkap Kegiatan</label>
                        <textarea wire:model="description" rows="5" placeholder="Tuliskan detail acara, syarat pendaftaran, atau timeline kegiatan..." 
                            class="w-full px-4 py-2 border border-[#dcd9d0] rounded-xl text-xs focus:outline-none focus:border-[#c8b282] bg-[#faf9f6]"></textarea>
                    </div>
                </div>

                <!-- Modal Footer -->
                <div class="p-6 border-t border-[#e5e2d9] flex justify-end gap-3 bg-[#faf9f6]">
                    <button type="button" wire:click="closeForm" class="px-4 py-2 border border-[#e5e2d9] hover:bg-slate-100 text-[#5c5950] rounded-xl text-xs font-bold transition">Batal</button>
                    <button type="button" wire:click="save" class="px-4 py-2 bg-[#052c22] hover:bg-[#073d30] text-[#c8b282] rounded-xl text-xs font-bold transition">Simpan Kegiatan</button>
                </div>

            </div>
        </div>
    @endif
</div>
