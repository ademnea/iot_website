<?php

namespace App\Http\Controllers;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Session;
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
             return redirect('/login')->with('status','Wrong credentials!');
         }
       

    }

}
