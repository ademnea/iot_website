<?php

namespace App\Http\Controllers;
use Illuminate\Support\Facades\DB;
use Illuminate\Http\Request;

class restore_stuff extends Controller
{

    public function restore_member(Request $request){

        $id = $request->query('user_id');
 
       // return $id;
      //procede with delete actions ie change member status from Active to Inactive.
 
     DB::table('members')
     ->where('id', $id)
     ->update(['status' => 'Active']);
 
           return redirect('/oldteam')->with('success','the member was restored successfully');
 
     }
}
