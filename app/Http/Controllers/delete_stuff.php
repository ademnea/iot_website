<?php

namespace App\Http\Controllers;
use Illuminate\Support\Facades\DB;
use Illuminate\Http\Request;

class delete_stuff extends Controller
{
    //this will have delete functions. lets start with the team

    public function delete_member(Request $request){

       $id = $request->query('user_id');

      // return $id;
     //procede with delete actions ie change member status from Active to Inactive.

    DB::table('members')
    ->where('id', $id)
    ->update(['status' => 'Inactive']);

          
     return redirect('/aboutuscontent')->with('success','user was deleted successfully');

    }
}
