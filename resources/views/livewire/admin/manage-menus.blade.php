<div>
    <x-slot name="title">Navigasi Menu - Dasbor Kemahasiswaan</x-slot>

    {{-- Page Header --}}
    <div class="flex flex-col md:flex-row md:items-center justify-between gap-4 mb-8">
        <div>
            <span class="px-3 py-1 rounded-full border border-[#c8b282]/50 bg-[#faf9f6] text-[#c8b282] text-[10px] font-bold uppercase tracking-widest inline-block mb-3">CMS</span>
            <h1 class="text-2xl font-extrabold text-[#052c22] tracking-tight">Navigasi Menu</h1>
            <p class="text-xs text-[#5c5950] mt-1">Susun struktur navigasi situs. Menu dapat bertingkat (parent → child).</p>
        </div>
        <button wire:click="openForm" class="px-4 py-2 bg-[#052c22] hover:bg-[#073d30] text-[#c8b282] rounded-xl text-xs font-bold uppercase tracking-wider transition self-start md:self-auto">
            + Tambah Menu
        </button>
    </div>

    {{-- Flash Message --}}
    @if (session()->has('message'))
        <div class="p-4 mb-6 bg-emerald-50 border border-emerald-100 text-emerald-800 text-xs font-bold rounded-xl flex items-center justify-between">
            <span>{{ session('message') }}</span>
            <button class="text-emerald-500 hover:text-emerald-800" onclick="this.parentElement.remove()">✕</button>
        </div>
    @endif

    <div class="grid grid-cols-1 lg:grid-cols-2 gap-6">

        {{-- LEFT: Menu Tree --}}
        <div class="bg-white border border-[#e5e2d9] rounded-2xl shadow-sm overflow-hidden">
            <div class="p-4 border-b border-[#e5e2d9] bg-[#faf9f6]">
                <h2 class="text-xs font-bold uppercase tracking-wider text-[#052c22]">Struktur Navigasi</h2>
                <p class="text-[10px] text-[#8c887d] mt-0.5">Urutan & hierarki menu yang akan tampil di frontend.</p>
            </div>

            <div class="p-4 space-y-2">
                @forelse ($menus as $menu)
                    {{-- Parent Item --}}
                    <div class="border border-[#e5e2d9] rounded-xl overflow-hidden">
                        <div class="flex items-center gap-3 p-3 bg-[#faf9f6]">
                            <span class="w-6 h-6 rounded-lg bg-[#052c22] text-white flex items-center justify-center text-[9px] font-bold">{{ $menu->order }}</span>
                            <div class="flex-1 min-w-0">
                                <p class="text-xs font-bold text-[#052c22] truncate">{{ $menu->title }}</p>
                                <p class="text-[10px] text-[#8c887d] truncate">
                                    @if ($menu->type === 'page' && $menu->page)
                                        📄 /{{ $menu->page->slug }}
                                    @elseif ($menu->type === 'external')
                                        🔗 {{ $menu->external_url }}
                                    @else
                                        <span class="text-amber-500">⚠ Tidak ada target</span>
                                    @endif
                                </p>
                            </div>
                            <div class="flex items-center gap-1 shrink-0">
                                {{-- Urutan --}}
                                <button wire:click="moveUp({{ $menu->id }})" title="Pindah ke atas"
                                    class="px-2 py-1 text-[9px] border border-[#e5e2d9] hover:bg-[#052c22] hover:text-[#c8b282] text-[#5c5950] rounded-lg transition font-bold">↑</button>
                                <button wire:click="moveDown({{ $menu->id }})" title="Pindah ke bawah"
                                    class="px-2 py-1 text-[9px] border border-[#e5e2d9] hover:bg-[#052c22] hover:text-[#c8b282] text-[#5c5950] rounded-lg transition font-bold">↓</button>
                                <button wire:click="openChildForm({{ $menu->id }})" title="Tambah sub-menu"
                                    class="px-2 py-1 text-[9px] border border-[#e5e2d9] hover:bg-[#e5e2d9]/30 text-[#5c5950] rounded-lg transition">+ Sub</button>
                                <button wire:click="openForm({{ $menu->id }})" title="Edit"
                                    class="px-2 py-1 text-[9px] border border-[#e5e2d9] hover:bg-[#faf9f6] text-[#052c22] rounded-lg transition">Edit</button>
                                <button wire:click="delete({{ $menu->id }})" wire:confirm="Hapus menu '{{ $menu->title }}'? Sub-menu juga akan terhapus."
                                    class="px-2 py-1 text-[9px] border border-red-100 hover:bg-red-50 text-red-400 rounded-lg transition">✕</button>
                            </div>
                        </div>

                        {{-- Children --}}
                        @if ($menu->children->isNotEmpty())
                            <div class="divide-y divide-[#e5e2d9] border-t border-[#e5e2d9]">
                                @foreach ($menu->children->sortBy('order') as $child)
                                    <div class="flex items-center gap-3 px-4 py-2.5 pl-8 hover:bg-[#faf9f6]/50 transition">
                                        <span class="text-[#c8b282] text-xs">↳</span>
                                        <div class="flex-1 min-w-0">
                                            <p class="text-xs text-[#1a2421] font-semibold truncate">{{ $child->title }}</p>
                                            <p class="text-[10px] text-[#8c887d] truncate">
                                                @if ($child->type === 'page' && $child->page)
                                                    📄 /{{ $child->page->slug }}
                                                @elseif ($child->type === 'external')
                                                    🔗 {{ $child->external_url }}
                                                @endif
                                            </p>
                                        </div>
                                        <div class="flex gap-1">
                                            <button wire:click="moveUp({{ $child->id }})" title="Pindah ke atas"
                                                class="px-2 py-1 text-[9px] border border-[#e5e2d9] hover:bg-[#052c22] hover:text-[#c8b282] text-[#5c5950] rounded-lg transition font-bold">↑</button>
                                            <button wire:click="moveDown({{ $child->id }})" title="Pindah ke bawah"
                                                class="px-2 py-1 text-[9px] border border-[#e5e2d9] hover:bg-[#052c22] hover:text-[#c8b282] text-[#5c5950] rounded-lg transition font-bold">↓</button>
                                            <button wire:click="openForm({{ $child->id }})" class="px-2 py-1 text-[9px] border border-[#e5e2d9] hover:bg-[#faf9f6] text-[#052c22] rounded-lg transition">Edit</button>
                                            <button wire:click="delete({{ $child->id }})" wire:confirm="Hapus sub-menu '{{ $child->title }}'?"
                                                class="px-2 py-1 text-[9px] border border-red-100 hover:bg-red-50 text-red-400 rounded-lg transition">✕</button>
                                        </div>
                                    </div>
                                @endforeach
                            </div>
                        @endif
                    </div>
                @empty
                    <div class="p-8 text-center border-2 border-dashed border-[#e5e2d9] rounded-2xl text-[#8c887d]">
                        <p class="text-2xl mb-2">🔗</p>
                        <p class="text-xs font-bold">Belum ada menu.</p>
                        <p class="text-[10px] mt-1">Klik <strong>"+ Tambah Menu"</strong> untuk mulai menyusun navigasi.</p>
                    </div>
                @endforelse
            </div>
        </div>

        {{-- RIGHT: Form Add/Edit --}}
        <div class="bg-white border border-[#e5e2d9] rounded-2xl shadow-sm overflow-hidden">
            <div class="p-4 border-b border-[#e5e2d9] bg-[#faf9f6]">
                <h2 class="text-xs font-bold uppercase tracking-wider text-[#052c22]">
                    {{ $isFormOpen ? ($menuId ? 'Edit Item Menu' : 'Tambah Item Menu') : 'Form Menu' }}
                </h2>
                <p class="text-[10px] text-[#8c887d] mt-0.5">
                    {{ $isFormOpen ? 'Isi detail untuk item menu.' : 'Klik "Tambah Menu" atau "Edit" untuk membuka form.' }}
                </p>
            </div>

            @if ($isFormOpen)
                <div class="p-6 space-y-4">

                    {{-- Parent Menu --}}
                    <div>
                        <label class="block text-[10px] font-bold uppercase tracking-wider text-[#8c887d] mb-1.5">Parent Menu (opsional)</label>
                        <select wire:model="parent_id" class="w-full px-3 py-2 border border-[#dcd9d0] rounded-xl text-xs bg-[#faf9f6] focus:outline-none focus:border-[#c8b282] transition">
                            <option value="">— Tidak ada (menu utama) —</option>
                            @foreach ($menus as $m)
                                <option value="{{ $m->id }}" {{ $parent_id == $m->id ? 'selected' : '' }}>{{ $m->title }}</option>
                            @endforeach
                        </select>
                    </div>

                    {{-- Title --}}
                    <div>
                        <label class="block text-[10px] font-bold uppercase tracking-wider text-[#8c887d] mb-1.5">Label Menu</label>
                        <input type="text" wire:model="title" placeholder="Contoh: Tentang Kami"
                            class="w-full px-3 py-2 border border-[#dcd9d0] rounded-xl text-xs bg-white focus:outline-none focus:border-[#c8b282] transition @error('title') border-red-300 @enderror">
                        @error('title') <p class="text-red-500 text-[10px] mt-1">{{ $message }}</p> @enderror
                    </div>

                    {{-- Type: Dropdown --}}
                    <div>
                        <label class="block text-[10px] font-bold uppercase tracking-wider text-[#8c887d] mb-1.5">Tipe Link</label>
                        <select wire:model.live="type" class="w-full px-3 py-2 border border-[#dcd9d0] rounded-xl text-xs bg-[#faf9f6] focus:outline-none focus:border-[#c8b282] transition">
                            <option value="page">📄 Halaman Internal (CMS)</option>
                            <option value="ormawa">🏛 Organisasi (Ormawa)</option>
                            <option value="layanan">🎓 Layanan Kemahasiswaan</option>
                            <option value="external">🔗 URL Kustom / Eksternal</option>
                        </select>
                    </div>

                    {{-- Target: conditional based on type --}}
                    @if ($type === 'page')
                        <div>
                            <label class="block text-[10px] font-bold uppercase tracking-wider text-[#8c887d] mb-1.5">Pilih Halaman</label>
                            <select wire:model="page_id" class="w-full px-3 py-2 border border-[#dcd9d0] rounded-xl text-xs bg-[#faf9f6] focus:outline-none focus:border-[#c8b282] transition @error('page_id') border-red-300 @enderror">
                                <option value="">— Pilih halaman —</option>
                                @foreach ($pages as $page)
                                    <option value="{{ $page->id }}" {{ $page_id == $page->id ? 'selected' : '' }}>{{ $page->title }} (/{{ $page->slug }})</option>
                                @endforeach
                            </select>
                            @error('page_id') <p class="text-red-500 text-[10px] mt-1">{{ $message }}</p> @enderror
                            @if ($pages->isEmpty())
                                <p class="text-[10px] text-amber-600 mt-1">⚠ Belum ada halaman publik. Buat halaman dulu di menu "Halaman Dinamis".</p>
                            @endif
                        </div>

                    @elseif ($type === 'ormawa')
                        <div>
                            <label class="block text-[10px] font-bold uppercase tracking-wider text-[#8c887d] mb-1.5">Pilih Organisasi (Ormawa)</label>
                            <select wire:model="ormawa_slug" class="w-full px-3 py-2 border border-[#dcd9d0] rounded-xl text-xs bg-[#faf9f6] focus:outline-none focus:border-[#c8b282] transition">
                                <option value="">— Pilih organisasi —</option>
                                @foreach ($ormawas as $org)
                                    <option value="{{ $org->slug }}" {{ $ormawa_slug === $org->slug ? 'selected' : '' }}>{{ $org->name }}</option>
                                @endforeach
                            </select>
                            @if ($ormawa_slug)
                                <p class="text-[9px] text-[#8c887d] mt-1">URL yang dibuat: <b>/ormawa?tab={{ $ormawa_slug }}</b></p>
                            @endif
                            @if ($ormawas->isEmpty())
                                <p class="text-[10px] text-amber-600 mt-1">⚠ Belum ada data Ormawa. Tambahkan dulu di menu "Ormawa".</p>
                            @endif
                        </div>

                    @elseif ($type === 'layanan')
                        <div>
                            <label class="block text-[10px] font-bold uppercase tracking-wider text-[#8c887d] mb-1.5">Pilih Layanan</label>
                            <select wire:model="layanan_slug" class="w-full px-3 py-2 border border-[#dcd9d0] rounded-xl text-xs bg-[#faf9f6] focus:outline-none focus:border-[#c8b282] transition">
                                <option value="">— Semua Layanan (halaman utama) —</option>
                                @foreach ($layanans as $layanan)
                                    <option value="{{ $layanan->slug }}" {{ $layanan_slug === $layanan->slug ? 'selected' : '' }}>{{ $layanan->title }}</option>
                                @endforeach
                            </select>
                            @if ($layanan_slug)
                                <p class="text-[9px] text-[#8c887d] mt-1">URL yang dibuat: <b>/layanan?tab={{ $layanan_slug }}</b></p>
                            @elseif ($type === 'layanan')
                                <p class="text-[9px] text-[#8c887d] mt-1">Kosong = arahkan ke halaman utama <b>/layanan</b></p>
                            @endif
                            @if ($layanans->isEmpty())
                                <p class="text-[10px] text-amber-600 mt-1">⚠ Belum ada data Layanan aktif.</p>
                            @endif
                        </div>

                    @else
                        <div>
                            <label class="block text-[10px] font-bold uppercase tracking-wider text-[#8c887d] mb-1.5">Masukkan URL</label>
                            <input type="text" wire:model="external_url" placeholder="Contoh: /berita atau https://google.com"
                                class="w-full px-3 py-2 border border-[#dcd9d0] rounded-xl text-xs bg-white focus:outline-none focus:border-[#c8b282] transition @error('external_url') border-red-300 @enderror">
                            <p class="text-[9px] text-[#8c887d] mt-1">Gunakan path relatif (misal <b>/berita</b>) atau URL lengkap eksternal.</p>
                            @error('external_url') <p class="text-red-500 text-[10px] mt-1">{{ $message }}</p> @enderror
                        </div>
                    @endif

                    {{-- Order --}}
                    <div>
                        <label class="block text-[10px] font-bold uppercase tracking-wider text-[#8c887d] mb-1.5">Urutan (Order)</label>
                        <input type="number" wire:model="order" min="0" placeholder="0"
                            class="w-32 px-3 py-2 border border-[#dcd9d0] rounded-xl text-xs bg-white focus:outline-none focus:border-[#c8b282] transition">
                        <p class="text-[10px] text-[#8c887d] mt-1">Angka lebih kecil = tampil lebih dulu.</p>
                    </div>

                    {{-- Actions --}}
                    <div class="flex gap-3 pt-2">
                        <button wire:click="closeForm" class="px-4 py-2 border border-[#e5e2d9] hover:bg-slate-100 text-[#5c5950] rounded-xl text-xs font-bold transition">Batal</button>
                        <button wire:click="save" class="px-4 py-2 bg-[#052c22] hover:bg-[#073d30] text-[#c8b282] rounded-xl text-xs font-bold transition">💾 Simpan</button>
                    </div>
                </div>
            @else
                <div class="p-10 text-center text-[#8c887d]">
                    <p class="text-3xl mb-3">🔗</p>
                    <p class="text-xs">Pilih aksi dari panel kiri untuk mulai.</p>
                </div>
            @endif
        </div>
    </div>
</div>
