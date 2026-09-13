<x-filament-panels::page>
    <form wire:submit="save" class="space-y-6">
        {{ $this->form }}

        @if ($this->previewSrc)
            <x-filament::section>
                <x-slot name="heading">Provera linka</x-slot>
                <x-slot name="description">Ovako prenos izgleda posetiocu. Ako je ovde prazno, link nije dobar.</x-slot>

                <div class="aspect-video w-full max-w-2xl overflow-hidden rounded-lg bg-black">
                    <iframe src="{{ $this->previewSrc }}" class="h-full w-full border-0"
                        allow="accelerometer; clipboard-write; encrypted-media; gyroscope; picture-in-picture"
                        allowfullscreen title="Provera prenosa"></iframe>
                </div>
            </x-filament::section>
        @endif

        <x-filament::button type="submit">
            Sačuvaj
        </x-filament::button>
    </form>
</x-filament-panels::page>
