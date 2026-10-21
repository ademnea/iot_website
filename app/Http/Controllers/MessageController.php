<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

// The public contact form on Team & contact. Rate limited by the 'contact' limiter
// (RouteServiceProvider). Messages are saved to the messages table.
class MessageController extends Controller
{
    // POST /insert_message
    public function store(Request $request)
    {
        $request->validate([
            'name' => 'required|string|max:255',
            'email' => 'required|email|max:255',
            'phone' => 'nullable|string|max:50',
            'message' => 'required|string|max:255',
        ]);

        DB::table('messages')->insert([
            'name' => $request->name,
            'email' => $request->email,
            'phone' => $request->phone,
            'message' => $request->message,
            'created_at' => now(),
            'updated_at' => now(),
        ]);

        return redirect('/about#contactus')->with('message', 'Thanks for the feedback, '.$request->name.'.');
    }
}
