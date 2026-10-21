<?php

namespace App\Http\Controllers;

use App\Models\projects;
use App\Support\Upload;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

// Prototypes, optionally linked to a project; listed under Sidebar → Products. There is no
// edit page (it was never built).
class PrototypeController extends Controller
{
    // GET /registerprototype: the add form, with the projects to link to.
    public function create()
    {
        return view('registerprototype', ['projects' => projects::all()]);
    }

    // POST /insertprototype
    public function store(Request $request)
    {
        $request->validate([
            'title' => 'required|string|max:255',
            'description' => 'required|string',
            'project_id' => 'nullable|exists:projects,id',
            'image' => 'nullable|'.Upload::IMAGE,
        ]);

        $photo = $request->hasFile('image') ? Upload::store($request->file('image'), 'images/prototypes') : null;

        $id = DB::table('prototypes')->insertGetId([
            'name' => $request->title,
            'description' => $request->description,
            'project_id' => $request->input('project_id') ?: null,
            'created_at' => now(),
            'updated_at' => now(),
        ]);

        // always one photo row, even without a photo, so the prototype shows in the lists.
        DB::table('prototype_photos')->insert([
            'photo' => $photo,
            'prototype_id' => $id,
            'created_at' => now(),
            'updated_at' => now(),
        ]);

        return redirect('/publicationscontent')->with('success', 'prototype added successfully!');
    }
}
