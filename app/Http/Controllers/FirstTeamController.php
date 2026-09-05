<?php

namespace App\Http\Controllers;

use App\Models\Category;
use App\Models\FootballMatch;
use App\Models\Photo;
use App\Models\Player;
use App\Models\Post;
use App\Models\StaffMember;
use App\Models\StandingRow;

class FirstTeamController extends Controller
{
    public function index()
    {
        $players = Player::visible()->get()->groupBy('position');
        $staff = StaffMember::visible()->department('first_team')->get();

        $upcoming = FootballMatch::firstTeam()->upcoming()->take(6)->get();
        $results = FootballMatch::firstTeam()->finished()->with('report')->take(6)->get();
        $standings = StandingRow::table()->get();

        $category = Category::where('slug', 'prvi-tim')->first();
        $posts = Post::published()->with('category')
            ->when($category, fn ($q) => $q->whereIn('category_id', array_filter([
                $category->id,
                Category::where('slug', 'utakmice')->value('id'),
            ])))
            ->orderByDesc('published_at')
            ->take(3)
            ->get();

        $photos = Photo::visible()->album('first_team')->take(8)->get();

        return view('team.index', compact('players', 'staff', 'upcoming', 'results', 'standings', 'posts', 'photos'));
    }
}
