<?php

namespace App\Http\Controllers;

use App\Models\news;
use App\Models\projects;
use App\Models\prototypes;
use App\Models\website_content;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Str;
use App\Models\User;


class insert_data extends Controller
{
    //lets insert an admin

    public function insert_admin(Request $request){
     $password = $request->input('password');
     $email = $request->input('email');
     $password_confirm = $request->input('password_confirmation');
     $rememberToken = Str::random(60);

     $user = User::where('email', $email)->first();
     if ($user) {
        return redirect('/register')->with('message','this email already exists');
     }


     if($password != $password_confirm){

        return redirect('/register')->with('message','these passwords do not match');
     }


      DB::table('users')->insert([
        'name' => $request->name,
        'email' => $email,
        'password' => $password,
        'remember_token' =>  $rememberToken,
    ]);

    return redirect('/aboutuscontent')->with('success','admin registered successfully');


    }




    //this function inserts data for mission, objectives, and word from the team leader.
   public function insert_home_form1(Request $request){

    
     //lets first query to see whether the table has some data yet
     $existingData = DB::table('website_content')->first();
     
      // return $request->input();
         $radio = $request->input('homecontent');
         $content = $request->input('content');
         $motto = $request->input('motto');

         if($motto){

            DB::table('website_content')->insert([
                'motto' => $motto,  
            ]);

         }
       
 
        if ($existingData) {

            if ($radio == "mission") {
              
                 // The table has data, update the existing row with the new data
            DB::table('website_content')->where('id', $existingData->id)->update([

                'mission' => $content,
            ]);
            
            }
            else if($radio == "objectives"){


                DB::table('website_content')->where('id', $existingData->id)->update([
                   
                    'objectives' => $content,

                ]);
            }
           else{

            DB::table('website_content')->where('id', $existingData->id)->update([
                   
                'team_leader_word' => $content,

            ]);
    
           }
           
        }
        
        
        else {
            // The table has no data, create a new row

            if ($radio == "mission") {
              
                // The table has data, update the existing row with the new data
                DB::table('website_content')->insert([
                    'mission' => $content,
                    // add more fields to insert as needed
                ]);
           
           }
           else if($radio == "objectives"){


            DB::table('website_content')->insert([
                'objectives' => $content,
                // add more fields to insert as needed
            ]);
           }
          else{

            DB::table('website_content')->insert([
                'team_leader_word' => $content,
                // add more fields to insert as needed
            ]);
   
          }

        }


      return redirect('/homecontent')->with('success', 'home content added successfully!');

   }

   public function insert_contacts(Request $request){

    $request->validate(['logo' => 'required|image|mimes:png,jpg,jpeg|max:2048']);
    $picname = $request->file('logo')->getClientOriginalName();
    $request->logo->move(public_path('images/logo'), $picname);

     
    
    
       //lets first query to see whether the table has some data yet
         $existingData = DB::table('website_content')->first();
 
    
           if ($existingData) {
                 
                // The table has data, update the existing row with the new data
               DB::table('website_content')->where('id', $existingData->id)->update([
   
                   'logo' => $picname,
                   'physical_address' => $request->address,
                   'company_telephone' => $request->telephone,
                   'company_email' => $request->email,
                   'twitter' => $request->twitter,
                   'facebook' => $request->facebook,
                   'linkedin' => $request->linkedin,
                   'youtube' => $request->youtube,
                   'instagram' => $request->instagram,
               ]);

 }    else{

    DB::table('website_content')->insert([
        'logo' => $picname,
        'physical_address' => $request->address,
        'company_telephone' => $request->telephone,
        'company_email' => $request->email,
        'twitter' => $request->twitter,
        'facebook' => $request->facebook,
        'linkedin' => $request->linkedin,
        'youtube' => $request->youtube,
        'instagram' => $request->instagram,  
    ]);

}

       return redirect('/homecontent')->with('success', 'home content added successfully!');

}


  // this function inserts partners into the database
   public function insert_partner(Request $request){

    $admin = $request->input('admin');

    $request->validate(['image' => 'required|image|mimes:png,jpg,jpeg|max:2048']);
    $picname = $request->file('image')->getClientOriginalName();
    $request->image->move(public_path('images/partners'), $picname);

    
            DB::table('partners')->insert([

                'logo' => $picname,
                'name' => $request->name,
                'description' => $request->description,
                'website_link' => $request->website,
                'admin_id' => $admin,
                  
            ]);

    
            return redirect('/homecontent')->with('success', 'partner added successfully!');

}



    public Function insert_news(Request $request){

        $admin = $request->input('admin');

    $request->validate(['image' => 'required|image|mimes:png,jpg,jpeg|max:2048']);
    $picname = $request->file('image')->getClientOriginalName();
    $request->image->move(public_path('images/events'), $picname);

    
            DB::table('events')->insert([

                'title' => $request->title,
                'start_date' => $request->start_date,
                'end_date' => $request->end_date,
                'description' => $request->description,
                'venue' => $request->venue,
                'admin_id' => $admin,
                  
            ]);

            //lets get the latest event and add this image to it.
            $latestRecord = news::latest()->first();
            $latestRecordId = $latestRecord->id;

            //now lets insert the image in the event images table.
            DB::table('event_photos')->insert([
                'photo' => $picname,
                'event_id' => $latestRecordId,     
            ]);

    
            return redirect('/newscontent')->with('success', 'event added successfully!');
}



public Function insert_project(Request $request){


          $admin = $request->input('admin');

                //this code uploads the picture from the form.
            $request->validate(['image' => 'required|image|mimes:png,jpg,jpeg|max:2048']);
            $picname = $request->file('image')->getClientOriginalName();
            $request->image->move(public_path('images/projects'), $picname);

            DB::table('projects')->insert([

                'name' => $request->projectName,
                'venue' => $request->venue,
                'participants' => $request->participants,
                'description' => $request->description,
                'start_date' => $request->start_date,
                'end_date' => $request->end_date,
                'website' => $request->project_link,
                'admin_id' => $admin,
                  
            ]);


    //lets get the latest project and add this image to it.
    $latestRecord = projects::latest()->first();
    $latestRecordId = $latestRecord->id;

    //now lets insert the image in the event images table.
    DB::table('project_photos')->insert([
        'photo' => $picname,
        'project_id' => $latestRecordId,     
    ]);

            return redirect('/projectscontent')->with('success', 'project added successfully!');

   // return $request->input();
}


public Function insert_publication(Request $request){
              
             $admin = $request->input('admin');

              $type = $request->input('type');
    
                   //this code uploads the publication file from the form.
                   // Validate and capture document file
            $request->validate([
                'file' => 'required|mimes:csv,pdf,doc,docx,xls,xlsx|max:2048',
            ]);
            $filename = $request->file('file')->getClientOriginalName();
            $request->file('file')->move(public_path('documents/publications'), $filename);


            DB::table('publications')->insert([

                'title' => $request->title,
                'author' => $request->author,
                'type' => $type,
                'description' => $request->description,
                'file_link' => $filename,
                'admin_id' => $admin,
                  
            ]);

            return redirect('/publicationscontent')->with('success', 'publication added successfully!');

    //return $request->input();
}


public Function insert_team(Request $request){
   
         $admin = $request->input('admin');

                //this code uploads the picture from the form.
            $request->validate(['image' => 'required|image|mimes:png,jpg,jpeg|max:2048']);
            $picname = $request->file('image')->getClientOriginalName();
            $request->image->move(public_path('images/team'), $picname);

            DB::table('members')->insert([

                'fname' => $request->firstname,
                'lname' => $request->lastname,
                'email' => $request->email,
                'phone' => $request->phone,
                'designation' => $request->designation,
                'role' => $request->role,
                'bio' => $request->bio,
                'photo' => $picname,
                'registered_by' => $admin,        
                  
            ]);
    
          return redirect('/aboutuscontent')->with('success', $request->firstname.' '.'has been registered successfully!');

}

//lets now insert the prototype

public Function insert_prototype(Request $request){


    $admin = $request->input('admin');
    $projectid = $request->input('project_id');;

                //this code uploads the picture from the form.
            $request->validate(['image' => 'required|image|mimes:png,jpg,jpeg|max:2048']);
            $picname = $request->file('image')->getClientOriginalName();
            $request->image->move(public_path('images/prototypes'), $picname);

            DB::table('prototypes')->insert([

                'name' => $request->title,
                'description' => $request->description,
                'project_id' => $projectid,
                  
            ]);


    //lets get the latest project and add this image to it.
    $latestRecord = prototypes::latest()->first();
    $latestRecordId = $latestRecord->id;

    //now lets insert the image in the event images table.
    DB::table('prototype_photos')->insert([
        'photo' => $picname,
        'prototype_id' => $latestRecordId,     
    ]);

            return redirect('/publicationscontent')->with('success', 'prototype added successfully!');

   // return $request->input();
}

}
