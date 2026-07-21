<div>
    @section('title', 'Kilas Berita & Publikasi - Kemahasiswaan UNBRAH')

    <!-- HERO SECTION -->
    <section class="relative bg-gradient-to-br from-brand-green-950 to-brand-green-900 py-16 text-white overflow-hidden shadow-xl">
        <div class="absolute top-[-100px] right-[-100px] w-96 h-96 bg-brand-gold-500/10 rounded-full blur-[80px]"></div>
        <div class="absolute bottom-[-150px] left-[-150px] w-[500px] h-[500px] bg-brand-green-500/10 rounded-full blur-[120px]"></div>
        <div class="max-w-[1440px] mx-auto px-4 sm:px-8 lg:px-12 text-center relative z-10 space-y-4">
            <span class="text-xs font-bold text-brand-gold-500 uppercase tracking-widest bg-brand-green-900/50 px-4 py-2 rounded-full border border-brand-gold-500/10">Warta & Kabar Terkini</span>
            <h1 class="text-3xl sm:text-5xl md:text-6xl font-bold font-display tracking-tight text-white leading-tight drop-shadow">
                Kilas Berita & <span class="bg-gradient-to-r from-brand-gold-500 to-brand-gold-100 bg-clip-text text-transparent">Publikasi</span>
            </h1>
            <p class="text-base md:text-lg text-slate-300/90 max-w-none mx-auto font-light leading-relaxed">
                Menyajikan warta terbaru, pengumuman resmi, beasiswa, serta agenda kegiatan kemahasiswaan Universitas Baiturrahmah secara akurat dan transparan.
            </p>
        </div>
    </section>

    <!-- MAIN SECTION -->
    <section class="py-16 bg-slate-50 text-slate-800 relative overflow-hidden min-h-screen">
        <div class="max-w-[1440px] mx-auto px-4 sm:px-8 lg:px-12 relative z-10 space-y-10">

            <!-- Controls: Search & Category Filter -->
            <div class="bg-white rounded-3xl p-6 sm:p-8 shadow-sm border border-slate-200/50 flex flex-col xl:flex-row xl:items-center justify-between gap-6">
                <!-- Search input -->
                <div class="relative w-full xl:max-w-md">
                    <input type="text" wire:model.live.debounce.400ms="search" placeholder="Cari judul berita atau deskripsi..."
                        class="w-full bg-slate-50 border border-slate-200 focus:border-brand-green-500 focus:bg-white text-slate-800 text-xs pl-12 pr-4 py-4 rounded-2xl shadow-sm outline-none transition-all duration-300">
                    <div class="absolute left-4 top-4 text-slate-400">
                        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z"></path></svg>
                    </div>
                </div>

                <!-- Category Filter Tags -->
                <div class="flex flex-wrap items-center gap-2">
                    <button wire:click="$set('tag', '')" class="px-4 py-2.5 rounded-xl text-xs font-bold transition-all duration-300 cursor-pointer border border-transparent {{ !$tag ? 'bg-brand-green-900 text-white shadow-md' : 'bg-slate-100 text-slate-600 hover:bg-slate-200' }}">
                        Semua
                    </button>
                    @foreach($tags as $t)
                        <button wire:click="$set('tag', '{{ $t }}')" class="px-4 py-2.5 rounded-xl text-xs font-bold transition-all duration-300 cursor-pointer border border-transparent {{ $tag === $t ? 'bg-brand-green-900 text-white shadow-md' : 'bg-slate-100 text-slate-600 hover:bg-slate-200' }}">
                            {{ $t }}
                        </button>
                    @endforeach
                </div>
            </div>

            <!-- News Grid -->
            <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-8">
                @forelse($posts as $post)
                    <article class="bg-white rounded-3xl overflow-hidden border border-slate-200/60 shadow-sm hover:shadow-xl transition-all duration-500 group flex flex-col justify-between">
                        <div>
                            <!-- Image -->
                            <div class="relative overflow-hidden h-56">
                                <div class="absolute inset-0 bg-gradient-to-t from-black/50 to-transparent z-10 opacity-60"></div>
                                @if($post->image_path)
                                    <img src="{{ $post->image_path }}" alt="{{ $post->title }}"
                                         class="w-full h-full object-cover group-hover:scale-105 transition-transform duration-700" loading="lazy"
                                         onerror="this.src='/images/logo-unbrah.png'">
                                @else
                                    <div class="w-full h-full bg-gradient-to-br from-brand-green-900 to-brand-green-950 flex items-center justify-center">
                                        <span class="text-4xl text-white/30">📰</span>
                                    </div>
                                @endif
                                @if($post->tag)
                                    <span class="absolute top-4 left-4 z-20 bg-brand-green-900 text-white font-bold text-[10px] tracking-widest uppercase px-3 py-1.5 rounded-lg">
                                        {{ $post->tag }}
                                    </span>
                                @endif
                            </div>
                            <!-- Body -->
                            <div class="p-6 space-y-3">
                                <div class="flex items-center space-x-3 text-xs text-slate-400">
                                    <span class="flex items-center space-x-1">
                                        <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z"></path></svg>
                                        <span>{{ $post->published_at ? $post->published_at->format('d M Y') : '-' }}</span>
                                    </span>
                                    <span>•</span>
                                    <span>{{ $post->author ?? 'Humas Unbrah' }}</span>
                                </div>
                                <h3 class="font-bold font-display text-slate-900 text-base sm:text-lg group-hover:text-brand-green-600 transition-colors leading-snug line-clamp-2">
                                    {{ $post->title }}
                                </h3>
                                <p class="text-slate-500 text-xs leading-relaxed line-clamp-3">
                                    {{ $post->excerpt ?? \Illuminate\Support\Str::limit(strip_tags($post->content ?? ''), 150) }}
                                </p>
                            </div>
                        </div>
                        <!-- Action -->
                        <div class="px-6 pb-6 pt-4 border-t border-slate-100/60 flex items-center justify-between">
                            <a href="{{ route('berita.show', $post->slug) }}" class="text-brand-green-900 hover:text-brand-green-950 font-bold text-xs flex items-center space-x-1 cursor-pointer group/link transition-colors">
                                <span>Baca Selengkapnya</span>
                                <svg class="w-3.5 h-3.5 transform group-hover/link:translate-x-0.5 transition-transform" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M9 5l7 7-7 7"></path></svg>
                            </a>
                            @if($post->tag)
                                <span class="text-[10px] text-slate-400 font-semibold font-mono">#{{ Str::slug($post->tag) }}</span>
                            @endif
                        </div>
                    </article>
                @empty
                    <div class="col-span-3 text-center py-20 bg-white rounded-3xl border border-slate-200/50 shadow-sm space-y-4">
                        <div class="w-16 h-16 bg-slate-100 text-slate-400 rounded-2xl flex items-center justify-center mx-auto text-3xl">📰</div>
                        <h3 class="font-bold text-slate-800 text-base">Berita Tidak Ditemukan</h3>
                        <p class="text-slate-500 text-xs px-6">Tidak ada berita yang sesuai dengan pencarian Anda.</p>
                        <button wire:click="$set('search', '')" class="inline-block px-5 py-2.5 bg-brand-green-900 hover:bg-brand-green-950 text-white font-bold text-xs rounded-xl shadow-md cursor-pointer transition-colors">Lihat Semua Berita</button>
                    </div>
                @endforelse
            </div>

            <!-- Pagination -->
            <div class="flex justify-center">
                {{ $posts->links() }}
            </div>

        </div>
    </section>
</div>
