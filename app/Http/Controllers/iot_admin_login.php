<?php

namespace App\Http\Controllers;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Session;
use App\Models\website_content;
use Illuminate\Support\Facades\Auth;

class iot_admin_login extends Controller
{
    public function login(Request $request){


      $email = $request->input('email');
      $password = $request->input('password');

      
          $user = User::where('email', $email)->first();
      
          if ($user && $password == $user->password) {
              Auth::login($user);
       
              $admin = DB::table('users')
              ->select('*')
              ->where('email', $email)
              ->first();
              
              Session::put('id',$admin->id);
              
    // User is authenticated, set session variables or redirect to dashboard
        return redirect('/dashboard')->with('greeting','Hello'.' '.$admin->name);
      
        }

     else {
        $contents = website_content::all();
        return view('auth/login', ['contents' => $contents, 'status' => 'Wrong Credentials']);

         }
       
    }

    public function logmein(){

        $contents = website_content::all();
        return view('auth/login', ['contents' => $contents, 'status' => '']);
    }

    public function registerme(){

        $contents = website_content::all();
        return view('register', ['contents' => $contents, 'status' => '']);
    }

}
