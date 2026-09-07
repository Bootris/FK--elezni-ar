@props(['match', 'label' => null, 'countdown' => false, 'dark' => true])

@php
    $club = $site['club_short_name'] ?? 'Železničar';
    $finished = $match->is_finished;
    $isLive = $match->status === 'live';
@endphp

<div {{ $attributes->merge(['class' => 'relative overflow-hidden rounded-sm p-6 ' . ($dark ? 'bg-navy-800 bg-pitch text-white' : 'border border-surface-300 bg-white text-navy-900')]) }}>
    <div class="flex items-center justify-between gap-3 text-[11px] font-semibold uppercase tracking-[0.16em] {{ $dark ? 'text-navy-200' : 'text-ink-400' }}">
        <span class="flex items-center gap-2 {{ $dark ? 'text-gold-400' : 'text-red-600' }}">
            @if ($isLive)<span class="live-dot" aria-hidden="true"></span>{{ __('club.match.live') }}@else{{ $label ?? $match->competition }}@endif
        </span>
        <span>{{ $match->competition }}@if ($match->round) · {{ $match->round }}@endif</span>
    </div>

    <div class="mt-5 grid grid-cols-[1fr_auto_1fr] items-center gap-3">
        <div class="text-right">
            <span class="display block text-2xl leading-none sm:text-3xl {{ $match->is_home ? ($dark ? 'text-white' : 'text-navy-900') : ($dark ? 'text-navy-100' : 'text-ink-600') }}">{{ $match->home_team }}</span>
            <span class="mt-1 block text-[11px] uppercase tracking-wider {{ $dark ? 'text-navy-300' : 'text-ink-400' }}">{{ __('club.match.home') }}</span>
        </div>

        <div class="px-2 text-center">
            @if ($finished || $isLive)
                <span class="score text-5xl sm:text-6xl">{{ $match->home_score ?? 0 }}:{{ $match->away_score ?? 0 }}</span>
            @else
                <span class="score text-4xl sm:text-5xl">{{ $match->kickoff_at->format('H:i') }}</span>
                <span class="mt-1 block text-[11px] font-semibold uppercase tracking-wider {{ $dark ? 'text-navy-300' : 'text-ink-400' }}">{{ rtrim($match->kickoff_at->translatedFormat('D'), '.') }} {{ $match->kickoff_at->format('d.m.') }}</span>
            @endif
        </div>

        <div>
            <span class="display block text-2xl leading-none sm:text-3xl {{ !$match->is_home ? ($dark ? 'text-white' : 'text-navy-900') : ($dark ? 'text-navy-100' : 'text-ink-600') }}">{{ $match->away_team }}</span>
            <span class="mt-1 block text-[11px] uppercase tracking-wider {{ $dark ? 'text-navy-300' : 'text-ink-400' }}">{{ __('club.match.away') }}</span>
        </div>
    </div>

    @if ($countdown && !$finished && !$isLive && $match->kickoff_at->isFuture())
        <div class="mt-5 grid grid-cols-4 gap-2 border-t border-white/10 pt-4" data-countdown="{{ $match->kickoff_at->toIso8601String() }}">
            @foreach (['d' => 'd', 'h' => 'h', 'm' => 'min', 's' => 'sec'] as $key => $unit)
                <div class="text-center">
                    <span class="score block text-2xl" data-cd="{{ $key }}">00</span>
                    <span class="text-[10px] uppercase tracking-wider {{ $dark ? 'text-navy-300' : 'text-ink-400' }}">{{ $unit }}</span>
                </div>
            @endforeach
        </div>
    @endif

    <div class="mt-4 flex flex-wrap items-center justify-between gap-2 text-xs {{ $dark ? 'text-navy-200' : 'text-ink-600' }}">
        <span>{{ $match->kickoff_at->format('d.m.Y.') }}@if ($match->venue) · {{ $match->venue }}@endif</span>
        @if ($finished && $match->outcome)
            <span class="form-pill form-{{ $match->outcome }}">{{ $match->outcome }}</span>
        @elseif ($match->status === 'postponed')
            <span class="font-semibold uppercase tracking-wider text-red-500">{{ __('club.match.postponed') }}</span>
        @endif
    </div>
</div>
