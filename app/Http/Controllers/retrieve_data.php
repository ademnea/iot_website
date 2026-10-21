<?php

namespace App\Http\Controllers;

use App\Models\members;
use App\Models\partners;
use App\Models\projects;
use App\Models\publications;
use Illuminate\Support\Facades\DB;

// The admin content lists. The home content row ($contents) is added to homecontent by the
// composer in AppServiceProvider.
class retrieve_data extends Controller
{
    public function fetch_home()
    {
        return view('homecontent', [
            'leader' => DB::table('members')->where('role', 'leader')->get(),
            'partners' => partners::all(),
        ]);
    }

    public function fetch_team()
    {
        return view('aboutuscontent', ['users' => members::where('status', 'Active')->get()]);
    }

    // members who left (marked Inactive); they can be restored from this list.
    public function fetch_oldteam()
    {
        return view('oldteam', ['users' => members::where('status', 'Inactive')->get()]);
    }

    public function fetch_projects()
    {
        // projects without a photo are not listed (inner join; defect D9, awaiting a decision).
        $projects = DB::table('projects')
            ->join('project_photos', 'projects.id', '=', 'project_photos.project_id')
            ->select('projects.*', 'project_photos.photo')
            ->get();

        return view('projectscontent', [
            'onprojects' => $projects->where('status', 'Ongoing')->values(),
            'pastprojects' => $projects->where('status', 'Past')->values(),
        ]);
    }

    public function fetch_publications()
    {
        return view('publicationscontent', [
            'publications' => publications::all(),
            'prototypes' => DB::table('prototypes')
                ->join('prototype_photos', 'prototypes.id', '=', 'prototype_photos.prototype_id')
                ->select('prototypes.*', 'prototype_photos.*')
                ->get(),
        ]);
    }

    public function fetch_events()
    {
        // events without a photo are not listed (inner join; defect D9). Only the photo column is
        // taken from event_photos, so each row keeps the event's own id (for Edit) and status.
        $events = DB::table('events')
            ->join('event_photos', 'events.id', '=', 'event_photos.event_id')
            ->select('events.*', 'event_photos.photo')
            ->get()
            ->unique('id');   // one card per event, with its first photo

        return view('newscontent', [
            'onevents' => $events->where('status', 'Ongoing')->values(),
            'pastevents' => $events->where('status', 'Past')->values(),
        ]);
    }

    // the Add Prototype form, which links a prototype to one of the projects.
    public function fetch_project_prototype()
    {
        return view('registerprototype', ['projects' => projects::all()]);
    }
}
