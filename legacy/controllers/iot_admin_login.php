<?php

namespace App\Http\Controllers;

class iot_admin_login extends Controller
{
    // The "add another admin" form (admins only). $contents comes from the composer in
    // AppServiceProvider; insert_admin redirects back here with a 'message' flash.
    public function registerme()
    {
        return view('register', ['status' => session('message', '')]);
    }
}
