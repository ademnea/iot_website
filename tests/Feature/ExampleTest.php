<?php

namespace Tests\Feature;

use Illuminate\Foundation\Testing\RefreshDatabase;
use App\Models\User;
use Illuminate\Support\Facades\DB;
use Tests\TestCase;

class ExampleTest extends TestCase
{
    use RefreshDatabase;

    public function test_a_fresh_install_shows_the_home_page_not_the_login(): void
    {
        // with no website_content yet, visitors still get the home page under the lab's name.
        $this->get('/')->assertOk()
            ->assertSee(config('app.name'))
            ->assertDontSee("hasn't been added yet", false);
    }

    public function test_on_a_fresh_install_admins_are_pointed_to_add_home_content(): void
    {
        $this->actingAs(User::factory()->create())->get('/')->assertOk()
            ->assertSee('href="/register_home"', false)
            ->assertDontSee('/edithome?id=', false);
    }

    public function test_the_home_page_renders_once_content_exists(): void
    {
        DB::table('website_content')->insert(['Labname' => 'IoT Lab', 'logo' => 'logo.png', 'banner' => 'banner.png']);

        $this->get('/')->assertOk()->assertSee('IoT Lab');
    }
}
