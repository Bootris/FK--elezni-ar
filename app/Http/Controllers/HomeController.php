<?php

namespace App\Http\Controllers;

use App\Models\FootballMatch;
use App\Models\Player;
use App\Models\Post;
use App\Models\StaffMember;
use App\Models\StandingRow;
use App\Models\YouthSelection;

class HomeController extends Controller
{
    public function index()
    {
        // Lead story: an editor-featured post, else the newest one.
        $featured = Post::published()->with('category')
            ->where('show_on_home', true)
            ->orderByDesc('published_at')
            ->first()
            ?? Post::published()->with('category')->orderByDesc('published_at')->first();

        $latestPosts = Post::published()->with('category')
            ->when($featured, fn ($q) => $q->whereKeyNot($featured->id))
            ->orderByDesc('published_at')
            ->take(6)
            ->get();

        $videoPosts = Post::published()->with('category')
            ->whereNotNull('video_url')
            ->where('video_url', '!=', '')
            ->orderByDesc('published_at')
            ->take(3)
            ->get();

        $nextMatch = FootballMatch::firstTeam()->upcoming()->first();
        $lastMatch = FootballMatch::firstTeam()->finished()->first();
        $standings = StandingRow::table()->get();

        // A different six on every visit, still listed in line order (GK → DF → MF → FW).
        $players = Player::where('visible', true)->inRandomOrder()->take(6)->get()
            ->sortBy(fn (Player $p) => [['GK' => 0, 'DF' => 1, 'MF' => 2][$p->position] ?? 3, $p->sort_order])
            ->values();
        $playerCount = Player::where('visible', true)->count();
        $academyCount = Player::where('visible', true)->where('from_academy', true)->count();

        $selections = YouthSelection::visible()->get();
        $coachCount = StaffMember::visible()->department('youth')->count();

        return view('home', compact(
            'featured', 'latestPosts', 'videoPosts', 'nextMatch', 'lastMatch', 'standings',
            'players', 'playerCount', 'academyCount', 'selections', 'coachCount',
        ));
    }
}
