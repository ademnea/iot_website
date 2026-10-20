<?php

namespace Tests\Feature\Fixes;

use Illuminate\Support\Facades\DB;

// docs/fixes/015-dashboard-shortcuts.md
class DashboardShortcutsTest extends FixesTestCase
{
    public function test_dashboard_boxes_link_to_the_add_forms(): void
    {
        $page = $this->actingAs($this->admin())->get('/dashboard')->assertOk();

        foreach (['/register_projects', '/register_news', '/register_publications', '/registerprototype',
                  '/register_team', '/register_home#partners', '/register', '/oldteam', route('profile.show')] as $href) {
            $page->assertSee('href="'.$href.'"', false);
        }
    }

    public function test_settings_shortcuts_open_the_existing_home_content(): void
    {
        $id = DB::table('website_content')->value('id');

        $page = $this->actingAs($this->admin())->get('/dashboard')->assertOk();
        foreach (['edithome', 'editlogo', 'editbanner', 'editcontacts', 'edittopbar', 'editleader'] as $edit) {
            $page->assertSee('href="/'.$edit.'?id='.$id.'"', false);
        }

        // and each one opens a working page
        foreach (['edithome', 'editlogo', 'editbanner', 'editcontacts', 'edittopbar', 'editleader'] as $edit) {
            $this->get('/'.$edit.'?id='.$id)->assertOk();
        }
    }

    public function test_on_a_fresh_install_settings_point_to_add_new_home_content(): void
    {
        DB::table('website_content')->delete();

        $this->actingAs($this->admin())->get('/dashboard')->assertOk()
            ->assertSee('The home page has no content yet.')
            ->assertDontSee('/edithome?id=', false);
    }
}
