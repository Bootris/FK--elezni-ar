<?php

namespace App\Filament\Pages;

use App\Models\Setting;
use App\Rules\EmbeddableVideoUrl;
use App\Support\VideoEmbed;
use BackedEnum;
use Filament\Actions\Action;
use Filament\Forms\Components\TextInput;
use Filament\Forms\Components\Toggle;
use Filament\Notifications\Notification;
use Filament\Pages\Page;
use Filament\Schemas\Components\Section;
use Filament\Schemas\Schema;
use Filament\Support\Icons\Heroicon;

/**
 * Turning the broadcast on and off is match-day work, not site administration —
 * whoever is at the ground with the phone needs it, so this lives in Sadržaj and
 * the editor role can reach it. The values are ordinary Settings rows.
 */
class ManageLiveStream extends Page
{
    /** Setting keys this page owns. */
    private const KEYS = ['live_enabled', 'live_url', 'live_title', 'live_note'];

    protected string $view = 'filament.pages.manage-live-stream';

    protected static string|BackedEnum|null $navigationIcon = Heroicon::OutlinedVideoCamera;

    protected static string|\UnitEnum|null $navigationGroup = 'Sadržaj';

    protected static ?int $navigationSort = 4;

    protected static ?string $navigationLabel = 'Prenos uživo';

    protected static ?string $title = 'Prenos uživo';

    /** @var array<string, mixed>|null */
    public ?array $data = [];

    public static function canAccess(): bool
    {
        // Panel access is already limited to admin and editor.
        return auth()->check();
    }

    /** A red dot in the sidebar while a broadcast is on air. */
    public static function getNavigationBadge(): ?string
    {
        return static::isLive() ? 'UŽIVO' : null;
    }

    public static function getNavigationBadgeColor(): ?string
    {
        return 'danger';
    }

    public function mount(): void
    {
        $this->form->fill(Setting::allCached());
    }

    public function form(Schema $schema): Schema
    {
        return $schema
            ->components([
                Section::make('Traka „UŽIVO SADA“')
                    ->columns(2)
                    ->description('Dok je uključena, na početnoj strani i na strani Prvi tim iskače crvena traka sa prenosom. Klikom na nju prenos se otvara u plejeru, bez napuštanja sajta. Isključi je kad se prenos završi.')
                    ->components([
                        Toggle::make('live_enabled')
                            ->label('Uključi traku „UŽIVO SADA“')
                            ->live()
                            ->columnSpanFull(),
                        TextInput::make('live_url')
                            ->label('Link prenosa')
                            ->rule(new EmbeddableVideoUrl())
                            ->columnSpanFull()
                            ->helperText('Jedan prenos: nalepi link sa YouTube-a (youtube.com/live/… ili watch?v=…). Uvek ono što je trenutno uživo na kanalu: nalepi ID kanala koji počinje sa UC…, ili link youtube.com/channel/UC…/live'),
                        TextInput::make('live_title')
                            ->label('Naslov prenosa')
                            ->helperText('Npr. „Železničar – Sinđelić, 12. kolo“. Ako je prazno, piše samo „Prenos uživo“.'),
                        TextInput::make('live_note')
                            ->label('Dodatni tekst')
                            ->helperText('Npr. „Stadion Čair, 16.00“. Opciono.'),
                    ]),
            ])
            ->statePath('data');
    }

    protected function getHeaderActions(): array
    {
        return [
            Action::make('stop')
                ->label('Zaustavi prenos')
                ->icon(Heroicon::OutlinedStop)
                ->color('danger')
                ->visible(fn (): bool => static::isLive())
                ->requiresConfirmation()
                ->modalHeading('Zaustavi prenos')
                ->modalDescription('Traka „UŽIVO SADA“ odmah nestaje sa sajta. Link ostaje sačuvan.')
                ->modalSubmitActionLabel('Zaustavi')
                ->action(function (): void {
                    Setting::set('live_enabled', false);
                    $this->data['live_enabled'] = false;

                    Notification::make()->title('Prenos je zaustavljen')->success()->send();
                }),
        ];
    }

    public function save(): void
    {
        $state = $this->form->getState();

        foreach (self::KEYS as $key) {
            Setting::set($key, $state[$key] ?? null);
        }

        Notification::make()
            ->title(($state['live_enabled'] ?? false) ? 'Prenos je uključen' : 'Sačuvano')
            ->success()
            ->send();
    }

    /** The live preview shown next to the form, so nobody publishes a dead link. */
    public function getPreviewSrcProperty(): ?string
    {
        return VideoEmbed::url($this->data['live_url'] ?? null);
    }

    private static function isLive(): bool
    {
        return filter_var(Setting::get('live_enabled'), FILTER_VALIDATE_BOOLEAN)
            && VideoEmbed::url(Setting::get('live_url')) !== null;
    }
}
