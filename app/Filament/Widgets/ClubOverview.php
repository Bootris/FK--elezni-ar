<?php

namespace App\Filament\Widgets;

use App\Models\ContactMessage;
use App\Models\FootballMatch;
use App\Models\Player;
use App\Models\Post;
use App\Models\YouthApplication;
use App\Models\YouthSelection;
use Filament\Widgets\StatsOverviewWidget;
use Filament\Widgets\StatsOverviewWidget\Stat;

/** Dashboard at a glance: what needs attention and what is coming up. */
class ClubOverview extends StatsOverviewWidget
{
    protected static ?int $sort = -1;

    protected ?string $heading = 'Pregled';

    protected function getStats(): array
    {
        $newApplications = YouthApplication::where('status', 'new')->count();
        $unreadMessages = ContactMessage::whereNull('read_at')->count();
        $next = FootballMatch::firstTeam()->upcoming()->first();

        return [
            Stat::make('Nove prijave za upis', $newApplications)
                ->description($newApplications ? 'Čekaju na poziv roditeljima' : 'Sve prijave su obrađene')
                ->color($newApplications ? 'warning' : 'success')
                ->url(route('filament.admin.resources.youth-applications.index')),
            Stat::make('Nepročitane poruke', $unreadMessages)
                ->description('Kontakt forma')
                ->color($unreadMessages ? 'warning' : 'gray')
                ->url(route('filament.admin.resources.contact-messages.index')),
            Stat::make('Sledeća utakmica', $next ? ($next->is_home ? 'vs ' : '@ ') . $next->opponent : '—')
                ->description($next ? $next->kickoff_at->format('d.m.Y · H:i') . ' · ' . $next->competition : 'Nema zakazanih utakmica')
                ->color('primary')
                ->url(route('filament.admin.resources.matches.index')),
            Stat::make('Objavljene vesti', Post::published()->count())
                ->description(Post::where('status', 'draft')->count() . ' u nacrtu')
                ->url(route('filament.admin.resources.posts.index')),
            Stat::make('Prvi tim', Player::where('visible', true)->count() . ' igrača')
                ->description(Player::where('visible', true)->where('from_academy', true)->count() . ' iz omladinske škole')
                ->url(route('filament.admin.resources.players.index')),
            Stat::make('Omladinske selekcije', YouthSelection::where('visible', true)->count())
                ->description(YouthSelection::where('accepting_applications', true)->count() . ' primaju nove igrače')
                ->url(route('filament.admin.resources.youth-selections.index')),
        ];
    }
}
