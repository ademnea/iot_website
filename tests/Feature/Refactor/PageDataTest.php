<?php

namespace Tests\Feature\Refactor;

use Illuminate\Support\Facades\DB;
use Tests\Feature\Fixes\FixesTestCase;

// Pins down what each page receives, so the controller refactor can't change what's shown.
class PageDataTest extends FixesTestCase
{
    public function test_team_page_groups_active_members_by_role(): void
    {
        $ids = [];
        foreach (['leader', 'researcher', 'phd_stdt', 'masters_stdt', 'admin', 'intern'] as $i => $role) {
            $ids[$role] = $this->member(['role' => $role, 'email' => "$role@x.org", 'phone' => "07000000$i"]);
        }
        $this->member(['role' => 'researcher', 'status' => 'Inactive', 'email' => 'gone@x.org', 'phone' => '0709999999']);

        $ids_of = fn ($rows) => $rows->pluck('id')->all();
        $this->get('/about')->assertOk()
            ->assertViewHas('teams', fn ($r) => $ids_of($r) === [$ids['leader'], $ids['researcher']])
            ->assertViewHas('PhDs', fn ($r) => $ids_of($r) === [$ids['phd_stdt']])
            ->assertViewHas('masters', fn ($r) => $ids_of($r) === [$ids['masters_stdt']])
            ->assertViewHas('admins', fn ($r) => $ids_of($r) === [$ids['admin']])
            ->assertViewHas('interns', fn ($r) => $ids_of($r) === [$ids['intern']]);
    }

    public function test_every_public_page_gets_the_site_content(): void
    {
        foreach (['/', '/about', '/projects', '/publications', '/news', '/partners', '/gallery',
                  '/team_gallery', '/project_gallery', '/prototypes_gallery', '/events_gallery', '/login'] as $page) {
            $this->get($page)->assertOk()->assertViewHas('contents', fn ($c) => $c->first()?->Labname === 'IoT Lab');
        }
    }

    public function test_admin_news_list_shows_each_event_once_with_a_working_edit_link(): void
    {
        $admin = $this->admin();
        $event = fn (string $status) => DB::table('events')->insertGetId([
            'title' => "$status event", 'status' => $status, 'description' => 'd', 'admin_id' => $admin->id,
        ]);
        DB::table('event_photos')->insert(['photo' => 'unrelated.jpg', 'event_id' => $event('Ongoing')]);
        $past = $event('Past');
        // two photos; the photo rows keep their default status (Ongoing), the event's own status decides.
        DB::table('event_photos')->insert([['photo' => 'a.jpg', 'event_id' => $past], ['photo' => 'b.jpg', 'event_id' => $past]]);

        $this->actingAs($admin)->get('/newscontent')->assertOk()
            ->assertViewHas('onevents', fn ($r) => $r->pluck('title')->all() === ['Ongoing event'])
            ->assertViewHas('pastevents', fn ($r) => $r->pluck('id')->all() === [$past])
            ->assertSee('href="/editnews?id='.$past.'"', false);
    }
}
