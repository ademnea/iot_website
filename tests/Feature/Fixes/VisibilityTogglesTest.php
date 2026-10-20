<?php

namespace Tests\Feature\Fixes;

use Illuminate\Support\Facades\DB;

// docs/fixes/014-show-hide-contacts-and-socials.md
class VisibilityTogglesTest extends FixesTestCase
{
    private function setContent(array $values): int
    {
        DB::table('website_content')->update($values);
        return (int) DB::table('website_content')->value('id');
    }

    public function test_existing_links_and_contacts_stay_visible_after_the_migration(): void
    {
        $this->setContent(['linkedin' => 'linkedin.com/company/lab', 'company_email' => 'lab@example.org']);

        $this->get('/')->assertOk()
            ->assertSee('href="https://linkedin.com/company/lab"', false)
            ->assertSee('aria-label="LinkedIn"', false)
            ->assertSee('lab@example.org');
    }

    public function test_a_switched_off_link_is_hidden_but_kept(): void
    {
        $id = $this->setContent(['youtube' => 'youtube.com/@lab', 'youtube_visible' => false]);

        $this->get('/')->assertOk()->assertDontSee('youtube.com/@lab');
        $this->assertDatabaseHas('website_content', ['id' => $id, 'youtube' => 'youtube.com/@lab']);
    }

    public function test_a_switched_on_link_without_a_value_shows_nothing(): void
    {
        $this->setContent(['facebook' => null, 'facebook_visible' => true]);

        $this->get('/')->assertOk()->assertDontSee('aria-label="Facebook"', false);
    }

    public function test_contact_switches_apply_to_the_header_footer_and_contact_page(): void
    {
        $this->setContent(['company_telephone' => '+256 700 111222', 'phone_visible' => false,
                           'physical_address' => 'Block B', 'address_visible' => true]);

        foreach (['/', '/about'] as $page) {
            $this->get($page)->assertOk()->assertDontSee('+256 700 111222')->assertSee('Block B');
        }
    }

    public function test_admin_can_switch_items_off_and_back_on(): void
    {
        $admin = $this->admin();
        $id = $this->setContent(['twitter' => 'x.com/lab', 'instagram' => 'instagram.com/lab']);

        // only Instagram ticked: X is switched off, but its link is kept
        $this->actingAs($admin)->post('/edittopbar_route', [
            'id' => $id, 'twitter' => 'x.com/lab', 'instagram' => 'instagram.com/lab', 'instagram_visible' => '1',
        ])->assertRedirect('/homecontent');

        $this->assertDatabaseHas('website_content', ['id' => $id, 'twitter' => 'x.com/lab', 'twitter_visible' => false, 'instagram_visible' => true]);
        $this->get('/')->assertDontSee('x.com/lab')->assertSee('instagram.com/lab');
        $this->actingAs($admin)->get('/homecontent')->assertSee('Hidden on the website');

        // switching X back on brings the icon back without retyping the link
        $this->actingAs($admin)->post('/edittopbar_route', [
            'id' => $id, 'twitter' => 'x.com/lab', 'twitter_visible' => '1', 'instagram' => 'instagram.com/lab', 'instagram_visible' => '1',
        ]);
        $this->get('/')->assertSee('href="https://x.com/lab"', false);
    }

    public function test_edit_forms_show_the_current_switch_state(): void
    {
        $id = $this->setContent(['company_email' => 'lab@example.org', 'email_visible' => false]);

        $this->actingAs($this->admin())->get('/editcontacts?id='.$id)->assertOk()
            ->assertSee('name="email_visible"', false)
            ->assertSee('Show on the website');
    }

    public function test_the_site_has_a_tab_icon(): void
    {
        $this->assertFileExists(public_path('favicon.ico'));
        $this->assertGreaterThan(0, filesize(public_path('favicon.ico')));
        $this->get('/')->assertSee('rel="icon"', false)->assertSee('img/icon-192.png', false);
    }
}
