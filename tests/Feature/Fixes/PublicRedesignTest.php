<?php

namespace Tests\Feature\Fixes;

use Illuminate\Support\Facades\DB;

// docs/fixes/013-public-redesign.md
class PublicRedesignTest extends FixesTestCase
{
    public function test_every_public_page_renders(): void
    {
        foreach (['/', '/about', '/projects', '/publications', '/news', '/partners', '/gallery',
                  '/team_gallery', '/project_gallery', '/prototypes_gallery', '/events_gallery'] as $page) {
            $this->get($page)->assertOk();
        }
    }

    public function test_public_pages_no_longer_load_the_old_cdn_libraries(): void
    {
        $html = $this->get('/about')->assertOk()->getContent();

        foreach (['bootstrap.min.css', 'jquery', 'owl.carousel', 'tailwind.min.css', 'wow.min.js'] as $old) {
            $this->assertStringNotContainsString($old, $html);
        }
    }

    public function test_missing_content_shows_admin_hints_to_admins_only(): void
    {
        // the test case seeds a lab name and motto but no vision, mission or objectives
        $this->get('/')->assertOk()->assertDontSee("hasn't been added yet", false);

        $this->actingAs($this->admin())->get('/')
            ->assertOk()
            ->assertSee('Vision')
            ->assertSee("hasn't been added yet", false);
    }

    public function test_home_shows_the_content_admins_entered(): void
    {
        DB::table('website_content')->update([
            'vision' => 'Vision text', 'mission' => 'Mission text', 'objectives' => "First\nSecond",
        ]);

        $this->get('/')->assertOk()
            ->assertSee('Motto')
            ->assertSee('Vision text')
            ->assertSee('Mission text')
            ->assertSeeInOrder(['<li>First</li>', '<li>Second</li>'], false);
    }

    public function test_logo_falls_back_to_the_repository_logo_until_one_is_uploaded(): void
    {
        DB::table('website_content')->update(['logo' => null]);
        $this->get('/')->assertSee('img/logo-iot-ra.png', false);

        DB::table('website_content')->update(['logo' => 'uploaded.png']);
        $this->get('/')->assertSee('images/logo/uploaded.png', false);
    }

    public function test_projects_are_grouped_by_status_and_links_get_a_scheme(): void
    {
        foreach ([['Hive monitor', 'Ongoing', 'www.example.org'], ['Weather station', 'Past', 'https://example.net/ws']] as [$name, $status, $site]) {
            $id = DB::table('projects')->insertGetId(['name' => $name, 'description' => 'D', 'status' => $status, 'website' => $site]);
            DB::table('project_photos')->insert(['photo' => null, 'project_id' => $id]);
        }

        $this->get('/projects')->assertOk()
            ->assertSeeInOrder(['id="ongoing"', 'Hive monitor', 'id="past"', 'Weather station'], false)
            ->assertSee('href="https://www.example.org"', false)
            ->assertSee('href="https://example.net/ws"', false);
    }

    public function test_the_lab_name_is_used_in_titles_and_footer_everywhere(): void
    {
        config(['app.name' => 'IoT-ra (Internet of Things - Research & Applications) Lab']);
        $escaped = 'IoT-ra (Internet of Things - Research &amp; Applications) Lab';

        // no lab name entered yet: the app name fills in
        DB::table('website_content')->update(['Labname' => null]);
        $this->get('/projects')->assertOk()
            ->assertSee('<title>Projects · '.$escaped.'</title>', false)
            ->assertSee('<span class="foot-mark">'.$escaped.'</span>', false);

        // the login page (as a guest) and admin pages carry the name too
        $this->get('/login')->assertOk()->assertSee('<title>Admin login · '.$escaped.'</title>', false);
        $this->actingAs($this->admin())->get('/homecontent')->assertOk()
            ->assertSee('<title>Home content · '.$escaped.'</title>', false);
    }

    public function test_contact_form_keeps_the_visitors_text_after_an_error(): void
    {
        $this->from('/about')->post('/insert_message', ['name' => 'Ada', 'email' => 'not-an-email', 'message' => 'Hello'])
            ->assertRedirect('/about');

        $this->get('/about')->assertOk()
            ->assertSee('value="Ada"', false)
            ->assertSee('The email field must be a valid email address.')
            ->assertSee('>Hello</textarea>', false);
    }
}
