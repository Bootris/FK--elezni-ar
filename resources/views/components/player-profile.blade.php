@props(['player'])

@php
    $dash = __('club.team.not_set');
    $rows = [
        __('club.team.position') => $player->position_label,
        __('club.team.shirt_number') => $player->shirt_number,
        __('club.team.born') => $player->birth_date
            ? $player->birth_date->format('d.m.Y') . ($player->age ? ' (' . $player->age . ' ' . __('club.team.age') . ')' : '')
            : null,
        __('club.team.birth_place') => $player->birth_place,
        __('club.team.nationality') => $player->nationality,
        __('club.team.height') => $player->height_cm ? $player->height_cm . ' cm' : null,
        __('club.team.weight') => $player->weight_kg ? $player->weight_kg . ' kg' : null,
        __('club.team.foot') => $player->preferred_foot_label,
        __('club.team.joined') => $player->joined_year,
        __('club.team.previous_club') => $player->previous_club,
    ];
@endphp

<dialog id="player-{{ $player->id }}" class="player-profile" aria-labelledby="player-{{ $player->id }}-name">
    <div class="player-profile__panel bg-navy-950 bg-pitch text-white">
        <button type="button" class="player-profile__close" data-profile-close aria-label="{{ __('club.team.close') }}">
            <svg class="h-5 w-5" viewBox="0 0 20 20" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round"><path d="M5 5l10 10M15 5L5 15"/></svg>
        </button>

        <div class="grid sm:grid-cols-[minmax(0,2fr)_minmax(0,3fr)]">
            <div class="relative aspect-[4/3] overflow-hidden sm:aspect-auto sm:min-h-[26rem]">
                @if ($player->photo_url)
                    <img src="{{ $player->photo_url }}" alt="{{ $player->name }}" class="h-full w-full object-cover object-top" loading="lazy">
                @else
                    <div class="silhouette h-full w-full"></div>
                @endif
                <div class="absolute inset-x-0 bottom-0 h-1/3 bg-gradient-to-t from-navy-950 to-transparent sm:hidden"></div>
                @if ($player->shirt_number)
                    <span class="score absolute bottom-3 right-4 text-6xl text-gold-400 sm:bottom-4 sm:right-5">{{ $player->shirt_number }}</span>
                @endif
            </div>

            <div class="flex flex-col p-6 sm:p-8">
                <p class="kicker text-gold-400">{{ __('club.team.profile') }}</p>
                <h2 id="player-{{ $player->id }}-name" class="display mt-2 text-4xl leading-none sm:text-5xl">{{ $player->name }}</h2>
                <div class="mt-3 flex flex-wrap gap-1.5">
                    @if ($player->is_captain)
                        <span class="rounded-sm bg-gold-500 px-2 py-0.5 font-display text-[10.5px] font-bold uppercase tracking-[0.14em] text-navy-950">{{ __('club.team.captain') }}</span>
                    @endif
                    @if ($player->from_academy)
                        <span class="rounded-sm bg-red-500 px-2 py-0.5 font-display text-[10.5px] font-bold uppercase tracking-[0.14em] text-white">{{ __('club.team.academy_badge') }}</span>
                    @endif
                </div>

                <dl class="mt-6 grid grid-cols-2 gap-x-6 gap-y-3 border-t border-white/10 pt-5 text-sm">
                    @foreach ($rows as $label => $value)
                        <div class="min-w-0">
                            <dt class="text-[11px] font-semibold uppercase tracking-[0.14em] text-navy-200">{{ $label }}</dt>
                            <dd class="mt-0.5 break-words font-display text-lg font-bold {{ $value === null || $value === '' ? 'text-white/35' : '' }}">{{ $value === null || $value === '' ? $dash : $value }}</dd>
                        </div>
                    @endforeach
                </dl>

                @if ($player->bio)
                    <p class="mt-6 border-t border-white/10 pt-5 text-sm leading-relaxed text-navy-100">{{ $player->bio }}</p>
                @endif
            </div>
        </div>
    </div>
</dialog>
