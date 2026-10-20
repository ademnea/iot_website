<?php

namespace App\Http\Controllers;
use App\Models\news;
use App\Models\projects;
use App\Models\prototypes;
use App\Models\website_content;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;


class edit_data extends Controller
{
    //edit logo
    public function edit_logo(Request $request){
    
       // this code uploads the picture from the form.
        $request->validate(['image' => 'required|image|mimes:png,jpg,jpeg|max:11000']);
        $picname = $request->file('image')->hashName();
        $request->image->move(public_path('images/logo'), $picname);

        $id = $request->input('id');
    
        DB::table('website_content')
        ->where('id', $id)
        ->update([
            'logo' => $picname,
            // add more fields as needed
        ]);
    return redirect('/homecontent')->with('success', 'logo changed successfully!');

    }

        //edit banner
        public function edit_banner(Request $request){
    
            // this code uploads the picture from the form.
             $request->validate(['image' => 'required|image|mimes:png,jpg,jpeg|max:11000']);
             $picname = $request->file('image')->hashName();
             $request->image->move(public_path('images/banner'), $picname);
     
             $id = $request->input('id');
         
             DB::table('website_content')
             ->where('id', $id)
             ->update([
                 'banner' => $picname,
                 // add more fields as needed
             ]);
         return redirect('/homecontent')->with('success', 'banner changed successfully!');
     
         }


    //editing the leader's content.

    public function edit_leader(Request $request){

        // validate everything before any update so a bad upload can't leave a half-saved record.
        $request->validate([
            'id' => 'required|exists:website_content,id',
            'image' => 'nullable|image|mimes:png,jpg,jpeg|max:11000',
        ]);

         
        $id = $request->input('id');
     
        DB::table('website_content')
        ->where('id', $id)
        ->update([
            'team_leader_word' => $request->word,
        ]);

        //lets check if the image is not null and update the table.
     
        if ($request->hasFile('image')) {
           // this code uploads the picture from the form.
           $request->validate(['image' => 'required|image|mimes:png,jpg,jpeg|max:11000']);
           $picname = $request->file('image')->hashName();
           $request->image->move(public_path('images/team'), $picname);

           DB::table('members')
           ->where('role', 'leader')
           ->update([
               'photo' => $picname,
               // add more fields as needed
           ]);

       }

    return redirect('/homecontent')->with('success', 'leader content updated successfully!');
        

    }

    //edit home data
    public function edit_mission(Request $request){

         $id = $request->input('id');
    
        DB::table('website_content')
        ->where('id', $id)
        ->update([
            'Labname'=> $request->input('labname'),
            'motto'=> $request->input('motto'),
            'vision' => $request->input('vision'),
            'mission' => $request->input('mission'),
            'objectives' => $request->input('objectives'),
            // add more fields as needed
        ]);
    return redirect('/homecontent')->with('success', 'changes have been made successfully!');

    }


    // edits top bar information
    public function edit_topbar(Request $request){
      
        $id = $request->input('id');
     
        DB::table('website_content')
        ->where('id', $id)
        ->update([
            'twitter' => $request->twitter,
            'linkedin' => $request->linkedIn,
            'youtube' => $request->youtube,
            'instagram' => $request->instagram,
            'facebook' => $request->facebook,
            // "Show on the website" switches; an unticked box isn't sent, so it saves as hidden.
            'twitter_visible' => $request->boolean('twitter_visible'),
            'linkedin_visible' => $request->boolean('linkedin_visible'),
            'youtube_visible' => $request->boolean('youtube_visible'),
            'instagram_visible' => $request->boolean('instagram_visible'),
            'facebook_visible' => $request->boolean('facebook_visible'),
        ]);
    return redirect('/homecontent')->with('success', 'social links updated successfully!');

    }

       // edits labinfo information
       public function edit_labcontacts(Request $request){
      
        $id = $request->input('id');
     
        DB::table('website_content')
        ->where('id', $id)
        ->update([
            'physical_address' => $request->address,
            'company_email' => $request->email,
            'company_telephone' => $request->phone,
            'address_visible' => $request->boolean('address_visible'),
            'email_visible' => $request->boolean('email_visible'),
            'phone_visible' => $request->boolean('phone_visible'),
        ]);
    return redirect('/homecontent')->with('success', 'lab contacts updated successfully!');

    }

    //editing the partner

    public function edit_partner(Request $request){

        // validate everything before any update so a bad upload can't leave a half-saved record.
        $request->validate([
            'id' => 'required|exists:partners,id',
            'fullname' => 'required|string|max:255',
            'image' => 'nullable|image|mimes:png,jpg,jpeg|max:11000',
        ]);

    
          $id = $request->input('id');
     
         DB::table('partners')
         ->where('id', $id)
         ->update([
             'name' => $request->fullname,
         ]);


         if ($request->hasFile('image')) {
            // this code uploads the picture from the form.
            $request->validate(['image' => 'required|image|mimes:png,jpg,jpeg|max:11000']);
            $picname = $request->file('image')->hashName();
            $request->image->move(public_path('images/partners'), $picname);

            DB::table('partners')
            ->where('id', $id)
            ->update([
                'logo' => $picname,
                // add more fields as needed
            ]);
  
        }


     return redirect('/homecontent')->with('success', 'partner info updated successfully!');
 
     }


     //edit project info
     public function edit_project(Request $request){

        // validate everything before any update so a bad upload can't leave a half-saved record.
        $request->validate([
            'id' => 'required|exists:projects,id',
            'projectName' => 'required|string|max:255',
            'description' => 'required|string',
            'venue' => 'nullable|string|max:255',
            'participants' => 'nullable|string|max:255',
            'link' => 'nullable|string|max:255',
            'image' => 'nullable|image|mimes:png,jpg,jpeg|max:11000',
        ]);

 
         $id = $request->input('id');
     
         DB::table('projects')
         ->where('id', $id)
         ->update([
             'name' => $request->projectName,
             'venue' => $request->venue,
             'participants' => $request->participants,
             'website' => $request->link,
             'description' => $request->description,
             // add more fields as needed
         ]);

         //lets check if the image is not null and update the table.
      
         if ($request->hasFile('image')) {
            // this code uploads the picture from the form.
            $request->validate(['image' => 'required|image|mimes:png,jpg,jpeg|max:11000']);
            $picname = $request->file('image')->hashName();
            $request->image->move(public_path('images/projects'), $picname);

            DB::table('project_photos')
            ->where('project_id', $id)
            ->update([
                'photo' => $picname,
                // add more fields as needed
            ]);

        }

     return redirect('/projectscontent')->with('success', 'project info updated successfully!');
 
     }


     //editing news info
     public function edit_event(Request $request){

        // validate everything before any update so a bad upload can't leave a half-saved record.
        $request->validate([
            'id' => 'required|exists:events,id',
            'news' => 'required|string|max:255',
            'description' => 'required|string',
            'venue' => 'nullable|string|max:255',
            'link' => 'nullable|string|max:255',
            'startdate' => 'nullable|date',
            'enddate' => 'nullable|date|after_or_equal:startdate',
            'image' => 'nullable|image|mimes:png,jpg,jpeg|max:11000',
        ]);

 
        $id = $request->input('id');
   
       DB::table('events')
       ->where('id', $id)
       ->update([
           'title' => $request->news,
           'venue' => $request->venue,
           'article_link' => $request->link,
           'start_date' => $request->startdate,
           'description' => $request->description,
           'end_date' => $request->enddate,
           // add more fields as needed
       ]);

       //the image is optional; a new one is added to the event's photos instead of overwriting them all.
       if ($request->hasFile('image')) {
          $request->validate(['image' => 'image|mimes:png,jpg,jpeg|max:11000']);
          $picname = $request->file('image')->hashName();
          $request->image->move(public_path('images/events'), $picname);

          DB::table('event_photos')->insert([
              'photo' => $picname,
              'event_id' => $id,
              'created_at' => now(),
              'updated_at' => now(),
          ]);
       }


   return redirect('/newscontent')->with('success', 'event updated successfully!');

   }

   //editing the 

   //editing news info
   public function edit_publication(Request $request){

        // validate everything before any update so a bad upload can't leave a half-saved record.
        $request->validate([
            'id' => 'required|exists:publications,id',
            'title' => 'required|string|max:255',
            'type' => 'required|string|max:255',
            'author' => 'required|string|max:255',
            'description' => 'required|string',
            'date_published' => 'nullable|date',
            'file' => 'nullable|mimes:csv,pdf,doc,docx,xls,xlsx|max:11000',
            'photo' => 'nullable|image|mimes:png,jpg,jpeg|max:11000',
        ]);

 
    $id = $request->input('id');

   DB::table('publications')
   ->where('id', $id)
   ->update([
       'title' => $request->title,
       'type' => $request->type,
       'author' => $request->author,
       'date_published' => $request->date_published,
       'description' => $request->description,
       // add more fields as needed
   ]);

   //lets check if the image is not null and update the table.

   if ($request->hasFile('file')) {
      // this code uploads the picture from the form.
      $request->validate([
        'file' => 'required|mimes:csv,pdf,doc,docx,xls,xlsx|max:11000',
          ]);
    $filename = $request->file('file')->hashName();
    $request->file('file')->move(public_path('documents/publications'), $filename);

      DB::table('publications')
      ->where('id', $id)
      ->update([
          'file_link' => $filename,
          // add more fields as needed
      ]);

  }

  if($request->hasFile('photo')){

    $request->validate(['photo' => 'required|image|mimes:png,jpg,jpeg|max:11000']);
    $picname = $request->file('photo')->hashName();
    $request->photo->move(public_path('images/publications'), $picname);

    DB::table('publications')
    ->where('id', $id)
    ->update([
        'photo' => $picname,
        // add more fields as needed
    ]);

  }


return redirect('/publicationscontent')->with('success', 'publication updated successfully!');

}

//editing the team members
 public function edit_team(Request $request){

        // validate everything before any update so a bad upload can't leave a half-saved record.
        $request->validate([
            'id' => 'required|exists:members,id',
            'firstname' => 'required|string|max:255',
            'lastname' => 'required|string|max:255',
            'email' => 'required|email|max:255|unique:members,email,'.$request->input('id'),
            'designation' => 'required|string|max:255',
            'role' => 'nullable|string|max:255',
            'bio' => 'nullable|string',
            'image' => 'nullable|image|mimes:png,jpg,jpeg|max:11000',
        ]);

    $id = $request->input('id');
   
    DB::table('members')
    ->where('id', $id)
    ->update([
        'fname' => $request->firstname,
        'lname' => $request->lastname,
        'email' => $request->email,
        'designation' => $request->designation,
        'role' => $request->role,
        'bio' => $request->bio,
        // add more fields as needed
    ]);

    //lets check if the image is not null and update the table.
 
    if ($request->hasFile('image')) {
       // this code uploads the picture from the form.
       $request->validate(['image' => 'required|image|mimes:png,jpg,jpeg|max:11000']);
       $picname = $request->file('image')->hashName();
       $request->image->move(public_path('images/team'), $picname);

       DB::table('members')
       ->where('id', $id)
       ->update([
           'photo' => $picname,
           // add more fields as needed
       ]);

   }

return redirect('/aboutuscontent')->with('success', 'member details updated successfully!');

 }


}
