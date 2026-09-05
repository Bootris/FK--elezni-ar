@props(['kicker' => null, 'title', 'text' => null, 'href' => null, 'link' => null, 'dark' => false])

<div {{ $attributes->merge(['class' => 'reveal flex flex-wrap items-end justify-between gap-6 border-b-2 pb-6 ' . ($dark ? 'border-white/15' : 'border-navy-900')]) }}>
    <div>
        @if ($kicker)
            <p class="kicker flex items-center gap-2 {{ $dark ? 'text-gold-400' : 'text-red-600' }}">
                <span class="h-1.5 w-1.5 rounded-full {{ $dark ? 'bg-gold-400' : 'bg-red-500' }}" aria-hidden="true"></span>{{ $kicker }}
            </p>
        @endif
        <h2 class="display mt-2 text-4xl sm:text-5xl lg:text-6xl {{ $dark ? 'text-white' : 'text-navy-900' }}">{{ $title }}</h2>
        @if ($text)
            <p class="mt-3 max-w-xl {{ $dark ? 'text-navy-200' : 'text-ink-600' }}">{{ $text }}</p>
        @endif
    </div>
    @if ($href && $link)
        <a href="{{ $href }}" class="inline-flex shrink-0 items-center gap-2 font-display text-base font-bold uppercase tracking-wide transition hover:gap-3 {{ $dark ? 'text-gold-400' : 'text-red-600' }}">
            {{ $link }}
            <svg class="h-4 w-4" viewBox="0 0 20 20" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" aria-hidden="true"><path d="M3 10h13M12 5l5 5-5 5" /></svg>
        </a>
    @endif
</div>
