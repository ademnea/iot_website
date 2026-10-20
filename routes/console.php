<?php

use Illuminate\Foundation\Inspiring;
use App\Models\User;
use Illuminate\Support\Facades\Artisan;
use Illuminate\Support\Facades\Hash;

/*
|--------------------------------------------------------------------------
| Console Routes
|--------------------------------------------------------------------------
|
| This file is where you may define all of your Closure based console
| commands. Each Closure is bound to a command instance allowing a
| simple approach to interacting with each command's IO methods.
|
*/

Artisan::command('inspire', function () {
    $this->comment(Inspiring::quote());
})->purpose('Display an inspiring quote');

Artisan::command('admin:create {email} {--name=Admin}', function (string $email) {
    if (User::where('email', $email)->exists()) {
        $this->error("An account with {$email} already exists.");
        return 1;
    }

    $password = $this->secret('Password (min 8 characters)');
    if (strlen((string) $password) < 8 || $password !== $this->secret('Confirm password')) {
        $this->error('Passwords must match and be at least 8 characters.');
        return 1;
    }

    User::forceCreate([
        'name' => $this->option('name'),
        'email' => $email,
        'password' => Hash::make($password),
    ]);
    $this->info("Admin {$email} created.");
})->purpose('Create an admin account with a hashed password');

Artisan::command('admin:hash-passwords', function () {
    // Older versions of the app stored admin passwords as plain text.
    $count = 0;
    User::all()->each(function (User $user) use (&$count) {
        if (Hash::info($user->password)['algo'] === null) {
            $user->forceFill(['password' => Hash::make($user->password)])->save();
            $count++;
        }
    });
    $this->info("Hashed {$count} plain-text password(s).");
})->purpose('Hash any admin passwords still stored as plain text');

Artisan::command('admin:password {email}', function (string $email) {
    // For when an admin is locked out and password-reset email isn't available.
    $user = User::where('email', strtolower($email))->first();
    if (! $user) {
        $this->error("No admin account uses {$email}.");
        return 1;
    }

    $password = $this->secret('New password (min 8 characters)');
    if (strlen((string) $password) < 8 || $password !== $this->secret('Confirm new password')) {
        $this->error('Passwords must match and be at least 8 characters.');
        return 1;
    }

    $user->forceFill(['password' => Hash::make($password)])->save();
    $this->info("Password changed for {$email}. They can log in with it now.");
})->purpose("Set a new password for an admin (when reset emails can't be sent)");

Artisan::command('admin:reset-2fa {email}', function (string $email) {
    // For an admin who lost their authenticator app and their recovery codes.
    $user = User::where('email', strtolower($email))->first();
    if (! $user) {
        $this->error("No admin account uses {$email}.");
        return 1;
    }

    $user->forceFill(['two_factor_secret' => null, 'two_factor_recovery_codes' => null, 'two_factor_confirmed_at' => null])->save();
    $this->info("Two-factor authentication is off for {$email}. They can log in with their password and set it up again under Profile & security.");
})->purpose('Turn off two-factor authentication for an admin who lost their device');
