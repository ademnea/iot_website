<?php

namespace App\Http\Controllers;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

// Loads the record behind each edit form from the database by id.
// The edit links used to carry every field in the query string, which broke
// (HTTP 500) whenever a value contained & or #, or a link left a field out.
// Each method hands its view a $form array with the same keys the old links used.
class edit_forms extends Controller
{
    private function find(string $table, Request $request){
        $request->validate(['id' => 'required|integer']);
        $record = DB::table($table)->where('id', $request->query('id'))->first();
        abort_if(!$record, 404);
        return $record;
    }

    // date inputs need Y-m-d, the database stores a datetime.
    private function date($value){
        return $value ? substr($value, 0, 10) : '';
    }

    public function editlogo(Request $request){
        $content = $this->find('website_content', $request);
        return view('editlogo', ['form' => ['id' => $content->id]]);
    }

    public function editbanner(Request $request){
        $content = $this->find('website_content', $request);
        return view('editbanner', ['form' => ['id' => $content->id]]);
    }

    public function edittopbar(Request $request){
        $content = $this->find('website_content', $request);
        return view('edittopbar', ['form' => [
            'id' => $content->id,
            'facebook' => $content->facebook,
            'twitter' => $content->twitter,
            'linkedin' => $content->linkedin,
            'instagram' => $content->instagram,
            'youtube' => $content->youtube,
            'twitter_visible' => (bool) $content->twitter_visible,
            'facebook_visible' => (bool) $content->facebook_visible,
            'linkedin_visible' => (bool) $content->linkedin_visible,
            'instagram_visible' => (bool) $content->instagram_visible,
            'youtube_visible' => (bool) $content->youtube_visible,
        ]]);
    }

    public function editcontacts(Request $request){
        $content = $this->find('website_content', $request);
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

    public function edithome(Request $request){
        $content = $this->find('website_content', $request);
        return view('edithome', ['form' => [
            'id' => $content->id,
            'labname' => $content->Labname,
            'motto' => $content->motto,
            'vision' => $content->vision,
            'mission' => $content->mission,
            'objectives' => $content->objectives,
        ]]);
    }

    public function editleader(Request $request){
        $content = $this->find('website_content', $request);
        $leader = DB::table('members')->where('role', 'leader')->first();
        return view('editleader', ['form' => [
            'id' => $content->id,
            'word' => $content->team_leader_word,
            'photo' => $leader->photo ?? '',
        ]]);
    }

    public function editpartner(Request $request){
        $partner = $this->find('partners', $request);
        return view('editpartner', ['form' => [
            'id' => $partner->id,
            'name' => $partner->name,
        ]]);
    }

    public function editproject(Request $request){
        $project = $this->find('projects', $request);
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

    public function editteam(Request $request){
        $member = $this->find('members', $request);
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

    public function editnews(Request $request){
        $event = $this->find('events', $request);
        return view('editnews', ['form' => [
            'id' => $event->id,
            'title' => $event->title,
            'description' => $event->description,
            'venue' => $event->venue,
            'link' => $event->article_link,
            'startdate' => $this->date($event->start_date),
            'enddate' => $this->date($event->end_date),
        ]]);
    }

    public function editpublications(Request $request){
        $publication = $this->find('publications', $request);
        return view('editpublications', ['form' => [
            'id' => $publication->id,
            'title' => $publication->title,
            'type' => $publication->type,
            'author' => $publication->author,
            'date_published' => $this->date($publication->date_published),
            'photo' => $publication->photo,
            'description' => $publication->description,
        ]]);
    }
}
