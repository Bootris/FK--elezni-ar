@php
    $rows = array_filter([
        'account_holder' => $site['account_holder'] ?? null,
        'bank' => $site['bank_name'] ?? null,
        'account_number' => $site['bank_account'] ?? null,
        'purpose' => $site['payment_purpose'] ?? null,
        'code' => $site['payment_code'] ?? null,
        'model' => $site['payment_model'] ?? null,
        'reference' => $site['payment_reference'] ?? null,
        'iban' => $site['iban'] ?? null,
        'swift' => $site['swift'] ?? null,
    ]);
    $locale = app()->getLocale();
@endphp

<x-site-layout :title="__('club.support.page_title')" :description="$site['support_intro'] ?? __('club.support.subtitle')">

    <section class="relative overflow-hidden bg-navy-950 bg-pitch bg-spot text-white">
        <x-wordmark :text="$site['founded_year'] ?? '1928'" align="right" />
        <div class="relative mx-auto max-w-[100rem] px-6 py-14 sm:py-20">
            <p class="kicker text-gold-400">{{ __('club.support.eyebrow') }}</p>
            <h1 class="display mt-3 max-w-4xl text-5xl text-balance sm:text-7xl lg:text-8xl">{{ __('club.support.title') }}</h1>
            <p class="mt-5 max-w-2xl text-lg text-navy-100">{{ $site['support_intro'] ?? __('club.support.subtitle') }}</p>
        </div>
        <div class="rails text-white"></div>
    </section>

    <section class="mx-auto max-w-[100rem] px-6 py-16 lg:py-24">
        <div class="grid gap-12 lg:grid-cols-[1.2fr_1fr] lg:gap-20">
            {{-- Payment slip --}}
            <div class="reveal">
                <p class="kicker text-red-600">{{ __('club.support.how_title') }}</p>
                <p class="mt-3 max-w-xl text-ink-600">{{ __('club.support.how_text') }}</p>

                <div class="mt-8 overflow-hidden rounded-sm border-2 border-navy-900 bg-white">
                    <div class="flex items-center justify-between bg-navy-900 px-5 py-3 text-white">
                        <span class="display text-2xl">{{ $site['site_name'] ?? 'FK Železničar' }}</span>
                        <img src="{{ asset('images/logo.png') }}" alt="" class="h-9 w-9">
                    </div>
                    <dl class="divide-y divide-surface-300">
                        @foreach ($rows as $key => $value)
                            <div class="grid grid-cols-[1fr_auto] items-center gap-x-3 gap-y-1 px-5 py-3.5 sm:grid-cols-[11rem_1fr_auto]">
                                <dt class="col-span-2 text-[11px] font-bold uppercase tracking-[0.14em] text-ink-400 sm:col-span-1">{{ __('club.support.' . $key) }}</dt>
                                <dd class="min-w-0 break-words {{ $key === 'account_number' ? 'score text-xl text-navy-900 sm:text-3xl' : 'font-semibold text-ink-900' }}">{{ $value }}</dd>
                                <dd>
                                    <button type="button" data-copy="{{ $value }}" data-copied="{{ __('club.support.copied') }}"
                                        class="rounded-sm border border-surface-300 px-2.5 py-1 font-display text-[11px] font-bold uppercase tracking-wider text-ink-600 transition hover:border-navy-900 hover:text-navy-900 [&.is-copied]:border-green-600 [&.is-copied]:text-green-700">
                                        <span data-copy-label>{{ __('club.support.copy') }}</span>
                                    </button>
                                </dd>
                            </div>
                        @endforeach
                    </dl>
                </div>

                @if (!empty($site['support_note']))
                    <p class="mt-4 text-sm text-ink-600">{{ $site['support_note'] }}</p>
                @endif
                <p class="display mt-8 text-3xl text-red-600">{{ __('club.support.thanks') }}</p>
            </div>

            {{-- Where the money goes --}}
            <div class="reveal">
                <p class="kicker text-red-600">{{ __('club.support.where_title') }}</p>
                <ul class="mt-6 space-y-4">
                    @foreach (__('club.support.where') as $i => [$whereTitle, $whereText])
                        <li class="flex gap-5 rounded-sm border border-surface-300 bg-white p-5">
                            <span class="score text-4xl text-gold-500">{{ str_pad($i + 1, 2, '0', STR_PAD_LEFT) }}</span>
                            <div>
                                <h3 class="display text-2xl text-navy-900">{{ $whereTitle }}</h3>
                                <p class="mt-1 text-sm text-ink-600">{{ $whereText }}</p>
                            </div>
                        </li>
                    @endforeach
                </ul>

                <div class="mt-8 rounded-sm bg-navy-950 bg-pitch p-6 text-white">
                    <h3 class="display text-2xl">{{ __('club.support.other_title') }}</h3>
                    <p class="mt-2 text-sm text-navy-200">{{ __('club.support.other_text') }}</p>
                    <div class="mt-5 flex flex-wrap gap-3">
                        @if (!empty($site['email']))<a href="mailto:{{ $site['email'] }}" class="btn btn-gold btn-sm">{{ $site['email'] }}</a>@endif
                        <a href="{{ route('contact.index', $locale) }}" class="btn btn-outline btn-sm">{{ __('club.nav.contact') }}</a>
                    </div>
                </div>
            </div>
        </div>
    </section>

    <x-cta-band />

</x-site-layout>
