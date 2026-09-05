@php
    use Illuminate\Support\Facades\Storage;
    $locale = app()->getLocale();
    $years = range(now()->year - 4, now()->year - 20);
@endphp

<x-site-layout :title="__('club.youth.page_title')" :description="$site['youth_intro'] ?? __('club.youth.subtitle')">

    <x-flash key="enrol_success" :message="__('club.youth.form.success')" />

    {{-- ===== Hero ===== --}}
    <section class="relative overflow-hidden bg-navy-950 bg-pitch bg-spot text-white">
        <div class="wordmark-ghost absolute -bottom-8 -left-4 text-[22vw]" aria-hidden="true">{{ __('club.nav.youth') }}</div>
        <div class="relative mx-auto grid max-w-[100rem] gap-10 px-6 py-14 sm:py-20 lg:grid-cols-[1.2fr_1fr] lg:items-end">
            <div>
                <p class="kicker flex items-center gap-2 text-gold-400"><span class="live-dot" aria-hidden="true"></span>{{ __('club.youth.eyebrow') }}</p>
                <h1 class="display mt-3 text-5xl text-balance sm:text-7xl lg:text-8xl">{{ __('club.youth.title') }}</h1>
                <p class="mt-5 max-w-xl text-lg text-navy-100">{{ $site['youth_intro'] ?? __('club.youth.subtitle') }}</p>
                <div class="mt-8 flex flex-wrap gap-3">
                    <a href="#upis" class="btn btn-red">{{ __('club.cta.enroll_long') }}</a>
                    <a href="#selekcije" class="btn btn-outline">{{ __('club.youth.selections_title') }}</a>
                </div>
            </div>
            <div class="grid grid-cols-3 gap-4 border-t border-white/10 pt-6 lg:border-0 lg:pt-0">
                <div><span class="score block text-5xl text-gold-400">{{ $selections->count() }}</span><span class="text-xs uppercase tracking-wider text-navy-300">{{ __('club.home.youth_selections') }}</span></div>
                <div><span class="score block text-5xl">{{ $coaches->count() }}</span><span class="text-xs uppercase tracking-wider text-navy-300">{{ __('club.home.youth_coaches') }}</span></div>
                <div><span class="display block text-3xl leading-tight">{{ $site['youth_age_range'] ?? '5–19' }}</span><span class="text-xs uppercase tracking-wider text-navy-300">{{ __('club.home.youth_age') }}</span></div>
            </div>
        </div>
        <div class="rails text-white"></div>
    </section>

    {{-- ===== Kako izgleda upis ===== --}}
    <section class="mx-auto max-w-[100rem] px-6 py-16 lg:py-20">
        <x-section-heading :title="__('club.youth.steps_title')" />
        <ol class="reveal-group mt-10 grid gap-6 sm:grid-cols-2 lg:grid-cols-4">
            @foreach (__('club.youth.steps') as $i => [$stepTitle, $stepText])
                <li class="relative rounded-sm border border-surface-300 bg-white p-6 pt-14">
                    <span class="score absolute left-6 top-4 text-5xl text-surface-300">{{ str_pad($i + 1, 2, '0', STR_PAD_LEFT) }}</span>
                    <h3 class="display text-2xl text-navy-900">{{ $stepTitle }}</h3>
                    <p class="mt-2 text-sm leading-relaxed text-ink-600">{{ $stepText }}</p>
                </li>
            @endforeach
        </ol>
    </section>

    {{-- ===== Selekcije ===== --}}
    <section id="selekcije" class="slant-top bg-navy-900 bg-pitch text-white">
        <div class="mx-auto max-w-[100rem] px-6 py-16 lg:py-24">
            <x-section-heading :kicker="__('club.home.youth_kicker')" :title="__('club.youth.selections_title')" :text="__('club.youth.selections_text')" :dark="true" />

            <div class="reveal-group mt-12 grid gap-4 sm:grid-cols-2 lg:grid-cols-3">
                @foreach ($selections as $selection)
                    <article id="{{ $selection->slug }}" class="group flex flex-col rounded-sm border border-white/10 bg-navy-800/70 transition hover:border-gold-400/60">
                        @if ($selection->photo)
                            <img src="{{ Storage::disk('public')->url($selection->photo) }}" alt="{{ $selection->name }}" class="aspect-[16/9] w-full rounded-t-sm object-cover" loading="lazy">
                        @endif
                        <div class="flex flex-1 flex-col p-6">
                            <div class="flex items-start justify-between gap-3">
                                <div>
                                    <span class="text-[11px] uppercase tracking-wider text-navy-300">{{ __('club.youth.years') }} {{ $selection->birth_years }}</span>
                                    <h3 class="display mt-1 text-4xl">{{ $selection->name }}</h3>
                                </div>
                                <span class="shrink-0 rounded-sm px-2 py-1 font-display text-[10.5px] font-bold uppercase tracking-wider {{ $selection->accepting_applications ? 'bg-red-500 text-white' : 'bg-white/10 text-navy-200' }}">
                                    {{ $selection->accepting_applications ? __('club.youth.open') : __('club.youth.closed') }}
                                </span>
                            </div>
                            @if ($selection->description)
                                <p class="mt-4 text-sm leading-relaxed text-navy-100">{{ $selection->description }}</p>
                            @endif
                            <dl class="mt-5 grid gap-2 border-t border-white/10 pt-4 text-sm">
                                @if ($selection->training_schedule)
                                    <div class="flex gap-3"><dt class="w-20 shrink-0 text-[11px] uppercase tracking-wider text-navy-300">{{ __('club.youth.schedule') }}</dt><dd>{{ $selection->training_schedule }}</dd></div>
                                @endif
                                @if ($selection->training_venue)
                                    <div class="flex gap-3"><dt class="w-20 shrink-0 text-[11px] uppercase tracking-wider text-navy-300">{{ __('club.youth.venue') }}</dt><dd>{{ $selection->training_venue }}</dd></div>
                                @endif
                                @if ($selection->coaches->isNotEmpty())
                                    <div class="flex gap-3"><dt class="w-20 shrink-0 text-[11px] uppercase tracking-wider text-navy-300">{{ __('club.youth.coach') }}</dt><dd>{{ $selection->coaches->pluck('name')->implode(', ') }}</dd></div>
                                @endif
                            </dl>
                            @if ($selection->accepting_applications)
                                <a href="#upis" data-select="{{ $selection->id }}" class="mt-auto inline-flex items-center gap-2 pt-5 font-display text-sm font-bold uppercase tracking-wider text-gold-400 transition group-hover:gap-3">{{ __('club.cta.enroll') }} →</a>
                            @endif
                        </div>
                    </article>
                @endforeach
            </div>
        </div>
    </section>

    {{-- ===== Treneri + treninzi ===== --}}
    <section class="mx-auto max-w-[100rem] px-6 py-16 lg:py-24">
        <div class="grid gap-12 lg:grid-cols-[1fr_1.4fr] lg:gap-16">
            <div>
                <x-section-heading :title="__('club.youth.training_title')" />
                <p class="mt-8 text-lg leading-relaxed text-ink-700">{{ $site['youth_training_info'] ?? '' }}</p>
                <ul class="mt-6 space-y-3 text-sm text-ink-600">
                    @if (!empty($site['stadium']))<li class="flex gap-3"><span class="mt-1.5 h-1.5 w-1.5 shrink-0 rounded-full bg-red-500"></span>{{ $site['stadium'] }}</li>@endif
                    @if (!empty($site['youth_phone']))<li class="flex gap-3"><span class="mt-1.5 h-1.5 w-1.5 shrink-0 rounded-full bg-red-500"></span>{{ __('club.youth.form.or_call') }}: <a href="tel:{{ preg_replace('/[^+\d]/', '', $site['youth_phone']) }}" class="font-semibold text-navy-900 hover:underline">{{ $site['youth_phone'] }}</a></li>@endif
                    @if (!empty($site['youth_email']))<li class="flex gap-3"><span class="mt-1.5 h-1.5 w-1.5 shrink-0 rounded-full bg-red-500"></span><a href="mailto:{{ $site['youth_email'] }}" class="font-semibold text-navy-900 hover:underline">{{ $site['youth_email'] }}</a></li>@endif
                </ul>

                @if ($upcoming->isNotEmpty())
                    <h3 class="display mt-12 text-2xl text-navy-900">{{ __('club.youth.upcoming_title') }}</h3>
                    <div class="mt-2">
                        @foreach ($upcoming as $match)
                            <x-match-row :match="$match" />
                        @endforeach
                    </div>
                @endif
            </div>
            <div>
                <x-section-heading :title="__('club.youth.coaches_title')" />
                <div class="reveal-group mt-8 grid gap-4 sm:grid-cols-2">
                    @forelse ($coaches as $coach)
                        <x-staff-card :member="$coach" />
                    @empty
                        <p class="text-ink-600">{{ __('club.team.no_staff') }}</p>
                    @endforelse
                </div>
            </div>
        </div>
    </section>

    {{-- ===== Galerija / video ===== --}}
    @if ($photos->isNotEmpty() || $videoPosts->isNotEmpty())
        <section class="border-t border-surface-300 bg-white">
            <div class="mx-auto max-w-[100rem] px-6 py-16 lg:py-24">
                <x-section-heading :title="__('club.youth.gallery_title')" :href="route('video.index')" :link="__('club.cta.all_videos')" />
                <div class="reveal-group mt-8 grid grid-cols-2 gap-3 sm:grid-cols-4">
                    @foreach ($videoPosts as $post)
                        <a href="{{ route('news.show', $post->slug) }}" class="group relative col-span-2 block aspect-video overflow-hidden rounded-sm">
                            @if ($post->featured_image)
                                <img src="{{ Storage::disk('public')->url($post->featured_image) }}" alt="{{ $post->title }}" class="h-full w-full object-cover transition duration-500 group-hover:scale-105" loading="lazy">
                            @else
                                <span class="film absolute inset-0"></span>
                            @endif
                            <span class="play-ring absolute left-1/2 top-1/2 z-10 h-14 w-14 -translate-x-1/2 -translate-y-1/2" aria-hidden="true"></span>
                            <span class="absolute inset-x-0 bottom-0 bg-gradient-to-t from-navy-950/90 to-transparent p-4 font-display text-lg font-bold uppercase leading-tight text-white">{{ $post->title }}</span>
                        </a>
                    @endforeach
                    @foreach ($photos as $photo)
                        <figure class="group relative aspect-[4/3] overflow-hidden rounded-sm">
                            <img src="{{ Storage::disk('public')->url($photo->image) }}" alt="{{ $photo->title }}" class="h-full w-full object-cover transition duration-500 group-hover:scale-105" loading="lazy">
                        </figure>
                    @endforeach
                </div>
            </div>
        </section>
    @endif

    {{-- ===== UPIS — forma ===== --}}
    <section id="upis" class="bg-red-500 text-white">
        <div class="mx-auto max-w-[100rem] px-6 py-16 lg:py-24">
            <div class="grid gap-12 lg:grid-cols-[1fr_1.3fr] lg:gap-20">
                <div class="reveal">
                    <p class="kicker text-white/80">{{ __('club.home.enroll_kicker') }}</p>
                    <h2 class="display mt-3 text-5xl sm:text-6xl lg:text-7xl">{{ __('club.youth.form.title') }}</h2>
                    <p class="mt-5 max-w-md text-lg text-white/90">{{ __('club.youth.form.subtitle') }}</p>
                    <p class="mt-8 max-w-md text-white/80">{{ __('club.home.enroll_text') }}</p>
                    @if (!empty($site['youth_phone']))
                        <p class="mt-8 text-sm uppercase tracking-wider text-white/80">{{ __('club.youth.form.or_call') }}</p>
                        <a href="tel:{{ preg_replace('/[^+\d]/', '', $site['youth_phone']) }}" class="display mt-1 block text-4xl hover:underline">{{ $site['youth_phone'] }}</a>
                    @endif
                </div>

                <div class="reveal rounded-sm bg-white p-6 text-ink-900 shadow-2xl sm:p-10">
                    @if (session('enrol_success'))
                        <div class="mb-6 flex items-start gap-3 rounded-sm border border-green-300 bg-green-50 p-4 text-sm text-green-800" role="status">
                            <svg class="mt-0.5 h-5 w-5 shrink-0" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" aria-hidden="true"><circle cx="12" cy="12" r="9" /><path d="m8.5 12.5 2.5 2.5 5-5.5" /></svg>
                            {{ __('club.youth.form.success') }}
                        </div>
                    @endif

                    <form method="POST" action="{{ route('youth.apply') }}" class="grid gap-5 sm:grid-cols-2">
                        @csrf
                        <div class="absolute -left-[9999px]" aria-hidden="true">
                            <label>Website<input type="text" name="website" tabindex="-1" autocomplete="off"></label>
                        </div>

                        <div class="sm:col-span-2">
                            <label for="e-child" class="field-label">{{ __('club.youth.form.child_name') }} *</label>
                            <input id="e-child" type="text" name="child_name" value="{{ old('child_name') }}" required class="field">
                            @error('child_name')<p class="mt-1 text-xs text-red-600">{{ $message }}</p>@enderror
                        </div>
                        <div>
                            <label for="e-year" class="field-label">{{ __('club.youth.form.birth_year') }} *</label>
                            <select id="e-year" name="birth_year" required class="field">
                                <option value="">—</option>
                                @foreach ($years as $year)
                                    <option value="{{ $year }}" @selected(old('birth_year') == $year)>{{ $year }}.</option>
                                @endforeach
                            </select>
                            @error('birth_year')<p class="mt-1 text-xs text-red-600">{{ $message }}</p>@enderror
                        </div>
                        <div>
                            <label for="e-selection" class="field-label">{{ __('club.youth.form.selection') }}</label>
                            <select id="e-selection" name="youth_selection_id" class="field">
                                <option value="">{{ __('club.youth.form.selection_any') }}</option>
                                @foreach ($openSelections as $selection)
                                    <option value="{{ $selection->id }}" @selected(old('youth_selection_id') == $selection->id)>{{ $selection->name }} @if ($selection->birth_years)({{ $selection->birth_years }})@endif</option>
                                @endforeach
                            </select>
                        </div>
                        <div class="sm:col-span-2">
                            <label for="e-parent" class="field-label">{{ __('club.youth.form.parent_name') }} *</label>
                            <input id="e-parent" type="text" name="parent_name" value="{{ old('parent_name') }}" required class="field">
                            @error('parent_name')<p class="mt-1 text-xs text-red-600">{{ $message }}</p>@enderror
                        </div>
                        <div>
                            <label for="e-phone" class="field-label">{{ __('club.youth.form.phone') }} *</label>
                            <input id="e-phone" type="tel" name="phone" value="{{ old('phone') }}" required class="field">
                            @error('phone')<p class="mt-1 text-xs text-red-600">{{ $message }}</p>@enderror
                        </div>
                        <div>
                            <label for="e-email" class="field-label">{{ __('club.youth.form.email') }} *</label>
                            <input id="e-email" type="email" name="email" value="{{ old('email') }}" required class="field">
                            @error('email')<p class="mt-1 text-xs text-red-600">{{ $message }}</p>@enderror
                        </div>
                        <div class="sm:col-span-2">
                            <label for="e-note" class="field-label">{{ __('club.youth.form.note') }}</label>
                            <textarea id="e-note" name="note" rows="3" class="field">{{ old('note') }}</textarea>
                        </div>
                        <div class="sm:col-span-2">
                            <label class="flex items-start gap-3 text-sm text-ink-600">
                                <input type="checkbox" name="consent" value="1" required class="mt-1 h-4 w-4 accent-red-500" @checked(old('consent'))>
                                <span>{{ __('club.youth.form.consent') }}</span>
                            </label>
                            @error('consent')<p class="mt-1 text-xs text-red-600">{{ $message }}</p>@enderror
                        </div>
                        <div class="sm:col-span-2">
                            <button type="submit" class="btn btn-red w-full">{{ __('club.youth.form.submit') }}</button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </section>

    {{-- ===== Vesti omladinaca ===== --}}
    @if ($posts->isNotEmpty())
        <section class="mx-auto max-w-[100rem] px-6 py-16 lg:py-24">
            <x-section-heading :title="__('club.youth.news_title')" :href="route('news.index', ['category' => 'omladinci'])" :link="__('club.cta.all_news')" />
            <div class="reveal-group mt-10 grid gap-x-8 gap-y-12 sm:grid-cols-2 lg:grid-cols-3">
                @foreach ($posts as $post)
                    <x-post-card :post="$post" />
                @endforeach
            </div>
        </section>
    @endif

    <script>
        // "Upiši se" on a selection card preselects that selection in the form.
        document.querySelectorAll('[data-select]').forEach((a) => a.addEventListener('click', () => {
            const select = document.getElementById('e-selection');
            if (select) select.value = a.dataset.select;
        }));
    </script>

</x-site-layout>
