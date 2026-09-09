@php
    use Illuminate\Support\Facades\Storage;
    $locale = app()->getLocale();
    $heroImage = !empty($site['hero_image']) ? Storage::disk('public')->url($site['hero_image']) : null;
    $shortName = $site['club_short_name'] ?? 'Železničar';
@endphp

<x-site-layout>

    {{-- ===== HERO ===== --}}
    <section class="relative overflow-hidden bg-navy-950 text-white">
        @if ($heroImage)
            <img src="{{ $heroImage }}" alt="" class="absolute inset-0 h-full w-full object-cover opacity-40" aria-hidden="true">
            <div class="absolute inset-0 bg-gradient-to-r from-navy-950 via-navy-950/85 to-navy-950/40"></div>
        @else
            <div class="absolute inset-0 bg-pitch bg-spot"></div>
        @endif
        <x-wordmark :text="$shortName" />

        <div class="relative mx-auto grid max-w-[100rem] items-center gap-12 px-6 pt-14 pb-16 lg:grid-cols-[1.15fr_.85fr] lg:gap-16 lg:py-24">
            <div class="reveal is-visible">
                <p class="kicker flex items-center gap-2 text-gold-400">
                    <svg class="h-3.5 w-3.5 text-red-500" viewBox="0 0 24 24" fill="currentColor" aria-hidden="true"><path d="M12 2l2.9 6.3 6.9.8-5.1 4.7 1.4 6.8L12 17.3 5.9 20.6l1.4-6.8L2.2 9.1l6.9-.8z"/></svg>
                    {{ $site['hero_kicker'] ?? __('club.hero.kicker') }}
                </p>
                <h1 class="display mt-5 text-[3.4rem] text-balance sm:text-7xl lg:text-[6.5rem]">{{ $site['hero_title'] ?? __('club.hero.title') }}</h1>
                <p class="mt-6 max-w-xl text-lg leading-relaxed text-navy-100">{{ $site['hero_subtitle'] ?? __('club.hero.subtitle') }}</p>
                <div class="mt-9 flex flex-wrap gap-3">
                    <a href="{{ route('youth.index', $locale) }}#upis" class="btn btn-red whitespace-normal">{{ __('club.cta.enroll_long') }}</a>
                    <a href="{{ route('support.index', $locale) }}" class="btn btn-outline whitespace-normal">{{ __('club.cta.support') }}</a>
                </div>
                <div class="mt-16 flex flex-wrap gap-x-10 gap-y-4">
                    <div><span class="score block text-4xl text-gold-400">{{ $site['founded_year'] ?? '1928' }}.</span><span class="text-xs uppercase tracking-wider text-navy-300">{{ __('club.footer.founded') }}</span></div>
                    <div><span class="score block text-4xl">{{ $selections->count() }}</span><span class="text-xs uppercase tracking-wider text-navy-300">{{ __('club.home.youth_selections') }}</span></div>
                    <div><span class="score block text-4xl">{{ $playerCount }}</span><span class="text-xs uppercase tracking-wider text-navy-300">{{ __('club.home.team_players') }}</span></div>
                </div>
            </div>

            {{-- Match centre --}}
            <div class="reveal is-visible grid gap-4">
                @if ($nextMatch)
                    <x-match-card :match="$nextMatch" :label="__('club.hero.next_match')" :countdown="true" class="border border-white/10 shadow-2xl" />
                @endif
                @if ($lastMatch)
                    <x-match-card :match="$lastMatch" :label="__('club.hero.last_result')" class="border border-white/10 !bg-navy-900/70 backdrop-blur" />
                @endif
                @if (!$nextMatch && !$lastMatch)
                    <div class="rounded-sm border border-dashed border-white/20 p-8 text-center text-navy-200">{{ __('club.hero.no_match') }}</div>
                @endif
                @if ($standings->isNotEmpty())
                    <a href="{{ route('team.index', $locale) }}#tabela" class="flex items-center justify-between rounded-sm border border-white/10 bg-navy-900/70 px-5 py-3 text-sm backdrop-blur transition hover:border-gold-400/50">
                        <span class="font-display text-base font-bold uppercase tracking-wide">{{ $site['league_name'] ?? __('club.match.table') }}</span>
                        @php $us = $standings->firstWhere('is_club', true); @endphp
                        @if ($us)
                            <span class="text-navy-200">{{ $us->position }}. · {{ $us->points }} {{ __('club.match.cols.pts') }}</span>
                        @endif
                        <span class="text-gold-400">{{ __('club.match.full_table') }} →</span>
                    </a>
                @endif
            </div>
        </div>
        <div class="rails text-white"></div>
    </section>

    {{-- ===== NAJNOVIJE VESTI ===== --}}
    @if ($featured || $latestPosts->isNotEmpty())
        <section class="mx-auto max-w-[100rem] px-6 py-16 lg:py-24">
            <x-section-heading :kicker="__('club.home.latest_kicker')" :title="__('club.home.latest_title')" :href="route('news.index')" :link="__('club.cta.all_news')" />

            <div class="reveal-group mt-12 grid gap-x-8 gap-y-12 lg:grid-cols-[1.5fr_1fr]">
                @if ($featured)
                    <x-post-card :post="$featured" :large="true" />
                @endif
                <div class="grid gap-8 sm:grid-cols-2 lg:grid-cols-1">
                    @foreach ($latestPosts->take(2) as $post)
                        <x-post-card :post="$post" />
                    @endforeach
                </div>
            </div>

            @if ($latestPosts->count() > 2)
                <div class="reveal-group mt-12 grid gap-x-8 gap-y-12 sm:grid-cols-2 lg:grid-cols-4">
                    @foreach ($latestPosts->slice(2, 4) as $post)
                        <x-post-card :post="$post" />
                    @endforeach
                </div>
            @endif
        </section>
    @endif

    {{-- ===== VIDEO ===== --}}
    @if ($videoPosts->isNotEmpty())
        <section class="slant-top bg-navy-950 bg-pitch text-white">
            <div class="mx-auto max-w-[100rem] px-6 py-16 lg:py-24">
                <x-section-heading :kicker="__('club.home.video_kicker')" :title="__('club.home.video_title')" :href="route('video.index')" :link="__('club.cta.all_videos')" :dark="true" />

                <div class="reveal-group mt-12 grid gap-8 lg:grid-cols-[1.6fr_1fr]">
                    @php $lead = $videoPosts->first(); @endphp
                    <a href="{{ route('news.show', $lead->slug) }}" class="group relative block aspect-video overflow-hidden rounded-sm border border-white/10">
                        @if ($lead->featured_image)
                            <img src="{{ Storage::disk('public')->url($lead->featured_image) }}" alt="{{ $lead->title }}" class="h-full w-full object-cover transition duration-500 group-hover:scale-105">
                        @else
                            <span class="film absolute inset-0"></span>
                        @endif
                        <div class="absolute inset-0 bg-gradient-to-t from-navy-950 via-navy-950/20 to-transparent"></div>
                        <span class="play-ring absolute left-1/2 top-1/2 z-10 h-20 w-20 -translate-x-1/2 -translate-y-1/2" aria-hidden="true"></span>
                        <div class="absolute inset-x-0 bottom-0 p-6 sm:p-8">
                            @if ($lead->category)<span class="kicker text-gold-400">{{ $lead->category->name }}</span>@endif
                            <h3 class="display mt-2 text-3xl sm:text-4xl">{{ $lead->title }}</h3>
                        </div>
                    </a>
                    <div class="grid gap-4">
                        @foreach ($videoPosts->slice(1) as $post)
                            <a href="{{ route('news.show', $post->slug) }}" class="group grid grid-cols-[7rem_1fr] items-center gap-4 rounded-sm border border-white/10 bg-navy-900/60 p-3 transition hover:border-gold-400/50">
                                <span class="relative block aspect-video overflow-hidden rounded-sm">
                                    @if ($post->featured_image)
                                        <img src="{{ Storage::disk('public')->url($post->featured_image) }}" alt="" class="h-full w-full object-cover">
                                    @else
                                        <span class="film absolute inset-0"></span>
                                    @endif
                                    <span class="play-ring absolute left-1/2 top-1/2 h-8 w-8 -translate-x-1/2 -translate-y-1/2" aria-hidden="true"></span>
                                </span>
                                <span>
                                    <span class="block text-[11px] uppercase tracking-wider text-navy-300">{{ $post->published_at?->format('d.m.Y.') }}</span>
                                    <span class="display mt-1 block text-xl leading-none transition group-hover:text-gold-400">{{ $post->title }}</span>
                                </span>
                            </a>
                        @endforeach
                        @if (!empty($site['youtube']))
                            <a href="{{ $site['youtube'] }}" target="_blank" rel="noopener" class="btn btn-outline btn-sm mt-auto justify-start">▶ {{ __('club.video.youtube') }}</a>
                        @endif
                    </div>
                </div>
            </div>
        </section>
    @endif

    {{-- ===== PRVI TIM ===== --}}
    <section class="mx-auto max-w-[100rem] px-6 py-16 lg:py-24">
        <x-section-heading :kicker="__('club.home.team_kicker')" :title="__('club.home.team_title')" :text="__('club.home.team_text')" :href="route('team.index', $locale)" :link="__('club.home.team_link')" />

        @if ($players->isNotEmpty())
            <div class="reveal-group mt-12 grid grid-cols-2 gap-4 sm:grid-cols-3 lg:grid-cols-6">
                @foreach ($players as $player)
                    <x-player-card :player="$player" />
                @endforeach
            </div>
        @endif

        <div class="reveal mt-10 flex flex-wrap items-center gap-x-10 gap-y-4 rounded-sm bg-surface-200 px-6 py-5">
            <div><span class="score text-4xl text-navy-900">{{ $playerCount }}</span> <span class="ml-2 text-sm text-ink-600">{{ __('club.home.team_players') }}</span></div>
            <div><span class="score text-4xl text-red-600">{{ $academyCount }}</span> <span class="ml-2 text-sm text-ink-600">{{ __('club.home.team_academy') }}</span></div>
            @if ($standings->isNotEmpty() && ($us = $standings->firstWhere('is_club', true)))
                <div><span class="score text-4xl text-navy-900">{{ $us->position }}.</span> <span class="ml-2 text-sm text-ink-600">{{ $site['league_name'] ?? __('club.match.table') }}</span></div>
            @endif
            <a href="{{ route('team.index', $locale) }}" class="btn btn-outline-dark btn-sm ml-auto">{{ __('club.nav.first_team') }}</a>
        </div>
    </section>

    {{-- ===== OMLADINSKI POGON ===== --}}
    <section class="slant-top bg-navy-900 bg-pitch text-white">
        <div class="mx-auto max-w-[100rem] px-6 py-16 lg:py-24">
            <div class="grid gap-12 lg:grid-cols-[1fr_1.3fr] lg:gap-20">
                <div class="reveal">
                    <p class="kicker flex items-center gap-2 text-gold-400"><span class="h-1.5 w-1.5 rounded-full bg-gold-400"></span>{{ __('club.home.youth_kicker') }}</p>
                    <h2 class="display mt-3 text-5xl sm:text-6xl lg:text-7xl">{{ __('club.home.youth_title') }}</h2>
                    <p class="mt-5 max-w-lg text-navy-100">{{ $site['youth_intro'] ?? '' }}</p>
                    <div class="mt-8 grid grid-cols-3 gap-4 border-t border-white/10 pt-6">
                        <div><span class="score block text-4xl text-gold-400">{{ $selections->count() }}</span><span class="text-xs uppercase tracking-wider text-navy-300">{{ __('club.home.youth_selections') }}</span></div>
                        <div><span class="score block text-4xl">{{ $coachCount }}</span><span class="text-xs uppercase tracking-wider text-navy-300">{{ __('club.home.youth_coaches') }}</span></div>
                        <div><span class="display block text-2xl leading-tight">{{ $site['youth_age_range'] ?? '5–19' }}</span><span class="text-xs uppercase tracking-wider text-navy-300">{{ __('club.home.youth_age') }}</span></div>
                    </div>
                    <div class="mt-8 flex flex-wrap gap-3">
                        <a href="{{ route('youth.index', $locale) }}#upis" class="btn btn-red">{{ __('club.cta.enroll') }}</a>
                        <a href="{{ route('youth.index', $locale) }}" class="btn btn-outline">{{ __('club.home.youth_link') }}</a>
                    </div>
                </div>

                <div class="reveal-group grid grid-cols-2 gap-3 sm:grid-cols-3 lg:grid-cols-4">
                    @foreach ($selections as $selection)
                        <a href="{{ route('youth.index', $locale) }}#{{ $selection->slug }}" class="group flex aspect-square flex-col justify-between rounded-sm border border-white/10 bg-navy-800/70 p-4 transition hover:border-gold-400/60 hover:bg-navy-800">
                            <span class="text-[11px] uppercase tracking-wider text-navy-300">{{ $selection->birth_years }}</span>
                            <span>
                                <span class="display block text-3xl leading-none transition group-hover:text-gold-400">{{ $selection->name }}</span>
                                @if ($selection->accepting_applications)
                                    <span class="mt-2 inline-block rounded-sm bg-red-500 px-1.5 py-0.5 text-[9.5px] font-bold uppercase tracking-wider">{{ __('club.youth.open') }}</span>
                                @endif
                            </span>
                        </a>
                    @endforeach
                </div>
            </div>
        </div>
    </section>

    {{-- ===== UPIS + PODRŽI KLUB ===== --}}
    <x-cta-band />

    {{-- ===== DRUŠTVENE MREŽE ===== --}}
    @php $socials = array_filter(['YouTube' => $site['youtube'] ?? null, 'Instagram' => $site['instagram'] ?? null, 'Facebook' => $site['facebook'] ?? null, 'TikTok' => $site['tiktok'] ?? null]); @endphp
    @if ($socials)
        <section class="border-t border-surface-300 bg-white">
            <div class="mx-auto flex max-w-[100rem] flex-wrap items-center justify-between gap-6 px-6 py-10">
                <h2 class="display text-3xl text-navy-900">{{ __('club.home.social_title') }}</h2>
                <div class="flex flex-wrap gap-3">
                    @foreach ($socials as $label => $url)
                        <a href="{{ $url }}" target="_blank" rel="noopener" class="btn btn-outline-dark btn-sm">{{ $label }}</a>
                    @endforeach
                </div>
            </div>
        </section>
    @endif

</x-site-layout>
