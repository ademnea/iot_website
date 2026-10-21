<?php

namespace App\Http\Controllers;

use App\Models\projects;
use App\Support\Upload;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

// Sidebar → Projects. Each project has one row in project_photos (its photo may be empty).
class ProjectController extends Controller
{
    // /projectscontent
    public function index()
    {
        // projects without a photo row are not listed (defect D9, awaiting a decision).
        $projects = projects::has('photos')->with('photos')->get();

        return view('projectscontent', [
            'onprojects' => $projects->where('status', 'Ongoing')->values(),
            'pastprojects' => $projects->where('status', 'Past')->values(),
        ]);
    }

    // POST /submit-project
    public function store(Request $request)
    {
        $request->validate([
            'projectName' => 'required|string|max:255',
            'description' => 'required|string',
            'venue' => 'nullable|string|max:255',
            'participants' => 'nullable|string|max:255',
            'start_date' => 'nullable|date',
            'end_date' => 'nullable|date|after_or_equal:start_date',
            'project_link' => 'nullable|string|max:255',
            'image' => 'nullable|'.Upload::IMAGE,
        ]);

        $photo = $request->hasFile('image') ? Upload::store($request->file('image'), 'images/projects') : null;

        $id = DB::table('projects')->insertGetId([
            'name' => $request->projectName,
            'venue' => $request->venue,
            'participants' => $request->participants,
            'description' => $request->description,
            'start_date' => $request->start_date,
            'end_date' => $request->end_date,
            'website' => $request->project_link,
            'admin_id' => auth()->id(),
            'created_at' => now(),
            'updated_at' => now(),
        ]);

        // always one photo row, even without a photo, so the project shows in the lists.
        DB::table('project_photos')->insert([
            'photo' => $photo,
            'project_id' => $id,
            'created_at' => now(),
            'updated_at' => now(),
        ]);

        return redirect('/projectscontent')->with('success', 'project added successfully!');
    }

    // GET /editproject?id=
    public function edit(Request $request)
    {
        $project = $this->findForEdit('projects', $request);

        return view('editproject', ['form' => [
            'id' => $project->id,
            'name' => $project->name,
            'link' => $project->website,
            'venue' => $project->venue,
            'duration' => $project->duration,
            'description' => $project->description,
            'participants' => $project->participants,
        ]]);
    }

    // POST /editproject_route
    public function update(Request $request)
    {
        // validate everything before any update so a bad upload can't leave a half-saved record.
        $request->validate([
            'id' => 'required|exists:projects,id',
            'projectName' => 'required|string|max:255',
            'description' => 'required|string',
            'venue' => 'nullable|string|max:255',
            'participants' => 'nullable|string|max:255',
            'link' => 'nullable|string|max:255',
            'image' => 'nullable|'.Upload::IMAGE,
        ]);

        $id = $request->input('id');
        DB::table('projects')->where('id', $id)->update([
            'name' => $request->projectName,
            'venue' => $request->venue,
            'participants' => $request->participants,
            'website' => $request->link,
            'description' => $request->description,
        ]);

        // a new photo replaces the project's photo.
        if ($request->hasFile('image')) {
            DB::table('project_photos')->where('project_id', $id)->update([
                'photo' => Upload::store($request->file('image'), 'images/projects'),
            ]);
        }

        return redirect('/projectscontent')->with('success', 'project info updated successfully!');
    }
}
