<?php

namespace App\Http\Controllers;

use App\Models\members;
use App\Models\news;
use App\Models\partners;
use App\Models\projects;
use App\Models\prototypes;
use App\Models\publications;
use Illuminate\Support\Facades\DB;

// The public pages. $contents (the lab's name, logo, contacts) is added to every one of these
// views by the composer in AppServiceProvider, so only page-specific data is loaded here.
class PublicPageController extends Controller
{
    public function fetch_home()
    {
        // Visitors always get the home page, even before an admin has added any content.
        // Signed-in admins see "add it" hints for whatever is missing.
        return view('index', [
            'leader' => DB::table('members')->where('role', 'leader')->get(),
            'partners' => partners::all(),
        ]);
    }

    public function fetch_team()
    {
        // one query for every active member, split by role for the page's sections.
        $active = DB::table('members')->where('status', 'Active')->get();

        return view('about_us', [
            'teams' => $active->whereIn('role', ['researcher', 'leader'])->values(),
            'PhDs' => $active->where('role', 'phd_stdt')->values(),
            'masters' => $active->where('role', 'masters_stdt')->values(),
            'admins' => $active->where('role', 'admin')->values(),
            'interns' => $active->where('role', 'intern')->values(),
            'partners' => partners::all(),
        ]);
    }

    public function fetch_projects()
    {
        // projects without a photo row are not listed (defect D9, awaiting a decision).
        return view('projects', ['onprojects' => projects::has('photos')->with('photos')->get()]);
    }

    public function fetch_publications()
    {
        return view('publications', [
            'publications' => publications::all(),
            'prototypes' => prototypes::has('photos')->with('photos')->get(),
        ]);
    }

    public function fetch_events()
    {
        return view('news', [
            'onevents' => news::with('photos')->where('status', 'Ongoing')->get(),
            // past events without a photo are not listed (defect D9).
            'pastevents' => news::has('photos')->with('photos')->where('status', 'Past')->get(),
        ]);
    }

    public function fetch_partners()
    {
        return view('partners', ['partners' => partners::all()]);
    }

    public function fetch_gallery()
    {
        // the gallery index previews six random team photos.
        return view('gallery', ['randomPhotos' => members::inRandomOrder()->limit(6)->get()]);
    }

    public function fetch_team_gallery()
    {
        return view('team_gallery', ['teams' => members::all()]);
    }

    public function fetch_project_gallery()
    {
        return view('project_gallery', [
            'projects' => projects::with('photos')->get(),
        ]);
    }

    public function fetch_prototype_gallery()
    {
        return view('prototypes_gallery', [
            'prototypes' => prototypes::with('photos')->get(),
        ]);
    }

    public function fetch_events_gallery()
    {
        return view('events_gallery', [
            'events' => news::with('photos')->get(),
        ]);
    }

}
