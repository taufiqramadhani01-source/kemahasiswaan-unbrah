<x-app-layout>
    <x-slot name="title">Dasbor Kemahasiswaan</x-slot>

    <!-- Header Section -->
    <div class="mb-10 text-center md:text-left">
        <span class="px-3 py-1 rounded-full border border-[#c8b282]/50 bg-[#faf9f6] text-[#c8b282] text-[10px] font-bold uppercase tracking-widest inline-block mb-3">Selamat Datang</span>
        <h1 class="text-3xl md:text-4xl font-extrabold text-[#052c22] tracking-tight">Pusat Kendali Kemahasiswaan</h1>
        <p class="text-[#5c5950] mt-2 text-xs md:text-sm">Kelola organisasi mahasiswa, beasiswa, agenda kegiatan, prestasi, dan berita dalam satu dasbor terpadu.</p>
    </div>

    <!-- Stats Grid (White Cards with Thin Sand Borders & Forest Green Accents) -->
    <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-6 mb-12">
        <!-- Ormawa Card -->
        <div class="bg-white border border-[#e5e2d9] p-6 rounded-2xl shadow-sm hover:border-[#c8b282]/60 transition duration-300 relative overflow-hidden group">
            <div class="flex justify-between items-start mb-4">
                <span class="text-2xl">👥</span>
                <span class="px-2 py-0.5 text-[9px] font-bold rounded-full bg-[#faf9f6] text-[#c8b282] border border-[#e5e2d9] uppercase tracking-wider">ORMAWA</span>
            </div>
            <h3 class="text-[10px] font-bold uppercase tracking-wider text-[#8c887d]">Organisasi Mahasiswa</h3>
            <p class="text-3xl font-extrabold text-[#052c22] mt-1">{{ \App\Models\Ormawa::count() }}</p>
            <a href="/admin/ormawas" class="text-[10px] font-bold text-[#c8b282] hover:text-[#b49d6c] mt-4 inline-flex items-center gap-1 group/link">
                Kelola Organisasi <span class="group-hover/link:translate-x-0.5 transition duration-150">&rarr;</span>
            </a>
        </div>

        <!-- Kegiatan Card -->
        <div class="bg-white border border-[#e5e2d9] p-6 rounded-2xl shadow-sm hover:border-[#c8b282]/60 transition duration-300 relative overflow-hidden group">
            <div class="flex justify-between items-start mb-4">
                <span class="text-2xl">📅</span>
                <span class="px-2 py-0.5 text-[9px] font-bold rounded-full bg-[#faf9f6] text-[#c8b282] border border-[#e5e2d9] uppercase tracking-wider">AGENDA</span>
            </div>
            <h3 class="text-[10px] font-bold uppercase tracking-wider text-[#8c887d]">Kegiatan Mendatang</h3>
            <p class="text-3xl font-extrabold text-[#052c22] mt-1">{{ \App\Models\Kegiatan::count() }}</p>
            <a href="/admin/kegiatans" class="text-[10px] font-bold text-[#c8b282] hover:text-[#b49d6c] mt-4 inline-flex items-center gap-1 group/link">
                Kelola Agenda <span class="group-hover/link:translate-x-0.5 transition duration-150">&rarr;</span>
            </a>
        </div>

        <!-- Prestasi Card -->
        <div class="bg-white border border-[#e5e2d9] p-6 rounded-2xl shadow-sm hover:border-[#c8b282]/60 transition duration-300 relative overflow-hidden group">
            <div class="flex justify-between items-start mb-4">
                <span class="text-2xl">🏆</span>
                <span class="px-2 py-0.5 text-[9px] font-bold rounded-full bg-[#faf9f6] text-[#c8b282] border border-[#e5e2d9] uppercase tracking-wider">PRESTASI</span>
            </div>
            <h3 class="text-[10px] font-bold uppercase tracking-wider text-[#8c887d]">Prestasi Mahasiswa</h3>
            <p class="text-3xl font-extrabold text-[#052c22] mt-1">{{ \App\Models\Prestasi::count() }}</p>
            <a href="/admin/prestasis" class="text-[10px] font-bold text-[#c8b282] hover:text-[#b49d6c] mt-4 inline-flex items-center gap-1 group/link">
                Kelola Prestasi <span class="group-hover/link:translate-x-0.5 transition duration-150">&rarr;</span>
            </a>
        </div>

        <!-- Beasiswa Card -->
        <div class="bg-white border border-[#e5e2d9] p-6 rounded-2xl shadow-sm hover:border-[#c8b282]/60 transition duration-300 relative overflow-hidden group">
            <div class="flex justify-between items-start mb-4">
                <span class="text-2xl">🎓</span>
                <span class="px-2 py-0.5 text-[9px] font-bold rounded-full bg-[#faf9f6] text-[#c8b282] border border-[#e5e2d9] uppercase tracking-wider">BEASISWA</span>
            </div>
            <h3 class="text-[10px] font-bold uppercase tracking-wider text-[#8c887d]">Program Beasiswa</h3>
            <p class="text-3xl font-extrabold text-[#052c22] mt-1">{{ \App\Models\Beasiswa::count() }}</p>
            <a href="/admin/beasiswas" class="text-[10px] font-bold text-[#c8b282] hover:text-[#b49d6c] mt-4 inline-flex items-center gap-1 group/link">
                Kelola Beasiswa <span class="group-hover/link:translate-x-0.5 transition duration-150">&rarr;</span>
            </a>
        </div>
    </div>

    <!-- Quick Info & News Section -->
    <div class="grid grid-cols-1 lg:grid-cols-3 gap-8">
        <!-- News Feed List -->
        <div class="lg:col-span-2 bg-white border border-[#e5e2d9] p-6 md:p-8 rounded-2xl shadow-sm">
            <div class="flex justify-between items-center mb-6">
                <h3 class="font-bold text-[#052c22] text-base flex items-center gap-2">📰 Berita & Pengumuman Terbaru</h3>
                <a href="/admin/posts" class="text-[10px] font-bold text-[#c8b282] hover:underline">Kelola Semua &rarr;</a>
            </div>

            <div class="space-y-4">
                @forelse (\App\Models\Post::latest()->take(3)->get() as $post)
                    <div class="p-4 rounded-xl bg-[#faf9f6] border border-[#e5e2d9] flex items-start gap-4 hover:border-[#c8b282]/50 transition duration-200">
                        @if ($post->image_path)
                            <img src="{{ $post->image_path }}" alt="Banner" class="w-16 h-16 rounded-xl object-cover border border-[#e5e2d9]">
                        @else
                            <div class="w-16 h-16 rounded-xl bg-white border border-[#e5e2d9] flex items-center justify-center font-bold text-xs text-slate-400">N</div>
                        @endif
                        <div class="flex-1">
                            <span class="px-2 py-0.5 text-[8px] font-bold rounded-full bg-white text-[#c8b282] border border-[#e5e2d9] uppercase tracking-widest">
                                {{ $post->published_at ? $post->published_at->format('d M Y') : 'Draft' }}
                            </span>
                            <h4 class="font-bold text-sm text-[#052c22] mt-1 hover:text-[#c8b282] transition cursor-pointer">{{ $post->title }}</h4>
                            <p class="text-[10px] text-[#5c5950] mt-1 line-clamp-1">{!! strip_tags($post->content) !!}</p>
                        </div>
                    </div>
                @empty
                    <p class="text-xs text-slate-500 text-center py-6">Belum ada berita kemahasiswaan yang diterbitkan.</p>
                @endforelse
            </div>
        </div>

        <!-- Quick SOP / Guidelines -->
        <div class="bg-white border border-[#e5e2d9] p-6 rounded-2xl shadow-sm">
            <h3 class="font-bold text-[#052c22] text-base mb-6 flex items-center gap-2">🛡️ SOP & Panduan Singkat</h3>
            <div class="space-y-4 text-xs text-[#5c5950] leading-relaxed">
                <div class="p-3 bg-[#faf9f6] rounded-xl border border-[#e5e2d9]">
                    <p class="font-bold text-[#052c22] mb-1">Pengajuan Beasiswa</p>
                    <p class="text-[10px] text-[#8c887d] font-semibold">Validasi persyaratan Beasiswa KIP-K dan PPA dilakukan via admin panel untuk memperbarui data publik di halaman depan.</p>
                </div>
                <div class="p-3 bg-[#faf9f6] rounded-xl border border-[#e5e2d9]">
                    <p class="font-bold text-[#052c22] mb-1">Persetujuan Proposal Ormawa</p>
                    <p class="text-[10px] text-[#8c887d] font-semibold">Setiap agenda ormawa (BEM/DPM) harus diajukan terlebih dahulu sebelum di-publish ke timeline publik.</p>
                </div>
            </div>
        </div>
    </div>
</x-app-layout>
