<div>
    <x-slot name="title">Halaman Dinamis - Dasbor Kemahasiswaan</x-slot>

    {{-- Page Header --}}
    <div class="flex flex-col md:flex-row md:items-center justify-between gap-4 mb-8">
        <div>
            <span class="px-3 py-1 rounded-full border border-[#c8b282]/50 bg-[#faf9f6] text-[#c8b282] text-[10px] font-bold uppercase tracking-widest inline-block mb-3">CMS</span>
            <h1 class="text-2xl font-extrabold text-[#052c22] tracking-tight">Halaman Dinamis</h1>
            <p class="text-xs text-[#5c5950] mt-1">Buat dan kelola tata letak halaman statis (Visi Misi, Fasilitas, Layanan, dsb.) dengan blok builder.</p>
        </div>
        <a href="/admin/pages/create" class="px-4 py-2 bg-[#052c22] hover:bg-[#073d30] text-[#c8b282] rounded-xl text-xs font-bold uppercase tracking-wider transition self-start md:self-auto flex items-center gap-1.5 no-underline">
            + Tambah Halaman Baru
        </a>
    </div>

    {{-- Flash Message --}}
    @if (session()->has('message'))
        <div class="p-4 mb-6 bg-emerald-50 border border-emerald-100 text-emerald-800 text-xs font-bold rounded-xl flex items-center justify-between">
            <span>✅ {{ session('message') }}</span>
            <button onclick="this.parentElement.remove()">✕</button>
        </div>
    @endif

    {{-- Table Card --}}
    <div class="bg-white border border-[#e5e2d9] rounded-2xl shadow-sm overflow-hidden">
        {{-- Search bar --}}
        <div class="p-5 border-b border-[#e5e2d9] bg-[#faf9f6] flex flex-col md:flex-row md:items-center justify-between gap-4">
            <h2 class="text-xs font-bold uppercase tracking-wider text-[#052c22]">Daftar Halaman Publik</h2>
            <div class="relative w-full md:w-72">
                <input type="text" wire:model.live="search" placeholder="Cari judul atau slug..."
                    class="w-full px-3.5 py-2 border border-[#dcd9d0] rounded-xl text-xs focus:outline-none focus:border-[#c8b282] transition pl-8 bg-white">
                <span class="absolute left-3 top-2.5 text-[#8c887d]">🔍</span>
            </div>
        </div>

        {{-- Table --}}
        <div class="overflow-x-auto">
            <table class="w-full text-left border-collapse">
                <thead>
                    <tr class="border-b border-[#e5e2d9] text-[10px] font-bold uppercase tracking-wider text-[#8c887d] bg-[#faf9f6]">
                        <th class="px-6 py-4">Judul Halaman</th>
                        <th class="px-6 py-4">Slug URL</th>
                        <th class="px-6 py-4">Jumlah Blok</th>
                        <th class="px-6 py-4">Status</th>
                        <th class="px-6 py-4 text-right">Aksi</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-[#e5e2d9]">
                    @forelse ($pages as $p)
                        <tr class="hover:bg-[#faf9f6]/40 transition">
                            <td class="px-6 py-4">
                                <span class="text-xs font-bold text-[#052c22] block">{{ $p->title }}</span>
                                <span class="text-[9px] text-[#8c887d] block mt-0.5">Dibuat: {{ $p->created_at->format('d M Y') }}</span>
                            </td>
                            <td class="px-6 py-4">
                                <span class="px-2 py-0.5 rounded-lg bg-[#faf9f6] text-[10px] text-[#5c5950] font-mono border border-[#e5e2d9]">/page/{{ $p->slug }}</span>
                            </td>
                            <td class="px-6 py-4">
                                <span class="text-xs font-bold text-[#052c22]">{{ is_array($p->content) ? count($p->content) : 0 }} blok</span>
                            </td>
                            <td class="px-6 py-4">
                                <button wire:click="togglePublish({{ $p->id }})" 
                                    class="px-2.5 py-1 rounded-full text-[9px] font-bold uppercase tracking-wider border transition
                                        {{ $p->is_published 
                                            ? 'bg-emerald-50 border-emerald-200 text-emerald-700 hover:bg-emerald-100' 
                                            : 'bg-amber-50 border-amber-200 text-amber-700 hover:bg-amber-100' }}">
                                    {{ $p->is_published ? 'Publik' : 'Draft' }}
                                </button>
                            </td>
                            <td class="px-6 py-4 text-right">
                                <div class="inline-flex gap-2">
                                    <a href="/page/{{ $p->slug }}" target="_blank" class="px-3 py-1.5 text-[10px] font-bold border border-[#e5e2d9] hover:bg-[#faf9f6] text-[#5c5950] rounded-xl transition no-underline">Pratinjau 🌐</a>
                                    <a href="/admin/pages/{{ $p->id }}/edit" class="px-3 py-1.5 text-[10px] font-bold border border-[#e5e2d9] hover:bg-[#faf9f6] text-[#052c22] rounded-xl transition no-underline">Edit Blok 📝</a>
                                    <button wire:click="delete({{ $p->id }})" wire:confirm="Hapus halaman '{{ $p->title }}'?"
                                        class="px-3 py-1.5 text-[10px] font-bold border border-red-100 hover:bg-red-50 text-red-500 rounded-xl transition">Hapus ✕</button>
                                </div>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="5" class="px-6 py-12 text-center text-[#8c887d]">
                                <p class="text-3xl mb-2">📄</p>
                                <p class="text-xs font-bold">Belum ada halaman dinamis.</p>
                                <p class="text-[10px] mt-1">Mulai dengan mengklik **"+ Tambah Halaman Baru"**.</p>
                            </td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>

        {{-- Pagination --}}
        @if ($pages->hasPages())
            <div class="p-5 border-t border-[#e5e2d9] bg-[#faf9f6]">
                {{ $pages->links() }}
            </div>
        @endif
    </div>
</div>
