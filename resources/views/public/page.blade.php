@extends('layouts.frontend')

@section('title', $page->title . ' - Kemahasiswaan Unbrah')

@section('content')
<div class="pb-12 space-y-12">

    @forelse ($page->content ?? [] as $block)
        @if ($block['type'] === 'hero')
            {{-- 1. HERO BANNER (FULL WIDTH) --}}
            <section class="hero-section w-full py-20 md:py-24 px-6 bg-gradient-to-br from-brand-green-950 to-brand-green-900 text-{{ $block['data']['align'] ?? 'center' }} shadow-md text-white">
                <div class="max-w-7xl mx-auto">
                    @if (!empty($block['data']['label']))
                        <p class="text-xs font-bold uppercase tracking-[0.2em] mb-3" style="color: {{ $block['data']['highlight_color'] ?? 'var(--color-accent)' }}">
                            {{ $block['data']['label'] }}
                        </p>
                    @endif
                    <h1 class="text-3xl md:text-5xl font-extrabold tracking-tight leading-tight">
                        {{ $block['data']['title'] ?? '' }}
                        @if (!empty($block['data']['highlight']))
                            <span style="color: {{ $block['data']['highlight_color'] ?? 'var(--color-accent)' }}">
                                {{ $block['data']['highlight'] }}
                            </span>
                        @endif
                    </h1>
                    @if (!empty($block['data']['subtitle']))
                        <p class="text-sm md:text-base text-white/80 mt-4 max-w-3xl {{ ($block['data']['align'] ?? 'center') === 'center' ? 'mx-auto' : '' }}">
                            {{ $block['data']['subtitle'] }}
                        </p>
                    @endif
                </div>
            </section>
        @else
            {{-- NON-HERO BLOCKS (WRAPPED IN CONTAINER) --}}
            <div class="max-w-7xl mx-auto px-6">
                @switch($block['type'])

            {{-- 2. TWO COLUMN --}}
            @case('two_col')
                @php
                    $ratio = $block['data']['ratio'] ?? '50/50';
                    [$l, $r] = explode('/', $ratio);
                    $leftBg    = !empty($block['data']['left_bg_color'])  ? 'background-color: ' . $block['data']['left_bg_color']  . ';' : '';
                    $leftText  = 'color: ' . ($block['data']['left_text_color']  ?? 'var(--color-text)') . ';';
                    $leftClass = !empty($block['data']['left_bg_color'])  ? 'p-8 rounded-3xl border border-[#e5e2d9]/40 shadow-sm' : '';
                    $rightBg   = !empty($block['data']['right_bg_color']) ? 'background-color: ' . $block['data']['right_bg_color'] . ';' : '';
                    $rightText = 'color: ' . ($block['data']['right_text_color'] ?? 'var(--color-text)') . ';';
                    $rightClass= !empty($block['data']['right_bg_color']) ? 'p-8 rounded-3xl border border-[#e5e2d9]/40 shadow-sm' : '';
                    
                    // Tailwind needs explicit full string classes for safelisting
                    $lCol = $l === '50' ? 'md:col-span-6' : ($l === '40' ? 'md:col-span-5' : 'md:col-span-7');
                    $rCol = $r === '50' ? 'md:col-span-6' : ($r === '60' ? 'md:col-span-7' : 'md:col-span-5');
                @endphp
                <div class="grid grid-cols-1 md:grid-cols-12 gap-8 items-start">
                    <div class="{{ $leftClass }} {{ $lCol }}" style="{{ $leftBg }} {{ $leftText }}">
                        @if (!empty($block['data']['left_heading']))
                            <h3 class="font-extrabold text-xl mb-4" style="{{ $leftText }}">{{ $block['data']['left_heading'] }}</h3>
                        @endif
                        <div class="ck-content leading-relaxed text-sm">{!! $block['data']['left_content'] ?? '' !!}</div>
                    </div>
                    <div class="{{ $rightClass }} {{ $rCol }}" style="{{ $rightBg }} {{ $rightText }}">
                        @if (!empty($block['data']['right_heading']))
                            <h3 class="font-extrabold text-xl mb-4" style="{{ $rightText }}">{{ $block['data']['right_heading'] }}</h3>
                        @endif
                        <div class="ck-content leading-relaxed text-sm">{!! $block['data']['right_content'] ?? '' !!}</div>
                    </div>
                </div>
                @break

            {{-- 3. HEADING --}}
            @case('heading')
                @php
                    $level     = $block['data']['level'] ?? 'h2';
                    $sizeClass = $level === 'h2' ? 'text-2xl' : ($level === 'h3' ? 'text-xl' : 'text-lg');
                @endphp
                <{{ $level }} class="{{ $sizeClass }} font-extrabold text-[#052c22] border-b border-[#e5e2d9] pb-2 mt-8 mb-4">
                    {{ $block['data']['text'] ?? '' }}
                </{{ $level }}>
                @break

            {{-- 4. TEXT (RICH CONTENT) --}}
            @case('text')
                <div class="ck-content text-sm leading-relaxed prose max-w-none">
                    {!! $block['data']['html'] ?? '' !!}
                </div>
                @break

            {{-- 5. IMAGE --}}
            @case('image')
                @php
                    $align      = $block['data']['align'] ?? 'center';
                    $figureClass = 'my-6';
                    $imgClass    = 'rounded-3xl border border-[#e5e2d9] shadow-sm max-w-full';
                    if ($align === 'full') {
                        $imgClass .= ' w-full h-auto object-cover';
                    } elseif ($align === 'left') {
                        $figureClass .= ' float-left mr-6 max-w-md';
                    } elseif ($align === 'right') {
                        $figureClass .= ' float-right ml-6 max-w-md';
                    } else {
                        $figureClass .= ' text-center';
                        $imgClass    .= ' mx-auto max-h-[450px] object-contain';
                    }
                @endphp
                <figure class="{{ $figureClass }}">
                    <img src="{{ $block['data']['url'] }}" alt="{{ $block['data']['caption'] ?? '' }}" class="{{ $imgClass }}">
                    @if (!empty($block['data']['caption']))
                        <figcaption class="text-xs text-[#8c887d] mt-2 italic text-center">{{ $block['data']['caption'] }}</figcaption>
                    @endif
                </figure>
                <div class="clear-both"></div>
                @break

            {{-- 6. DIVIDER --}}
            @case('divider')
                <hr class="border-[#e5e2d9] my-8 opacity-60">
                @break

            {{-- 7. CALLOUT --}}
            @case('callout')
                @php
                    $colors = [
                        'blue'   => ['bg' => 'bg-blue-50/70',    'border' => 'border-blue-200',    'text' => 'text-blue-800',    'bullet' => '🔵'],
                        'green'  => ['bg' => 'bg-emerald-50/70', 'border' => 'border-emerald-200', 'text' => 'text-emerald-800', 'bullet' => '🟢'],
                        'yellow' => ['bg' => 'bg-amber-50/70',   'border' => 'border-amber-200',   'text' => 'text-amber-800',   'bullet' => '🟡'],
                        'red'    => ['bg' => 'bg-red-50/70',     'border' => 'border-red-200',     'text' => 'text-red-800',     'bullet' => '🔴'],
                    ];
                    $c = $colors[$block['data']['color'] ?? 'blue'];
                @endphp
                <div class="flex items-start gap-3 p-5 rounded-2xl border {{ $c['bg'] }} {{ $c['border'] }} {{ $c['text'] }} text-xs font-semibold">
                    <span>{{ $c['bullet'] }}</span>
                    <div class="flex-1 leading-relaxed">{{ $block['data']['text'] ?? '' }}</div>
                </div>
                @break

            {{-- 8. ACCORDION (FAQ) --}}
            @case('accordion')
                <div class="space-y-3" x-data="{ active: null }">
                    @foreach ($block['data']['items'] ?? [] as $subIdx => $item)
                        <div class="border border-[#e5e2d9] rounded-2xl bg-white overflow-hidden shadow-sm">
                            <button type="button" @click="active = (active === {{ $subIdx }} ? null : {{ $subIdx }})"
                                class="w-full flex items-center justify-between p-5 text-left font-bold text-sm text-[#052c22] hover:bg-[#faf9f6]/40 transition duration-200">
                                <span>{{ $item['title'] ?? '' }}</span>
                                <span class="text-xs transition-transform duration-200" :class="active === {{ $subIdx }} ? 'rotate-180' : ''">▼</span>
                            </button>
                            <div class="px-5 pb-5 border-t border-[#e5e2d9]/40 mt-3 ck-content text-xs leading-relaxed text-[#5c5950]"
                                 x-show="active === {{ $subIdx }}" x-collapse x-cloak>
                                {!! $item['content'] ?? '' !!}
                            </div>
                        </div>
                    @endforeach
                </div>
                @break

            {{-- 9. DOWNLOADS --}}
            @case('downloads')
                <div class="flex flex-wrap gap-4">
                    @foreach ($block['data']['items'] ?? [] as $item)
                        @php
                            $isPdf    = ($item['type'] ?? 'pdf') === 'pdf';
                            $icon     = $isPdf ? '📄' : '🔗';
                            $btnStyle = $isPdf
                                ? 'bg-[#faf9f6] text-[#052c22] border border-[#e5e2d9] hover:bg-[#e5e2d9]/30'
                                : 'bg-[#052c22] text-white hover:opacity-90';
                        @endphp
                        <a href="{{ $item['url'] ?? '#' }}" target="_blank"
                           class="inline-flex items-center gap-2 px-5 py-3 rounded-xl text-xs font-bold transition shadow-sm no-underline {{ $btnStyle }}">
                            <span>{{ $icon }}</span>
                            <span>{{ $item['label'] ?? 'Link / Unduh' }}</span>
                        </a>
                    @endforeach
                </div>
                @break

            {{-- 10. CARD GRID --}}
            @case('card_grid')
                @php
                    $cols      = $block['data']['columns'] ?? '3';
                    $gridClass = $cols == '2'
                        ? 'grid-cols-1 md:grid-cols-2'
                        : ($cols == '4' ? 'grid-cols-1 sm:grid-cols-2 lg:grid-cols-4' : 'grid-cols-1 md:grid-cols-3');
                @endphp
                <div class="grid gap-6 {{ $gridClass }}">
                    @foreach ($block['data']['items'] ?? [] as $item)
                        @php
                            $hasLink = !empty($item['link']);
                            $tag     = $hasLink ? 'a' : 'div';
                            $href    = $hasLink ? 'href="' . $item['link'] . '"' : '';
                        @endphp
                        <{{ $tag }} {!! $href !!} class="bg-white border border-[#e5e2d9] rounded-2xl overflow-hidden shadow-sm hover:border-[#c8b282]/50 hover:shadow transition duration-300 no-underline block">
                            @if (!empty($item['image']))
                                <img src="{{ $item['image'] }}" alt="{{ $item['title'] }}" class="w-full h-44 object-cover border-b border-[#e5e2d9]">
                            @endif
                            <div class="p-5">
                                <h4 class="font-bold text-sm text-[#052c22] mb-2">{{ $item['title'] ?? 'Judul Kartu' }}</h4>
                                <p class="text-xs text-[#8c887d] leading-relaxed line-clamp-3">{{ $item['desc'] ?? '' }}</p>
                                @if ($hasLink)
                                    <span class="text-[10px] font-bold text-[#c8b282] mt-3 inline-block">Selengkapnya &rarr;</span>
                                @endif
                            </div>
                        </{{ $tag }}>
                    @endforeach
                </div>
                @break

            {{-- 11. CUSTOM HTML/JS CODE --}}
            @case('custom_html')
                <div class="custom-html-block w-full overflow-x-auto">
                    {!! $block['data']['code'] ?? '' !!}
                </div>
                @break

        @endswitch
            </div>
        @endif
    @empty
        <div class="max-w-7xl mx-auto px-6 text-center py-20 text-[#8c887d]">
            <p class="text-xs">Halaman ini belum memiliki konten.</p>
        </div>
    @endforelse

</div>
@endsection
