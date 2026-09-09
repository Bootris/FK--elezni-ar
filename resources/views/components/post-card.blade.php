@props(['post', 'large' => false])

@php
    $isVideo = (bool) $post->video_embed_url;
    $film = ['film', 'film film-red', 'film film-gold'][$post->id % 3];
    $imageUrl = $post->featured_image
        ? Illuminate\Support\Facades\Storage::disk('public')->url($post->featured_image)
        : null;
@endphp

<article {{ $attributes->merge(['class' => 'group flex h-full flex-col']) }}>
    <a href="{{ route('news.show', $post->slug) }}" class="relative block overflow-hidden rounded-sm {{ $large ? 'aspect-[16/10]' : 'aspect-[3/2]' }}">
        @if ($imageUrl)
            <img src="{{ $imageUrl }}" alt="{{ $post->title }}" class="h-full w-full object-cover transition duration-500 group-hover:scale-105" loading="lazy">
        @else
            <span class="{{ $film }} absolute inset-0 transition duration-500 group-hover:scale-105"></span>
            <img src="{{ asset('images/logo.png') }}" alt="" class="absolute left-1/2 top-1/2 z-[1] h-16 w-16 -translate-x-1/2 -translate-y-1/2 opacity-25" aria-hidden="true">
        @endif

        @if ($post->category)
            <span class="absolute left-3 top-3 z-10 rounded-sm bg-navy-950/80 px-2.5 py-1 font-display text-[11px] font-bold uppercase tracking-[0.14em] text-white backdrop-blur">{{ $post->category->name }}</span>
        @endif

        @if ($isVideo)
            <span class="play-ring absolute left-1/2 top-1/2 z-10 h-12 w-12 -translate-x-1/2 -translate-y-1/2" aria-hidden="true"></span>
        @endif
    </a>

    <div class="mt-4 flex items-center gap-3 text-[12px] font-semibold uppercase tracking-[0.12em] text-ink-400">
        <time datetime="{{ $post->published_at?->toDateString() }}">{{ $post->published_at?->format('d.m.Y.') }}</time>
        @if ($isVideo)
            <span class="text-red-600">{{ __('club.news.video_badge') }}</span>
        @endif
    </div>

    <h3 class="display mt-2 text-[1.65rem] leading-[0.95] text-navy-900 transition group-hover:text-red-600 {{ $large ? 'sm:text-4xl' : '' }}">
        <a href="{{ route('news.show', $post->slug) }}">{{ $post->title }}</a>
    </h3>

    @if ($large && $post->excerpt)
        <p class="mt-3 line-clamp-3 text-ink-600">{{ $post->excerpt }}</p>
    @endif
</article>
