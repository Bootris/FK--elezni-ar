{{-- The two calls to action that follow every page: enrol (primary) + support (secondary). --}}
@php $locale = app()->getLocale(); @endphp

<section class="mx-auto max-w-[100rem] px-6 py-16 lg:py-20">
    <div class="reveal-group grid gap-6 lg:grid-cols-[1.4fr_1fr]">
        <a href="{{ route('youth.index', $locale) }}#upis" class="group relative overflow-hidden rounded-sm bg-red-500 p-8 text-white transition hover:bg-red-600 sm:p-12">
            <span class="wordmark-ghost absolute -bottom-6 -right-4 text-[9rem]" style="-webkit-text-stroke-color: rgba(255,255,255,0.15);" aria-hidden="true">upis</span>
            <p class="kicker text-white/80">{{ __('club.home.enroll_kicker') }}</p>
            <h2 class="display mt-3 max-w-xl text-4xl sm:text-5xl">{{ __('club.home.enroll_title') }}</h2>
            <p class="mt-4 max-w-md text-white/85">{{ __('club.home.enroll_text') }}</p>
            <span class="btn btn-sm mt-8 bg-white text-red-600 transition group-hover:gap-3">{{ __('club.cta.enroll_long') }} <span aria-hidden="true">→</span></span>
        </a>

        <a href="{{ route('support.index', $locale) }}" class="group relative overflow-hidden rounded-sm bg-navy-900 bg-pitch p-8 text-white transition hover:bg-navy-800 sm:p-12">
            <p class="kicker text-gold-400">{{ __('club.home.support_kicker') }}</p>
            <h2 class="display mt-3 text-4xl sm:text-5xl">{{ __('club.home.support_title') }}</h2>
            <p class="mt-4 max-w-md text-navy-200">{{ __('club.home.support_text') }}</p>
            <span class="btn btn-gold btn-sm mt-8 transition group-hover:gap-3">{{ __('club.cta.support_long') }} <span aria-hidden="true">→</span></span>
        </a>
    </div>
</section>
