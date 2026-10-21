<?php

namespace App\Http\Controllers;

use App\Models\event_photos;
use App\Models\news;
use App\Models\projects;
use App\Models\prototypes;
use App\Models\website_content;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Str;


class insert_data extends Controller
{
    //lets insert an admin

    public function insert_admin(Request $request){

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
        'remember_token' =>  Str::random(60),
        'created_at' => now(),
        'updated_at' => now(),
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
         $Labname = $request->input('labname');

       
 
        if ($existingData) {

            DB::table('website_content')->where('id', $existingData->id)->update([

                'motto' => $motto,
                'Labname' => $Labname,
                'vision' => $content,
                'updated_at' => now(),

            ]);
           
        }
          
        else {

            DB::table('website_content')->insert([
                'motto' => $motto,
                'Labname' => $Labname,
                'vision' => $content,
                'created_at' => now(),
                'updated_at' => now(),
                // add more fields to insert as needed
            ]);

        }


      return redirect('/homecontent')->with('success', 'home content added successfully!');

   }

   public function insert_contacts(Request $request){

    $request->validate(['logo' => 'required|image|mimes:png,jpg,jpeg|max:11000']);
    $picname = $request->file('logo')->hashName();
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
                   'updated_at' => now(),
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
        'created_at' => now(),
        'updated_at' => now(), 
    ]);

}

       return redirect('/homecontent')->with('success', 'home content added successfully!');

}


  // this function inserts partners into the database
   public function insert_partner(Request $request){

    $request->validate([
        'name' => 'required|string|max:255',
        'description' => 'required|string',
        'website' => 'nullable|string|max:255',
        'image' => 'required|image|mimes:png,jpg,jpeg|max:11000',
    ]);

    $picname = $request->file('image')->hashName();
    $request->image->move(public_path('images/partners'), $picname);

    
            DB::table('partners')->insert([

                'logo' => $picname,
                'name' => $request->name,
                'description' => $request->description,
                'website_link' => $request->website,
                'admin_id' => auth()->id(),
                'created_at' => now(),
                'updated_at' => now(),
                  
            ]);

    
            return redirect('/homecontent')->with('success', 'partner added successfully!');

}



    public Function insert_news(Request $request){

        $request->validate([
            'title' => 'required|string|max:255',
            'description' => 'required|string',
            'start_date' => 'nullable|date',
            'end_date' => 'nullable|date|after_or_equal:start_date',
            'venue' => 'nullable|string|max:255',
            'link' => 'nullable|string|max:255',
            'images' => 'nullable|array',
            'images.*' => 'image|mimes:png,jpg,jpeg|max:11000',
        ]);

            //this method below inserts and gets id at the same time
            $latestRecordId = DB::table('events')->insertGetId([

                'title' => $request->title,
                'start_date' => $request->start_date,
                'end_date' => $request->end_date,
                'description' => $request->description,
                'venue' => $request->venue,
                'article_link' => $request->link,
                'admin_id' => auth()->id(),
                'created_at' => now(),
                'updated_at' => now(),
                  
            ]);

            //now lets insert the images in the event images table.
          
            if ($request->hasFile('images')) {
            foreach ($request->images as $file) {

               $picname = $file->hashName();
               $file->move(public_path('images/events'), $picname);

                $fileModel = new event_photos;
                $fileModel->photo = $picname;
                $fileModel->event_id = $latestRecordId;
                $fileModel->save();          
                     
        }
    }
        

            return redirect('/newscontent')->with('success', 'event added successfully!');
}



public Function insert_project(Request $request){

          $request->validate([
              'projectName' => 'required|string|max:255',
              'description' => 'required|string',
              'venue' => 'nullable|string|max:255',
              'participants' => 'nullable|string|max:255',
              'start_date' => 'nullable|date',
              'end_date' => 'nullable|date|after_or_equal:start_date',
              'project_link' => 'nullable|string|max:255',
              'image' => 'nullable|image|mimes:png,jpg,jpeg|max:11000',
          ]);

          $picname = null;

                //this code uploads the picture from the form.

            if($request->hasFile('image')){
            $picname = $request->file('image')->hashName();
            $request->image->move(public_path('images/projects'), $picname);
                }

            //this method below inserts and gets id at the same time
           $latestRecordId = DB::table('projects')->insertGetId([

                'name' => $request->projectName,
                'venue' => $request->venue,
                'participants' => $request->participants,
                'description' => $request->description,
                'start_date' => $request->start_date,
                'end_date' => $request->end_date,
                'website' => $request->project_link,
                'admin_id' => auth()->id(),
                'created_at' => now(),
                'updated_at' => now(),
                  
            ]);

    //now lets insert the image in the project images table.
    DB::table('project_photos')->insert([
        'photo' => $picname,
        'project_id' => $latestRecordId,
        'created_at' => now(),
        'updated_at' => now(),     
    ]);

            return redirect('/projectscontent')->with('success', 'project added successfully!');

}


public Function insert_publication(Request $request){

            $request->validate([
                'title' => 'required|string|max:255',
                'author' => 'required|string|max:255',
                'type' => 'required|string|max:255',
                'description' => 'required|string',
                'date_published' => 'nullable|date',
                'photo' => 'required|image|mimes:png,jpg,jpeg|max:11000',
                'file' => 'required|mimes:csv,pdf,doc,docx,xls,xlsx|max:11000',
            ]);

            $picname = $request->file('photo')->hashName();
            $request->photo->move(public_path('images/publications'), $picname);

            $filename = $request->file('file')->hashName();
            $request->file('file')->move(public_path('documents/publications'), $filename);


            DB::table('publications')->insert([

                'title' => $request->title,
                'author' => $request->author,
                'photo' => $picname,
                'date_published' => $request->date_published,
                'type' => $request->type,
                'description' => $request->description,
                'file_link' => $filename,
                'admin_id' => auth()->id(),
                'created_at' => now(),
                'updated_at' => now(),
                  
            ]);

            return redirect('/publicationscontent')->with('success', 'publication added successfully!');

}


public Function insert_team(Request $request){

            $request->validate([
                'firstname' => 'required|string|max:255',
                'lastname' => 'required|string|max:255',
                'email' => 'required|email|max:255|unique:members,email',
                'phone' => 'required|string|max:255|unique:members,phone',
                'designation' => 'required|string|max:255',
                'role' => 'nullable|string|max:255',
                'bio' => 'nullable|string',
                'image' => 'required|image|mimes:png,jpg,jpeg|max:11000',
            ]);

                //this code uploads the picture from the form.
            $picname = $request->file('image')->hashName();
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
                // members.registered_by references members.id, not users.id, so the
                // logged-in admin can't be stored here.
                'registered_by' => null,
                'created_at' => now(),
                'updated_at' => now(),        
                  
            ]);
    
          return redirect('/aboutuscontent')->with('success', $request->firstname.' '.'has been registered successfully!');

}

//lets now insert the prototype

public Function insert_prototype(Request $request){

    $request->validate([
        'title' => 'required|string|max:255',
        'description' => 'required|string',
        'project_id' => 'nullable|exists:projects,id',
        'image' => 'nullable|image|mimes:png,jpg,jpeg|max:11000',
    ]);

    $picname = null;

                //this code uploads the picture from the form.

                if($request->hasFile('image')){
            $picname = $request->file('image')->hashName();
            $request->image->move(public_path('images/prototypes'), $picname);
                }

            //this method below inserts and gets id at the same time
            $latestRecordId = DB::table('prototypes')->insertGetId([

                'name' => $request->title,
                'description' => $request->description,
                'project_id' => $request->input('project_id') ?: null,
                'created_at' => now(),
                'updated_at' => now(),
                  
            ]);

    //now lets insert the image in the prototype images table.
    DB::table('prototype_photos')->insert([
        'photo' => $picname,
        'prototype_id' => $latestRecordId,
        'created_at' => now(),
        'updated_at' => now(),     
    ]);

            return redirect('/publicationscontent')->with('success', 'prototype added successfully!');

}


public Function insert_message(Request $request){

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

            return redirect('/about#contactus')->with('message','Thanks for the feedback,'.' '.$request->name.'.');

}

}
