<div>
    <x-slot name="title">Kelola Berita - Dasbor Kemahasiswaan</x-slot>

    <!-- Page Header -->
    <div class="flex flex-col md:flex-row md:items-center justify-between gap-4 mb-8">
        <div>
            <span class="px-3 py-1 rounded-full border border-[#c8b282]/50 bg-[#faf9f6] text-[#c8b282] text-[10px] font-bold uppercase tracking-widest inline-block mb-3">Kemahasiswaan</span>
            <h1 class="text-2xl font-extrabold text-[#052c22] tracking-tight">Berita & Artikel</h1>
            <p class="text-xs text-[#5c5950] mt-1">Kelola publikasi seputar prestasi, program beasiswa, event ormawa, dan informasi penting lainnya.</p>
        </div>
        <a href="/admin/posts/create" wire:navigate class="px-4 py-2 bg-[#052c22] hover:bg-[#073d30] text-[#c8b282] rounded-xl text-xs font-bold uppercase tracking-wider transition self-start md:self-auto no-underline">
            + Tulis Berita
        </a>
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
            <input type="text" wire:model.live.debounce.300ms="search" placeholder="Cari berdasarkan judul berita..." 
                class="w-full max-w-md px-4 py-2 border border-[#dcd9d0] rounded-xl text-xs bg-white focus:outline-none focus:border-[#c8b282] transition">
        </div>

        <table class="w-full text-left border-collapse">
            <thead>
                <tr class="bg-[#faf9f6] text-[10px] font-bold uppercase tracking-wider text-[#8c887d] border-b border-[#e5e2d9]">
                    <th class="p-4 w-24">Banner</th>
                    <th class="p-4">Judul Berita</th>
                    <th class="p-4">Tanggal Rilis</th>
                    <th class="p-4 w-32 text-center">Status</th>
                    <th class="p-4 w-32 text-right">Aksi</th>
                </tr>
            </thead>
            <tbody class="divide-y divide-[#e5e2d9]">
                @forelse ($posts as $post)
                    <tr class="text-xs text-[#1a2421] hover:bg-[#faf9f6]/40 transition">
                        <td class="p-4">
                            @if ($post->image_path)
                                <img src="{{ $post->image_path }}" alt="Cover" class="w-16 h-10 rounded-lg object-cover border border-[#e5e2d9] bg-slate-100">
                            @else
                                <div class="w-16 h-10 rounded-lg bg-[#faf9f6] border border-[#e5e2d9] flex items-center justify-center font-bold text-[#8c887d] uppercase text-[9px]">
                                    No Image
                                </div>
                            @endif
                        </td>
                        <td class="p-4">
                            <p class="font-bold text-[#052c22]">{{ $post->title }}</p>
                            <p class="text-[10px] text-[#8c887d] font-mono mt-0.5">{{ $post->slug }}</p>
                        </td>
                        <td class="p-4 font-mono text-[#5c5950] font-semibold">
                            {{ $post->published_at ? $post->published_at->format('d M Y H:i') : '-' }}
                        </td>
                        <td class="p-4 text-center">
                            <button wire:click="togglePublish({{ $post->id }})" 
                                class="px-3 py-1 rounded-full text-[9px] font-bold uppercase tracking-wider transition border
                                {{ $post->is_published 
                                    ? 'bg-emerald-50 border-emerald-200 text-emerald-700' 
                                    : 'bg-slate-100 border-slate-200 text-slate-500' }}">
                                {{ $post->is_published ? 'Publik' : 'Draft' }}
                            </button>
                        </td>
                        <td class="p-4 text-right space-x-1">
                            <a href="/admin/posts/{{ $post->id }}/edit" wire:navigate class="inline-block p-1.5 text-blue-600 hover:bg-blue-50 rounded-lg transition no-underline" title="Edit">
                                ✏️
                            </a>
                            <button onclick="confirm('Hapus berita ini?') || event.stopImmediatePropagation()" wire:click="delete({{ $post->id }})" class="p-1.5 text-red-600 hover:bg-red-50 rounded-lg transition" title="Hapus">
                                🗑️
                            </button>
                        </td>
                    </tr>
                @empty
                    <tr>
                        <td colspan="5" class="p-8 text-center text-[#8c887d]">Belum ada berita kemahasiswaan yang ditulis.</td>
                    </tr>
                @endforelse
            </tbody>
        </table>
        
        <!-- Pagination -->
        <div class="p-4 border-t border-[#e5e2d9]">
            {{ $posts->links() }}
        </div>
    </div>
</div>
