<?php

namespace App\Http\Controllers;

use App\Models\members;
use App\Models\partners;
use App\Models\publications;
use Illuminate\Support\Facades\DB;

// The public pages. $contents (the lab's name, logo, contacts) is added to every one of these
// views by the composer in AppServiceProvider, so only page-specific data is loaded here.
class show_data extends Controller
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
        // projects without a photo are not listed (inner join; defect D9, awaiting a decision).
        return view('projects', [
            'onprojects' => DB::table('projects')
                ->join('project_photos', 'projects.id', '=', 'project_photos.project_id')
                ->select('projects.*', 'project_photos.photo')
                ->get(),
        ]);
    }

    public function fetch_publications()
    {
        return view('publications', [
            'publications' => publications::all(),
            'prototypes' => DB::table('prototypes')
                ->join('prototype_photos', 'prototypes.id', '=', 'prototype_photos.prototype_id')
                ->select('prototypes.*', 'prototype_photos.*')
                ->get(),
        ]);
    }

    public function fetch_events()
    {
        return view('news', [
            // grouped by event: one entry per event with all of its photos.
            'onevents' => $this->withPhotos('events', 'event_photos', 'event_id')
                ->where('events.status', 'Ongoing')
                ->get()
                ->groupBy('id'),
            // past events without a photo are not listed (inner join; defect D9).
            'pastevents' => DB::table('events')
                ->join('event_photos', 'events.id', '=', 'event_photos.event_id')
                ->select('events.*', 'event_photos.*')
                ->where('events.status', 'Past')
                ->get(),
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
            'projects' => $this->withPhotos('projects', 'project_photos', 'project_id')->get()->groupBy('id'),
        ]);
    }

    public function fetch_prototype_gallery()
    {
        return view('prototypes_gallery', [
            'prototypes' => $this->withPhotos('prototypes', 'prototype_photos', 'prototype_id')->get()->groupBy('id'),
        ]);
    }

    public function fetch_events_gallery()
    {
        return view('events_gallery', [
            'events' => $this->withPhotos('events', 'event_photos', 'event_id')->get()->groupBy('id'),
        ]);
    }

    // Every record of $table with each of its photos (one row per photo; records without a
    // photo appear once with photo = null).
    private function withPhotos(string $table, string $photos, string $key)
    {
        return DB::table($table)
            ->leftJoin($photos, "$table.id", '=', "$photos.$key")
            ->select("$table.*", "$photos.photo");
    }
}
