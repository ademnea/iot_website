<?php

namespace Tests\Feature\Fixes;

use Illuminate\Support\Facades\DB;

// Fix 018: phones and tablets. Every page must declare the mobile viewport (without it phones
// draw the page at desktop width, zoomed out), the public menu folds behind a Menu button,
// and the admin sidebar is included once with its toggle at the sidebar's own breakpoint.
class ResponsiveLayoutTest extends FixesTestCase
{
    private const VIEWPORT = '<meta name="viewport" content="width=device-width, initial-scale=1';
    // older admin pages write the same tag with its attributes the other way round.
    private const ANY_VIEWPORT = '/<meta (?=[^>]*name="viewport")(?=[^>]*content="width=device-width)[^>]*>/';

    public function test_every_public_page_declares_the_mobile_viewport_and_has_the_menu_button(): void
    {
        foreach (['/', '/projects', '/publications', '/news', '/about', '/partners', '/gallery'] as $uri) {
            $this->get($uri)->assertOk()
                ->assertSee(self::VIEWPORT, false)
                ->assertSee('class="menu-toggle" type="button" aria-controls="site-nav" aria-expanded="false"', false)
                ->assertSee('id="site-nav"', false);
        }
    }

    public function test_every_admin_page_declares_the_mobile_viewport(): void
    {
        $admin = $this->admin();
        $site = DB::table('website_content')->value('id');
        $project = DB::table('projects')->insertGetId(['name' => 'Hive', 'description' => 'd', 'admin_id' => $admin->id]);
        $event = DB::table('events')->insertGetId(['title' => 'Day', 'description' => 'd', 'admin_id' => $admin->id]);
        $publication = DB::table('publications')->insertGetId(['title' => 'Report', 'type' => 'Report', 'author' => 'Lab', 'description' => 'd', 'file_link' => 'r.pdf', 'admin_id' => $admin->id]);
        $partner = DB::table('partners')->insertGetId(['name' => 'UNCST', 'description' => 'd', 'admin_id' => $admin->id]);
        $member = $this->member();

        $pages = [
            '/dashboard', '/homecontent', '/projectscontent', '/publicationscontent', '/newscontent', '/aboutuscontent',
            '/oldteam', '/register_home', '/register_projects', '/register_publications', '/register_news',
            '/register_team', '/registerprototype', '/register',
            "/edithome?id=$site", "/editlogo?id=$site", "/editbanner?id=$site", "/edittopbar?id=$site",
            "/editcontacts?id=$site", "/editleader?id=$site", "/editproject?id=$project", "/editnews?id=$event",
            "/editpublications?id=$publication", "/editpartner?id=$partner", "/editteam?id=$member",
        ];
        foreach ($pages as $uri) {
            $html = $this->actingAs($admin)->get($uri)->assertOk()->getContent();
            $this->assertMatchesRegularExpression(self::ANY_VIEWPORT, $html, "$uri has no viewport meta");
        }
    }

    public function test_the_admin_sidebar_is_included_once_and_toggles_where_it_hides(): void
    {
        $admin = $this->admin();

        foreach (['/homecontent', '/newscontent', '/publicationscontent', '/projectscontent'] as $uri) {
            $html = $this->actingAs($admin)->get($uri)->assertOk()->getContent();
            $this->assertSame(1, substr_count($html, 'id="default-sidebar"'), "$uri includes the sidebar more than once");
            // the sidebar shows from the sm breakpoint, so its toggle must hide at sm too.
            $this->assertStringContainsString('rounded-lg sm:hidden', $html);
            // opened by our own script, not Flowbite's data-drawer attributes (which did nothing).
            $this->assertStringContainsString('id="sidebar-toggle" aria-controls="default-sidebar" aria-expanded="false"', $html);
            $this->assertStringNotContainsString('data-drawer-toggle', $html);
        }
    }
}
