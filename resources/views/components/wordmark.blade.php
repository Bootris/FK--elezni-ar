{{-- Oversized outline wordmark sitting behind a hero (and in the footer).

     The size is derived from the character count instead of a hand-picked
     `text-[Nvw]` per page, so the whole word stays inside the screen on a 320px
     phone just as it does on a wide laptop, and it keeps working when the club
     edits the club name / season / founding year in the admin.

     It is laid out in the same `max-w-[100rem] px-6` container as the page
     content, so it starts where the heading starts instead of hugging the
     viewport edge on a wide monitor. --}}

@props([
    'text' => '',
    'align' => 'left',      // which edge of the container the word is anchored to
    'fit' => 92,            // share of the container width (%) the word spans
    'max' => 22,            // ceiling in vw, so short words stay hero-sized
    'container' => 1600,    // max-w-[100rem] in px; caps the size on wide screens
    'floating' => true,     // false = normal flow (footer), no absolute wrapper
])

@php
    $text = trim((string) $text);
    // Barlow Condensed Black uppercase runs 0.40-0.45em per glyph depending on
    // the letters; the upper bound keeps even the widest string inside $fit%.
    $size = min($max, round($fit / (max(mb_strlen($text), 1) * 0.45), 2));
    $fontSize = "min({$size}vw, " . round($size / 100 * $container, 1) . 'px)';
@endphp

@if ($text !== '')
    @if ($floating)
        <div class="pointer-events-none absolute inset-x-0 bottom-3 mx-auto max-w-[100rem] px-4 sm:bottom-4 sm:px-6" aria-hidden="true">
            <div {{ $attributes->merge(['class' => 'wordmark-ghost ' . ($align === 'right' ? 'text-right' : 'text-left')]) }}
                style="font-size: {{ $fontSize }}">{{ $text }}</div>
        </div>
    @else
        <div {{ $attributes->merge(['class' => 'wordmark-ghost']) }}
            style="font-size: {{ $fontSize }}" aria-hidden="true">{{ $text }}</div>
    @endif
@endif
