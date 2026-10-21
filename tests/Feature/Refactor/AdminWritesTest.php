<?php

namespace Tests\Feature\Refactor;

use App\Models\User;
use Illuminate\Http\UploadedFile;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;
use Tests\Feature\Fixes\FixesTestCase;

// Pins down every add/edit/status form: where it redirects, the message it shows, what it saves
// and where uploads go. Written before the controllers were reorganised by content type, and
// passing against both the old and the new code.
class AdminWritesTest extends FixesTestCase
{
    private User $user;
    private int $site;

    protected function setUp(): void
    {
        parent::setUp();
        $this->user = $this->admin();
        $this->site = (int) DB::table('website_content')->value('id');
    }

    // posts as an admin and checks the redirect and its success message.
    private function save(string $uri, array $data, string $redirect, string $message): void
    {
        $this->actingAs($this->user)->post($uri, $data)
            ->assertSessionHasNoErrors()
            ->assertRedirect($redirect)
            ->assertSessionHas('success', $message);
    }

    // asserts an uploaded file was stored under a generated name in $dir, and cleans it up.
    private function assertStored(string $dir, ?string $name, string $original): void
    {
        $this->track($dir, $name);
        $this->assertNotNull($name);
        $this->assertNotSame($original, $name);
        $this->assertFileExists(public_path("$dir/$name"));
    }

    private function image(string $name = 'photo.png'): UploadedFile
    {
        return UploadedFile::fake()->image($name);
    }

    private function siteRow(): object
    {
        return DB::table('website_content')->find($this->site);
    }

    // ---- home content ------------------------------------------------------------------------

    public function test_add_home_content_updates_the_existing_row(): void
    {
        $this->save('/insertmission', ['labname' => 'IoT-ra', 'motto' => 'Smart', 'content' => 'A vision'],
            '/homecontent', 'home content added successfully!');

        $this->assertDatabaseCount('website_content', 1);
        $this->assertDatabaseHas('website_content', ['id' => $this->site, 'Labname' => 'IoT-ra', 'motto' => 'Smart', 'vision' => 'A vision']);
    }

    public function test_add_home_content_creates_the_row_on_a_fresh_install(): void
    {
        DB::table('website_content')->delete();

        $this->save('/insertmission', ['labname' => 'IoT-ra', 'motto' => 'Smart', 'content' => 'A vision'],
            '/homecontent', 'home content added successfully!');

        $this->assertDatabaseHas('website_content', ['Labname' => 'IoT-ra', 'vision' => 'A vision']);
    }

    public function test_add_contacts_saves_the_logo_and_contact_details(): void
    {
        $this->save('/insertcontacts', [
            'logo' => $this->image('logo.png'), 'address' => 'Block B', 'telephone' => '+256 700', 'email' => 'lab@x.org',
            'twitter' => 'x.com/lab', 'facebook' => 'fb.com/lab', 'linkedin' => 'li.com/lab', 'youtube' => 'yt.com/lab', 'instagram' => 'ig.com/lab',
        ], '/homecontent', 'home content added successfully!');

        $row = $this->siteRow();
        $this->assertStored('images/logo', $row->logo, 'logo.png');
        $this->assertSame(['Block B', '+256 700', 'lab@x.org', 'x.com/lab', 'ig.com/lab'],
            [$row->physical_address, $row->company_telephone, $row->company_email, $row->twitter, $row->instagram]);
    }

    public function test_edit_lab_information(): void
    {
        $this->save('/editmission_route', ['id' => $this->site, 'labname' => 'IoT-ra', 'motto' => 'M', 'vision' => 'V', 'mission' => 'Mi', 'objectives' => "a\nb"],
            '/homecontent', 'changes have been made successfully!');

        $this->assertDatabaseHas('website_content', ['id' => $this->site, 'Labname' => 'IoT-ra', 'motto' => 'M', 'vision' => 'V', 'mission' => 'Mi', 'objectives' => "a\nb"]);
    }

    public function test_edit_logo_and_banner(): void
    {
        $this->save('/editlogo_route', ['id' => $this->site, 'image' => $this->image('l.png')], '/homecontent', 'logo changed successfully!');
        $this->save('/editbanner_route', ['id' => $this->site, 'image' => $this->image('b.png')], '/homecontent', 'banner changed successfully!');

        $row = $this->siteRow();
        $this->assertStored('images/logo', $row->logo, 'l.png');
        $this->assertStored('images/banner', $row->banner, 'b.png');
    }

    public function test_edit_contacts_and_their_switches(): void
    {
        $this->save('/editlabinfo_route', ['id' => $this->site, 'address' => 'Block B', 'email' => 'lab@x.org', 'phone' => '0700', 'email_visible' => '1'],
            '/homecontent', 'lab contacts updated successfully!');

        $this->assertDatabaseHas('website_content', ['id' => $this->site, 'physical_address' => 'Block B', 'company_email' => 'lab@x.org',
            'company_telephone' => '0700', 'email_visible' => true, 'address_visible' => false, 'phone_visible' => false]);
    }

    public function test_edit_leader_message_and_photo(): void
    {
        $leader = $this->member(['role' => 'leader']);
        $other = $this->member(['role' => 'researcher', 'photo' => 'keep.jpg']);

        $this->save('/editleader_route', ['id' => $this->site, 'word' => 'Welcome', 'image' => $this->image('lead.png')],
            '/homecontent', 'leader content updated successfully!');

        $this->assertSame('Welcome', $this->siteRow()->team_leader_word);
        $this->assertStored('images/team', DB::table('members')->where('id', $leader)->value('photo'), 'lead.png');
        $this->assertSame('keep.jpg', DB::table('members')->where('id', $other)->value('photo'));
    }

    // ---- partners ----------------------------------------------------------------------------

    public function test_add_and_edit_a_partner(): void
    {
        $this->save('/insertpartner', ['name' => 'UNCST', 'description' => 'Funder', 'website' => 'uncst.go.ug', 'image' => $this->image('p.png')],
            '/homecontent', 'partner added successfully!');
        $partner = DB::table('partners')->first();
        $this->assertSame(['UNCST', 'Funder', 'uncst.go.ug', $this->user->id], [$partner->name, $partner->description, $partner->website_link, (int) $partner->admin_id]);
        $this->assertStored('images/partners', $partner->logo, 'p.png');

        $this->save('/editpartner_route', ['id' => $partner->id, 'fullname' => 'UNCST Uganda', 'image' => $this->image('p2.png')],
            '/homecontent', 'partner info updated successfully!');
        $partner = DB::table('partners')->first();
        $this->assertSame('UNCST Uganda', $partner->name);
        $this->assertStored('images/partners', $partner->logo, 'p2.png');
    }

    // ---- news / events -----------------------------------------------------------------------

    public function test_add_and_edit_an_event(): void
    {
        $this->save('/insert_news', [
            'title' => 'Hackathon', 'description' => 'Two days', 'start_date' => '2026-11-01', 'end_date' => '2026-11-02',
            'venue' => 'CoCIS', 'link' => 'x.org/h', 'images' => [$this->image('a.png'), $this->image('b.png')],
        ], '/newscontent', 'event added successfully!');

        $event = DB::table('events')->first();
        $this->assertSame(['Hackathon', 'CoCIS', 'x.org/h', $this->user->id], [$event->title, $event->venue, $event->article_link, (int) $event->admin_id]);
        $photos = DB::table('event_photos')->where('event_id', $event->id)->pluck('photo');
        $this->assertCount(2, $photos);
        $photos->each(fn ($p) => $this->assertStored('images/events', $p, 'a.png'));

        $this->save('/editnews_route', [
            'id' => $event->id, 'news' => 'Hackathon 2', 'description' => 'Three days', 'venue' => 'Main hall',
            'link' => '', 'startdate' => '2026-11-01', 'enddate' => '2026-11-03', 'image' => $this->image('c.png'),
        ], '/newscontent', 'event updated successfully!');

        $this->assertDatabaseHas('events', ['id' => $event->id, 'title' => 'Hackathon 2', 'description' => 'Three days', 'venue' => 'Main hall']);
        $photos = DB::table('event_photos')->where('event_id', $event->id)->pluck('photo');
        $this->assertCount(3, $photos);   // a new photo is added, the others are kept
        $this->assertStored('images/events', $photos->last(), 'c.png');
    }

    // ---- projects ----------------------------------------------------------------------------

    public function test_add_and_edit_a_project(): void
    {
        $this->save('/submit-project', [
            'projectName' => 'Beehive monitor', 'description' => 'Sensors', 'venue' => 'Kampala', 'participants' => '5',
            'start_date' => '2026-01-01', 'end_date' => '2026-12-31', 'project_link' => 'bees.org', 'image' => $this->image('hive.png'),
        ], '/projectscontent', 'project added successfully!');

        $project = DB::table('projects')->first();
        $this->assertSame(['Beehive monitor', 'Kampala', '5', 'bees.org', $this->user->id],
            [$project->name, $project->venue, $project->participants, $project->website, (int) $project->admin_id]);
        $this->assertStored('images/projects', DB::table('project_photos')->where('project_id', $project->id)->value('photo'), 'hive.png');

        $this->save('/editproject_route', [
            'id' => $project->id, 'projectName' => 'Hive monitor', 'description' => 'More sensors', 'venue' => 'Gulu',
            'participants' => '6', 'link' => 'hive.org', 'image' => $this->image('hive2.png'),
        ], '/projectscontent', 'project info updated successfully!');

        $this->assertDatabaseHas('projects', ['id' => $project->id, 'name' => 'Hive monitor', 'venue' => 'Gulu', 'website' => 'hive.org']);
        $this->assertSame(1, DB::table('project_photos')->where('project_id', $project->id)->count());   // replaced, not added
        $this->assertStored('images/projects', DB::table('project_photos')->where('project_id', $project->id)->value('photo'), 'hive2.png');
    }

    public function test_a_project_without_a_photo_still_gets_a_photo_row(): void
    {
        $this->save('/submit-project', ['projectName' => 'No photo', 'description' => 'd'], '/projectscontent', 'project added successfully!');

        $this->assertDatabaseHas('project_photos', ['project_id' => DB::table('projects')->value('id'), 'photo' => null]);
    }

    // ---- publications and prototypes ---------------------------------------------------------

    public function test_add_and_edit_a_publication(): void
    {
        $this->save('/insert_publication', [
            'title' => 'Hive report', 'author' => 'Lab', 'type' => 'Report', 'description' => 'Findings', 'date_published' => '2026-05-01',
            'photo' => $this->image('cover.png'), 'file' => UploadedFile::fake()->create('report.pdf', 10, 'application/pdf'),
        ], '/publicationscontent', 'publication added successfully!');

        $pub = DB::table('publications')->first();
        $this->assertSame(['Hive report', 'Lab', 'Report', $this->user->id], [$pub->title, $pub->author, $pub->type, (int) $pub->admin_id]);
        $this->assertStored('images/publications', $pub->photo, 'cover.png');
        $this->assertStored('documents/publications', $pub->file_link, 'report.pdf');

        $this->save('/editpublication_route', [
            'id' => $pub->id, 'title' => 'Hive report v2', 'type' => 'Dataset', 'author' => 'Lab team', 'description' => 'More',
            'photo' => $this->image('cover2.png'), 'file' => UploadedFile::fake()->create('v2.csv', 1, 'text/csv'),
        ], '/publicationscontent', 'publication updated successfully!');

        $pub = DB::table('publications')->first();
        $this->assertSame(['Hive report v2', 'Dataset', 'Lab team'], [$pub->title, $pub->type, $pub->author]);
        $this->assertStored('images/publications', $pub->photo, 'cover2.png');
        $this->assertStored('documents/publications', $pub->file_link, 'v2.csv');
    }

    public function test_add_a_prototype_linked_to_a_project(): void
    {
        $project = DB::table('projects')->insertGetId(['name' => 'Hive', 'description' => 'd', 'admin_id' => $this->user->id]);

        $this->save('/insertprototype', ['title' => 'Hive scale', 'description' => 'Weighs hives', 'project_id' => $project, 'image' => $this->image('scale.png')],
            '/publicationscontent', 'prototype added successfully!');

        $prototype = DB::table('prototypes')->first();
        $this->assertSame(['Hive scale', $project], [$prototype->name, (int) $prototype->project_id]);
        $this->assertStored('images/prototypes', DB::table('prototype_photos')->where('prototype_id', $prototype->id)->value('photo'), 'scale.png');
    }

    // ---- team --------------------------------------------------------------------------------

    public function test_add_edit_deactivate_and_restore_a_member(): void
    {
        $this->save('/insert_team', [
            'firstname' => 'Grace', 'lastname' => 'Hopper', 'email' => 'grace@x.org', 'phone' => '0701',
            'designation' => 'PhD student', 'role' => 'phd_stdt', 'bio' => 'Compilers', 'image' => $this->image('grace.png'),
        ], '/aboutuscontent', 'Grace has been registered successfully!');

        $member = DB::table('members')->where('email', 'grace@x.org')->first();
        $this->assertSame(['Grace', 'Hopper', 'phd_stdt', 'Active'], [$member->fname, $member->lname, $member->role, $member->status]);
        $this->assertStored('images/team', $member->photo, 'grace.png');

        $this->save('/editmember_route', [
            'id' => $member->id, 'firstname' => 'Grace', 'lastname' => 'Hopper', 'email' => 'grace@x.org',
            'designation' => 'Researcher', 'role' => 'researcher', 'bio' => 'COBOL', 'image' => $this->image('grace2.png'),
        ], '/aboutuscontent', 'member details updated successfully!');
        $member = DB::table('members')->find($member->id);
        $this->assertSame(['Researcher', 'researcher', 'COBOL'], [$member->designation, $member->role, $member->bio]);
        $this->assertStored('images/team', $member->photo, 'grace2.png');

        $this->save('/deleteuser?user_id='.$member->id, [], '/aboutuscontent', 'user was deleted successfully');
        $this->assertSame('Inactive', DB::table('members')->where('id', $member->id)->value('status'));

        $this->save('/restoreuser?user_id='.$member->id, [], '/oldteam', 'the member was restored successfully');
        $this->assertSame('Active', DB::table('members')->where('id', $member->id)->value('status'));
    }

    // ---- admins and visitors -----------------------------------------------------------------

    public function test_register_another_admin(): void
    {
        $this->save('/registeradmin', ['name' => 'New Admin', 'email' => 'new@x.org', 'password' => 'long-enough-1', 'password_confirmation' => 'long-enough-1'],
            '/aboutuscontent', 'admin registered successfully');

        $this->assertTrue(Hash::check('long-enough-1', DB::table('users')->where('email', 'new@x.org')->value('password')));
    }

    public function test_visitor_message(): void
    {
        $this->post('/insert_message', ['name' => 'Sam', 'email' => 'sam@x.org', 'phone' => '0702', 'message' => 'Hello'])
            ->assertRedirect('/about#contactus')
            ->assertSessionHas('message', 'Thanks for the feedback, Sam.');

        $this->assertDatabaseHas('messages', ['name' => 'Sam', 'email' => 'sam@x.org', 'phone' => '0702', 'message' => 'Hello']);
    }
}
