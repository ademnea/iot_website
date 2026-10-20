<?php

namespace App\Http\Controllers;
use Illuminate\Http\Request;
use App\Models\website_content;

class iot_admin_login extends Controller
{
    public function registerme(){

        $contents = website_content::all();
        // insert_admin redirects back here with a 'message' flash on failure or success.
        return view('register', ['contents' => $contents, 'status' => session('message', '')]);
    }

}
