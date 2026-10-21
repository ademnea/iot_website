<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Str;

// "Add another admin" (Dashboard). Only a logged-in admin reaches these routes; there is no
// public sign-up. Maintainers can also run `php artisan admin:create` on the server.
class AdminAccountController extends Controller
{
    // GET /register (also /registeradminform). $contents comes from the view composer.
    public function create()
    {
        // store redirects back here with a 'message' flash on failure.
        return view('register', ['status' => session('message', '')]);
    }

    // POST /registeradmin
    public function store(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'name' => 'required|string|max:255',
            'email' => 'required|email|max:255|unique:users,email',
            'password' => 'required|string|min:8|confirmed',
        ]);

        // the register view shows a single status line, so pass back the first error.
        if ($validator->fails()) {
            return redirect('/register')->withInput($request->except('password', 'password_confirmation'))
                ->with('message', $validator->errors()->first());
        }

        DB::table('users')->insert([
            'name' => $request->name,
            'email' => $request->email,
            'password' => Hash::make($request->password),
            'remember_token' => Str::random(60),
            'created_at' => now(),
            'updated_at' => now(),
        ]);

        return redirect('/aboutuscontent')->with('success', 'admin registered successfully');
    }
}
