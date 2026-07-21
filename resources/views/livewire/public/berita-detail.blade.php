<div>
    @section('title', ($post->title ?? 'Berita') . ' - Kemahasiswaan UNBRAH')

    <div class="bg-slate-50 min-h-screen py-8 text-slate-800">
        <div class="max-w-[1440px] mx-auto px-4 sm:px-8 lg:px-12 space-y-6">

            <!-- Breadcrumbs -->
            <nav class="flex items-center space-x-2 text-xs text-slate-500 font-medium">
                <a href="/" class="hover:text-brand-green-700 transition-colors">Beranda</a>
                <svg class="w-3 h-3 text-slate-400" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M9 5l7 7-7 7"></path></svg>
                <a href="{{ route('berita.index') }}" class="hover:text-brand-green-700 transition-colors">Berita</a>
                <svg class="w-3 h-3 text-slate-400" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M9 5l7 7-7 7"></path></svg>
                <span class="text-slate-400 line-clamp-1">{{ $post->title }}</span>
            </nav>

            <!-- Grid Layout -->
            <div class="grid grid-cols-1 lg:grid-cols-3 gap-8 items-start">

                <!-- Main News Area -->
                <article class="lg:col-span-2 bg-white rounded-3xl p-6 sm:p-8 shadow-sm border border-slate-200/50 space-y-6">
                    <!-- Tag -->
                    @if($post->tag)
                        <div>
                            <span class="bg-brand-green-900/10 text-brand-green-900 font-extrabold text-[10px] tracking-wider uppercase px-3 py-1.5 rounded-lg inline-block">
                                {{ $post->tag }}
                            </span>
                        </div>
                    @endif

                    <!-- Title -->
                    <h1 class="text-xl sm:text-3xl md:text-4xl font-bold font-display text-slate-900 leading-tight">
                        {{ $post->title }}
                    </h1>

                    <!-- Meta -->
                    <div class="flex flex-wrap items-center gap-4 text-xs text-slate-450 border-y border-slate-100 py-3.5">
                        <span class="flex items-center space-x-1.5">
                            <svg class="w-4 h-4 text-brand-green-900" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z"></path></svg>
                            <span class="font-medium">{{ $post->published_at ? $post->published_at->format('d F Y') : '-' }}</span>
                        </span>
                        <span class="flex items-center space-x-1.5">
                            <svg class="w-4 h-4 text-brand-green-900" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z"></path></svg>
                            <span class="font-medium">{{ $post->author ?? 'Humas Unbrah' }}</span>
                        </span>
                    </div>

                    <!-- Featured Image -->
                    @if($post->image_path)
                        <div class="relative overflow-hidden rounded-2xl shadow-md h-72 sm:h-96 md:h-[450px]">
                            <img src="{{ $post->image_path }}" alt="{{ $post->title }}" class="w-full h-full object-cover">
                        </div>
                    @endif

                    <!-- Article Body -->
                    <div class="ck-content text-slate-700 text-sm leading-relaxed space-y-4 font-light pt-2 prose prose-sm max-w-none">
                        {!! $post->content !!}
                    </div>

                    <!-- Back button -->
                    <div class="pt-4 border-t border-slate-100">
                        <a href="{{ route('berita.index') }}" class="inline-flex items-center gap-2 text-xs font-bold text-brand-green-900 hover:text-brand-green-950 transition-colors">
                            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M15 19l-7-7 7-7"></path></svg>
                            Kembali ke Daftar Berita
                        </a>
                    </div>
                </article>

                <!-- Sidebar -->
                <aside class="lg:col-span-1 space-y-6">
                    <div class="bg-white rounded-3xl p-6 shadow-sm border border-slate-200/50 space-y-5">
                        <h4 class="font-bold font-display text-slate-900 text-sm border-b border-slate-100 pb-4">Berita Terkait</h4>
                        <div class="space-y-4">
                            @forelse($related as $item)
                                <a href="{{ route('berita.show', $item->slug) }}" class="flex items-start gap-3 group">
                                    <div class="w-16 h-16 rounded-xl overflow-hidden shrink-0 bg-slate-100">
                                        @if($item->image_path)
                                            <img src="{{ $item->image_path }}" alt="{{ $item->title }}" class="w-full h-full object-cover group-hover:scale-105 transition-transform duration-300">
                                        @else
                                            <div class="w-full h-full bg-brand-green-50 flex items-center justify-center text-lg">📰</div>
                                        @endif
                                    </div>
                                    <div class="flex-1 min-w-0">
                                        <h5 class="text-xs font-bold text-slate-800 group-hover:text-brand-green-700 transition-colors line-clamp-2 leading-snug">{{ $item->title }}</h5>
                                        <p class="text-[10px] text-slate-400 mt-1">{{ $item->published_at ? $item->published_at->format('d M Y') : '-' }}</p>
                                    </div>
                                </a>
                            @empty
                                <p class="text-xs text-slate-400 text-center py-4">Tidak ada berita terkait.</p>
                            @endforelse
                        </div>
                    </div>
                </aside>

            </div>
        </div>
    </div>
</div>
