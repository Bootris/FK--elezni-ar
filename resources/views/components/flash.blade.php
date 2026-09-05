@props(['key', 'message'])

@if (session($key))
    <div id="flash-toast" role="status"
        class="fixed bottom-20 left-1/2 z-50 flex w-[calc(100%-2rem)] max-w-md -translate-x-1/2 items-start gap-3 rounded-sm border-l-4 border-red-500 bg-navy-950 p-4 text-sm text-white shadow-2xl transition duration-500 lg:bottom-6">
        <svg class="mt-0.5 h-5 w-5 shrink-0 text-gold-400" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" aria-hidden="true"><circle cx="12" cy="12" r="9" /><path d="m8.5 12.5 2.5 2.5 5-5.5" /></svg>
        {{ $message }}
    </div>
@endif
