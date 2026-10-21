<?php

namespace App\Http\Controllers;

use App\Models\members;
use App\Support\Upload;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

// Sidebar → Current Team and Past Team. Members are never deleted: "Delete" marks them
// Inactive, which moves them to Past Team, where they can be restored.
class MemberController extends Controller
{
    // /aboutuscontent
    public function index()
    {
        return view('aboutuscontent', ['users' => members::where('status', 'Active')->get()]);
    }

    // /oldteam
    public function past()
    {
        return view('oldteam', ['users' => members::where('status', 'Inactive')->get()]);
    }

    // POST /insert_team
    public function store(Request $request)
    {
        $request->validate([
            'firstname' => 'required|string|max:255',
            'lastname' => 'required|string|max:255',
            'email' => 'required|email|max:255|unique:members,email',
            'phone' => 'required|string|max:255|unique:members,phone',
            'designation' => 'required|string|max:255',
            'role' => 'nullable|string|max:255',
            'bio' => 'nullable|string',
            'image' => 'required|'.Upload::IMAGE,
        ]);

        DB::table('members')->insert([
            'fname' => $request->firstname,
            'lname' => $request->lastname,
            'email' => $request->email,
            'phone' => $request->phone,
            'designation' => $request->designation,
            'role' => $request->role,
            'bio' => $request->bio,
            'photo' => Upload::store($request->file('image'), 'images/team'),
            // members.registered_by references members.id, not users.id, so the logged-in
            // admin can't be stored here.
            'registered_by' => null,
            'created_at' => now(),
            'updated_at' => now(),
        ]);

        return redirect('/aboutuscontent')->with('success', $request->firstname.' has been registered successfully!');
    }

    // GET /editteam?id=
    public function edit(Request $request)
    {
        $member = $this->findForEdit('members', $request);

        return view('editteam', ['form' => [
            'id' => $member->id,
            'firstname' => $member->fname,
            'lastname' => $member->lname,
            'email' => $member->email,
            'phone' => $member->phone,
            'role' => $member->role,
            'designation' => $member->designation,
            'bio' => $member->bio,
        ]]);
    }

    // POST /editmember_route
    public function update(Request $request)
    {
        // validate everything before any update so a bad upload can't leave a half-saved record.
        $request->validate([
            'id' => 'required|exists:members,id',
            'firstname' => 'required|string|max:255',
            'lastname' => 'required|string|max:255',
            'email' => 'required|email|max:255|unique:members,email,'.$request->input('id'),
            'designation' => 'required|string|max:255',
            'role' => 'nullable|string|max:255',
            'bio' => 'nullable|string',
            'image' => 'nullable|'.Upload::IMAGE,
        ]);

        $values = [
            'fname' => $request->firstname,
            'lname' => $request->lastname,
            'email' => $request->email,
            'designation' => $request->designation,
            'role' => $request->role,
            'bio' => $request->bio,
        ];
        if ($request->hasFile('image')) {
            $values['photo'] = Upload::store($request->file('image'), 'images/team');
        }
        DB::table('members')->where('id', $request->input('id'))->update($values);

        return redirect('/aboutuscontent')->with('success', 'member details updated successfully!');
    }

    // POST /deleteuser?user_id=: moves the member to Past Team.
    public function deactivate(Request $request)
    {
        $this->setStatus($request, 'Inactive');

        return redirect('/aboutuscontent')->with('success', 'user was deleted successfully');
    }

    // POST /restoreuser?user_id=: brings the member back to Current Team.
    public function restore(Request $request)
    {
        $this->setStatus($request, 'Active');

        return redirect('/oldteam')->with('success', 'the member was restored successfully');
    }

    private function setStatus(Request $request, string $status): void
    {
        DB::table('members')->where('id', $request->query('user_id'))->update(['status' => $status]);
    }
}
