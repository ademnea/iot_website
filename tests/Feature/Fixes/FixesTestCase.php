<?php

namespace Tests\Feature\Fixes;

use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Facades\DB;
use Tests\TestCase;

// Shared setup for the regression tests that cover docs/fixes.
abstract class FixesTestCase extends TestCase
{
    use RefreshDatabase;

    // files the app wrote under public/ during a test, removed afterwards.
    protected array $uploaded = [];

    protected function setUp(): void
    {
        parent::setUp();

        // every page reads the single website_content row for the navbar and footer.
        DB::table('website_content')->insert([
            'Labname' => 'IoT Lab', 'motto' => 'Motto', 'logo' => 'logo.png', 'banner' => 'banner.png',
            'created_at' => now(), 'updated_at' => now(),
        ]);
    }

    protected function tearDown(): void
    {
        foreach ($this->uploaded as $path) {
            @unlink(public_path($path));
        }
        parent::tearDown();
    }

    protected function admin(): User
    {
        return User::factory()->create();
    }

    protected function member(array $overrides = []): int
    {
        return DB::table('members')->insertGetId(array_merge([
            'fname' => 'Ada', 'lname' => 'Lovelace', 'email' => uniqid().'@lab.test', 'phone' => uniqid(),
            'designation' => 'Researcher', 'role' => 'researcher', 'status' => 'Active',
            'created_at' => now(), 'updated_at' => now(),
        ], $overrides));
    }

    protected function track(string $dir, ?string $name): void
    {
        if ($name) {
            $this->uploaded[] = "$dir/$name";
        }
    }
}
