<?php

namespace Tests\Feature;

use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Facades\DB;
use Tests\TestCase;

class ExampleTest extends TestCase
{
    use RefreshDatabase;

    public function test_home_page_sends_a_fresh_install_to_the_admin_login(): void
    {
        // with no website_content yet, the home page asks an admin to log in and add it.
        $this->get('/')->assertRedirect('/login');
    }

    public function test_the_home_page_renders_once_content_exists(): void
    {
        DB::table('website_content')->insert(['Labname' => 'IoT Lab', 'logo' => 'logo.png', 'banner' => 'banner.png']);

        $this->get('/')->assertOk()->assertSee('IoT Lab');
    }
}
