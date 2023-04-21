<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class insert_data extends Controller
{
    
   public function insert_home_form1(Request $request){

       return $request->input();

   }



   public function insert_home_form2(Request $request){

    return $request->input();

}


    public Function insert_news(Request $request){

        return $request->input();
}

public Function insert_project(Request $request){

    return $request->input();
}

public Function insert_publication(Request $request){

    return $request->input();
}

public Function insert_team(Request $request){

    return $request->input();
}



}
