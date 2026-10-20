<?php

namespace Tests\Feature\Fixes;

use Illuminate\Http\UploadedFile;
use Illuminate\Support\Facades\DB;

// docs/fixes/003-uploads.md and 004-record-ownership.md
class UploadFixesTest extends FixesTestCase
{
    public function test_event_images_must_be_images(): void
    {
        $this->actingAs($this->admin())->post('/insert_news', [
            'title' => 'Hackathon', 'description' => 'Two days',
            'images' => [UploadedFile::fake()->create('shell.php', 1, 'application/x-php')],
        ])->assertSessionHasErrors('images.0');

        $this->assertDatabaseCount('events', 0);
        $this->assertFileDoesNotExist(public_path('images/events/shell.php'));
    }

    public function test_uploads_get_generated_names_so_they_cannot_collide(): void
    {
        $admin = $this->admin();

        foreach (['First', 'Second'] as $name) {
            $this->actingAs($admin)->post('/insertpartner', [
                'name' => $name, 'description' => 'Partner',
                'image' => UploadedFile::fake()->image('logo.png'),
            ])->assertRedirect('/homecontent');
        }

        $logos = DB::table('partners')->pluck('logo');
        $logos->each(fn ($logo) => $this->track('images/partners', $logo));

        $this->assertCount(2, $logos->unique());
        $this->assertNotContains('logo.png', $logos);
        $logos->each(fn ($logo) => $this->assertFileExists(public_path('images/partners/'.$logo)));
    }

    public function test_owner_is_the_logged_in_admin_not_a_form_field(): void
    {
        $admin = $this->admin();
        $other = $this->admin();

        $this->actingAs($admin)->post('/insert_news', [
            'title' => 'Open day', 'description' => 'Visit the lab', 'admin' => $other->id,
        ])->assertRedirect('/newscontent');

        $this->assertDatabaseHas('events', ['title' => 'Open day', 'admin_id' => $admin->id]);
    }

    public function test_edited_partner_logo_goes_to_the_partners_folder(): void
    {
        $admin = $this->admin();
        $id = DB::table('partners')->insertGetId([
            'name' => 'Old', 'description' => 'Partner', 'created_at' => now(), 'updated_at' => now(),
        ]);

        $this->actingAs($admin)->post('/editpartner_route', [
            'id' => $id, 'fullname' => 'New name', 'image' => UploadedFile::fake()->image('new.png'),
        ])->assertRedirect('/homecontent');

        $logo = DB::table('partners')->where('id', $id)->value('logo');
        $this->track('images/partners', $logo);
        $this->assertFileExists(public_path('images/partners/'.$logo));
    }

    public function test_upload_folders_block_script_execution(): void
    {
        foreach (['images', 'documents'] as $dir) {
            $this->assertStringContainsString('Require all denied', file_get_contents(public_path("$dir/.htaccess")));
        }
    }
}
