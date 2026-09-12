@props(['player'])

@php
    $photoUrl = $player->photo ? Illuminate\Support\Facades\Storage::disk('public')->url($player->photo) : null;
    $parts = collect(explode(' ', trim($player->name)))->filter();
    $first = $parts->first();
    $last = $parts->slice(1)->implode(' ');
@endphp

<article {{ $attributes->merge(['class' => 'player-card group flex h-full flex-col rounded-sm text-white']) }}>
    <button type="button" class="player-card__open" data-player-open="player-{{ $player->id }}" aria-haspopup="dialog" aria-controls="player-{{ $player->id }}">
        <span class="sr-only">{{ __('club.team.view_profile') }}: {{ $player->name }}</span>
    </button>
    <span class="number-ghost">{{ $player->shirt_number ?? '' }}</span>

    <div class="relative aspect-[4/5] overflow-hidden">
        @if ($photoUrl)
            <img src="{{ $photoUrl }}" alt="{{ $player->name }}" class="h-full w-full object-cover object-top" loading="lazy">
        @else
            <div class="silhouette h-full w-full"></div>
        @endif
        <div class="absolute inset-x-0 bottom-0 h-1/2 bg-gradient-to-t from-navy-950 to-transparent"></div>

        <div class="absolute left-4 top-4 flex flex-col gap-1.5">
            @if ($player->is_captain)
                <span class="rounded-sm bg-gold-500 px-2 py-0.5 font-display text-[10.5px] font-bold uppercase tracking-[0.14em] text-navy-950">{{ __('club.team.captain') }}</span>
            @endif
            @if ($player->from_academy)
                <span class="rounded-sm bg-red-500 px-2 py-0.5 font-display text-[10.5px] font-bold uppercase tracking-[0.14em] text-white">{{ __('club.team.academy_badge') }}</span>
            @endif
        </div>
    </div>

    <div class="relative -mt-14 flex flex-1 flex-col p-5">
        <div class="flex items-end gap-2.5">
            @if ($player->shirt_number)
                <span class="score shrink-0 text-[2.6rem] text-gold-400">{{ $player->shirt_number }}</span>
            @endif
            <div class="min-w-0 pb-1 leading-none">
                <span class="block truncate font-display text-sm font-semibold uppercase tracking-wide text-navy-200">{{ $first }}</span>
                <span class="display block truncate text-xl">{{ $last ?: '&nbsp;' }}</span>
            </div>
        </div>
        <div class="mt-4 flex items-center justify-between border-t border-white/10 pt-3 text-[11.5px] font-semibold uppercase tracking-[0.12em] text-navy-200">
            <span>{{ $player->position_label }}</span>
            @if ($player->age)
                <span>{{ $player->age }} {{ __('club.team.age') }}</span>
            @else
                <span class="text-gold-400 transition group-hover:text-gold-300">{{ __('club.team.view_profile') }} →</span>
            @endif
        </div>
    </div>
</article>
<x-player-profile :player="$player" />
