@php
    use App\Support\VideoEmbed;

    $liveOn = filter_var($site['live_enabled'] ?? false, FILTER_VALIDATE_BOOLEAN);
    $liveSrc = $liveOn ? VideoEmbed::url($site['live_url'] ?? null) : null;
    $liveTitle = trim($site['live_title'] ?? '') ?: __('club.live.default_title');
    $liveNote = trim($site['live_note'] ?? '');
    // Autoplay only once the viewer has actually opened the player; the iframe
    // src is set by JS on open, so nothing loads from YouTube until then.
    $liveSrcAuto = $liveSrc ? $liveSrc . (str_contains($liveSrc, '?') ? '&' : '?') . 'autoplay=1' : null;
@endphp

@if ($liveSrc)
    <div class="live-bar" data-live-bar data-live-key="{{ md5($liveSrc . $liveTitle) }}" role="region" aria-label="{{ __('club.live.now') }}">
        <div class="mx-auto flex max-w-[100rem] items-center gap-4 px-6 py-3">
            <span class="flex shrink-0 items-center gap-2 font-display text-sm font-bold uppercase tracking-[0.16em]">
                <span class="live-dot" aria-hidden="true"></span>{{ __('club.live.now') }}
            </span>
            <span class="min-w-0 flex-1 truncate text-sm text-white/90">
                {{ $liveTitle }}@if ($liveNote)<span class="hidden text-white/60 sm:inline"> · {{ $liveNote }}</span>@endif
            </span>
            <button type="button" class="live-bar__cta" data-live-open="live-stream">
                <svg class="h-3.5 w-3.5" viewBox="0 0 12 14" fill="currentColor" aria-hidden="true"><path d="M0 0l12 7-12 7z"/></svg>
                <span class="hidden sm:inline">{{ __('club.live.watch') }}</span>
            </button>
            <button type="button" class="live-bar__dismiss" data-live-dismiss aria-label="{{ __('club.live.dismiss') }}">
                <svg class="h-4 w-4" viewBox="0 0 20 20" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" aria-hidden="true"><path d="M5 5l10 10M15 5L5 15"/></svg>
            </button>
        </div>
    </div>

    <dialog id="live-stream" class="live-modal" aria-labelledby="live-stream-title">
        <div class="live-modal__panel bg-navy-950">
            <div class="flex items-center gap-3 px-5 py-3.5">
                <span class="flex shrink-0 items-center gap-2 font-display text-xs font-bold uppercase tracking-[0.16em] text-red-400">
                    <span class="live-dot" aria-hidden="true"></span>{{ __('club.live.now') }}
                </span>
                <h2 id="live-stream-title" class="min-w-0 flex-1 truncate font-display text-base font-bold text-white">{{ $liveTitle }}</h2>
                <button type="button" class="live-modal__close" data-live-close aria-label="{{ __('club.live.close') }}">
                    <svg class="h-5 w-5" viewBox="0 0 20 20" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" aria-hidden="true"><path d="M5 5l10 10M15 5L5 15"/></svg>
                </button>
            </div>
            <div class="aspect-video w-full bg-black">
                <iframe data-live-src="{{ $liveSrcAuto }}" class="h-full w-full border-0"
                    allow="accelerometer; autoplay; clipboard-write; encrypted-media; gyroscope; picture-in-picture"
                    allowfullscreen title="{{ $liveTitle }}"></iframe>
            </div>
            @if ($liveNote)
                <p class="px-5 py-3 text-sm text-navy-200">{{ $liveNote }}</p>
            @endif
        </div>
    </dialog>
@endif
