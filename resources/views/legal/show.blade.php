@php
    $locale = app()->getLocale();
    $isPrivacy = $doc === 'privacy';
    $title = __('club.footer.' . ($isPrivacy ? 'privacy' : 'terms'));
    $other = $isPrivacy ? 'terms' : 'privacy';
    $siteName = $site['site_name'] ?? 'FK Železničar Niš';
    $description = $isPrivacy
        ? ($locale === 'en' ? 'How FK Železničar Niš collects, uses and protects personal data of website visitors, parents and children enrolling in the football school.' : 'Kako FK Železničar Niš prikuplja, koristi i štiti podatke o ličnosti posetilaca sajta, roditelja i dece koja se upisuju u školu fudbala.')
        : ($locale === 'en' ? 'Terms of use of the FK Železničar Niš website: content, enrolment form, donations and liability.' : 'Uslovi korišćenja sajta FK Železničar Niš: sadržaj, prijava za upis, donacije i odgovornost.');
@endphp

<x-site-layout :title="$title" :description="$description">

    <section class="relative overflow-hidden bg-navy-950 bg-pitch bg-spot text-white">
        <x-wordmark :text="$site['founded_year'] ?? '1928'" align="right" />
        <div class="relative mx-auto max-w-[100rem] px-6 py-14 sm:py-20">
            <p class="kicker text-gold-400">{{ __('club.footer.legal') }}</p>
            <h1 class="display mt-3 max-w-4xl text-5xl text-balance sm:text-7xl lg:text-8xl">{{ $title }}</h1>
            <p class="mt-5 max-w-2xl text-lg text-navy-100">
                {{ $locale === 'en' ? 'Last updated' : 'Poslednja izmena' }}: {{ $updated->format('d.m.Y.') }}
            </p>
        </div>
        <div class="rails text-white"></div>
    </section>

    <section class="mx-auto max-w-[100rem] px-6 py-16 lg:py-24">
        <div class="grid gap-10 lg:grid-cols-[16rem_1fr] lg:gap-16">
            <aside class="lg:sticky lg:top-28 lg:self-start">
                <p class="kicker text-red-600">{{ __('club.footer.legal') }}</p>
                <ul class="mt-4 space-y-2 border-l-2 border-navy-900 pl-4 font-display text-lg font-bold uppercase tracking-wide">
                    <li><a href="{{ route('legal.privacy', $locale) }}" class="{{ $isPrivacy ? 'text-red-600' : 'text-navy-900 transition hover:text-red-600' }}">{{ __('club.footer.privacy') }}</a></li>
                    <li><a href="{{ route('legal.terms', $locale) }}" class="{{ ! $isPrivacy ? 'text-red-600' : 'text-navy-900 transition hover:text-red-600' }}">{{ __('club.footer.terms') }}</a></li>
                </ul>
            </aside>

            <article class="prose-club max-w-3xl">
                @include('legal.' . $doc . '-' . $locale)

                <hr>
                <p>
                    <a href="{{ route('legal.' . $other, $locale) }}">{{ __('club.footer.' . $other) }} →</a>
                </p>
            </article>
        </div>
    </section>

</x-site-layout>
