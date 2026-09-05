<x-site-layout :title="__('club.video.page_title')" :description="__('club.video.page_subtitle')">

    <section class="bg-navy-950 bg-pitch text-white">
        <div class="mx-auto max-w-[100rem] px-6 py-14 sm:py-20">
            <p class="kicker flex items-center gap-2 text-gold-400"><span class="live-dot" aria-hidden="true"></span>{{ __('club.video.kicker') }}</p>
            <h1 class="display mt-3 text-6xl sm:text-8xl">{{ __('club.video.page_title') }}</h1>
            <p class="mt-5 max-w-xl text-navy-100">{{ __('club.video.page_subtitle') }}</p>
            <div class="mt-6 flex flex-wrap items-center gap-4 text-sm text-navy-200">
                <span>{{ $posts->total() }} {{ __('club.video.count') }}</span>
                @if (!empty($site['youtube']))
                    <a href="{{ $site['youtube'] }}" target="_blank" rel="noopener" class="btn btn-outline btn-sm">▶ {{ __('club.video.youtube') }}</a>
                @endif
            </div>
        </div>
        <div class="rails text-white"></div>
    </section>

    <section class="mx-auto max-w-[100rem] px-6 py-14">
        @if ($categories->isNotEmpty())
            <div class="flex flex-wrap items-center gap-2 border-b border-surface-300 pb-8">
                <a href="{{ route('video.index') }}"
                    class="rounded-sm px-4 py-1.5 font-display text-sm font-bold uppercase tracking-wider transition {{ $activeCategory ? 'border border-surface-300 text-ink-600 hover:border-navy-700 hover:text-navy-900' : 'bg-navy-900 text-white' }}">
                    {{ __('club.news.all') }}
                </a>
                @foreach ($categories as $category)
                    <a href="{{ route('video.index', ['category' => $category->slug]) }}"
                        class="rounded-sm px-4 py-1.5 font-display text-sm font-bold uppercase tracking-wider transition {{ $activeCategory === $category->slug ? 'bg-navy-900 text-white' : 'border border-surface-300 text-ink-600 hover:border-navy-700 hover:text-navy-900' }}">
                        {{ $category->name }} <span class="text-xs opacity-60">({{ $category->posts_count }})</span>
                    </a>
                @endforeach
            </div>
        @endif

        @if ($posts->isEmpty())
            <div class="mt-14 rounded-sm border-2 border-dashed border-surface-300 p-16 text-center">
                <img src="{{ asset('images/logo.gif') }}" alt="" class="mx-auto h-14 w-14 opacity-40">
                <p class="mt-4 text-ink-600">{{ __('club.video.empty') }}</p>
            </div>
        @else
            <div class="reveal-group mt-12 grid gap-x-8 gap-y-12 sm:grid-cols-2 lg:grid-cols-3">
                @foreach ($posts as $post)
                    @php
                        $imageUrl = $post->featured_image ? Illuminate\Support\Facades\Storage::disk('public')->url($post->featured_image) : null;
                        $film = ['film', 'film film-red', 'film film-gold'][$post->id % 3];
                    @endphp
                    <article class="group flex h-full flex-col">
                        <a href="{{ route('news.show', $post->slug) }}" class="relative block aspect-video overflow-hidden rounded-sm">
                            @if ($imageUrl)
                                <img src="{{ $imageUrl }}" alt="{{ $post->title }}" class="h-full w-full object-cover transition duration-500 group-hover:scale-105" loading="lazy">
                            @else
                                <span class="{{ $film }} absolute inset-0 transition duration-500 group-hover:scale-105"></span>
                            @endif
                            @if ($post->category)
                                <span class="absolute left-3 top-3 z-10 rounded-sm bg-navy-950/80 px-2.5 py-1 font-display text-[11px] font-bold uppercase tracking-[0.14em] text-white">{{ $post->category->name }}</span>
                            @endif
                            <span class="play-ring absolute left-1/2 top-1/2 z-10 h-16 w-16 -translate-x-1/2 -translate-y-1/2" aria-hidden="true"></span>
                        </a>
                        <div class="mt-4 text-[12px] font-semibold uppercase tracking-[0.12em] text-ink-400">{{ $post->published_at?->format('d.m.Y.') }}</div>
                        <h3 class="display mt-2 text-[1.65rem] leading-[0.95] text-navy-900 transition group-hover:text-red-600">
                            <a href="{{ route('news.show', $post->slug) }}">{{ $post->title }}</a>
                        </h3>
                    </article>
                @endforeach
            </div>

            <div class="mt-14">
                {{ $posts->links() }}
            </div>
        @endif
    </section>

    <x-cta-band />

</x-site-layout>
