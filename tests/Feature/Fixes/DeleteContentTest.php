<?php

namespace Tests\Feature\Fixes;

use App\Models\User;
use Illuminate\Support\Facades\DB;

// Fix 019: the trash icons on the admin Projects, News, Products and Home pages did nothing.
// After a confirmation they now delete the item (projects, events, publications, partners)
// or, on Home, clear that one value (logo, banner, lab name, vision, ...).
class DeleteContentTest extends FixesTestCase
{
    private User $user;

    protected function setUp(): void
    {
        parent::setUp();
        $this->user = $this->admin();
    }

    private function project(string $name = 'Hive'): int
    {
        $id = DB::table('projects')->insertGetId(['name' => $name, 'description' => 'd', 'status' => 'Ongoing', 'admin_id' => $this->user->id]);
        DB::table('project_photos')->insert(['project_id' => $id, 'photo' => 'hive.png']);

        return $id;
    }

    private function event(string $title = 'Open day'): int
    {
        $id = DB::table('events')->insertGetId(['title' => $title, 'description' => 'd', 'status' => 'Ongoing', 'admin_id' => $this->user->id]);
        DB::table('event_photos')->insert([['event_id' => $id, 'photo' => 'a.png'], ['event_id' => $id, 'photo' => 'b.png']]);

        return $id;
    }

    public function test_deleting_a_project_removes_it_and_its_photo_rows(): void
    {
        $id = $this->project();
        $other = $this->project('Weather');

        $this->actingAs($this->user)->post('/deleteproject', ['id' => $id])
            ->assertRedirect('/projectscontent')
            ->assertSessionHas('success', 'project deleted successfully!');

        $this->assertDatabaseMissing('projects', ['id' => $id]);
        $this->assertDatabaseMissing('project_photos', ['project_id' => $id]);
        $this->assertDatabaseHas('projects', ['id' => $other]);
    }

    public function test_deleting_an_event_removes_it_and_its_photo_rows(): void
    {
        $id = $this->event();
        $other = $this->event('Hackathon');

        $this->actingAs($this->user)->post('/deletenews', ['id' => $id])
            ->assertRedirect('/newscontent')
            ->assertSessionHas('success', 'event deleted successfully!');

        $this->assertDatabaseMissing('events', ['id' => $id]);
        $this->assertDatabaseMissing('event_photos', ['event_id' => $id]);
        $this->assertDatabaseHas('events', ['id' => $other]);
    }

    public function test_an_unknown_id_is_rejected(): void
    {
        $this->actingAs($this->user)->from('/projectscontent')->post('/deleteproject', ['id' => 999999])
            ->assertSessionHasErrors('id');
        $this->actingAs($this->user)->from('/newscontent')->post('/deletenews', [])
            ->assertSessionHasErrors('id');
    }

    public function test_visitors_cannot_delete(): void
    {
        $project = $this->project();
        $event = $this->event();

        $this->post('/deleteproject', ['id' => $project])->assertRedirect('/login');
        $this->post('/deletenews', ['id' => $event])->assertRedirect('/login');

        $this->assertDatabaseHas('projects', ['id' => $project]);
        $this->assertDatabaseHas('events', ['id' => $event]);
    }

    public function test_the_trash_icons_are_delete_forms_with_a_confirmation(): void
    {
        $project = $this->project();
        $event = $this->event();
        // a prototype linked to the project is deleted with it (database cascade), so the confirmation says so.
        $prototype = DB::table('prototypes')->insertGetId(['name' => 'Scale', 'description' => 'd', 'project_id' => $project]);
        DB::table('prototype_photos')->insert(['prototype_id' => $prototype, 'photo' => 's.png']);

        $this->actingAs($this->user)->get('/projectscontent')->assertOk()
            ->assertSee('action="/deleteproject"', false)
            ->assertSee('name="id" value="'.$project.'"', false)
            ->assertSee('Delete the project “Hive” and its 1 prototype?', false);

        $this->actingAs($this->user)->get('/newscontent')->assertOk()
            ->assertSee('action="/deletenews"', false)
            ->assertSee('name="id" value="'.$event.'"', false)
            ->assertSee('Delete “Open day”?', false);
    }

    // ---- publications, partners, home content ------------------------------------------------

    public function test_deleting_a_publication(): void
    {
        $row = ['type' => 'Report', 'author' => 'Lab', 'description' => 'd', 'file_link' => 'r.pdf', 'admin_id' => $this->user->id];
        $id = DB::table('publications')->insertGetId(['title' => 'Hive report'] + $row);
        $other = DB::table('publications')->insertGetId(['title' => 'Dataset'] + $row);

        $this->actingAs($this->user)->post('/deletepublication', ['id' => $id])
            ->assertRedirect('/publicationscontent')
            ->assertSessionHas('success', 'publication deleted successfully!');

        $this->assertDatabaseMissing('publications', ['id' => $id]);
        $this->assertDatabaseHas('publications', ['id' => $other]);

        $this->actingAs($this->user)->get('/publicationscontent')->assertOk()
            ->assertSee('action="/deletepublication"', false)
            ->assertSee('Delete “Dataset”?', false);
    }

    public function test_deleting_a_partner(): void
    {
        $id = DB::table('partners')->insertGetId(['name' => 'UNCST', 'description' => 'd']);
        $other = DB::table('partners')->insertGetId(['name' => 'Makerere', 'description' => 'd']);

        $this->actingAs($this->user)->post('/deletepartner', ['id' => $id])
            ->assertRedirect('/homecontent')
            ->assertSessionHas('success', 'partner deleted successfully!');

        $this->assertDatabaseMissing('partners', ['id' => $id]);
        $this->assertDatabaseHas('partners', ['id' => $other]);
    }

    public function test_each_home_content_trash_icon_clears_only_its_own_value(): void
    {
        $site = DB::table('website_content')->value('id');
        $values = [
            'logo' => 'logo.png', 'banner' => 'banner.png', 'Labname' => 'IoT Lab', 'vision' => 'V', 'motto' => 'M',
            'mission' => 'Mi', 'objectives' => 'O', 'team_leader_word' => 'W',
        ];
        DB::table('website_content')->where('id', $site)->update($values);

        $html = $this->actingAs($this->user)->get('/homecontent')->assertOk()->getContent();
        foreach (array_keys($values) as $field) {
            $this->assertStringContainsString('name="field" value="'.$field.'"', $html, "no trash form for $field");
        }

        foreach (array_keys($values) as $field) {
            $this->actingAs($this->user)->post('/clearhomefield', ['id' => $site, 'field' => $field])
                ->assertRedirect('/homecontent')
                ->assertSessionHas('success');

            $row = (array) DB::table('website_content')->find($site);
            $this->assertNull($row[$field], "$field was not cleared");
            unset($values[$field]);
            foreach ($values as $kept => $value) {
                $this->assertSame($value, $row[$kept], "clearing $field also changed $kept");
            }
        }
    }

    public function test_only_the_listed_home_content_values_can_be_cleared(): void
    {
        $site = DB::table('website_content')->value('id');
        DB::table('website_content')->where('id', $site)->update(['company_email' => 'lab@x.org']);

        foreach (['company_email', 'id', 'twitter_visible', ''] as $field) {
            $this->actingAs($this->user)->from('/homecontent')->post('/clearhomefield', ['id' => $site, 'field' => $field])
                ->assertSessionHasErrors('field');
        }
        $this->assertSame('lab@x.org', DB::table('website_content')->value('company_email'));
    }

    public function test_visitors_cannot_delete_publications_partners_or_home_content(): void
    {
        $this->post('/deletepublication', ['id' => 1])->assertRedirect('/login');
        $this->post('/deletepartner', ['id' => 1])->assertRedirect('/login');
        $this->post('/clearhomefield', ['id' => 1, 'field' => 'logo'])->assertRedirect('/login');

        $this->assertSame('logo.png', DB::table('website_content')->value('logo'));
    }
}
