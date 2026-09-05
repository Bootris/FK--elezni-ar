<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Http\Resources\Concerns\ResolvesMedia;
use App\Mail\YouthApplicationConfirmation;
use App\Mail\YouthApplicationSubmitted;
use App\Models\FootballMatch;
use App\Models\Player;
use App\Models\Setting;
use App\Models\StaffMember;
use App\Models\StandingRow;
use App\Models\YouthApplication;
use App\Models\YouthSelection;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Mail;

/** Read-only club data + the enrolment endpoint for headless frontends. */
class ClubController extends Controller
{
    use ResolvesMedia;

    /** GET /api/v1/squad — first-team players and staff. */
    public function squad(): JsonResponse
    {
        return response()->json([
            'players' => Player::visible()->get()->map(fn (Player $p) => [
                'slug' => $p->slug,
                'name' => $p->name,
                'number' => $p->shirt_number,
                'position' => $p->position,
                'nationality' => $p->nationality,
                'birth_date' => $p->birth_date?->toDateString(),
                'height_cm' => $p->height_cm,
                'is_captain' => $p->is_captain,
                'from_academy' => $p->from_academy,
                'photo_url' => $this->mediaUrl($p->photo),
            ]),
            'staff' => $this->staff('first_team'),
        ]);
    }

    /** GET /api/v1/matches?team=first|youth&status=upcoming|finished */
    public function matches(Request $request): JsonResponse
    {
        $matches = FootballMatch::query()
            ->where('team_type', $request->query('team', 'first'))
            ->when($request->query('status') === 'upcoming', fn ($q) => $q->upcoming())
            ->when($request->query('status') === 'finished', fn ($q) => $q->finished())
            ->when(! $request->query('status'), fn ($q) => $q->orderByDesc('kickoff_at'))
            ->with('selection')
            ->take(50)
            ->get();

        return response()->json($matches->map(fn (FootballMatch $m) => [
            'id' => $m->id,
            'competition' => $m->competition,
            'round' => $m->round,
            'kickoff_at' => $m->kickoff_at->toIso8601String(),
            'home_team' => $m->home_team,
            'away_team' => $m->away_team,
            'home_score' => $m->home_score,
            'away_score' => $m->away_score,
            'is_home' => $m->is_home,
            'venue' => $m->venue,
            'status' => $m->status,
            'outcome' => $m->outcome,
            'selection' => $m->selection?->name,
            'report_slug' => $m->report?->slug,
        ]));
    }

    /** GET /api/v1/standings?competition=first */
    public function standings(Request $request): JsonResponse
    {
        return response()->json(
            StandingRow::table($request->query('competition', 'first'))->get()->map(fn (StandingRow $r) => [
                'position' => $r->position,
                'team' => $r->team,
                'played' => $r->played,
                'won' => $r->won,
                'drawn' => $r->drawn,
                'lost' => $r->lost,
                'goals_for' => $r->goals_for,
                'goals_against' => $r->goals_against,
                'goal_difference' => $r->goal_difference,
                'points' => $r->points,
                'form' => $r->form,
                'is_club' => $r->is_club,
            ]),
        );
    }

    /** GET /api/v1/youth — selections and coaches. */
    public function youth(): JsonResponse
    {
        return response()->json([
            'selections' => YouthSelection::visible()->get()->map(fn (YouthSelection $s) => [
                'id' => $s->id,
                'slug' => $s->slug,
                'name' => $s->name,
                'birth_years' => $s->birth_years,
                'description' => $s->description,
                'training_schedule' => $s->training_schedule,
                'training_venue' => $s->training_venue,
                'accepting_applications' => $s->accepting_applications,
                'photo_url' => $this->mediaUrl($s->photo),
            ]),
            'coaches' => $this->staff('youth'),
        ]);
    }

    /** POST /api/v1/enrol — youth enrolment (honeypot + throttle in routes). */
    public function enrol(Request $request): JsonResponse
    {
        if ($request->filled('website')) {
            return response()->json(['ok' => true]);
        }

        $validated = $request->validate([
            'child_name' => 'required|string|max:255',
            'birth_year' => 'required|integer|min:' . (now()->year - 21) . '|max:' . now()->year,
            'parent_name' => 'required|string|max:255',
            'phone' => 'required|string|max:50',
            'email' => 'required|email|max:255',
            'youth_selection_id' => 'nullable|exists:youth_selections,id',
            'note' => 'nullable|string|max:2000',
        ]);

        $application = YouthApplication::create($validated);

        $notifyTo = Setting::get('application_notify_email')
            ?: Setting::get('youth_email')
            ?: Setting::get('email');

        try {
            if ($notifyTo) {
                Mail::to($notifyTo)->send(new YouthApplicationSubmitted($application));
            }
            Mail::to($application->email)->send(new YouthApplicationConfirmation($application));
        } catch (\Throwable $e) {
            report($e);
        }

        return response()->json(['ok' => true, 'id' => $application->id], 201);
    }

    private function staff(string $department)
    {
        return StaffMember::visible()->department($department)->with('selection')->get()->map(fn (StaffMember $s) => [
            'slug' => $s->slug,
            'name' => $s->name,
            'role' => $s->role,
            'licence' => $s->licence,
            'selection' => $s->selection?->name,
            'photo_url' => $this->mediaUrl($s->photo),
        ]);
    }
}
