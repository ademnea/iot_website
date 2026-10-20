<?php

namespace Tests\Feature\Fixes;

use Illuminate\Http\UploadedFile;
use Illuminate\Support\Facades\DB;

// docs/fixes/005-input-validation.md, 006-edit-pages.md, 007-display-fixes.md
class StabilityFixesTest extends FixesTestCase
{
    private function project(array $overrides = []): int
    {
        $id = DB::table('projects')->insertGetId(array_merge([
            'name' => 'Hive monitor', 'description' => 'R&D on hives #2', 'status' => 'Ongoing',
            'created_at' => now(), 'updated_at' => now(),
        ], $overrides));
        DB::table('project_photos')->insert(['photo' => null, 'project_id' => $id]);
        return $id;
    }

    public function test_edit_project_page_loads_when_text_contains_ampersand_and_hash(): void
    {
        $id = $this->project();

        $this->actingAs($this->admin())->get('/editproject?id='.$id)
            ->assertOk()
            ->assertSee('R&amp;D on hives #2', false);
    }

    public function test_edit_page_for_past_project_and_past_event_loads(): void
    {
        $admin = $this->admin();
        $project = $this->project(['status' => 'Past']);
        $event = DB::table('events')->insertGetId([
            'title' => 'Old event', 'description' => 'Done', 'status' => 'Past',
            'start_date' => '2024-01-02 09:00:00', 'admin_id' => $admin->id,
            'created_at' => now(), 'updated_at' => now(),
        ]);

        $this->actingAs($admin)->get('/editproject?id='.$project)->assertOk();
        $this->actingAs($admin)->get('/editnews?id='.$event)->assertOk()->assertSee('2024-01-02');
    }

    public function test_list_pages_link_to_edit_forms_by_id_only(): void
    {
        $id = $this->project();

        $this->actingAs($this->admin())->get('/projectscontent')
            ->assertOk()
            ->assertSee('href="/editproject?id='.$id.'"', false);
    }

    public function test_edit_page_for_missing_record_is_404(): void
    {
        $this->actingAs($this->admin())->get('/editproject?id=999')->assertNotFound();
    }

    public function test_every_edit_page_renders(): void
    {
        $admin = $this->admin();
        $content = DB::table('website_content')->value('id');
        $partner = DB::table('partners')->insertGetId(['name' => 'P', 'description' => 'D']);
        $member = $this->member();
        $publication = DB::table('publications')->insertGetId([
            'title' => 'Paper', 'type' => 'journal', 'author' => 'Ada', 'description' => 'D',
            'file_link' => 'paper.pdf', 'admin_id' => $admin->id,
        ]);

        foreach (['editlogo', 'editbanner', 'edittopbar', 'editcontacts', 'edithome', 'editleader'] as $page) {
            $this->actingAs($admin)->get("/$page?id=$content")->assertOk();
        }
        $this->actingAs($admin)->get("/editpartner?id=$partner")->assertOk();
        $this->actingAs($admin)->get("/editteam?id=$member")->assertOk();
        $this->actingAs($admin)->get("/editpublications?id=$publication")->assertOk();
    }

    public function test_missing_required_fields_return_validation_errors_not_500(): void
    {
        $admin = $this->admin();

        $this->actingAs($admin)->post('/insert_team', [])->assertSessionHasErrors(['firstname', 'email', 'designation', 'image']);
        $this->actingAs($admin)->post('/insertpartner', [])->assertSessionHasErrors(['name', 'description']);
        $this->actingAs($admin)->post('/insert_publication', [])->assertSessionHasErrors(['title', 'file']);
        $this->actingAs($admin)->post('/submit-project', [])->assertSessionHasErrors(['projectName']);
    }

    public function test_duplicate_member_email_is_a_validation_error(): void
    {
        $this->member(['email' => 'ada@lab.test']);

        $this->actingAs($this->admin())->post('/insert_team', [
            'firstname' => 'A', 'lastname' => 'B', 'email' => 'ada@lab.test', 'phone' => '0700',
            'designation' => 'Intern', 'image' => UploadedFile::fake()->image('a.png'),
        ])->assertSessionHasErrors('email');
    }

    public function test_validation_errors_are_displayed_on_admin_pages(): void
    {
        $this->actingAs($this->admin())
            ->withSession(['errors' => (new \Illuminate\Support\ViewErrorBag)->put('default', new \Illuminate\Support\MessageBag(['name' => 'The name field is required.']))])
            ->get('/register_home')
            ->assertSee('The name field is required.');
    }

    public function test_prototype_can_be_added_without_an_image(): void
    {
        $this->actingAs($this->admin())->post('/insertprototype', [
            'title' => 'Smart hive', 'description' => 'Sensor kit', 'project_id' => '',
        ])->assertRedirect('/publicationscontent');

        $id = DB::table('prototypes')->where('name', 'Smart hive')->value('id');
        $this->assertNotNull($id);
        $this->assertDatabaseHas('prototype_photos', ['prototype_id' => $id, 'photo' => null]);
    }

    public function test_event_can_be_edited_without_a_new_image_and_keeps_its_photos(): void
    {
        $admin = $this->admin();
        $event = DB::table('events')->insertGetId([
            'title' => 'Expo', 'description' => 'D', 'admin_id' => $admin->id,
            'created_at' => now(), 'updated_at' => now(),
        ]);
        DB::table('event_photos')->insert([['photo' => 'a.jpg', 'event_id' => $event], ['photo' => 'b.jpg', 'event_id' => $event]]);

        $this->actingAs($admin)->post('/editnews_route', [
            'id' => $event, 'news' => 'Expo 2026', 'description' => 'Updated',
        ])->assertRedirect('/newscontent');

        $this->assertDatabaseHas('events', ['id' => $event, 'title' => 'Expo 2026']);
        $this->assertEquals(['a.jpg', 'b.jpg'], DB::table('event_photos')->where('event_id', $event)->orderBy('photo')->pluck('photo')->all());
    }

    public function test_contact_form_validates_input(): void
    {
        $this->post('/insert_message', ['name' => '', 'email' => 'not-an-email'])
            ->assertSessionHasErrors(['name', 'email', 'message']);
        $this->assertDatabaseCount('messages', 0);
    }

    // docs/fixes/012-profile-link.md: admins must be able to reach 2FA from the admin pages.
    public function test_admin_sidebar_links_to_profile_and_security(): void
    {
        $this->actingAs($this->admin())->get('/dashboard')
            ->assertOk()
            ->assertSee('href="'.route('profile.show').'"', false)
            ->assertSee('Profile &amp; security', false);
    }

    public function test_about_page_hides_inactive_leader_and_escapes_bios(): void
    {
        $this->member(['fname' => 'Retired', 'role' => 'leader', 'status' => 'Inactive']);
        $this->member(['fname' => 'Current', 'bio' => "<script>alert(1)</script>\nSecond line"]);

        $response = $this->get('/about')->assertOk();

        $response->assertDontSee('Retired');
        $response->assertSee('Current');
        $response->assertDontSee('<script>alert(1)</script>', false);
        $response->assertSee('&lt;script&gt;alert(1)&lt;/script&gt;<br />', false);
    }
}
