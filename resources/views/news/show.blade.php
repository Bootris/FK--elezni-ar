@php
    use Illuminate\Support\Facades\Storage;
    $imageUrl = $post->featured_image ? url(Storage::disk('public')->url($post->featured_image)) : null;
@endphp

<x-site-layout :title="$post->seo_title ?: $post->title" :description="$post->seo_description ?: $post->excerpt" :image="$imageUrl">

    <section class="bg-navy-950 bg-pitch text-white">
        <div class="mx-auto max-w-4xl px-6 py-12 sm:py-16">
            <a href="{{ str_contains(url()->previous(), '/video') ? route('video.index') : route('news.index') }}"
                class="inline-flex items-center gap-2 font-display text-sm font-bold uppercase tracking-wider text-navy-200 transition hover:text-gold-400">
                <svg class="h-4 w-4" viewBox="0 0 20 20" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" aria-hidden="true"><path d="M17 10H4M8 5l-5 5 5 5" /></svg>
                {{ __('club.news.back') }}
            </a>

            <div class="mt-8 flex flex-wrap items-center gap-x-4 gap-y-2 text-xs text-navy-200">
                @if ($post->category)
                    <span class="kicker flex items-center gap-2 text-gold-400"><span class="h-1.5 w-1.5 rounded-full bg-red-500"></span>{{ $post->category->name }}</span>
                @endif
                @if ($post->video_embed_url)
                    <span class="rounded-sm bg-red-500 px-2 py-0.5 font-display text-[10px] font-bold uppercase tracking-[0.12em] text-white">{{ __('club.news.video_badge') }}</span>
                @endif
                <time datetime="{{ $post->published_at?->toDateString() }}">{{ __('club.news.published') }} {{ $post->published_at?->format('d.m.Y.') }}</time>
                <span aria-hidden="true">·</span>
                <span>{{ $post->reading_time }} {{ __('club.news.min_read') }}</span>
            </div>

            <h1 class="display mt-5 text-4xl text-balance sm:text-6xl">{{ $post->title }}</h1>

            @if ($post->excerpt)
                <p class="mt-6 max-w-2xl text-lg leading-relaxed text-navy-100">{{ $post->excerpt }}</p>
            @endif
        </div>
    </section>

    <article class="mx-auto max-w-4xl px-6 py-12">
        @if ($post->video_embed_url)
            <div class="mb-10 aspect-video overflow-hidden rounded-sm border border-surface-300 bg-navy-950">
                <iframe src="{{ $post->video_embed_url }}" class="h-full w-full border-0" loading="lazy"
                    allow="accelerometer; autoplay; clipboard-write; encrypted-media; gyroscope; picture-in-picture"
                    allowfullscreen title="{{ $post->title }}"></iframe>
            </div>
        @elseif ($imageUrl)
            <img src="{{ $imageUrl }}" alt="{{ $post->title }}" class="mb-10 w-full rounded-sm border border-surface-300">
        @endif

        @if ($match)
            <div class="mb-10">
                <p class="kicker mb-3 text-red-600">{{ __('club.news.match_box') }}</p>
                <x-match-card :match="$match" :dark="false" />
            </div>
        @endif

        <div class="prose-club">
            {!! $post->body !!}
        </div>

        @if ($post->video_embed_url && $imageUrl)
            <img src="{{ $imageUrl }}" alt="{{ $post->title }}" class="mt-10 w-full rounded-sm border border-surface-300">
        @endif
    </article>

    @if ($related->isNotEmpty())
        <section class="border-t border-surface-300 bg-white py-16">
            <div class="mx-auto max-w-[100rem] px-6">
                <h2 class="display text-3xl text-navy-900">{{ __('club.news.related') }}</h2>
                <div class="mt-8 grid gap-x-8 gap-y-12 sm:grid-cols-2 lg:grid-cols-3">
                    @foreach ($related as $relatedPost)
                        <x-post-card :post="$relatedPost" />
                    @endforeach
                </div>
            </div>
        </section>
    @endif

    <x-cta-band />

</x-site-layout>
