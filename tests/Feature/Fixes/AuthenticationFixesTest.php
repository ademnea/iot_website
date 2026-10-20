<?php

namespace Tests\Feature\Fixes;

use App\Models\User;
use Illuminate\Support\Facades\Hash;

// docs/fixes/002-authentication.md
class AuthenticationFixesTest extends FixesTestCase
{
    public function test_guests_cannot_open_or_submit_admin_registration(): void
    {
        $this->get('/register')->assertRedirect('/login');
        $this->get('/registeradminform')->assertRedirect('/login');

        $this->post('/registeradmin', [
            'name' => 'Intruder', 'email' => 'x@evil.test',
            'password' => 'password123', 'password_confirmation' => 'password123',
        ])->assertRedirect('/login');

        $this->assertDatabaseMissing('users', ['email' => 'x@evil.test']);
    }

    public function test_fortify_public_registration_is_disabled(): void
    {
        $this->post('/register', [
            'name' => 'Intruder', 'email' => 'y@evil.test',
            'password' => 'password123', 'password_confirmation' => 'password123',
        ]);

        $this->assertDatabaseMissing('users', ['email' => 'y@evil.test']);
    }

    public function test_admin_can_register_another_admin_with_a_hashed_password(): void
    {
        $this->actingAs($this->admin())->post('/registeradmin', [
            'name' => 'New Admin', 'email' => 'new@lab.test',
            'password' => 'password123', 'password_confirmation' => 'password123',
        ])->assertRedirect('/aboutuscontent');

        $user = User::where('email', 'new@lab.test')->firstOrFail();
        $this->assertNotSame('password123', $user->password);
        $this->assertTrue(Hash::check('password123', $user->password));
    }

    public function test_registration_errors_are_shown_on_the_form(): void
    {
        $admin = $this->admin();

        $this->actingAs($admin)->post('/registeradmin', [
            'name' => 'Dup', 'email' => $admin->email,
            'password' => 'password123', 'password_confirmation' => 'password123',
        ])->assertRedirect('/register');

        $this->actingAs($admin)->get('/register')->assertSee('already been taken');
    }

    public function test_login_checks_the_password_hash(): void
    {
        $user = User::factory()->create(['password' => Hash::make('correct-horse')]);

        $this->post('/login', ['email' => $user->email, 'password' => 'wrong'])
            ->assertSessionHasErrors('email');
        $this->assertGuest();

        // emails are matched case-insensitively (fortify.lowercase_usernames).
        $this->post('/login', ['email' => strtoupper($user->email), 'password' => 'correct-horse'])
            ->assertRedirect('/dashboard');
        $this->assertAuthenticatedAs($user);
    }

    public function test_branded_login_page_is_the_login_page(): void
    {
        $this->get('/login')->assertOk()->assertSee('Admin Login')->assertSee('action="/login"', false);
        $this->get('/oldlogin')->assertRedirect('/login');
        $this->post('/mylogin', [])->assertNotFound();
    }

    public function test_login_requires_the_two_factor_code_when_2fa_is_enabled(): void
    {
        $user = User::factory()->create(['password' => Hash::make('correct-horse')]);
        $user->forceFill([
            'two_factor_secret' => encrypt('SECRETSECRETSECR'),
            'two_factor_recovery_codes' => encrypt(json_encode(['code-1'])),
            'two_factor_confirmed_at' => now(),
        ])->save();

        $this->post('/login', ['email' => $user->email, 'password' => 'correct-horse'])
            ->assertRedirect('/two-factor-challenge');
        $this->assertGuest();
    }

    public function test_get_login_with_credentials_in_the_url_is_gone(): void
    {
        $user = User::factory()->create(['password' => Hash::make('correct-horse')]);

        $this->get('/logmein?email='.$user->email.'&password=correct-horse')->assertNotFound();
        $this->assertGuest();
    }

    public function test_login_is_rate_limited(): void
    {
        $user = User::factory()->create();

        for ($i = 0; $i < 5; $i++) {
            $this->post('/login', ['email' => $user->email, 'password' => 'wrong']);
        }

        $this->post('/login', ['email' => $user->email, 'password' => 'wrong'])->assertStatus(429);
    }

    public function test_failed_logins_do_not_use_up_the_contact_form_limit(): void
    {
        $user = User::factory()->create();

        for ($i = 0; $i < 6; $i++) {
            $this->post('/login', ['email' => $user->email, 'password' => 'wrong']);
        }

        // same IP, different limiter: the contact form still accepts a message.
        $this->post('/insert_message', ['name' => 'Visitor', 'email' => 'v@example.test', 'message' => 'Hello'])
            ->assertRedirect('/about#contactus');
    }

    // docs/fixes/014-show-hide-contacts-and-socials.md (password reset without email)
    public function test_forgot_password_explains_when_email_is_not_set_up(): void
    {
        $user = User::factory()->create();
        // a mail server that can't be reached, like the default MAIL_HOST=mailpit on a server without it
        config(['mail.default' => 'smtp', 'mail.mailers.smtp.host' => 'mail.invalid', 'mail.mailers.smtp.port' => 2525, 'mail.mailers.smtp.timeout' => 2]);

        $this->from('/forgot-password')->post('/forgot-password', ['email' => $user->email])
            ->assertRedirect('/forgot-password')
            ->assertSessionHasErrors(['email' => "The reset email couldn't be sent because email isn't set up on this server yet. Ask the site administrator to reset your password."]);
    }

    public function test_admin_password_command_sets_a_new_password(): void
    {
        $user = User::factory()->create();

        $this->artisan('admin:password', ['email' => strtoupper($user->email)])
            ->expectsQuestion('New password (min 8 characters)', 'brand-new-pass')
            ->expectsQuestion('Confirm new password', 'brand-new-pass')
            ->assertSuccessful();

        $this->assertTrue(Hash::check('brand-new-pass', $user->fresh()->password));
    }

    public function test_reset_2fa_command_lets_a_locked_out_admin_log_in_again(): void
    {
        $user = User::factory()->create(['password' => Hash::make('correct-horse')]);
        $user->forceFill(['two_factor_secret' => encrypt('SECRETSECRETSECR'), 'two_factor_recovery_codes' => encrypt('[]'), 'two_factor_confirmed_at' => now()])->save();

        $this->artisan('admin:reset-2fa', ['email' => $user->email])->assertSuccessful();

        $this->post('/login', ['email' => $user->email, 'password' => 'correct-horse'])->assertRedirect('/dashboard');
    }

    public function test_hash_passwords_command_hashes_plain_text_passwords(): void
    {
        $user = User::factory()->create();
        // simulate an account created by the old code (written directly: the model would hash it).
        \Illuminate\Support\Facades\DB::table('users')->where('id', $user->id)->update(['password' => 'legacy-plain']);

        $this->artisan('admin:hash-passwords')->expectsOutput('Hashed 1 plain-text password(s).')->assertSuccessful();

        $this->assertTrue(Hash::check('legacy-plain', $user->fresh()->password));
        $this->artisan('admin:hash-passwords')->expectsOutput('Hashed 0 plain-text password(s).');
    }
}
