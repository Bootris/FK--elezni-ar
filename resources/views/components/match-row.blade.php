@props(['match'])

@php $finished = $match->is_finished; @endphp

<div class="grid grid-cols-[4.5rem_1fr_auto] items-center gap-4 border-b border-surface-300 py-4 sm:grid-cols-[6rem_1fr_auto_auto]">
    <div class="leading-tight">
        <span class="display block text-2xl text-navy-900">{{ $match->kickoff_at->format('d.m') }}</span>
        <span class="text-[11px] uppercase tracking-wider text-ink-400">{{ $finished ? $match->kickoff_at->format('Y.') : $match->kickoff_at->format('H:i') }}</span>
    </div>
    <div class="min-w-0">
        <div class="flex flex-wrap items-center gap-x-2 font-display text-lg font-bold uppercase leading-tight text-navy-900 sm:text-xl">
            <span class="{{ $match->is_home ? '' : 'text-ink-600' }}">{{ $match->home_team }}</span>
            <span class="text-ink-400">–</span>
            <span class="{{ $match->is_home ? 'text-ink-600' : '' }}">{{ $match->away_team }}</span>
        </div>
        <p class="mt-0.5 truncate text-xs text-ink-400">{{ $match->competition }}@if ($match->round) · {{ $match->round }}@endif @if ($match->venue) · {{ $match->venue }}@endif</p>
    </div>
    <div class="text-right">
        @if ($finished)
            <span class="score text-2xl text-navy-900">{{ $match->score_line }}</span>
        @else
            <span class="rounded-sm border border-surface-300 px-2 py-1 font-display text-[11px] font-bold uppercase tracking-wider text-ink-600">{{ $match->is_home ? __('club.match.home') : __('club.match.away') }}</span>
        @endif
    </div>
    <div class="hidden w-14 text-right sm:block">
        @if ($finished && $match->outcome)
            <span class="form-pill form-{{ $match->outcome }}">{{ $match->outcome }}</span>
        @endif
        @if ($match->report)
            <a href="{{ route('news.show', $match->report->slug) }}" class="ml-1 text-[11px] font-bold uppercase tracking-wider text-red-600 hover:underline">{{ __('club.match.report') }}</a>
        @endif
    </div>
</div>
