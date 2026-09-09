@php
    use Illuminate\Support\Facades\Storage;
    $locale = app()->getLocale();
    $order = ['GK', 'DF', 'MF', 'FW'];
@endphp

<x-site-layout :title="__('club.team.page_title')" :description="__('club.team.subtitle')">

    <section class="relative overflow-hidden bg-navy-950 bg-pitch bg-spot text-white">
        <x-wordmark :text="$site['season'] ?? ''" align="right" />
        <div class="relative mx-auto max-w-[100rem] px-6 py-14 sm:py-20">
            <p class="kicker text-gold-400">{{ __('club.team.eyebrow') }} @if (!empty($site['season']))· {{ __('club.team.season') }} {{ $site['season'] }}@endif</p>
            <h1 class="display mt-3 text-6xl sm:text-8xl">{{ __('club.team.page_title') }}</h1>
            <p class="mt-5 max-w-xl text-navy-100">{{ __('club.team.subtitle') }}</p>

            <nav class="mt-10 flex flex-wrap gap-2" aria-label="Sekcije">
                @foreach (['igraci' => __('club.team.players'), 'stab' => __('club.team.staff'), 'utakmice' => __('club.match.upcoming'), 'tabela' => __('club.match.table')] as $anchor => $label)
                    <a href="#{{ $anchor }}" class="rounded-sm border border-white/20 px-4 py-2 font-display text-sm font-bold uppercase tracking-wider transition hover:border-gold-400 hover:text-gold-400">{{ $label }}</a>
                @endforeach
            </nav>
        </div>
        <div class="rails text-white"></div>
    </section>

    {{-- ===== Match centre strip ===== --}}
    @if ($upcoming->isNotEmpty() || $results->isNotEmpty())
        <section class="bg-navy-900 text-white">
            <div class="mx-auto grid max-w-[100rem] gap-4 px-6 py-8 lg:grid-cols-2">
                @if ($upcoming->first())
                    <x-match-card :match="$upcoming->first()" :label="__('club.hero.next_match')" :countdown="true" class="border border-white/10" />
                @endif
                @if ($results->first())
                    <x-match-card :match="$results->first()" :label="__('club.hero.last_result')" class="border border-white/10" />
                @endif
            </div>
        </section>
    @endif

    {{-- ===== Igrači ===== --}}
    <section id="igraci" class="mx-auto max-w-[100rem] px-6 py-16 lg:py-24">
        <x-section-heading :kicker="__('club.team.eyebrow')" :title="__('club.team.players')" />

        @if ($players->isEmpty())
            <p class="mt-10 text-ink-600">{{ __('club.team.no_players') }}</p>
        @else
            @foreach ($order as $position)
                @if (isset($players[$position]))
                    <div class="mt-12">
                        <h3 class="flex items-center gap-3 font-display text-xl font-bold uppercase tracking-wider text-navy-900">
                            <span class="h-2 w-2 rounded-full bg-red-500"></span>{{ __('club.positions.' . $position) }}
                            <span class="text-sm font-semibold text-ink-400">{{ $players[$position]->count() }}</span>
                        </h3>
                        <div class="reveal-group mt-5 grid grid-cols-2 gap-4 sm:grid-cols-3 lg:grid-cols-4 xl:grid-cols-6">
                            @foreach ($players[$position] as $player)
                                <x-player-card :player="$player" />
                            @endforeach
                        </div>
                    </div>
                @endif
            @endforeach
        @endif
    </section>

    {{-- ===== Stručni štab ===== --}}
    <section id="stab" class="border-t border-surface-300 bg-white">
        <div class="mx-auto max-w-[100rem] px-6 py-16 lg:py-24">
            <x-section-heading :title="__('club.team.staff')" />
            @if ($staff->isEmpty())
                <p class="mt-10 text-ink-600">{{ __('club.team.no_staff') }}</p>
            @else
                <div class="reveal-group mt-10 grid gap-4 sm:grid-cols-2 lg:grid-cols-3">
                    @foreach ($staff as $member)
                        <x-staff-card :member="$member" />
                    @endforeach
                </div>
            @endif
        </div>
    </section>

    {{-- ===== Utakmice + tabela ===== --}}
    <section id="utakmice" class="mx-auto max-w-[100rem] px-6 py-16 lg:py-24">
        <div class="grid gap-12 lg:grid-cols-[1.2fr_1fr] lg:gap-16">
            <div data-tabs class="min-w-0">
                <div class="flex items-end justify-between gap-6 border-b-2 border-navy-900 pb-4">
                    <div class="flex flex-wrap gap-x-5 gap-y-1 sm:gap-x-6" role="tablist">
                        <button type="button" data-tab="utakmice" role="tab" class="display text-3xl text-ink-400 transition sm:text-5xl [&.is-active]:text-navy-900">{{ __('club.match.upcoming') }}</button>
                        <button type="button" data-tab="rezultati" role="tab" class="display text-3xl text-ink-400 transition sm:text-5xl [&.is-active]:text-navy-900">{{ __('club.match.results') }}</button>
                    </div>
                </div>

                <div data-panel="utakmice" class="mt-2">
                    @forelse ($upcoming as $match)
                        <x-match-row :match="$match" />
                    @empty
                        <p class="py-8 text-ink-600">{{ __('club.match.no_upcoming') }}</p>
                    @endforelse
                </div>
                <div data-panel="rezultati" class="mt-2" hidden>
                    @forelse ($results as $match)
                        <x-match-row :match="$match" />
                    @empty
                        <p class="py-8 text-ink-600">{{ __('club.match.no_results') }}</p>
                    @endforelse
                </div>
            </div>

            <div id="tabela" class="min-w-0 rounded-sm bg-navy-950 bg-pitch p-6 text-white sm:p-8">
                <div class="flex items-end justify-between gap-4 border-b border-white/15 pb-4">
                    <div>
                        <p class="kicker text-gold-400">{{ $site['league_name'] ?? '' }} @if (!empty($site['season']))· {{ $site['season'] }}@endif</p>
                        <h2 class="display mt-1 text-4xl">{{ __('club.match.table') }}</h2>
                    </div>
                </div>
                <div class="mt-4">
                    <x-standings-table :rows="$standings" :dark="true" />
                </div>
            </div>
        </div>
    </section>

    {{-- ===== Galerija ===== --}}
    @if ($photos->isNotEmpty())
        <section class="mx-auto max-w-[100rem] px-6 pb-16 lg:pb-24">
            <x-section-heading :title="__('club.team.gallery')" />
            <div class="reveal-group mt-8 grid grid-cols-2 gap-3 sm:grid-cols-4">
                @foreach ($photos as $photo)
                    <figure class="group relative aspect-[4/3] overflow-hidden rounded-sm">
                        <img src="{{ Storage::disk('public')->url($photo->image) }}" alt="{{ $photo->title }}" class="h-full w-full object-cover transition duration-500 group-hover:scale-105" loading="lazy">
                        @if ($photo->title)<figcaption class="absolute inset-x-0 bottom-0 bg-gradient-to-t from-navy-950/90 to-transparent p-3 text-xs text-white">{{ $photo->title }}</figcaption>@endif
                    </figure>
                @endforeach
            </div>
        </section>
    @endif

    {{-- ===== Vesti prvog tima ===== --}}
    @if ($posts->isNotEmpty())
        <section class="border-t border-surface-300 bg-white">
            <div class="mx-auto max-w-[100rem] px-6 py-16 lg:py-24">
                <x-section-heading :title="__('club.team.news_title')" :href="route('news.index')" :link="__('club.cta.all_news')" />
                <div class="reveal-group mt-10 grid gap-x-8 gap-y-12 sm:grid-cols-2 lg:grid-cols-3">
                    @foreach ($posts as $post)
                        <x-post-card :post="$post" />
                    @endforeach
                </div>
            </div>
        </section>
    @endif

    <x-cta-band />

</x-site-layout>
