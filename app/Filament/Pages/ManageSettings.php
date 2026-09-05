<?php

namespace App\Filament\Pages;

use App\Models\Setting;
use BackedEnum;
use Filament\Forms\Components\FileUpload;
use Filament\Forms\Components\Textarea;
use Filament\Forms\Components\TextInput;
use Filament\Notifications\Notification;
use Filament\Pages\Page;
use Filament\Schemas\Components\Section;
use Filament\Schemas\Components\Tabs;
use Filament\Schemas\Components\Tabs\Tab;
use Filament\Schemas\Schema;
use Filament\Support\Icons\Heroicon;

/**
 * Everything on the site that is "text the club changes once a season" lives
 * here: identity, homepage hero, youth-enrolment copy, bank details for
 * supporters, contact info, social links and default SEO.
 */
class ManageSettings extends Page
{
    protected string $view = 'filament.pages.manage-settings';

    protected static string|BackedEnum|null $navigationIcon = Heroicon::OutlinedCog6Tooth;

    protected static string|\UnitEnum|null $navigationGroup = 'Sistem';

    protected static ?int $navigationSort = 21;

    protected static ?string $navigationLabel = 'Podešavanja sajta';

    protected static ?string $title = 'Podešavanja sajta';

    /** @var array<string, mixed>|null */
    public ?array $data = [];

    public static function canAccess(): bool
    {
        return auth()->user()?->isAdmin() ?? false;
    }

    public function mount(): void
    {
        $this->form->fill(Setting::allCached());
    }

    public function form(Schema $schema): Schema
    {
        return $schema
            ->components([
                Tabs::make('Podešavanja')
                    ->columnSpanFull()
                    ->persistTabInQueryString()
                    ->tabs([
                        Tab::make('Klub')
                            ->icon(Heroicon::OutlinedShieldCheck)
                            ->schema([
                                Section::make('Identitet')
                                    ->columns(2)
                                    ->components([
                                        TextInput::make('site_name')->label('Pun naziv kluba')->required(),
                                        TextInput::make('club_short_name')->label('Kratak naziv')->required()
                                            ->helperText('Koristi se u rezultatima, npr. „Železničar 2:1 Sinđelić“.'),
                                        TextInput::make('tagline')->label('Slogan / podnaslov'),
                                        TextInput::make('founded_year')->label('Godina osnivanja')->numeric(),
                                        TextInput::make('city')->label('Grad'),
                                        TextInput::make('stadium')->label('Stadion'),
                                        TextInput::make('season')->label('Tekuća sezona')->helperText('Npr. 2025/26.'),
                                        TextInput::make('league_name')->label('Liga / takmičenje prvog tima'),
                                        FileUpload::make('logo')
                                            ->label('Grb (zamena za podrazumevani)')
                                            ->image()
                                            ->disk('public')
                                            ->directory('branding')
                                            ->columnSpanFull(),
                                    ]),
                            ]),

                        Tab::make('Početna strana')
                            ->icon(Heroicon::OutlinedHome)
                            ->schema([
                                Section::make('Hero sekcija')
                                    ->columns(2)
                                    ->description('Velika uvodna sekcija na početnoj strani. Ako je označena „glavna vest“, ona se prikazuje pored ovog teksta.')
                                    ->components([
                                        TextInput::make('hero_kicker')->label('Mali natpis iznad naslova'),
                                        TextInput::make('hero_title')->label('Glavni naslov'),
                                        Textarea::make('hero_subtitle')->label('Podnaslov')->rows(3)->columnSpanFull(),
                                        FileUpload::make('hero_image')
                                            ->label('Pozadinska fotografija')
                                            ->image()
                                            ->disk('public')
                                            ->directory('branding')
                                            ->helperText('Široka fotografija (npr. stadion, navijači). Tamni se automatski.')
                                            ->columnSpanFull(),
                                    ]),
                            ]),

                        Tab::make('Omladinci i upis')
                            ->icon(Heroicon::OutlinedSparkles)
                            ->schema([
                                Section::make('Omladinska škola')
                                    ->columns(2)
                                    ->components([
                                        Textarea::make('youth_intro')->label('Uvodni tekst')->rows(3)->columnSpanFull(),
                                        TextInput::make('youth_age_range')->label('Uzrast')->helperText('Npr. od 5 do 19 godina.'),
                                        Textarea::make('youth_training_info')->label('Informacije o treninzima')->rows(3)->columnSpanFull(),
                                        TextInput::make('youth_phone')->label('Telefon za upis')->tel(),
                                        TextInput::make('youth_email')->label('Imejl za upis')->email(),
                                        TextInput::make('application_notify_email')
                                            ->label('Obaveštenja o novim prijavama šalji na')
                                            ->email()
                                            ->helperText('Ako je prazno, koristi se imejl za upis, pa imejl kluba.')
                                            ->columnSpanFull(),
                                    ]),
                            ]),

                        Tab::make('Podrži klub')
                            ->icon(Heroicon::OutlinedHeart)
                            ->schema([
                                Section::make('Podaci za uplatu')
                                    ->columns(2)
                                    ->description('Prikazuju se na stranici „Podrži klub“. Bez onlajn plaćanja — samo podaci za uplatnicu / e-banking.')
                                    ->components([
                                        Textarea::make('support_intro')->label('Poziv na podršku')->rows(3)->columnSpanFull(),
                                        TextInput::make('account_holder')->label('Primalac'),
                                        TextInput::make('bank_name')->label('Banka'),
                                        TextInput::make('bank_account')->label('Broj računa')->required(),
                                        TextInput::make('payment_purpose')->label('Svrha uplate'),
                                        TextInput::make('payment_code')->label('Šifra plaćanja')->helperText('Npr. 289.'),
                                        TextInput::make('payment_model')->label('Model'),
                                        TextInput::make('payment_reference')->label('Poziv na broj'),
                                        TextInput::make('iban')->label('IBAN (za uplate iz inostranstva)'),
                                        TextInput::make('swift')->label('SWIFT / BIC'),
                                        Textarea::make('support_note')->label('Napomena')->rows(2)->columnSpanFull(),
                                    ]),
                            ]),

                        Tab::make('Kontakt i mreže')
                            ->icon(Heroicon::OutlinedPhone)
                            ->schema([
                                Section::make('Kontakt')
                                    ->columns(2)
                                    ->components([
                                        TextInput::make('email')->label('Imejl kluba')->email(),
                                        TextInput::make('phone')->label('Telefon')->tel(),
                                        Textarea::make('address')->label('Adresa')->rows(2),
                                        TextInput::make('working_hours')->label('Radno vreme')->helperText('Npr. Pon–Pet 10–18h.'),
                                        TextInput::make('contact_notify_email')
                                            ->label('Poruke sa kontakt forme šalji na')
                                            ->email()
                                            ->helperText('Ako je prazno, koristi se imejl kluba.'),
                                        TextInput::make('map_embed')
                                            ->label('Google Maps embed URL')
                                            ->url()
                                            ->helperText('Google Maps → Podeli → Ugradi mapu → kopiraj src adresu iz iframe-a.')
                                            ->columnSpanFull(),
                                    ]),
                                Section::make('Društvene mreže')
                                    ->columns(2)
                                    ->components([
                                        TextInput::make('youtube')->label('YouTube')->url(),
                                        TextInput::make('instagram')->label('Instagram')->url(),
                                        TextInput::make('facebook')->label('Facebook')->url(),
                                        TextInput::make('tiktok')->label('TikTok')->url(),
                                    ]),
                            ]),

                        Tab::make('SEO')
                            ->icon(Heroicon::OutlinedMagnifyingGlass)
                            ->schema([
                                Section::make('Podrazumevani meta podaci')
                                    ->components([
                                        TextInput::make('seo_title')->label('Naslov sajta u pretraživačima')
                                            ->helperText('Ako je prazno: „Naziv kluba — slogan“.'),
                                        Textarea::make('seo_description')->label('Opis sajta')->rows(3)->maxLength(300),
                                    ]),
                            ]),
                    ]),
            ])
            ->statePath('data');
    }

    public function save(): void
    {
        foreach ($this->form->getState() as $key => $value) {
            Setting::set($key, $value);
        }

        Notification::make()
            ->title('Podešavanja sačuvana')
            ->success()
            ->send();
    }
}
