<div>
    @section('title', 'Beranda - Kemahasiswaan UNBRAH')

    {{-- ================================================================
         HERO AUTO-SLIDER SECTION
    ================================================================ --}}
    <section class="relative w-full h-[580px] md:h-[700px] bg-brand-green-950 overflow-hidden shadow-2xl">
        <div id="slider" class="relative w-full h-full">
            @php
                $defaultSlides = [
                    ['image'=>'/images/slider2.jpg','badge'=>'Kemahasiswaan UNBRAH','title_line1'=>'Membina Karakter & Potensi','title_line2'=>'Lulusan Unggul & Beretika','description'=>'Mewujudkan pembinaan mahasiswa yang berorientasi pada kepemimpinan, integritas moral, serta kepedulian sosial yang tinggi.','button1_text'=>'Layanan Mahasiswa','button1_link'=>'/layanan','button2_text'=>'Profil Kemahasiswaan','button2_link'=>'#about'],
                    ['image'=>'/images/slider3.jpg','badge'=>'Prestasi & Minat Bakat','title_line1'=>'Raih Prestasi Gemilang','title_line2'=>'Salurkan Kreativitas & Bakat','description'=>'Didukung oleh berbagai Unit Kegiatan Mahasiswa (UKM) bidang penalaran, seni, olahraga, kerohanian, dan kemanusiaan.','button1_text'=>'Daftar Ormawa','button1_link'=>'/ormawa','button2_text'=>'Layanan Mahasiswa','button2_link'=>'/layanan'],
                    ['image'=>'/images/slider1.jpg','badge'=>'Kesejahteraan Mahasiswa','title_line1'=>'Layanan & Bantuan Beasiswa','title_line2'=>'Mendukung Studi Masa Depan','description'=>'Menyediakan program Beasiswa KIP-Kuliah, Beasiswa Yayasan, layanan konseling psikologis gratis, dan pembinaan karir alumni.','button1_text'=>'Arsip Berita','button1_link'=>'/berita','button2_text'=>'Hubungi Kami','button2_link'=>'https://wa.me/628116313712'],
                ];
                $slides = json_decode(setting('slides_list', json_encode($defaultSlides)), true);
                if (!is_array($slides) || count($slides) === 0) { $slides = $defaultSlides; }
            @endphp

            @foreach ($slides as $index => $slide)
                <div class="slide {{ $index === 0 ? 'active' : '' }}">
                    <div class="absolute inset-0 bg-gradient-to-t from-brand-green-950 via-brand-green-950/40 to-black/30 z-20"></div>
                    <img src="{{ $slide['image'] ?? '/images/slider1.jpg' }}" alt="{{ $slide['title_line1'] ?? '' }}" class="w-full h-full object-cover slide-bg-zoom">
                    <div class="absolute inset-0 z-30 flex items-center">
                        <div class="max-w-[1440px] mx-auto w-full px-4 sm:px-8 lg:px-12">
                            <div class="max-w-3xl text-left text-white">
                                @if(!empty($slide['badge']))
                                    <span class="inline-block bg-brand-gold-500/20 text-brand-gold-100 border border-brand-gold-500/30 text-[10px] md:text-xs font-bold tracking-widest uppercase px-3 py-1.5 rounded-full mb-4 {{ $index === 0 ? 'animate-pulse' : '' }}">{{ $slide['badge'] }}</span>
                                @endif
                                <h1 class="text-3xl sm:text-5xl md:text-6xl font-bold font-display tracking-tight text-white leading-tight mb-4 drop-shadow">
                                    {{ $slide['title_line1'] ?? '' }} <br class="hidden sm:inline">
                                    <span class="bg-gradient-to-r from-brand-gold-500 to-brand-gold-100 bg-clip-text text-transparent">{{ $slide['title_line2'] ?? '' }}</span>
                                </h1>
                                @if(!empty($slide['description']))
                                    <p class="text-sm md:text-lg text-slate-300 max-w-2xl mb-8 leading-relaxed font-light drop-shadow">{{ $slide['description'] }}</p>
                                @endif
                                <div class="flex flex-col sm:flex-row justify-start items-start sm:items-center space-y-3 sm:space-y-0 sm:space-x-4">
                                    @if(!empty($slide['button1_text']))
                                        @php
                                            $btn1Link = ($slide['button1_link'] ?? '#') === '/profil' ? '#about' : ($slide['button1_link'] ?? '#');
                                            $btn1Attr = str_starts_with($btn1Link, 'http') ? 'target="_blank"' : (str_starts_with($btn1Link, '#') ? '' : 'wire:navigate');
                                        @endphp
                                        <a href="{{ $btn1Link }}" {!! $btn1Attr !!} class="w-full sm:w-auto text-center bg-brand-gold-500 hover:bg-brand-gold-600 text-brand-green-950 font-bold px-7 py-3.5 rounded-full shadow-lg hover:shadow-xl transition-all duration-300 transform hover:-translate-y-0.5 text-sm no-underline">
                                            {{ $slide['button1_text'] }}
                                        </a>
                                    @endif
                                    @if(!empty($slide['button2_text']))
                                        @php
                                            $btn2Link = ($slide['button2_link'] ?? '#') === '/profil' ? '#about' : ($slide['button2_link'] ?? '#');
                                            $btn2Attr = str_starts_with($btn2Link, 'http') ? 'target="_blank"' : (str_starts_with($btn2Link, '#') ? '' : 'wire:navigate');
                                        @endphp
                                        <a href="{{ $btn2Link }}" {!! $btn2Attr !!} class="w-full sm:w-auto text-center border border-white/30 hover:border-white text-white font-semibold px-7 py-3.5 rounded-full hover:bg-white/10 transition-all duration-300 text-sm no-underline">
                                            {{ $slide['button2_text'] }}
                                        </a>
                                    @endif
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            @endforeach

            <!-- Slider Indicators -->
            <div class="absolute bottom-6 left-0 right-0 z-40 flex justify-center space-x-3">
                @foreach ($slides as $index => $slide)
                    <button class="indicator h-1.5 w-4 bg-white/40 rounded-full transition-all duration-300 cursor-pointer" onclick="goToSlide({{ $index }})"></button>
                @endforeach
            </div>

            <!-- Slider Controls -->
            <button onclick="prevSlide()" class="absolute top-1/2 left-4 z-40 -translate-y-1/2 w-10 h-10 rounded-full bg-white/10 backdrop-blur-md flex items-center justify-center text-white hover:bg-brand-gold-500 hover:text-brand-green-950 hover:scale-105 transition duration-300 cursor-pointer border-none">❮</button>
            <button onclick="nextSlide()" class="absolute top-1/2 right-4 z-40 -translate-y-1/2 w-10 h-10 rounded-full bg-white/10 backdrop-blur-md flex items-center justify-center text-white hover:bg-brand-gold-500 hover:text-brand-green-950 hover:scale-105 transition duration-300 cursor-pointer border-none">❯</button>
        </div>
    </section>

    {{-- ================================================================
         LAYANAN UTAMA SECTION — 6 Kartu Dinamis dari Setting
    ================================================================ --}}
    @php
        $iconPaths = [
            'brain'      => 'M8 12h.01M12 12h.01M16 12h.01M21 12c0 4.418-4.03 8-9 8a9.863 9.863 0 01-4.255-.949L3 20l1.395-3.72C3.512 15.042 3 13.574 3 12c0-4.418 4.03-8 9-8s9 3.582 9 8z',
            'briefcase'  => 'M21 13.255A23.931 23.931 0 0112 15c-3.183 0-6.22-.62-9-1.745M16 6V4a2 2 0 00-2-2h-4a2 2 0 00-2 2v2m4 6h.01M5 20h14a2 2 0 002-2V8a2 2 0 00-2-2H5a2 2 0 00-2 2v10a2 2 0 002 2z',
            'shield'     => 'M9 12l2 2 4-4m5.618-4.016A11.955 11.955 0 0112 2.944a11.955 11.955 0 01-8.618 3.04A12.02 12.02 0 003 9c0 5.591 3.824 10.29 9 11.622 5.176-1.332 9-6.03 9-11.622 0-1.042-.133-2.052-.382-3.016z',
            'money'      => 'M12 8c-1.657 0-3 .895-3 2s1.343 2 3 2 3 .895 3 2-1.343 2-3 2m0-8c1.11 0 2.08.402 2.599 1M12 8V7m0 1v8m0 0v1m0-1c-1.11 0-2.08-.402-2.599-1M21 12a9 9 0 11-18 0 9 9 0 0118 0z',
            'gift'       => 'M12 8v13m0-13V6a2 2 0 112 2h-2zm0 0V5.5A2.5 2.5 0 109.5 8H12zm-7 4h14M5 12a2 2 0 110-4h14a2 2 0 110 4M5 12v7a2 2 0 002 2h10a2 2 0 002-2v-7',
            'book'       => 'M12 6.253v13m0-13C10.832 5.477 9.246 5 7.5 5S4.168 5.477 3 6.253v13C4.168 18.477 5.754 18 7.5 18s3.332.477 4.5 1.253m0-13C13.168 5.477 14.754 5 16.5 5c1.747 0 3.332.477 4.5 1.253v13C19.832 18.477 18.247 18 16.5 18c-1.746 0-3.332.477-4.5 1.253',
        ];
        $colorBg = ['indigo'=>'bg-indigo-50','amber'=>'bg-amber-50','emerald'=>'bg-emerald-50','blue'=>'bg-blue-50','rose'=>'bg-rose-50','brand-green'=>'bg-brand-green-50','slate'=>'bg-slate-50'];

        $defaultLayanan = [
            ['label'=>'Layanan 01','title'=>'Layanan Bimbingan Konseling','icon'=>'brain','url'=>'/layanan?tab=psikologi','color'=>'indigo'],
            ['label'=>'Layanan 02','title'=>'Pusat Karir & Kewirausahaan (P2K2)','icon'=>'briefcase','url'=>'/layanan?tab=p2k2','color'=>'amber'],
            ['label'=>'Layanan 03','title'=>'Satgas PPKTP','icon'=>'shield','url'=>'/layanan?tab=ppktp','color'=>'emerald'],
            ['label'=>'Layanan 04','title'=>'Layanan Beasiswa','icon'=>'money','url'=>'/layanan?tab=beasiswa','color'=>'blue'],
            ['label'=>'Layanan 05','title'=>'SAPS (Poin Kredit Prestasi)','icon'=>'gift','url'=>'/layanan?tab=saps','color'=>'rose'],
            ['label'=>'Layanan 06','title'=>'Hasimaru (Pembinaan Karakter)','icon'=>'book','url'=>'/layanan?tab=hasimaru','color'=>'brand-green'],
        ];
        $layananCards = json_decode(setting('layanan_cards', json_encode($defaultLayanan)), true);
        if (!is_array($layananCards) || count($layananCards) === 0) { $layananCards = $defaultLayanan; }
    @endphp

    <section class="relative z-30 -mt-8 max-w-[1440px] mx-auto px-4 sm:px-8 lg:px-12">
        <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-3 gap-6">
            @foreach($layananCards as $ci => $card)
                @if(!empty($card['title']))
                @php
                    $bgClass  = $colorBg[$card['color'] ?? 'slate'] ?? 'bg-slate-50';
                    $iconPath = $iconPaths[$card['icon'] ?? 'briefcase'] ?? $iconPaths['briefcase'];
                @endphp
                <a href="{{ $card['url'] ?? '#' }}" wire:navigate class="group reveal {{ $ci > 0 ? 'delay-'.($ci*100) : '' }} bg-white hover:bg-gradient-to-br hover:from-brand-green-900 hover:to-brand-green-950 rounded-2xl shadow-lg border border-slate-100 py-6 px-5 flex items-center space-x-4 transform hover:-translate-y-1 transition duration-300 no-underline">
                    <div class="w-11 h-11 rounded-xl {{ $bgClass }} flex items-center justify-center shrink-0 group-hover:bg-brand-gold-500 group-hover:text-brand-green-950 transition duration-300">
                        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="{{ $iconPath }}"></path></svg>
                    </div>
                    <div class="flex-1 min-w-0">
                        <p class="text-[8px] text-slate-400 font-bold uppercase tracking-wider group-hover:text-brand-gold-300">{{ $card['label'] ?? '' }}</p>
                        <h4 class="text-xs font-extrabold text-slate-800 font-display mt-0.5 group-hover:text-white truncate">{{ $card['title'] }}</h4>
                    </div>
                    <div class="text-brand-green-600 group-hover:text-brand-gold-500 group-hover:translate-x-1 transition-transform duration-300">
                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7"/></svg>
                    </div>
                </a>
                @endif
            @endforeach
        </div>
    </section>

    {{-- ================================================================
         SAMBUTAN WAKIL REKTOR III SECTION
    ================================================================ --}}
    <section id="sambutan" class="py-24 bg-slate-50 border-y border-slate-200 mt-20">
        <div class="max-w-[1440px] mx-auto px-4 sm:px-8 lg:px-12">
            <div class="grid grid-cols-1 md:grid-cols-12 gap-16 items-center">
                <!-- Foto WR III -->
                @php
                    $wr3Photo = setting('wr3_photo_path', '/images/slider2.jpg');
                    $wr3Para2 = setting('wr3_para2', 'Melalui pembinaan karakter, dukungan beasiswa, jajaran organisasi kemahasiswaan (Ormawa), serta program penunjang karir alumni, kami berharap dapat melahirkan generasi muda yang tangguh dan berakhlak mulia.');
                    $wr3Quote = setting('wr3_quote', '"Membina Karakter Islami, Mengembangkan Potensi, Mengukir Prestasi."');
                @endphp
                <div class="md:col-span-4 flex justify-center reveal-left">
                    <div class="relative w-64 h-80 rounded-3xl overflow-hidden shadow-2xl border-4 border-white bg-slate-100 flex items-center justify-center">
                        <div class="absolute inset-0 bg-gradient-to-t from-brand-green-950/70 to-transparent z-10"></div>
                        @if(!empty($wr3Photo))
                            <img src="{{ $wr3Photo }}" alt="Foto WR III" class="absolute inset-0 w-full h-full object-cover">
                        @else
                            <svg class="w-48 h-48 text-slate-300 mt-12 animate-pulse" fill="currentColor" viewBox="0 0 24 24"><path d="M12 12c2.21 0 4-1.79 4-4s-1.79-4-4-4-4 1.79-4 4 1.79 4 4 4zm0 2c-2.67 0-8 1.34-8 4v2h16v-2c0-2.66-5.33-4-8-4z"></path></svg>
                        @endif
                        <div class="absolute bottom-6 left-4 right-4 z-20 text-center text-white">
                            <h4 class="font-bold text-sm tracking-wide">{{ setting('wr3_name', 'Dr. rer. nat. Syafrimen Yasin, M.S.') }}</h4>
                            <p class="text-[10px] text-brand-gold-500 font-semibold uppercase tracking-widest mt-0.5">Wakil Rektor III</p>
                        </div>
                    </div>
                </div>
                <!-- Teks Sambutan -->
                <div class="md:col-span-8 space-y-6 reveal-right">
                    <div class="flex items-center space-x-2 text-brand-green-600 font-bold text-xs uppercase tracking-widest">
                        <span class="w-8 h-[2px] bg-brand-green-500 inline-block"></span>
                        <span>Welcome Message</span>
                    </div>
                    <h2 class="text-3xl font-bold font-display text-slate-900 tracking-tight leading-tight">Sambutan Wakil Rektor III</h2>
                    <p class="text-slate-600 text-sm md:text-base leading-relaxed text-justify">
                        {{ setting('wr3_para1', 'Selamat datang di portal informasi resmi Bidang Kemahasiswaan & Alumni Universitas Baiturrahmah.') }}
                    </p>
                    @if(!empty($wr3Para2))
                        <p class="text-slate-600 text-sm md:text-base leading-relaxed text-justify">{{ $wr3Para2 }}</p>
                    @endif
                    @if(!empty($wr3Quote))
                        <div class="flex items-center space-x-4 pt-2">
                            <div class="text-brand-green-900 font-display font-semibold text-xs border-l-4 border-brand-gold-500 pl-4 italic">
                                {{ $wr3Quote }}
                            </div>
                        </div>
                    @endif
                </div>
            </div>
        </div>
    </section>

    {{-- ================================================================
         PROFIL / ABOUT SECTION + Video Widget — FULL DINAMIS
    ================================================================ --}}
    <section id="about" class="py-24 max-w-[1440px] mx-auto px-4 sm:px-8 lg:px-12">
        <div class="grid grid-cols-1 lg:grid-cols-2 gap-16 items-center">
            <!-- Text Content -->
            <div class="reveal-left space-y-6">
                <div class="flex items-center space-x-2 text-brand-green-600 font-bold text-xs uppercase tracking-widest">
                    <span class="w-8 h-[2px] bg-brand-green-500 inline-block"></span>
                    <span>{{ setting('about_label', 'Tentang Kemahasiswaan') }}</span>
                </div>
                <h2 class="text-3xl sm:text-4xl font-bold font-display tracking-tight text-slate-900 leading-tight">
                    {{ setting('about_title', 'Mengoptimalkan Potensi Mahasiswa Menuju Lulusan Berdaya Saing') }}
                </h2>
                <p class="text-slate-600 text-sm md:text-base leading-relaxed text-justify">
                    {{ setting('about_description', 'Bidang Kemahasiswaan & Alumni Universitas Baiturrahmah mengelola berbagai program pembinaan minat, bakat, penalaran, dan organisasi mahasiswa. Kami berkomitmen untuk menyediakan lingkungan kampus yang dinamis, inklusif, dan mendukung kesejahteraan mahasiswa.') }}
                </p>
                <div class="grid grid-cols-2 gap-4 pt-4">
                    <div class="flex items-start space-x-3">
                        <div class="w-6 h-6 rounded bg-brand-green-100 flex items-center justify-center text-brand-green-600 mt-1 flex-shrink-0">
                            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"></path></svg>
                        </div>
                        <div>
                            <h4 class="font-bold text-slate-800 text-sm">{{ setting('about_point1_title', 'Akreditasi Unggul') }}</h4>
                            <p class="text-slate-500 text-[11px] mt-0.5">{{ setting('about_point1_desc', 'Semua program studi diploma telah terakreditasi BAN-PT dan LAM-PTKes.') }}</p>
                        </div>
                    </div>
                    <div class="flex items-start space-x-3">
                        <div class="w-6 h-6 rounded bg-brand-green-100 flex items-center justify-center text-brand-green-600 mt-1 flex-shrink-0">
                            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"></path></svg>
                        </div>
                        <div>
                            <h4 class="font-bold text-slate-800 text-sm">{{ setting('about_point2_title', 'Mitra DUDI Luas') }}</h4>
                            <p class="text-slate-500 text-[11px] mt-0.5">{{ setting('about_point2_desc', 'Jejaring magang klinis di berbagai rumah sakit & industri kesehatan.') }}</p>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Video Widget — URL & judul dari setting -->
            @php
                $rawVideoUrl = setting('video_youtube_url', 'https://www.youtube.com/embed/jXZVBE2wbs0');
                $videoTitle  = setting('video_title', 'Jelajahi Kegiatan, Ormawa, dan Pembinaan Kemahasiswaan UNBRAH');
                // Ekstrak video ID
                $vId = '';
                if (str_contains($rawVideoUrl, 'youtube.com/watch')) {
                    parse_str(parse_url($rawVideoUrl, PHP_URL_QUERY), $vq);
                    $vId = $vq['v'] ?? '';
                } elseif (str_contains($rawVideoUrl, 'youtu.be/')) {
                    $vId = explode('?', last(explode('youtu.be/', $rawVideoUrl)))[0];
                } elseif (str_contains($rawVideoUrl, 'youtube.com/embed/')) {
                    $vId = explode('?', last(explode('embed/', $rawVideoUrl)))[0];
                }
                $embedUrl   = $vId ? "https://www.youtube.com/embed/{$vId}" : $rawVideoUrl;
                $thumbUrl   = $vId ? "https://img.youtube.com/vi/{$vId}/hqdefault.jpg" : '/images/slider2.jpg';
            @endphp
            <div id="video-widget-container" class="reveal-right relative rounded-3xl overflow-hidden shadow-2xl border-4 border-white group h-80 bg-slate-900">
                <div class="absolute inset-0 flex flex-col justify-between">
                    <div class="absolute inset-0 bg-brand-green-950/20 group-hover:bg-brand-green-950/10 transition-colors z-10 duration-300"></div>
                    <img src="{{ $thumbUrl }}" alt="Video Profil Preview" class="absolute inset-0 w-full h-full object-cover group-hover:scale-105 transition-transform duration-700">
                    <!-- Play Button -->
                    <div class="absolute inset-0 flex items-center justify-center z-20">
                        <button onclick="playProfileVideo('{{ $embedUrl }}')" class="w-16 h-16 rounded-full bg-brand-gold-500 hover:bg-brand-gold-600 text-brand-green-950 flex items-center justify-center shadow-2xl hover:scale-110 transition-all duration-300 cursor-pointer border-none focus:outline-none">
                            <svg class="w-8 h-8 fill-current translate-x-0.5" viewBox="0 0 24 24"><path d="M8 5v14l11-7z"></path></svg>
                        </button>
                    </div>
                    <div class="absolute bottom-4 left-4 right-4 z-20 bg-brand-green-950/80 backdrop-blur rounded-xl p-3 border border-white/15">
                        <span class="text-brand-gold-500 text-[10px] font-bold tracking-widest uppercase block">Video Profil</span>
                        <h4 class="text-white text-xs font-bold mt-0.5 block">{{ $videoTitle }}</h4>
                    </div>
                </div>
            </div>
        </div>
    </section>

    {{-- ================================================================
         AGENDA TIMELINE SECTION — Dinamis dari tabel kegiatan
    ================================================================ --}}
    <section class="py-20 bg-brand-green-950 text-white overflow-hidden">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
            <div class="text-center space-y-4 mb-16">
                <span class="inline-block bg-brand-gold-500/10 text-brand-gold-500 border border-brand-gold-500/20 text-xs font-bold tracking-widest uppercase px-4 py-1.5 rounded-full">Agenda Kemahasiswaan</span>
                <h2 class="text-3xl sm:text-4xl font-bold font-display tracking-tight">Kalender Kegiatan Terdekat Mahasiswa</h2>
                <p class="text-slate-400 text-sm max-w-xl mx-auto leading-relaxed">Ikuti jadwal kegiatan orientasi, pemilihan mahasiswa berprestasi, seminar pembekalan karir, dan pelantikan organisasi mahasiswa.</p>
            </div>

            @if($upcomingEvents->count() > 0)
                <div class="reveal grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-8">
                    @foreach ($upcomingEvents->take(4) as $index => $event)
                        <div class="group relative bg-brand-green-900/40 hover:bg-brand-green-900/70 border border-brand-green-800/60 hover:border-brand-gold-500/30 rounded-2xl p-6 transition-all duration-300 flex flex-col justify-between">
                            <div>
                                <span class="inline-block bg-brand-gold-500 text-brand-green-950 font-bold text-xs px-3 py-1 rounded-full mb-4">
                                    {{ $event->date ? $event->date->translatedFormat('d F Y') : '-' }}
                                </span>
                                <h4 class="font-bold text-base text-white group-hover:text-brand-gold-500 transition-colors">{{ $event->title }}</h4>
                                <p class="text-slate-400 text-xs mt-2 leading-relaxed text-justify line-clamp-3">{{ $event->description }}</p>
                            </div>
                            <div class="mt-6 pt-4 border-t border-brand-green-800/50">
                                <span class="text-[10px] text-slate-500 uppercase tracking-widest font-bold">{{ $event->location ?? '-' }}</span>
                            </div>
                        </div>
                    @endforeach
                </div>
            @else
                {{-- Fallback jika belum ada kegiatan di database --}}
                <div class="reveal text-center py-12 text-slate-500">
                    <svg class="w-12 h-12 mx-auto mb-4 text-brand-green-800" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z"/></svg>
                    <p class="text-sm font-semibold text-slate-400">Belum ada agenda yang ditambahkan.</p>
                    <p class="text-xs text-slate-500 mt-1">Tambahkan kegiatan melalui menu <span class="text-brand-gold-500 font-semibold">Admin → Kegiatan</span>.</p>
                </div>
            @endif
        </div>
    </section>

    {{-- ================================================================
         KILAS BERITA SECTION (Featured + Sidebar Layout)
    ================================================================ --}}
    <section id="news" class="py-24 max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
        <div class="flex flex-col md:flex-row md:items-end justify-between mb-12">
            <div class="space-y-4">
                <div class="flex items-center space-x-2 text-brand-green-600 font-bold text-xs uppercase tracking-widest">
                    <span class="w-8 h-[2px] bg-brand-green-500 inline-block"></span>
                    <span>Warta & Berita</span>
                </div>
                <h2 class="text-3xl sm:text-4xl font-bold font-display tracking-tight text-slate-900 leading-tight">Berita Terkini Universitas</h2>
            </div>
            <a href="/berita" wire:navigate class="mt-4 md:mt-0 flex items-center space-x-2 bg-brand-green-50 hover:bg-brand-green-100 text-brand-green-900 font-bold text-xs px-5 py-2.5 rounded-full transition-all duration-300 border border-brand-green-200 no-underline">
                <span>Lihat Seluruh Berita</span>
                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M14 5l7 7m0 0l-7 7m7-7H3"></path></svg>
            </a>
        </div>

        @php
            $featuredPost  = $latestPosts->first();
            $sidebarPosts  = $latestPosts->skip(1)->take(3);
        @endphp

        <div class="grid grid-cols-1 lg:grid-cols-12 gap-8">
            <!-- Featured News -->
            @if ($featuredPost)
                <div class="reveal-left lg:col-span-8 bg-white border border-slate-100 rounded-3xl overflow-hidden shadow-lg hover:shadow-2xl transition-all duration-500 group flex flex-col justify-between">
                    <div>
                        <div class="relative overflow-hidden h-72">
                            <div class="absolute inset-0 bg-gradient-to-t from-black/60 to-transparent z-10 opacity-70"></div>
                            @if($featuredPost->image_path)
                                <img src="{{ $featuredPost->image_path }}" alt="{{ $featuredPost->title }}" class="w-full h-full object-cover group-hover:scale-105 transition-transform duration-700">
                            @else
                                <div class="w-full h-full bg-brand-green-950/10 flex items-center justify-center text-4xl">📰</div>
                            @endif
                            <span class="absolute top-4 left-4 z-20 bg-brand-green-900 text-white font-bold text-[10px] tracking-widest uppercase px-3 py-1.5 rounded-full">Berita</span>
                        </div>
                        <div class="p-8 space-y-4">
                            <div class="flex items-center space-x-4 text-xs text-slate-400">
                                <span>📅 {{ $featuredPost->created_at->format('d M Y') }}</span>
                                <span>•</span>
                                <span>✍️ Humas Unbrah</span>
                            </div>
                            <a href="/berita/{{ $featuredPost->slug }}" wire:navigate class="block no-underline">
                                <h3 class="text-xl sm:text-2xl font-bold font-display text-slate-900 group-hover:text-brand-green-600 transition-colors leading-tight">{{ $featuredPost->title }}</h3>
                            </a>
                            <p class="text-slate-600 text-xs sm:text-sm leading-relaxed line-clamp-3">{{ strip_tags($featuredPost->content) }}</p>
                        </div>
                    </div>
                    <div class="px-8 pb-8">
                        <a href="/berita/{{ $featuredPost->slug }}" wire:navigate class="text-[11px] font-bold text-brand-green-900 hover:text-brand-green-700 no-underline">Baca Selengkapnya →</a>
                    </div>
                </div>
            @else
                <div class="reveal-left lg:col-span-8 bg-white border border-slate-100 rounded-3xl overflow-hidden shadow-lg flex items-center justify-center h-64 text-slate-400 text-sm">
                    Berita sedang tidak tersedia.
                </div>
            @endif

            <!-- Berita Lainnya (Sidebar) -->
            <div class="reveal-right lg:col-span-4 space-y-6 flex flex-col">
                <h3 class="text-lg font-bold font-display text-slate-900 pb-2 border-b border-slate-200">📋 Berita Lainnya</h3>
                <div class="space-y-6 flex-1">
                    @forelse ($sidebarPosts as $item)
                        <div class="group flex space-x-4 items-start pb-4 border-b border-slate-100 last:border-0 last:pb-0">
                            @if($item->image_path)
                                <img src="{{ $item->image_path }}" alt="{{ $item->title }}" class="w-20 h-20 rounded-xl object-cover flex-shrink-0 group-hover:scale-105 transition-transform duration-300">
                            @else
                                <div class="w-20 h-20 rounded-xl bg-slate-100 flex items-center justify-center text-2xl flex-shrink-0">📰</div>
                            @endif
                            <div class="space-y-1">
                                <span class="text-[10px] font-bold text-brand-green-600">{{ $item->created_at->format('d M Y') }}</span>
                                <a href="/berita/{{ $item->slug }}" wire:navigate class="block no-underline">
                                    <h4 class="font-bold text-xs text-slate-900 group-hover:text-brand-green-600 transition-colors line-clamp-2 leading-snug">{{ $item->title }}</h4>
                                </a>
                            </div>
                        </div>
                    @empty
                        <p class="text-xs text-slate-400">Berita lainnya tidak tersedia.</p>
                    @endforelse
                </div>
            </div>
        </div>
    </section>

    {{-- Script Slider & Scroll Reveal --}}
    @push('scripts')
    <script>
        // --- SLIDER LOGIC ---
        let currentSlide = 0;
        const slides = document.querySelectorAll('.slide');
        const indicators = document.querySelectorAll('.indicator');
        let sliderInterval = setInterval(autoSlide, 5000);

        function updateSlider() {
            slides.forEach((slide, i) => {
                slide.classList.toggle('active', i === currentSlide);
                if (indicators[i]) {
                    indicators[i].classList.toggle('bg-brand-gold-500', i === currentSlide);
                    indicators[i].classList.toggle('w-10', i === currentSlide);
                    indicators[i].classList.toggle('bg-white/40', i !== currentSlide);
                    indicators[i].classList.toggle('w-4', i !== currentSlide);
                }
            });
        }

        function autoSlide() { currentSlide = (currentSlide + 1) % slides.length; updateSlider(); }
        function nextSlide() { clearInterval(sliderInterval); currentSlide = (currentSlide + 1) % slides.length; updateSlider(); sliderInterval = setInterval(autoSlide, 5000); }
        function prevSlide() { clearInterval(sliderInterval); currentSlide = (currentSlide - 1 + slides.length) % slides.length; updateSlider(); sliderInterval = setInterval(autoSlide, 5000); }
        function goToSlide(i) { clearInterval(sliderInterval); currentSlide = i; updateSlider(); sliderInterval = setInterval(autoSlide, 5000); }

        // --- SCROLL REVEAL ---
        const revealEls = document.querySelectorAll('.reveal, .reveal-left, .reveal-right, .reveal-scale');
        const observer = new IntersectionObserver((entries) => {
            entries.forEach(e => { if (e.isIntersecting) e.target.classList.add('active'); });
        }, { threshold: 0.15, rootMargin: '0px 0px -50px 0px' });
        revealEls.forEach(el => observer.observe(el));

        // --- VIDEO PLAYER ---
        function playProfileVideo(url) {
            const container = document.getElementById('video-widget-container');
            // Pastikan URL sudah embed, tambahkan autoplay
            let embedUrl = url;
            if (!url.includes('autoplay')) {
                embedUrl = url + (url.includes('?') ? '&' : '?') + 'autoplay=1';
            }
            container.innerHTML = `<iframe src="${embedUrl}" class="w-full h-full border-none rounded-3xl" allow="accelerometer; autoplay; clipboard-write; encrypted-media; gyroscope; picture-in-picture" allowfullscreen></iframe>`;
        }
    </script>
    @endpush

</div>
