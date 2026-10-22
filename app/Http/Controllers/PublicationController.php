<?php

namespace App\Http\Controllers;

use App\Models\prototypes;
use App\Models\publications;
use App\Support\Upload;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

// Sidebar → Products: publications (a cover image and a document each). The same list page
// also shows the prototypes (PrototypeController).
class PublicationController extends Controller
{
    // /publicationscontent
    public function index()
    {
        return view('publicationscontent', [
            'publications' => publications::all(),
            'prototypes' => prototypes::has('photos')->with('photos')->get(),
        ]);
    }

    // POST /insert_publication
    public function store(Request $request)
    {
        $request->validate([
            'title' => 'required|string|max:255',
            'author' => 'required|string|max:255',
            'type' => 'required|string|max:255',
            'description' => 'required|string',
            'date_published' => 'nullable|date',
            'photo' => 'required|'.Upload::IMAGE,
            'file' => 'required|'.Upload::DOCUMENT,
        ]);

        DB::table('publications')->insert([
            'title' => $request->title,
            'author' => $request->author,
            'photo' => Upload::store($request->file('photo'), 'images/publications'),
            'date_published' => $request->date_published,
            'type' => $request->type,
            'description' => $request->description,
            'file_link' => Upload::store($request->file('file'), 'documents/publications'),
            'admin_id' => auth()->id(),
            'created_at' => now(),
            'updated_at' => now(),
        ]);

        return redirect('/publicationscontent')->with('success', 'publication added successfully!');
    }

    // GET /editpublications?id=
    public function edit(Request $request)
    {
        $publication = $this->findForEdit('publications', $request);

        return view('editpublications', ['form' => [
            'id' => $publication->id,
            'title' => $publication->title,
            'type' => $publication->type,
            'author' => $publication->author,
            'date_published' => $this->dateInput($publication->date_published),
            'photo' => $publication->photo,
            'description' => $publication->description,
        ]]);
    }

    // POST /editpublication_route
    public function update(Request $request)
    {
        // validate everything before any update so a bad upload can't leave a half-saved record.
        $request->validate([
            'id' => 'required|exists:publications,id',
            'title' => 'required|string|max:255',
            'type' => 'required|string|max:255',
            'author' => 'required|string|max:255',
            'description' => 'required|string',
            'date_published' => 'nullable|date',
            'file' => 'nullable|'.Upload::DOCUMENT,
            'photo' => 'nullable|'.Upload::IMAGE,
        ]);

        $values = [
            'title' => $request->title,
            'type' => $request->type,
            'author' => $request->author,
            'date_published' => $request->date_published,
            'description' => $request->description,
        ];
        // a new document or cover replaces the old one; leaving them empty keeps the current files.
        if ($request->hasFile('file')) {
            $values['file_link'] = Upload::store($request->file('file'), 'documents/publications');
        }
        if ($request->hasFile('photo')) {
            $values['photo'] = Upload::store($request->file('photo'), 'images/publications');
        }
        DB::table('publications')->where('id', $request->input('id'))->update($values);

        return redirect('/publicationscontent')->with('success', 'publication updated successfully!');
    }

    // POST /deletepublication: the database also removes its links to team members (foreign key
    // cascade). Its document and cover stay on disk, as they do when they are replaced.
    public function destroy(Request $request)
    {
        $request->validate(['id' => 'required|exists:publications,id']);

        DB::table('publications')->where('id', $request->input('id'))->delete();

        return redirect('/publicationscontent')->with('success', 'publication deleted successfully!');
    }
}
