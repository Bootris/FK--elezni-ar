<x-site-layout :title="__('club.news.title')" :description="__('club.news.subtitle')">

    <section class="bg-navy-950 bg-pitch text-white">
        <div class="mx-auto max-w-[100rem] px-6 py-14 sm:py-20">
            <p class="kicker text-gold-400">{{ __('club.news.eyebrow') }}</p>
            <h1 class="display mt-3 text-6xl sm:text-8xl">{{ __('club.news.title') }}</h1>
            <p class="mt-5 max-w-xl text-navy-100">{{ __('club.news.subtitle') }}</p>
        </div>
        <div class="rails text-white"></div>
    </section>

    <section class="mx-auto max-w-[100rem] px-6 py-14">
        @if ($categories->isNotEmpty())
            <div class="flex flex-wrap items-center gap-2 border-b border-surface-300 pb-8">
                <a href="{{ route('news.index') }}"
                    class="rounded-sm px-4 py-1.5 font-display text-sm font-bold uppercase tracking-wider transition {{ $activeCategory ? 'border border-surface-300 text-ink-600 hover:border-navy-700 hover:text-navy-900' : 'bg-navy-900 text-white' }}">
                    {{ __('club.news.all') }}
                </a>
                @foreach ($categories as $category)
                    <a href="{{ route('news.index', ['category' => $category->slug]) }}"
                        class="rounded-sm px-4 py-1.5 font-display text-sm font-bold uppercase tracking-wider transition {{ $activeCategory === $category->slug ? 'bg-navy-900 text-white' : 'border border-surface-300 text-ink-600 hover:border-navy-700 hover:text-navy-900' }}">
                        {{ $category->name }} <span class="text-xs opacity-60">({{ $category->posts_count }})</span>
                    </a>
                @endforeach
            </div>
        @endif

        @if ($posts->isEmpty())
            <div class="mt-14 rounded-sm border-2 border-dashed border-surface-300 p-16 text-center">
                <img src="{{ asset('images/logo.gif') }}" alt="" class="mx-auto h-14 w-14 opacity-40">
                <p class="mt-4 text-ink-600">{{ __('club.news.empty') }}</p>
            </div>
        @else
            <div class="reveal-group mt-12 grid gap-x-8 gap-y-12 sm:grid-cols-2 lg:grid-cols-3 xl:grid-cols-4">
                @foreach ($posts as $post)
                    <x-post-card :post="$post" />
                @endforeach
            </div>

            <div class="mt-14">
                {{ $posts->links() }}
            </div>
        @endif
    </section>

    <x-cta-band />

</x-site-layout>
