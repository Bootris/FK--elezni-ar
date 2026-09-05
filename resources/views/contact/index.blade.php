@php
    $locale = app()->getLocale();
    $socials = array_filter(['YouTube' => $site['youtube'] ?? null, 'Instagram' => $site['instagram'] ?? null, 'Facebook' => $site['facebook'] ?? null, 'TikTok' => $site['tiktok'] ?? null]);
@endphp

<x-site-layout :title="__('club.contact.page_title')" :description="__('club.contact.subtitle')">

    <x-flash key="contact_success" :message="__('club.contact.form.success')" />

    <section class="bg-navy-950 bg-pitch text-white">
        <div class="mx-auto max-w-[100rem] px-6 py-14 sm:py-20">
            <p class="kicker text-gold-400">{{ __('club.contact.eyebrow') }}</p>
            <h1 class="display mt-3 text-6xl sm:text-8xl">{{ __('club.contact.title') }}</h1>
            <p class="mt-5 max-w-xl text-navy-100">{{ __('club.contact.subtitle') }}</p>
        </div>
        <div class="rails text-white"></div>
    </section>

    <section class="mx-auto max-w-[100rem] px-6 py-16 lg:py-24">
        <div class="grid gap-10 lg:grid-cols-5">
            <div class="reveal lg:col-span-3">
                @if (session('contact_success'))
                    <div class="mb-6 flex items-start gap-3 rounded-sm border border-green-300 bg-green-50 p-4 text-sm text-green-800" role="status">
                        <svg class="mt-0.5 h-5 w-5 shrink-0" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" aria-hidden="true"><circle cx="12" cy="12" r="9" /><path d="m8.5 12.5 2.5 2.5 5-5.5" /></svg>
                        {{ __('club.contact.form.success') }}
                    </div>
                @endif

                <form method="POST" action="{{ route('contact.submit') }}" class="grid gap-5 sm:grid-cols-2">
                    @csrf
                    <div class="absolute -left-[9999px]" aria-hidden="true">
                        <label>Website<input type="text" name="website" tabindex="-1" autocomplete="off"></label>
                    </div>
                    <div>
                        <label for="c-name" class="field-label">{{ __('club.contact.form.name') }} *</label>
                        <input id="c-name" type="text" name="name" value="{{ old('name') }}" required class="field">
                        @error('name')<p class="mt-1 text-xs text-red-600">{{ $message }}</p>@enderror
                    </div>
                    <div>
                        <label for="c-email" class="field-label">{{ __('club.contact.form.email') }} *</label>
                        <input id="c-email" type="email" name="email" value="{{ old('email') }}" required class="field">
                        @error('email')<p class="mt-1 text-xs text-red-600">{{ $message }}</p>@enderror
                    </div>
                    <div>
                        <label for="c-phone" class="field-label">{{ __('club.contact.form.phone') }}</label>
                        <input id="c-phone" type="tel" name="phone" value="{{ old('phone') }}" class="field">
                    </div>
                    <div>
                        <label for="c-subject" class="field-label">{{ __('club.contact.form.subject') }}</label>
                        <input id="c-subject" type="text" name="subject" value="{{ old('subject') }}" class="field">
                    </div>
                    <div class="sm:col-span-2">
                        <label for="c-message" class="field-label">{{ __('club.contact.form.message') }} *</label>
                        <textarea id="c-message" name="message" rows="6" required class="field">{{ old('message') }}</textarea>
                        @error('message')<p class="mt-1 text-xs text-red-600">{{ $message }}</p>@enderror
                    </div>
                    <div class="sm:col-span-2">
                        <button type="submit" class="btn btn-red w-full sm:w-auto">{{ __('club.contact.form.submit') }}</button>
                    </div>
                </form>
            </div>

            <div class="reveal lg:col-span-2">
                <div class="flex h-full flex-col rounded-sm bg-navy-950 bg-pitch p-8 text-navy-100">
                    <h2 class="display text-3xl text-white">{{ __('club.contact.info_title') }}</h2>
                    <ul class="mt-6 space-y-5 text-sm">
                        @if (!empty($site['address']))
                            <li><span class="block text-[11px] uppercase tracking-wider text-navy-300">{{ __('club.contact.address') }}</span>{{ $site['address'] }}</li>
                        @endif
                        @if (!empty($site['stadium']))
                            <li><span class="block text-[11px] uppercase tracking-wider text-navy-300">{{ __('club.match.venue') }}</span>{{ $site['stadium'] }}</li>
                        @endif
                        @if (!empty($site['phone']))
                            <li><span class="block text-[11px] uppercase tracking-wider text-navy-300">{{ __('club.contact.phone') }}</span>
                                <a href="tel:{{ preg_replace('/[^+\d]/', '', $site['phone']) }}" class="transition hover:text-gold-400">{{ $site['phone'] }}</a></li>
                        @endif
                        @if (!empty($site['email']))
                            <li><span class="block text-[11px] uppercase tracking-wider text-navy-300">{{ __('club.contact.email') }}</span>
                                <a href="mailto:{{ $site['email'] }}" class="transition hover:text-gold-400">{{ $site['email'] }}</a></li>
                        @endif
                        @if (!empty($site['working_hours']))
                            <li><span class="block text-[11px] uppercase tracking-wider text-navy-300">{{ __('club.contact.hours') }}</span>{{ $site['working_hours'] }}</li>
                        @endif
                    </ul>

                    @if (!empty($site['youth_phone']) || !empty($site['youth_email']))
                        <h3 class="display mt-8 text-2xl text-gold-400">{{ __('club.contact.youth_title') }}</h3>
                        <ul class="mt-3 space-y-2 text-sm">
                            @if (!empty($site['youth_phone']))<li><a href="tel:{{ preg_replace('/[^+\d]/', '', $site['youth_phone']) }}" class="transition hover:text-gold-400">{{ $site['youth_phone'] }}</a></li>@endif
                            @if (!empty($site['youth_email']))<li><a href="mailto:{{ $site['youth_email'] }}" class="transition hover:text-gold-400">{{ $site['youth_email'] }}</a></li>@endif
                        </ul>
                    @endif

                    @if ($socials)
                        <h3 class="display mt-8 text-2xl text-white">{{ __('club.contact.socials') }}</h3>
                        <div class="mt-3 flex flex-wrap gap-2">
                            @foreach ($socials as $label => $url)
                                <a href="{{ $url }}" target="_blank" rel="noopener" class="btn btn-outline btn-sm">{{ $label }}</a>
                            @endforeach
                        </div>
                    @endif

                    @if (!empty($site['map_embed']))
                        <iframe src="{{ $site['map_embed'] }}" class="mt-8 h-56 w-full rounded-sm border-0" loading="lazy" referrerpolicy="no-referrer-when-downgrade" title="Mapa"></iframe>
                    @endif
                </div>
            </div>
        </div>
    </section>

    <x-cta-band />

</x-site-layout>
