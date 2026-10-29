<div>
    @section('title', 'Organisasi Mahasiswa - Kemahasiswaan UNBRAH')

    <!-- HERO SECTION -->
    <section class="relative bg-gradient-to-br from-brand-green-950 to-brand-green-900 py-12 text-white overflow-hidden shadow-xl">
        <div class="max-w-[1440px] mx-auto px-4 sm:px-8 lg:px-12 text-center relative z-10 space-y-3">
            <h1 class="text-3xl sm:text-5xl md:text-6xl font-bold font-display tracking-tight text-white leading-tight drop-shadow">
                Organisasi Mahasiswa <br class="hidden sm:inline">
                <span class="bg-gradient-to-r from-brand-gold-500 to-brand-gold-100 bg-clip-text text-transparent">Profil Lembaga & UKM</span>
            </h1>
            <p class="text-base md:text-lg text-slate-300/90 max-w-none mx-auto font-light leading-relaxed">
                Informasi sejarah, visi-misi, serta jajaran pengurus pengelola organisasi kemahasiswaan di lingkungan Universitas Baiturrahmah.
            </p>
        </div>
    </section>

    <!-- CONTENT SECTION -->
    <section class="py-16 bg-slate-50 min-h-[700px]">
        <div class="max-w-[1440px] mx-auto px-4 sm:px-8 lg:px-12 space-y-12">
            @if($ormawas->count() > 1)
                <div class="flex flex-wrap justify-center gap-2.5">
                    @foreach($ormawas as $org)
                        <button type="button"
                                wire:click="switchTab('{{ $org->slug }}')"
                                class="px-5 py-2.5 rounded-xl text-xs font-bold transition-all cursor-pointer {{ $activeOrmawa?->slug === $org->slug ? 'bg-brand-green-900 text-white shadow-md ring-2 ring-brand-gold-500/40' : 'bg-white text-slate-600 hover:bg-brand-green-50 hover:text-brand-green-900 border border-slate-200' }}">
                            {{ $org->name }}
                        </button>
                    @endforeach
                </div>
            @endif
            
            @if($activeOrmawa)
                <div class="tab-content active space-y-12 animate-fade-in">
                    <!-- Detail Sejarah & Profil (Top Section) -->
                    <div class="bg-white rounded-3xl p-8 md:p-12 shadow-sm border border-slate-100 space-y-8">
                        <div class="space-y-4">
                            <div class="flex flex-col sm:flex-row sm:items-center justify-between gap-4">
                                <div class="space-y-2">
                                    <span class="px-3.5 py-1.5 text-[11px] font-extrabold uppercase tracking-widest bg-brand-green-50 text-brand-green-800 rounded-full border border-brand-green-200 inline-block">Profil Lembaga</span>
                                    <h3 class="text-2xl md:text-3xl font-bold font-display text-slate-900">{{ $activeOrmawa->name }}</h3>
                                </div>
                                @if($activeOrmawa->logo_path)
                                    <img src="{{ $activeOrmawa->logo_path }}" alt="Logo {{ $activeOrmawa->name }}" class="w-20 h-20 object-contain rounded-2xl border border-slate-100 p-2 bg-white shadow-sm">
                                @endif
                            </div>
                            <div class="w-16 h-1 bg-brand-green-600 rounded"></div>
                            
                            <p class="text-slate-650 text-sm md:text-base leading-relaxed text-justify font-light">
                                {{ $activeOrmawa->description ?? 'Deskripsi organisasi belum diisi oleh admin.' }}
                            </p>
                        </div>

                        @if(!empty($activeOrmawa->focus_items))
                            <div class="border-t border-slate-100 pt-8 space-y-4">
                                <h4 class="font-bold font-display text-slate-900 text-sm md:text-base tracking-wide uppercase">Fokus Kerja & Agenda Strategis</h4>
                                <ul class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-4 gap-4 text-xs text-slate-650">
                                    @foreach($activeOrmawa->focus_items as $item)
                                        <li class="flex items-center space-x-3 bg-slate-50 p-4 rounded-2xl border border-slate-100">
                                            <span class="w-2.5 h-2.5 rounded-full bg-brand-green-600 shrink-0"></span>
                                            <span>{{ $item }}</span>
                                        </li>
                                    @endforeach
                                </ul>
                            </div>
                        @endif
                    </div>

                    <!-- Pengurus Inti (Bottom Section) -->
                    <div class="space-y-6">
                        <div class="text-center space-y-1">
                            <h4 class="text-xl md:text-2xl font-bold font-display text-slate-900">Struktur Pengurus Inti</h4>
                            <p class="text-xs text-slate-500 font-light">Jajaran Pengurus & Pimpinan Utama {{ $activeOrmawa->name }}</p>
                        </div>

                        <div class="grid grid-cols-2 sm:grid-cols-3 lg:grid-cols-4 gap-5">
                            @forelse($activeOrmawa->structure ?? [] as $member)
                                <div class="bg-white p-4 rounded-2xl text-slate-800 shadow-md border border-slate-100 relative overflow-hidden group hover:-translate-y-1 transition-all duration-300 flex flex-col space-y-4">
                                    <div class="w-full aspect-[3/4] rounded-xl overflow-hidden bg-slate-100 relative shadow-inner">
                                        @if(!empty($member['photo']))
                                            <img src="{{ $member['photo'] }}"
                                                 class="w-full h-full object-cover transform group-hover:scale-105 transition-transform duration-500"
                                                 alt="Foto {{ $member['name'] }}"
                                                 onerror="this.onerror=null; this.parentElement.innerHTML='<div class=\'w-full h-full flex flex-col items-center justify-center bg-slate-50 text-slate-400\'><span class=\'text-5xl\'>👤</span></div>'">
                                        @else
                                            <div class="w-full h-full flex flex-col items-center justify-center bg-slate-50 text-slate-400">
                                                <span class="text-5xl">👤</span>
                                                <span class="text-[9px] font-bold uppercase tracking-wider mt-2">Foto 3:4</span>
                                            </div>
                                        @endif
                                    </div>
                                    <div class="text-center space-y-2 flex-grow flex flex-col justify-between">
                                        <div class="space-y-1">
                                            <span class="px-3 py-1 text-[10px] font-bold uppercase tracking-wider bg-brand-green-50 text-brand-green-800 rounded-full border border-brand-green-150 inline-block">
                                                {{ $member['role'] ?? 'Pengurus' }}
                                            </span>
                                            <h5 class="text-sm font-bold font-display text-slate-900 leading-tight">{{ $member['name'] ?? 'Nama Pengurus' }}</h5>
                                        </div>
                                        @if(!empty($member['faculty']))
                                            <p class="text-[11px] text-slate-500 font-medium pt-2 border-t border-slate-100">{{ $member['faculty'] }}</p>
                                        @endif
                                    </div>
                                </div>
                            @empty
                                <div class="col-span-full text-center py-14 text-slate-400">
                                    <div class="text-4xl mb-3">👥</div>
                                    <p class="text-sm font-bold">Susunan pengurus belum diisi.</p>
                                    <p class="text-xs mt-1">Admin dapat menambahkannya melalui Backoffice → Organisasi (Ormawa) → Edit.</p>
                                </div>
                            @endforelse
                        </div>
                    </div>
                </div>
            @else
                <div class="text-center py-20 bg-white rounded-3xl border border-slate-100 shadow-sm text-slate-400">
                    <p class="text-xs">Organisasi tidak ditemukan atau belum aktif.</p>
                </div>
            @endif

        </div>
    </section>
</div>
