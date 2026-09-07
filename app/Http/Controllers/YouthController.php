<?php

namespace App\Http\Controllers;

use App\Mail\YouthApplicationConfirmation;
use App\Mail\YouthApplicationSubmitted;
use App\Models\Category;
use App\Models\FootballMatch;
use App\Models\Photo;
use App\Models\Post;
use App\Models\Setting;
use App\Models\StaffMember;
use App\Models\YouthApplication;
use App\Models\YouthSelection;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Mail;

class YouthController extends Controller
{
    public function index()
    {
        $selections = YouthSelection::visible()->with('coaches')->get();
        $coaches = StaffMember::visible()->department('youth')->with('selection')->get();
        $openSelections = $selections->where('accepting_applications', true);

        $category = Category::where('slug', 'omladinci')->first();
        $posts = Post::published()->with('category')
            ->when($category, fn ($q) => $q->where('category_id', $category->id))
            ->orderByDesc('published_at')
            ->take(3)
            ->get();

        $videoPosts = Post::published()
            ->when($category, fn ($q) => $q->where('category_id', $category->id))
            ->whereNotNull('video_url')->where('video_url', '!=', '')
            ->orderByDesc('published_at')
            ->take(2)
            ->get();

        $photos = Photo::visible()->album('youth')->take(8)->get();
        $upcoming = FootballMatch::where('team_type', 'youth')->upcoming()->with('selection')->take(5)->get();

        return view('youth.index', compact('selections', 'coaches', 'openSelections', 'posts', 'videoPosts', 'photos', 'upcoming'));
    }

    public function apply(Request $request)
    {
        // Honeypot: hidden field real visitors never fill.
        if ($request->filled('website')) {
            return back()->with('enrol_success', true);
        }

        $validated = $request->validate([
            'child_name' => 'required|string|max:255',
            'birth_year' => 'required|integer|min:' . (now()->year - 21) . '|max:' . now()->year,
            'parent_name' => 'required|string|max:255',
            'phone' => 'required|string|max:50',
            'email' => 'nullable|email|max:255',
            'youth_selection_id' => 'nullable|exists:youth_selections,id',
            'note' => 'nullable|string|max:2000',
            'consent' => 'accepted',
        ]);

        unset($validated['consent']);

        $application = YouthApplication::create($validated);

        $notifyTo = Setting::get('application_notify_email')
            ?: Setting::get('youth_email')
            ?: Setting::get('email');

        try {
            if ($notifyTo) {
                Mail::to($notifyTo)->send(new YouthApplicationSubmitted($application));
            }
            if ($application->email) {
                Mail::to($application->email)->send(new YouthApplicationConfirmation($application));
            }
        } catch (\Throwable $e) {
            // The application is stored in the admin either way.
            report($e);
        }

        return redirect()->to(url()->previous() . '#upis')->with('enrol_success', true);
    }
}
