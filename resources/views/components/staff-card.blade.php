@props(['member'])

@php
    $photoUrl = $member->photo ? Illuminate\Support\Facades\Storage::disk('public')->url($member->photo) : null;
    $initials = collect(explode(' ', trim($member->name)))->filter()->map(fn ($p) => mb_substr($p, 0, 1))->take(2)->implode('');
@endphp

<article {{ $attributes->merge(['class' => 'flex items-center gap-4 rounded-sm border border-surface-300 bg-white p-4 transition hover:-translate-y-0.5 hover:shadow-lg']) }}>
    @if ($photoUrl)
        <img src="{{ $photoUrl }}" alt="{{ $member->name }}" class="h-16 w-16 shrink-0 rounded-sm object-cover" loading="lazy">
    @else
        <div class="flex h-16 w-16 shrink-0 items-center justify-center rounded-sm bg-navy-800 font-display text-xl font-bold text-gold-400">{{ $initials }}</div>
    @endif
    <div class="min-w-0">
        <h3 class="display text-xl text-navy-900">{{ $member->name }}</h3>
        <p class="mt-0.5 text-sm font-semibold text-red-600">{{ $member->role }}@if ($member->selection) · {{ $member->selection->name }}@endif</p>
        @if ($member->licence)
            <p class="mt-0.5 text-xs uppercase tracking-wider text-ink-400">{{ __('club.team.licence') }} {{ $member->licence }}</p>
        @endif
    </div>
</article>
