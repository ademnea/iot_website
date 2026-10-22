<?php

namespace App\Http\Controllers;

use App\Support\Upload;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

// Partner organisations. They're added from Home → Add New Home Content and listed on the
// Home admin page; the public site shows them on Home, Team & contact and Partners.
class PartnerController extends Controller
{
    // POST /insertpartner
    public function store(Request $request)
    {
        $request->validate([
            'name' => 'required|string|max:255',
            'description' => 'required|string',
            'website' => 'nullable|string|max:255',
            'image' => 'required|'.Upload::IMAGE,
        ]);

        DB::table('partners')->insert([
            'logo' => Upload::store($request->file('image'), 'images/partners'),
            'name' => $request->name,
            'description' => $request->description,
            'website_link' => $request->website,
            'admin_id' => auth()->id(),
            'created_at' => now(),
            'updated_at' => now(),
        ]);

        return redirect('/homecontent')->with('success', 'partner added successfully!');
    }

    // GET /editpartner?id=
    public function edit(Request $request)
    {
        $partner = $this->findForEdit('partners', $request);

        return view('editpartner', ['form' => ['id' => $partner->id, 'name' => $partner->name]]);
    }

    // POST /editpartner_route
    public function update(Request $request)
    {
        // validate everything before any update so a bad upload can't leave a half-saved record.
        $request->validate([
            'id' => 'required|exists:partners,id',
            'fullname' => 'required|string|max:255',
            'image' => 'nullable|'.Upload::IMAGE,
        ]);

        $values = ['name' => $request->fullname];
        if ($request->hasFile('image')) {
            $values['logo'] = Upload::store($request->file('image'), 'images/partners');
        }
        DB::table('partners')->where('id', $request->input('id'))->update($values);

        return redirect('/homecontent')->with('success', 'partner info updated successfully!');
    }

    // POST /deletepartner
    public function destroy(Request $request)
    {
        return $this->deleteRecord($request, 'partners', '/homecontent', 'partner deleted successfully!');
    }
}
