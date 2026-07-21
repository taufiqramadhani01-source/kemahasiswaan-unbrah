<div>
    <x-slot name="title">Kelola Kelompok Hak Akses - Dasbor Kemahasiswaan</x-slot>

    <!-- Page Header -->
    <div class="flex flex-col md:flex-row md:items-center justify-between gap-4 mb-8">
        <div>
            <span class="px-3 py-1 rounded-full border border-[#c8b282]/50 bg-[#faf9f6] text-[#c8b282] text-[10px] font-bold uppercase tracking-widest inline-block mb-3">Keamanan & Akses</span>
            <h1 class="text-2xl font-extrabold text-[#052c22] tracking-tight">Kelompok Hak Akses Menu</h1>
            <p class="text-xs text-[#5c5950] mt-1">Buat sejumlah kelompok/staf, lalu centang menu mana saja yang boleh mereka buka secara dinamis.</p>
        </div>
        <button wire:click="openForm" class="px-4 py-2 bg-[#052c22] hover:bg-[#073d30] text-[#c8b282] rounded-xl text-xs font-bold uppercase tracking-wider transition self-start md:self-auto">
            + Tambah Kelompok
        </button>
    </div>

    <!-- Flash Message Success -->
    @if (session()->has('message'))
        <div class="p-4 mb-6 bg-emerald-50 border border-emerald-100 text-emerald-800 text-xs font-bold rounded-xl flex items-center justify-between">
            <span>{{ session('message') }}</span>
            <button class="text-emerald-500 hover:text-emerald-800" onclick="this.parentElement.remove()">✕</button>
        </div>
    @endif

    <!-- Flash Message Error -->
    @if (session()->has('error'))
        <div class="p-4 mb-6 bg-red-50 border border-red-100 text-red-800 text-xs font-bold rounded-xl flex items-center justify-between">
            <span>{{ session('error') }}</span>
            <button class="text-red-500 hover:text-red-850" onclick="this.parentElement.remove()">✕</button>
        </div>
    @endif

    <!-- Filter & Search Bar -->
    <div class="bg-white border border-[#e5e2d9] rounded-2xl shadow-sm p-4 mb-6">
        <input type="text" wire:model.live.debounce.300ms="search" placeholder="Cari nama kelompok..."
            class="w-full md:max-w-md px-4 py-2 border border-[#dcd9d0] rounded-xl text-xs bg-[#faf9f6] focus:outline-none focus:border-[#c8b282] transition">
    </div>

    <!-- Data List Panel -->
    <div class="bg-white border border-[#e5e2d9] rounded-2xl shadow-sm overflow-hidden">
        <table class="w-full text-left border-collapse">
            <thead>
                <tr class="bg-[#faf9f6] text-[10px] font-bold uppercase tracking-wider text-[#8c887d] border-b border-[#e5e2d9]">
                    <th class="p-4">Nama Kelompok</th>
                    <th class="p-4">Deskripsi</th>
                    <th class="p-4">Menu yang Diakses</th>
                    <th class="p-4">Jumlah Pengguna</th>
                    <th class="p-4 text-right">Aksi</th>
                </tr>
            </thead>
            <tbody class="divide-y divide-[#e5e2d9]">
                @forelse ($groups as $group)
                    <tr class="text-xs text-[#1a2421] hover:bg-[#faf9f6]/40 transition">
                        <td class="p-4">
                            <span class="font-bold text-[#052c22] text-sm">{{ $group->name }}</span>
                        </td>
                        <td class="p-4 text-[#5c5950]">{{ $group->description ?: '—' }}</td>
                        <td class="p-4">
                            <div class="flex flex-wrap gap-1 max-w-lg">
                                @php $access = $group->accessible_menus ?? []; @endphp
                                @forelse($access as $mKey)
                                    @if(isset($menuOptions[$mKey]))
                                        <span class="px-2 py-0.5 rounded-full text-[9px] font-bold bg-[#faf9f6] border border-[#e5e2d9] text-[#5c5950] whitespace-nowrap">{{ $menuOptions[$mKey] }}</span>
                                    @endif
                                @empty
                                    <span class="text-[9px] text-red-500 font-bold uppercase tracking-wider">🔒 Akses Dikunci</span>
                                @endforelse
                            </div>
                        </td>
                        <td class="p-4 text-center font-bold text-[#052c22]">
                            <span class="px-2.5 py-0.5 rounded-full bg-emerald-50 text-emerald-800 text-[10px]">{{ $group->users()->count() }} User</span>
                        </td>
                        <td class="p-4 text-right space-x-1">
                            <button wire:click="openForm({{ $group->id }})" class="p-1.5 text-blue-600 hover:bg-blue-50 rounded-lg transition" title="Edit Hak Akses">✏️</button>
                            <button onclick="confirm('Hapus kelompok hak akses ini?') || event.stopImmediatePropagation()" wire:click="delete({{ $group->id }})" class="p-1.5 text-red-600 hover:bg-red-50 rounded-lg transition" title="Hapus Kelompok">🗑️</button>
                        </td>
                    </tr>
                @empty
                    <tr>
                        <td colspan="5" class="p-10 text-center text-[#8c887d]">
                            <p class="text-3xl mb-2">👥</p>
                            <p class="text-xs font-bold">Belum ada kelompok hak akses.</p>
                        </td>
                    </tr>
                @endforelse
            </tbody>
        </table>
    </div>

    <!-- Add/Edit Modal -->
    @if ($isFormOpen)
        <div class="fixed inset-0 bg-[#052c22]/30 backdrop-blur-sm flex items-center justify-center p-4 z-50">
            <div class="bg-white border border-[#e5e2d9] rounded-2xl w-full max-w-xl overflow-hidden shadow-xl max-h-[90vh] flex flex-col">

                <!-- Modal Header -->
                <div class="p-6 border-b border-[#e5e2d9] flex justify-between items-center bg-[#faf9f6]">
                    <h2 class="font-extrabold text-base text-[#052c22] uppercase tracking-wide">
                        {{ $groupId ? 'Edit Kelompok Akses' : 'Tambah Kelompok Baru' }}
                    </h2>
                    <button wire:click="closeForm" class="text-[#8c887d] hover:text-[#052c22]">✕</button>
                </div>

                <!-- Modal Body -->
                <div class="p-6 overflow-y-auto flex-1 space-y-4">
                    <!-- Nama Kelompok -->
                    <div>
                        <label class="block text-[10px] font-bold uppercase tracking-wider text-[#8c887d] mb-1.5">Nama Kelompok</label>
                        <input type="text" wire:model="name" placeholder="Contoh: Tim Humas, Staf Layanan Akademik"
                            class="w-full px-3 py-2 border border-[#dcd9d0] rounded-xl text-xs bg-white focus:outline-none focus:border-[#c8b282] transition">
                        @error('name') <p class="text-red-500 text-[10px] mt-1">{{ $message }}</p> @enderror
                    </div>

                    <!-- Deskripsi -->
                    <div>
                        <label class="block text-[10px] font-bold uppercase tracking-wider text-[#8c887d] mb-1.5">Deskripsi Singkat</label>
                        <input type="text" wire:model="description" placeholder="Penjelasan mengenai peran kelompok ini"
                            class="w-full px-3 py-2 border border-[#dcd9d0] rounded-xl text-xs bg-white focus:outline-none focus:border-[#c8b282] transition">
                        @error('description') <p class="text-red-500 text-[10px] mt-1">{{ $message }}</p> @enderror
                    </div>

                    <!-- Pilih Akses Menu -->
                    <div>
                        <label class="block text-[10px] font-bold uppercase tracking-wider text-[#8c887d] mb-3">Centang Menu yang Dapat Diakses</label>
                        <div class="grid grid-cols-1 md:grid-cols-2 gap-3 p-4 bg-[#faf9f6] rounded-2xl border border-[#e5e2d9]">
                            @foreach ($menuOptions as $key => $label)
                                <label class="flex items-center gap-2.5 text-xs text-[#1a2421] cursor-pointer hover:bg-slate-100/50 p-1.5 rounded transition">
                                    <input type="checkbox" wire:model="accessible_menus" value="{{ $key }}"
                                        class="rounded border-[#dcd9d0] text-[#052c22] focus:ring-[#c8b282]">
                                    <span>{{ $label }}</span>
                                </label>
                            @endforeach
                        </div>
                    </div>
                </div>

                <!-- Modal Footer -->
                <div class="p-6 border-t border-[#e5e2d9] bg-[#faf9f6] flex justify-end gap-3">
                    <button wire:click="closeForm" class="px-5 py-2 rounded-xl text-xs font-bold text-[#5c5950] bg-white border border-[#dcd9d0] hover:bg-gray-50 transition">Batal</button>
                    <button wire:click="save" class="px-5 py-2 rounded-xl text-xs font-bold text-[#c8b282] bg-[#052c22] hover:bg-[#073d30] transition">Simpan Kelompok</button>
                </div>
            </div>
        </div>
    @endif
</div>
