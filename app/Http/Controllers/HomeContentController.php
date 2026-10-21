<?php

namespace App\Http\Controllers;

use App\Models\partners;
use App\Support\Upload;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

// Sidebar → Home: the single website_content row (lab details, logo, banner, contacts, social
// links, the leader's message). $contents is added to the list page by the view composer in
// AppServiceProvider.
class HomeContentController extends Controller
{
    // /homecontent
    public function index()
    {
        return view('homecontent', [
            'leader' => DB::table('members')->where('role', 'leader')->get(),
            'partners' => partners::all(),
        ]);
    }

    // ---- Add New Home Content (/register_home) -----------------------------------------------

    // POST /insertmission: lab name, motto and vision.
    public function storeInfo(Request $request)
    {
        $this->saveRow([
            'motto' => $request->input('motto'),
            'Labname' => $request->input('labname'),
            'vision' => $request->input('content'),
        ]);

        return redirect('/homecontent')->with('success', 'home content added successfully!');
    }

    // POST /insertcontacts: logo, contacts and social links.
    public function storeContacts(Request $request)
    {
        $request->validate(['logo' => 'required|'.Upload::IMAGE]);

        $this->saveRow([
            'logo' => Upload::store($request->file('logo'), 'images/logo'),
            'physical_address' => $request->address,
            'company_telephone' => $request->telephone,
            'company_email' => $request->email,
            'twitter' => $request->twitter,
            'facebook' => $request->facebook,
            'linkedin' => $request->linkedin,
            'youtube' => $request->youtube,
            'instagram' => $request->instagram,
        ]);

        return redirect('/homecontent')->with('success', 'home content added successfully!');
    }

    // The add forms fill in the one website_content row, creating it on a fresh install.
    private function saveRow(array $values): void
    {
        $existing = DB::table('website_content')->first();

        if ($existing) {
            DB::table('website_content')->where('id', $existing->id)->update($values + ['updated_at' => now()]);
        } else {
            DB::table('website_content')->insert($values + ['created_at' => now(), 'updated_at' => now()]);
        }
    }

    // ---- Edit pages (?id= is the website_content row) ----------------------------------------

    public function editInfo(Request $request)
    {
        $content = $this->findForEdit('website_content', $request);

        return view('edithome', ['form' => [
            'id' => $content->id,
            'labname' => $content->Labname,
            'motto' => $content->motto,
            'vision' => $content->vision,
            'mission' => $content->mission,
            'objectives' => $content->objectives,
        ]]);
    }

    public function updateInfo(Request $request)
    {
        DB::table('website_content')->where('id', $request->input('id'))->update([
            'Labname' => $request->input('labname'),
            'motto' => $request->input('motto'),
            'vision' => $request->input('vision'),
            'mission' => $request->input('mission'),
            'objectives' => $request->input('objectives'),
        ]);

        return redirect('/homecontent')->with('success', 'changes have been made successfully!');
    }

    public function editLogo(Request $request)
    {
        return view('editlogo', ['form' => ['id' => $this->findForEdit('website_content', $request)->id]]);
    }

    public function updateLogo(Request $request)
    {
        $this->replaceImage($request, 'logo', 'images/logo');

        return redirect('/homecontent')->with('success', 'logo changed successfully!');
    }

    public function editBanner(Request $request)
    {
        return view('editbanner', ['form' => ['id' => $this->findForEdit('website_content', $request)->id]]);
    }

    public function updateBanner(Request $request)
    {
        $this->replaceImage($request, 'banner', 'images/banner');

        return redirect('/homecontent')->with('success', 'banner changed successfully!');
    }

    private function replaceImage(Request $request, string $column, string $dir): void
    {
        $request->validate(['image' => 'required|'.Upload::IMAGE]);

        DB::table('website_content')->where('id', $request->input('id'))->update([
            $column => Upload::store($request->file('image'), $dir),
        ]);
    }

    // Social links (the top bar), each with a "Show on the website" switch.
    public function editSocials(Request $request)
    {
        $content = $this->findForEdit('website_content', $request);
        $form = ['id' => $content->id];
        foreach (['facebook', 'twitter', 'linkedin', 'instagram', 'youtube'] as $network) {
            $form[$network] = $content->$network;
            $form[$network.'_visible'] = (bool) $content->{$network.'_visible'};
        }

        return view('edittopbar', ['form' => $form]);
    }

    public function updateSocials(Request $request)
    {
        DB::table('website_content')->where('id', $request->input('id'))->update([
            'twitter' => $request->twitter,
            'linkedin' => $request->linkedIn,
            'youtube' => $request->youtube,
            'instagram' => $request->instagram,
            'facebook' => $request->facebook,
            // an unticked box isn't sent, so it saves as hidden.
            'twitter_visible' => $request->boolean('twitter_visible'),
            'linkedin_visible' => $request->boolean('linkedin_visible'),
            'youtube_visible' => $request->boolean('youtube_visible'),
            'instagram_visible' => $request->boolean('instagram_visible'),
            'facebook_visible' => $request->boolean('facebook_visible'),
        ]);

        return redirect('/homecontent')->with('success', 'social links updated successfully!');
    }

    // Address, email and phone, each with a "Show on the website" switch.
    public function editContacts(Request $request)
    {
        $content = $this->findForEdit('website_content', $request);

        return view('editcontacts', ['form' => [
            'id' => $content->id,
            'email' => $content->company_email,
            'phone' => $content->company_telephone,
            'address' => $content->physical_address,
            'address_visible' => (bool) $content->address_visible,
            'email_visible' => (bool) $content->email_visible,
            'phone_visible' => (bool) $content->phone_visible,
        ]]);
    }

    public function updateContacts(Request $request)
    {
        DB::table('website_content')->where('id', $request->input('id'))->update([
            'physical_address' => $request->address,
            'company_email' => $request->email,
            'company_telephone' => $request->phone,
            'address_visible' => $request->boolean('address_visible'),
            'email_visible' => $request->boolean('email_visible'),
            'phone_visible' => $request->boolean('phone_visible'),
        ]);

        return redirect('/homecontent')->with('success', 'lab contacts updated successfully!');
    }

    // The leader's message (stored on website_content) and the leader's photo (on members).
    public function editLeader(Request $request)
    {
        $content = $this->findForEdit('website_content', $request);
        $leader = DB::table('members')->where('role', 'leader')->first();

        return view('editleader', ['form' => [
            'id' => $content->id,
            'word' => $content->team_leader_word,
            'photo' => $leader->photo ?? '',
        ]]);
    }

    public function updateLeader(Request $request)
    {
        // validate everything before any update so a bad upload can't leave a half-saved record.
        $request->validate([
            'id' => 'required|exists:website_content,id',
            'image' => 'nullable|'.Upload::IMAGE,
        ]);

        DB::table('website_content')->where('id', $request->input('id'))->update([
            'team_leader_word' => $request->word,
        ]);

        if ($request->hasFile('image')) {
            DB::table('members')->where('role', 'leader')->update([
                'photo' => Upload::store($request->file('image'), 'images/team'),
            ]);
        }

        return redirect('/homecontent')->with('success', 'leader content updated successfully!');
    }
}
