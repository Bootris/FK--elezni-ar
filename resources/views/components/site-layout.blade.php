@props(['title' => null, 'description' => null, 'image' => null])

@php
    use Illuminate\Support\Facades\Cache;
    use Illuminate\Support\Facades\Storage;
    use App\Models\Post;

    $siteName = $site['site_name'] ?? config('app.name');
    $shortName = $site['club_short_name'] ?? 'Železničar';
    $tagline = $site['tagline'] ?? __('club.hero.kicker');
    $pageTitle = $title ? "{$title} · {$siteName}" : ($site['seo_title'] ?? "{$siteName} · {$tagline}");
    $pageDescription = $description ?? ($site['seo_description'] ?? __('club.meta.description'));
    $locale = app()->getLocale();
    $homeUrl = url($locale);
    $logoUrl = !empty($site['logo']) ? Storage::disk('public')->url($site['logo']) : asset('images/logo.gif');
    $ogImage = $image ?? url($logoUrl);

    $navLinks = [
        ['href' => route('news.index'), 'label' => __('club.nav.news')],
        ['href' => route('video.index'), 'label' => __('club.nav.video')],
        ['href' => route('team.index', $locale), 'label' => __('club.nav.first_team')],
        ['href' => route('youth.index', $locale), 'label' => __('club.nav.youth')],
        ['href' => route('support.index', $locale), 'label' => __('club.nav.support')],
        ['href' => route('contact.index', $locale), 'label' => __('club.nav.contact')],
    ];

    $enrolUrl = route('youth.index', $locale) . '#upis';
    $supportUrl = route('support.index', $locale);

    // Pinned posts feed the ticker; with nothing pinned the newest headlines scroll instead.
    $tickerHeadlines = Cache::remember('site_ticker', 600, function () {
        $pinned = Post::published()->where('is_pinned', true)->orderByDesc('published_at')->take(6)->pluck('title');

        return ($pinned->isNotEmpty()
            ? $pinned
            : Post::published()->orderByDesc('published_at')->take(6)->pluck('title')
        )->all();
    });

    $socials = array_filter([
        'YouTube' => $site['youtube'] ?? null,
        'Instagram' => $site['instagram'] ?? null,
        'Facebook' => $site['facebook'] ?? null,
        'TikTok' => $site['tiktok'] ?? null,
    ]);

    $isActive = fn (string $href) => str_starts_with(url()->current(), $href);
@endphp
<!DOCTYPE html>
<html lang="{{ $locale }}" class="scroll-pt-24">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>{{ $pageTitle }}</title>
    <meta name="description" content="{{ $pageDescription }}">
    <meta name="theme-color" content="#101a2e">
    <meta property="og:title" content="{{ $pageTitle }}">
    <meta property="og:description" content="{{ $pageDescription }}">
    <meta property="og:type" content="website">
    <meta property="og:url" content="{{ url()->current() }}">
    <meta property="og:image" content="{{ $ogImage }}">
    <meta property="og:site_name" content="{{ $siteName }}">
    <meta name="twitter:card" content="summary_large_image">
    <link rel="canonical" href="{{ url()->current() }}">
    <link rel="icon" href="{{ $logoUrl }}">
    <link rel="apple-touch-icon" href="{{ $logoUrl }}">

    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Barlow+Condensed:wght@600;700;800;900&family=Barlow:wght@400;500;600;700&display=swap" rel="stylesheet">

    @vite(['resources/css/app.css', 'resources/js/app.js'])

    <script type="application/ld+json">
    {!! json_encode([
        '@context' => 'https://schema.org',
        '@type' => 'SportsTeam',
        'name' => $siteName,
        'sport' => 'Football',
        'url' => $homeUrl,
        'logo' => url($logoUrl),
        'foundingDate' => $site['founded_year'] ?? '1928',
        'address' => ['@type' => 'PostalAddress', 'addressLocality' => $site['city'] ?? 'Niš', 'addressCountry' => 'RS'],
        'sameAs' => array_values($socials),
    ], JSON_UNESCAPED_UNICODE | JSON_UNESCAPED_SLASHES) !!}
    </script>
</head>

<body class="bg-surface-100 font-sans text-ink-900 antialiased">

    {{-- ===== Top strip: identity + ticker + language ===== --}}
    <div class="hidden bg-navy-950 text-navy-200 lg:block">
        <div class="mx-auto flex h-9 max-w-[100rem] items-center gap-6 px-6 text-[11.5px]">
            <span class="kicker text-[10.5px] text-gold-400">{{ __('club.footer.founded') }} {{ $site['founded_year'] ?? '1928' }}</span>
            @if (!empty($site['stadium']))
                <span class="text-navy-300">{{ $site['stadium'] }}</span>
            @endif

            @if (!empty($tickerHeadlines))
                <div class="ticker-mask h-9 flex-1 items-center overflow-hidden border-x border-white/10 px-5 flex">
                    <div class="ticker-track gap-10">
                        @foreach (array_merge($tickerHeadlines, $tickerHeadlines) as $headline)
                            <span class="flex items-center gap-10">
                                <span class="h-1.5 w-1.5 rounded-full bg-red-500" aria-hidden="true"></span>
                                {{ $headline }}
                            </span>
                        @endforeach
                    </div>
                </div>
            @else
                <div class="flex-1"></div>
            @endif

            <span class="flex items-center gap-1.5 font-semibold tracking-wide">
                <a href="{{ url('sr') }}" class="{{ $locale === 'sr' ? 'text-white' : 'transition hover:text-white' }}">SR</a>
                <span class="text-navy-600">/</span>
                <a href="{{ url('en') }}" class="{{ $locale === 'en' ? 'text-white' : 'transition hover:text-white' }}">EN</a>
            </span>
        </div>
    </div>

    {{-- ===== Main header ===== --}}
    <header id="site-header" class="sticky top-0 z-40 border-b border-white/10 bg-navy-900/95 text-white backdrop-blur transition-shadow">
        <div class="mx-auto flex h-[4.25rem] max-w-[100rem] items-center gap-5 px-4 sm:px-6 lg:h-20">
            <a href="{{ $homeUrl }}" class="flex shrink-0 items-center gap-3">
                <img src="{{ $logoUrl }}" alt="{{ $siteName }}" class="crest h-11 w-11 object-contain lg:h-14 lg:w-14" width="56" height="56">
                <span class="flex flex-col leading-none">
                    <span class="font-display text-xl font-black uppercase tracking-tight sm:text-[1.45rem] lg:text-[1.65rem]">FK {{ $shortName }}</span>
                    <span class="kicker mt-1 hidden text-[9.5px] tracking-[0.28em] text-gold-400 sm:block">{{ $site['city'] ?? 'Niš' }} · {{ $site['founded_year'] ?? '1928' }}</span>
                </span>
            </a>

            <nav class="ml-6 hidden items-center gap-1 lg:flex" aria-label="Main">
                @foreach ($navLinks as $link)
                    <a href="{{ $link['href'] }}"
                        class="rounded-sm px-3 py-2 font-display text-[15.5px] font-bold uppercase tracking-wide transition {{ $isActive($link['href']) ? 'text-white after:block after:h-0.5 after:bg-red-500' : 'text-navy-100 hover:text-white' }}">
                        {{ $link['label'] }}
                    </a>
                @endforeach
            </nav>

            <div class="ml-auto flex items-center gap-2 sm:gap-3">
                <a href="{{ $supportUrl }}" class="btn btn-outline btn-sm hidden md:inline-flex">{{ __('club.cta.support') }}</a>
                <a href="{{ $enrolUrl }}" class="btn btn-red btn-sm hidden lg:inline-flex">
                    <svg class="h-3.5 w-3.5" viewBox="0 0 24 24" fill="currentColor" aria-hidden="true"><path d="M12 2l2.9 6.3 6.9.8-5.1 4.7 1.4 6.8L12 17.3 5.9 20.6l1.4-6.8L2.2 9.1l6.9-.8z"/></svg>
                    {{ __('club.cta.enroll') }}
                </a>
                <button id="menu-toggle" type="button" aria-expanded="false" aria-controls="mobile-menu"
                    class="rounded-md p-2 text-white transition hover:bg-white/10 lg:hidden">
                    <span class="sr-only">Meni</span>
                    <svg class="h-6 w-6" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" aria-hidden="true"><path d="M4 7h16M4 12h16M4 17h16" /></svg>
                </button>
            </div>
        </div>
    </header>

    {{-- ===== Mobile menu ===== --}}
    <div id="menu-overlay" class="pointer-events-none fixed inset-0 z-40 bg-navy-950/75 opacity-0 transition-opacity duration-300 lg:hidden"></div>
    <div id="mobile-menu"
        class="fixed inset-y-0 right-0 z-50 flex w-80 max-w-[88vw] translate-x-full flex-col bg-navy-950 bg-pitch p-7 text-white transition-transform duration-300 lg:hidden">
        <div class="flex items-center justify-between">
            <span class="flex items-center gap-3">
                <img src="{{ $logoUrl }}" alt="" class="crest h-10 w-10 object-contain">
                <span class="font-display text-xl font-black uppercase">FK {{ $shortName }}</span>
            </span>
            <button id="menu-close" type="button" class="rounded-md p-2 transition hover:bg-white/10">
                <span class="sr-only">Zatvori</span>
                <svg class="h-6 w-6" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" aria-hidden="true"><path d="m6 6 12 12M18 6 6 18" /></svg>
            </button>
        </div>

        <nav class="mt-8 flex flex-col" aria-label="Mobile">
            @foreach ($navLinks as $link)
                <a href="{{ $link['href'] }}" class="border-b border-white/10 py-3.5 font-display text-2xl font-bold uppercase tracking-wide transition hover:text-gold-400">{{ $link['label'] }}</a>
            @endforeach
        </nav>

        <div class="mt-8 grid gap-3">
            <a href="{{ $enrolUrl }}" class="btn btn-red whitespace-normal">{{ __('club.cta.enroll') }}</a>
            <a href="{{ $supportUrl }}" class="btn btn-outline whitespace-normal">{{ __('club.cta.support') }}</a>
        </div>

        <div class="mt-auto flex items-center gap-3 pt-8 text-sm font-semibold text-navy-200">
            <a href="{{ url('sr') }}" class="{{ $locale === 'sr' ? 'text-gold-400' : '' }}">Srpski</a>
            <span class="text-navy-600">/</span>
            <a href="{{ url('en') }}" class="{{ $locale === 'en' ? 'text-gold-400' : '' }}">English</a>
        </div>
    </div>

    <main>
        {{ $slot }}
    </main>

    {{-- ===== Footer ===== --}}
    <footer class="relative overflow-hidden bg-navy-950 bg-pitch text-navy-200">
        <div class="mx-auto max-w-[100rem] px-6 pt-16 pb-24 lg:pb-8">
            <x-wordmark :text="$shortName" :floating="false" />

            <div class="mt-10 grid gap-10 border-t border-white/10 pt-12 md:grid-cols-[2fr_1fr_1fr_1fr]">
                <div>
                    <div class="flex items-center gap-3">
                        <img src="{{ $logoUrl }}" alt="" class="crest h-12 w-12 object-contain">
                        <span class="font-display text-2xl font-black uppercase text-white">{{ $siteName }}</span>
                    </div>
                    <p class="mt-5 max-w-sm text-sm leading-relaxed">{{ __('club.footer.about') }}</p>
                    <div class="mt-6 flex flex-wrap gap-3">
                        <a href="{{ $enrolUrl }}" class="btn btn-red btn-sm">{{ __('club.cta.enroll') }}</a>
                        <a href="{{ $supportUrl }}" class="btn btn-outline btn-sm">{{ __('club.cta.support') }}</a>
                    </div>
                </div>

                <div>
                    <h3 class="kicker text-navy-300">{{ __('club.footer.sections') }}</h3>
                    <ul class="mt-5 space-y-3 text-sm">
                        @foreach ($navLinks as $link)
                            <li><a href="{{ $link['href'] }}" class="transition hover:text-gold-400">{{ $link['label'] }}</a></li>
                        @endforeach
                    </ul>
                </div>

                <div>
                    <h3 class="kicker text-navy-300">{{ __('club.footer.club') }}</h3>
                    <ul class="mt-5 space-y-3 text-sm">
                        @if (!empty($site['address']))<li>{{ $site['address'] }}</li>@endif
                        @if (!empty($site['stadium']))<li>{{ $site['stadium'] }}</li>@endif
                        @if (!empty($site['email']))
                            <li><a href="mailto:{{ $site['email'] }}" class="transition hover:text-gold-400">{{ $site['email'] }}</a></li>
                        @endif
                        @if (!empty($site['phone']))
                            <li><a href="tel:{{ preg_replace('/[^+\d]/', '', $site['phone']) }}" class="transition hover:text-gold-400">{{ $site['phone'] }}</a></li>
                        @endif
                    </ul>
                </div>

                <div>
                    <h3 class="kicker text-navy-300">{{ __('club.footer.follow') }}</h3>
                    <ul class="mt-5 space-y-3 text-sm">
                        @forelse ($socials as $label => $url)
                            <li><a href="{{ $url }}" target="_blank" rel="noopener" class="transition hover:text-gold-400">{{ $label }}</a></li>
                        @empty
                            <li>YouTube</li>
                            <li>Instagram</li>
                            <li>Facebook</li>
                        @endforelse
                    </ul>
                </div>
            </div>

            <div class="mt-14 flex flex-col items-start justify-between gap-2 border-t border-white/10 pt-6 text-xs text-navy-300 sm:flex-row sm:items-center">
                <p>© {{ date('Y') }} {{ $siteName }}. {{ __('club.footer.rights') }}</p>
                <p>{{ $tagline }}</p>
            </div>
        </div>
    </footer>

    {{-- ===== Sticky mobile CTA bar ===== --}}
    <div class="mobile-cta fixed inset-x-0 bottom-0 z-30 grid grid-cols-2 gap-2 border-t border-white/10 bg-navy-950/95 p-2 backdrop-blur lg:hidden">
        <a href="{{ $supportUrl }}" class="btn btn-outline btn-sm">{{ __('club.cta.support') }}</a>
        <a href="{{ $enrolUrl }}" class="btn btn-red btn-sm">{{ __('club.cta.enroll') }}</a>
    </div>

</body>

</html>
