<div>
    <x-slot name="title">Kelola User Admin - Dasbor Kemahasiswaan</x-slot>

    <!-- Page Header -->
    <div class="flex flex-col md:flex-row md:items-center justify-between gap-4 mb-8">
        <div>
            <span class="px-3 py-1 rounded-full border border-[#c8b282]/50 bg-[#faf9f6] text-[#c8b282] text-[10px] font-bold uppercase tracking-widest inline-block mb-3">Keamanan & Akses</span>
            <h1 class="text-2xl font-extrabold text-[#052c22] tracking-tight">Manajemen User Admin</h1>
            <p class="text-xs text-[#5c5950] mt-1">Kelola akun administrator yang berhak mengakses dan mengubah konten website.</p>
        </div>
        <button wire:click="openForm" class="px-4 py-2 bg-[#052c22] hover:bg-[#073d30] text-[#c8b282] rounded-xl text-xs font-bold uppercase tracking-wider transition self-start md:self-auto">
            + Tambah Admin
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
        <input type="text" wire:model.live.debounce.300ms="search" placeholder="Cari berdasarkan nama atau email..."
            class="w-full md:max-w-md px-4 py-2 border border-[#dcd9d0] rounded-xl text-xs bg-[#faf9f6] focus:outline-none focus:border-[#c8b282] transition">
    </div>

    <!-- Data List Panel -->
    <div class="bg-white border border-[#e5e2d9] rounded-2xl shadow-sm overflow-hidden">
        <table class="w-full text-left border-collapse">
            <thead>
                <tr class="bg-[#faf9f6] text-[10px] font-bold uppercase tracking-wider text-[#8c887d] border-b border-[#e5e2d9]">
                    <th class="p-4">Nama Administrator</th>
                    <th class="p-4">Hak Akses (Role)</th>
                    <th class="p-4">Email</th>
                    <th class="p-4">Tanggal Dibuat</th>
                    <th class="p-4 text-right">Aksi</th>
                </tr>
            </thead>
            <tbody class="divide-y divide-[#e5e2d9]">
                @foreach ($users as $user)
                    <tr class="text-xs text-[#1a2421] hover:bg-[#faf9f6]/40 transition">
                        <td class="p-4">
                            <div class="flex items-center gap-3">
                                <div class="w-8 h-8 rounded-full bg-[#faf9f6] border border-[#e5e2d9] flex items-center justify-center font-bold text-[#052c22]">
                                    {{ strtoupper(substr($user->name, 0, 2)) }}
                                </div>
                                <div>
                                    <span class="font-bold text-[#052c22]">{{ $user->name }}</span>
                                    @if(auth()->id() == $user->id)
                                        <span class="ml-1.5 px-2 py-0.5 text-[8px] bg-emerald-50 text-emerald-700 font-extrabold uppercase rounded-full border border-emerald-200">Aktif (Anda)</span>
                                    @endif
                                </div>
                            </div>
                        </td>
                        <td class="p-4">
                            @if($user->role === 'superadmin')
                                <span class="px-2.5 py-0.5 rounded-full text-[9px] font-bold uppercase tracking-wider bg-red-50 border border-red-200 text-red-700">👑 Super Admin</span>
                            @elseif($user->group)
                                <span class="px-2.5 py-0.5 rounded-full text-[9px] font-bold uppercase tracking-wider bg-emerald-50 border border-emerald-200 text-emerald-700">👥 {{ $user->group->name }}</span>
                            @else
                                <span class="px-2.5 py-0.5 rounded-full text-[9px] font-bold uppercase tracking-wider bg-slate-100 border border-slate-200 text-slate-500">🔒 Tanpa Kelompok</span>
                            @endif
                        </td>
                        <td class="p-4 text-[#5c5950] font-medium">{{ $user->email }}</td>
                        <td class="p-4 text-[#8c887d]">{{ $user->created_at->format('d M Y') }}</td>
                        <td class="p-4 text-right space-x-1">
                            <button wire:click="openForm({{ $user->id }})" class="p-1.5 text-blue-600 hover:bg-blue-50 rounded-lg transition" title="Edit Akun / Reset Sandi">✏️</button>
                            @if(auth()->id() != $user->id)
                                <button onclick="confirm('Hapus akun administrator ini?') || event.stopImmediatePropagation()" wire:click="delete({{ $user->id }})" class="p-1.5 text-red-600 hover:bg-red-50 rounded-lg transition" title="Hapus Akun">🗑️</button>
                            @endif
                        </td>
                    </tr>
                @endforeach
            </tbody>
        </table>
    </div>

    <!-- Add/Edit User Modal -->
    @if ($isFormOpen)
        <div class="fixed inset-0 bg-[#052c22]/30 backdrop-blur-sm flex items-center justify-center p-4 z-50">
            <div class="bg-white border border-[#e5e2d9] rounded-2xl w-full max-w-md overflow-hidden shadow-xl flex flex-col">

                <!-- Modal Header -->
                <div class="p-6 border-b border-[#e5e2d9] flex justify-between items-center bg-[#faf9f6]">
                    <h2 class="font-extrabold text-base text-[#052c22] uppercase tracking-wide">
                        {{ $userId ? 'Edit Akun Admin' : 'Tambah Admin Baru' }}
                    </h2>
                    <button wire:click="closeForm" class="text-[#8c887d] hover:text-[#052c22]">✕</button>
                </div>

                <!-- Modal Body -->
                <div class="p-6 space-y-4">
                    <!-- Nama Lengkap -->
                    <div>
                        <label class="block text-[10px] font-bold uppercase tracking-wider text-[#8c887d] mb-1.5">Nama Lengkap</label>
                        <input type="text" wire:model="name" placeholder="Nama lengkap admin"
                            class="w-full px-3 py-2 border border-[#dcd9d0] rounded-xl text-xs bg-white focus:outline-none focus:border-[#c8b282] transition @error('name') border-red-300 @enderror">
                        @error('name') <p class="text-red-500 text-[10px] mt-1">{{ $message }}</p> @enderror
                    </div>

                    <!-- Email -->
                    <div>
                        <label class="block text-[10px] font-bold uppercase tracking-wider text-[#8c887d] mb-1.5">Alamat Email (Untuk Login)</label>
                        <input type="email" wire:model="email" placeholder="admin@unbrah.ac.id"
                            class="w-full px-3 py-2 border border-[#dcd9d0] rounded-xl text-xs bg-white focus:outline-none focus:border-[#c8b282] transition @error('email') border-red-300 @enderror">
                        @error('email') <p class="text-red-500 text-[10px] mt-1">{{ $message }}</p> @enderror
                    </div>

                    <!-- Role / Hak Akses -->
                    <div>
                        <label class="block text-[10px] font-bold uppercase tracking-wider text-[#8c887d] mb-1.5">Tipe Akun (Otoritas Sistem)</label>
                        <select wire:model.live="role" class="w-full px-3 py-2 border border-[#dcd9d0] rounded-xl text-xs bg-[#faf9f6] focus:outline-none focus:border-[#c8b282] transition">
                            <option value="superadmin">👑 Super Admin (Akses Penuh Seluruh Sistem)</option>
                            <option value="staf_layanan">🎓 Staf Unit Kerja (Akan Mengikuti Kelompok Hak Akses di Bawah)</option>
                        </select>
                        @error('role') <p class="text-red-500 text-[10px] mt-1">{{ $message }}</p> @enderror
                    </div>

                    <!-- Kelompok Hak Akses (Hanya muncul jika bukan Super Admin) -->
                    @if ($role !== 'superadmin')
                        <div>
                            <label class="block text-[10px] font-bold uppercase tracking-wider text-[#8c887d] mb-1.5">Pilih Kelompok Hak Akses Menu</label>
                            <select wire:model="group_id" class="w-full px-3 py-2 border border-[#dcd9d0] rounded-xl text-xs bg-white focus:outline-none focus:border-[#c8b282] transition @error('group_id') border-red-300 @enderror">
                                <option value="">— Pilih Kelompok Akses —</option>
                                @foreach ($groups as $g)
                                    <option value="{{ $g->id }}">{{ $g->name }}</option>
                                @endforeach
                            </select>
                            <p class="text-[9px] text-[#8c887d] mt-1">Staf ini hanya akan dapat mengakses menu-menu yang dicentang di dalam kelompok tersebut.</p>
                            @error('group_id') <p class="text-red-500 text-[10px] mt-1">{{ $message }}</p> @enderror
                        </div>
                    @endif

                    <!-- Sandi / Password -->
                    <div>
                        <label class="block text-[10px] font-bold uppercase tracking-wider text-[#8c887d] mb-1.5">
                            {{ $userId ? 'Sandi Baru (Kosongkan jika tidak diubah)' : 'Kata Sandi' }}
                        </label>
                        <input type="password" wire:model="password" placeholder="Minimal 6 karakter"
                            class="w-full px-3 py-2 border border-[#dcd9d0] rounded-xl text-xs bg-white focus:outline-none focus:border-[#c8b282] transition @error('password') border-red-300 @enderror">
                        @error('password') <p class="text-red-500 text-[10px] mt-1">{{ $message }}</p> @enderror
                    </div>

                    <!-- Konfirmasi Sandi / Password Confirmation -->
                    <div>
                        <label class="block text-[10px] font-bold uppercase tracking-wider text-[#8c887d] mb-1.5">Konfirmasi Kata Sandi</label>
                        <input type="password" wire:model="password_confirmation" placeholder="Ulangi kata sandi"
                            class="w-full px-3 py-2 border border-[#dcd9d0] rounded-xl text-xs bg-white focus:outline-none focus:border-[#c8b282] transition">
                    </div>
                </div>

                <!-- Modal Footer -->
                <div class="p-6 border-t border-[#e5e2d9] bg-[#faf9f6] flex justify-end gap-3">
                    <button wire:click="closeForm" class="px-5 py-2 rounded-xl text-xs font-bold text-[#5c5950] bg-white border border-[#dcd9d0] hover:bg-gray-50 transition">Batal</button>
                    <button wire:click="save" class="px-5 py-2 rounded-xl text-xs font-bold text-[#c8b282] bg-[#052c22] hover:bg-[#073d30] transition">Simpan Akun</button>
                </div>
            </div>
        </div>
    @endif
</div>
