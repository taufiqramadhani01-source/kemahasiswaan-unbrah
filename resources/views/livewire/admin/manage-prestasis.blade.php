<div>
    <x-slot name="title">Kelola Prestasi - Dasbor Kemahasiswaan</x-slot>

    <!-- Page Header -->
    <div class="flex flex-col md:flex-row md:items-center justify-between gap-4 mb-8">
        <div>
            <span class="px-3 py-1 rounded-full border border-[#c8b282]/50 bg-[#faf9f6] text-[#c8b282] text-[10px] font-bold uppercase tracking-widest inline-block mb-3">Kemahasiswaan</span>
            <h1 class="text-2xl font-extrabold text-[#052c22] tracking-tight">Prestasi Mahasiswa</h1>
            <p class="text-xs text-[#5c5950] mt-1">Kelola arsip rekam prestasi akademik, non-akademik, olahraga, seni tingkat nasional dan internasional.</p>
        </div>
        <button wire:click="openForm" class="px-4 py-2 bg-[#052c22] hover:bg-[#073d30] text-[#c8b282] rounded-xl text-xs font-bold uppercase tracking-wider transition self-start md:self-auto">
            + Tambah Prestasi
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
            <input type="text" wire:model.live.debounce.300ms="search" placeholder="Cari berdasarkan nama mahasiswa atau prestasi..." 
                class="w-full max-w-md px-4 py-2 border border-[#dcd9d0] rounded-xl text-xs bg-white focus:outline-none focus:border-[#c8b282] transition">
        </div>

        <table class="w-full text-left border-collapse">
            <thead>
                <tr class="bg-[#faf9f6] text-[10px] font-bold uppercase tracking-wider text-[#8c887d] border-b border-[#e5e2d9]">
                    <th class="p-4">Mahasiswa / Tim</th>
                    <th class="p-4">Nama Penghargaan / Kompetensi</th>
                    <th class="p-4">Tingkat / Level</th>
                    <th class="p-4">Tahun</th>
                    <th class="p-4 text-center">Sertifikat</th>
                    <th class="p-4 w-32 text-right">Aksi</th>
                </tr>
            </thead>
            <tbody class="divide-y divide-[#e5e2d9]">
                @forelse ($prestasis as $prestasi)
                    <tr class="text-xs text-[#1a2421] hover:bg-[#faf9f6]/40 transition">
                        <td class="p-4 font-bold text-[#052c22]">
                            {{ $prestasi->student_name }}
                        </td>
                        <td class="p-4 text-[#5c5950] font-semibold">
                            {{ $prestasi->title }}
                        </td>
                        <td class="p-4">
                            <span class="px-2.5 py-0.5 rounded-full text-[9px] font-bold uppercase tracking-wider border
                                {{ $prestasi->level === 'Internasional' 
                                    ? 'bg-purple-50 border-purple-200 text-purple-700' 
                                    : 'bg-[#faf9f6] border-[#e5e2d9] text-[#c8b282]' }}">
                                {{ $prestasi->level }}
                            </span>
                        </td>
                        <td class="p-4 font-mono font-bold text-[#5c5950]">
                            {{ $prestasi->year }}
                        </td>
                        <td class="p-4 text-center">
                            @if ($prestasi->certificate_path)
                                <a href="{{ $prestasi->certificate_path }}" target="_blank" class="text-blue-600 hover:underline font-bold text-[10px]">
                                    Unduh File 📎
                                </a>
                            @else
                                <span class="text-slate-400 italic text-[10px]">Tidak ada file</span>
                            @endif
                        </td>
                        <td class="p-4 text-right space-x-1">
                            <button wire:click="openForm({{ $prestasi->id }})" class="p-1.5 text-blue-600 hover:bg-blue-50 rounded-lg transition" title="Edit">
                                ✏️
                            </button>
                            <button onclick="confirm('Hapus data prestasi ini?') || event.stopImmediatePropagation()" wire:click="delete({{ $prestasi->id }})" class="p-1.5 text-red-600 hover:bg-red-50 rounded-lg transition" title="Hapus">
                                🗑️
                            </button>
                        </td>
                    </tr>
                @empty
                    <tr>
                        <td colspan="6" class="p-8 text-center text-[#8c887d]">Belum ada data prestasi mahasiswa yang diinputkan.</td>
                    </tr>
                @endforelse
            </tbody>
        </table>
        
        <!-- Pagination -->
        <div class="p-4 border-t border-[#e5e2d9]">
            {{ $prestasis->links() }}
        </div>
    </div>

    <!-- Add/Edit Modal (Light Warm Theme) -->
    @if ($isFormOpen)
        <div class="fixed inset-0 bg-[#052c22]/30 backdrop-blur-sm flex items-center justify-center p-4 z-50 animate-fade-in">
            <div class="bg-white border border-[#e5e2d9] rounded-2xl w-full max-w-2xl overflow-hidden shadow-xl max-h-[90vh] flex flex-col">
                
                <!-- Modal Header -->
                <div class="p-6 border-b border-[#e5e2d9] flex justify-between items-center bg-[#faf9f6]">
                    <h2 class="font-extrabold text-base text-[#052c22] uppercase tracking-wide">
                        {{ $prestasiId ? 'Edit Data Prestasi' : 'Tambah Prestasi Baru' }}
                    </h2>
                    <button wire:click="closeForm" class="text-[#8c887d] hover:text-[#052c22] text-sm">✕</button>
                </div>

                <!-- Modal Body (Scrollable) -->
                <div class="p-6 overflow-y-auto flex-1 space-y-6">
                    <!-- Student Name & Title -->
                    <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                        <div>
                            <label class="block text-[10px] font-bold uppercase tracking-wider text-[#8c887d] mb-1.5">Nama Mahasiswa / Anggota Tim</label>
                            <input type="text" wire:model="student_name" placeholder="misal: Doni Saputra & Tim" 
                                class="w-full px-4 py-2 border border-[#dcd9d0] rounded-xl text-xs focus:outline-none focus:border-[#c8b282] bg-[#faf9f6]">
                        </div>
                        <div>
                            <label class="block text-[10px] font-bold uppercase tracking-wider text-[#8c887d] mb-1.5">Nama Penghargaan / Pencapaian</label>
                            <input type="text" wire:model="title" placeholder="misal: Juara 1 Lomba Karya Tulis Nasional" 
                                class="w-full px-4 py-2 border border-[#dcd9d0] rounded-xl text-xs focus:outline-none focus:border-[#c8b282] bg-[#faf9f6]">
                        </div>
                    </div>

                    <!-- Level & Year -->
                    <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                        <div>
                            <label class="block text-[10px] font-bold uppercase tracking-wider text-[#8c887d] mb-1.5">Tingkat / Level</label>
                            <select wire:model="level" 
                                class="w-full px-4 py-2.5 border border-[#dcd9d0] rounded-xl text-xs focus:outline-none focus:border-[#c8b282] bg-[#faf9f6] text-[#1a2421]">
                                <option value="Nasional">Nasional</option>
                                <option value="Internasional">Internasional</option>
                                <option value="Regional">Regional</option>
                                <option value="Universitas">Universitas</option>
                            </select>
                        </div>
                        <div>
                            <label class="block text-[10px] font-bold uppercase tracking-wider text-[#8c887d] mb-1.5">Tahun Penghargaan</label>
                            <input type="number" wire:model="year" placeholder="misal: 2026" 
                                class="w-full px-4 py-2 border border-[#dcd9d0] rounded-xl text-xs focus:outline-none focus:border-[#c8b282] bg-[#faf9f6]">
                        </div>
                    </div>

                    <!-- Certificate File Upload -->
                    <div>
                        <label class="block text-[10px] font-bold uppercase tracking-wider text-[#8c887d] mb-1.5">Scan Sertifikat / Bukti Prestasi</label>
                        <div class="flex items-center gap-4">
                            @if ($certificate_path)
                                <div class="p-3 bg-[#faf9f6] border border-[#e5e2d9] rounded-xl text-xs text-[#052c22] font-semibold">
                                    File Tersimpan: <a href="{{ $certificate_path }}" target="_blank" class="text-blue-600 hover:underline">Download 📎</a>
                                </div>
                            @endif

                            <div class="flex-1">
                                <input type="file" wire:model="certificate_file" class="text-xs text-[#5c5950] file:mr-4 file:py-2 file:px-4 file:rounded-xl file:border file:border-[#e5e2d9] file:text-xs file:font-bold file:bg-[#faf9f6] file:text-[#052c22] hover:file:bg-[#e5e2d9]/30 file:cursor-pointer">
                                <p class="text-[10px] text-[#8c887d] mt-1">Format PDF, PNG, atau JPG maks 2MB.</p>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Modal Footer -->
                <div class="p-6 border-t border-[#e5e2d9] flex justify-end gap-3 bg-[#faf9f6]">
                    <button type="button" wire:click="closeForm" class="px-4 py-2 border border-[#e5e2d9] hover:bg-slate-100 text-[#5c5950] rounded-xl text-xs font-bold transition">Batal</button>
                    <button type="button" wire:click="save" class="px-4 py-2 bg-[#052c22] hover:bg-[#073d30] text-[#c8b282] rounded-xl text-xs font-bold transition">Simpan Prestasi</button>
                </div>

            </div>
        </div>
    @endif
</div>
