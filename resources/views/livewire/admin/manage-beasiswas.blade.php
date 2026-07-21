<div>
    <x-slot name="title">Kelola Beasiswa - Dasbor Kemahasiswaan</x-slot>

    <!-- Page Header -->
    <div class="flex flex-col md:flex-row md:items-center justify-between gap-4 mb-8">
        <div>
            <span class="px-3 py-1 rounded-full border border-[#c8b282]/50 bg-[#faf9f6] text-[#c8b282] text-[10px] font-bold uppercase tracking-widest inline-block mb-3">Kemahasiswaan</span>
            <h1 class="text-2xl font-extrabold text-[#052c22] tracking-tight">Program Beasiswa Mahasiswa</h1>
            <p class="text-xs text-[#5c5950] mt-1">Kelola data beasiswa pemerintah, LLDIKTI, internal kampus, maupun pihak swasta.</p>
        </div>
        <button wire:click="openForm" class="px-4 py-2 bg-[#052c22] hover:bg-[#073d30] text-[#c8b282] rounded-xl text-xs font-bold uppercase tracking-wider transition self-start md:self-auto">
            + Tambah Beasiswa
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
            <input type="text" wire:model.live.debounce.300ms="search" placeholder="Cari berdasarkan nama beasiswa..." 
                class="w-full max-w-md px-4 py-2 border border-[#dcd9d0] rounded-xl text-xs bg-white focus:outline-none focus:border-[#c8b282] transition">
        </div>

        <table class="w-full text-left border-collapse">
            <thead>
                <tr class="bg-[#faf9f6] text-[10px] font-bold uppercase tracking-wider text-[#8c887d] border-b border-[#e5e2d9]">
                    <th class="p-4">Nama Beasiswa</th>
                    <th class="p-4">Penyelenggara / Provider</th>
                    <th class="p-4">Syarat & Ketentuan</th>
                    <th class="p-4 w-32 text-center">Status</th>
                    <th class="p-4 w-32 text-right">Aksi</th>
                </tr>
            </thead>
            <tbody class="divide-y divide-[#e5e2d9]">
                @forelse ($beasiswas as $beasiswa)
                    <tr class="text-xs text-[#1a2421] hover:bg-[#faf9f6]/40 transition">
                        <td class="p-4 font-bold text-[#052c22]">
                            {{ $beasiswa->name }}
                        </td>
                        <td class="p-4 text-[#5c5950] font-semibold">
                            {{ $beasiswa->provider ?? 'Internal Universitas' }}
                        </td>
                        <td class="p-4 text-[#8c887d] line-clamp-1 max-w-xs mt-3">
                            {{ Str::limit($beasiswa->requirements, 60) }}
                        </td>
                        <td class="p-4 text-center">
                            <button wire:click="toggleStatus({{ $beasiswa->id }})" 
                                class="px-3 py-1 rounded-full text-[9px] font-bold uppercase tracking-wider transition border
                                {{ $beasiswa->is_active 
                                    ? 'bg-emerald-50 border-emerald-200 text-emerald-700' 
                                    : 'bg-red-50 border-red-200 text-red-600' }}">
                                {{ $beasiswa->is_active ? 'Aktif' : 'Tutup' }}
                            </button>
                        </td>
                        <td class="p-4 text-right space-x-1">
                            <button wire:click="openForm({{ $beasiswa->id }})" class="p-1.5 text-blue-600 hover:bg-blue-50 rounded-lg transition" title="Edit">
                                ✏️
                            </button>
                            <button onclick="confirm('Hapus beasiswa ini?') || event.stopImmediatePropagation()" wire:click="delete({{ $beasiswa->id }})" class="p-1.5 text-red-600 hover:bg-red-50 rounded-lg transition" title="Hapus">
                                🗑️
                            </button>
                        </td>
                    </tr>
                @empty
                    <tr>
                        <td colspan="5" class="p-8 text-center text-[#8c887d]">Belum ada program beasiswa yang diinputkan.</td>
                    </tr>
                @endforelse
            </tbody>
        </table>
        
        <!-- Pagination -->
        <div class="p-4 border-t border-[#e5e2d9]">
            {{ $beasiswas->links() }}
        </div>
    </div>

    <!-- Add/Edit Modal (Light Warm Theme) -->
    @if ($isFormOpen)
        <div class="fixed inset-0 bg-[#052c22]/30 backdrop-blur-sm flex items-center justify-center p-4 z-50 animate-fade-in">
            <div class="bg-white border border-[#e5e2d9] rounded-2xl w-full max-w-2xl overflow-hidden shadow-xl max-h-[90vh] flex flex-col">
                
                <!-- Modal Header -->
                <div class="p-6 border-b border-[#e5e2d9] flex justify-between items-center bg-[#faf9f6]">
                    <h2 class="font-extrabold text-base text-[#052c22] uppercase tracking-wide">
                        {{ $beasiswaId ? 'Edit Program Beasiswa' : 'Tambah Beasiswa Baru' }}
                    </h2>
                    <button wire:click="closeForm" class="text-[#8c887d] hover:text-[#052c22] text-sm">✕</button>
                </div>

                <!-- Modal Body (Scrollable) -->
                <div class="p-6 overflow-y-auto flex-1 space-y-6">
                    <!-- Name & Provider -->
                    <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                        <div>
                            <label class="block text-[10px] font-bold uppercase tracking-wider text-[#8c887d] mb-1.5">Nama Beasiswa</label>
                            <input type="text" wire:model="name" placeholder="misal: Beasiswa KIP-Kuliah" 
                                class="w-full px-4 py-2 border border-[#dcd9d0] rounded-xl text-xs focus:outline-none focus:border-[#c8b282] bg-[#faf9f6]">
                        </div>
                        <div>
                            <label class="block text-[10px] font-bold uppercase tracking-wider text-[#8c887d] mb-1.5">Penyelenggara / Provider</label>
                            <input type="text" wire:model="provider" placeholder="misal: LLDIKTI Wilayah X" 
                                class="w-full px-4 py-2 border border-[#dcd9d0] rounded-xl text-xs focus:outline-none focus:border-[#c8b282] bg-[#faf9f6]">
                        </div>
                    </div>

                    <!-- Active Status Toggle & URL Pendaftaran -->
                    <div class="grid grid-cols-1 md:grid-cols-2 gap-4 items-center">
                        <div class="flex items-center gap-2 bg-[#faf9f6] p-3 rounded-xl border border-[#e5e2d9]">
                            <input type="checkbox" wire:model="is_active" id="is_active_checkbox" class="w-4 h-4 text-[#052c22] focus:ring-0 rounded border-[#e5e2d9]">
                            <label for="is_active_checkbox" class="text-xs font-bold text-[#052c22] cursor-pointer">Buka Pendaftaran (Status Aktif)</label>
                        </div>
                        <div>
                            <label class="block text-[10px] font-bold uppercase tracking-wider text-[#8c887d] mb-1.5">Link Pendaftaran (Opsional)</label>
                            <input type="url" wire:model="registration_url" placeholder="misal: https://google.form/..." 
                                class="w-full px-4 py-2 border border-[#dcd9d0] rounded-xl text-xs focus:outline-none focus:border-[#c8b282] bg-[#faf9f6]">
                        </div>
                    </div>

                    <!-- Description -->
                    <div>
                        <label class="block text-[10px] font-bold uppercase tracking-wider text-[#8c887d] mb-1.5">Deskripsi Program</label>
                        <textarea wire:model="description" rows="3" placeholder="Tuliskan penjelasan umum tentang program beasiswa ini..." 
                            class="w-full px-4 py-2 border border-[#dcd9d0] rounded-xl text-xs focus:outline-none focus:border-[#c8b282] bg-[#faf9f6]"></textarea>
                    </div>

                    <!-- Requirements -->
                    <div>
                        <label class="block text-[10px] font-bold uppercase tracking-wider text-[#8c887d] mb-1.5">Persyaratan & Dokumen yang Dibutuhkan</label>
                        <textarea wire:model="requirements" rows="5" placeholder="Tuliskan syarat IPK, berkas KTP, surat rekomendasi dekan, dll..." 
                            class="w-full px-4 py-2 border border-[#dcd9d0] rounded-xl text-xs focus:outline-none focus:border-[#c8b282] bg-[#faf9f6]"></textarea>
                    </div>
                </div>

                <!-- Modal Footer -->
                <div class="p-6 border-t border-[#e5e2d9] flex justify-end gap-3 bg-[#faf9f6]">
                    <button type="button" wire:click="closeForm" class="px-4 py-2 border border-[#e5e2d9] hover:bg-slate-100 text-[#5c5950] rounded-xl text-xs font-bold transition">Batal</button>
                    <button type="button" wire:click="save" class="px-4 py-2 bg-[#052c22] hover:bg-[#073d30] text-[#c8b282] rounded-xl text-xs font-bold transition">Simpan Beasiswa</button>
                </div>

            </div>
        </div>
    @endif
</div>
