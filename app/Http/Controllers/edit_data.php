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
        $request->validate(['image' => 'required|image|mimes:png,jpg,jpeg|max:2048']);
        $picname = $request->file('image')->getClientOriginalName();
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
             $request->validate(['image' => 'required|image|mimes:png,jpg,jpeg|max:2048']);
             $picname = $request->file('image')->getClientOriginalName();
             $request->image->move(public_path('images/banner'), $picname);
     
             $id = $request->input('id');
         
             DB::table('website_content')
             ->where('id', $id)
             ->update([
                 'banner' => $picname,
                 // add more fields as needed
             ]);
         return redirect('/homecontent')->with('success', 'logo changed successfully!');
     
         }


    //editing the leader's content.

    public function edit_leader(Request $request){
         
        $id = $request->input('id');
     
        DB::table('website_content')
        ->where('id', $id)
        ->update([
            'team_leader_word' => $request->word,
        ]);

        //lets check if the image is not null and update the table.
     
        if ($request->hasFile('image')) {
           // this code uploads the picture from the form.
           $request->validate(['image' => 'required|image|mimes:png,jpg,jpeg|max:2048']);
           $picname = $request->file('image')->getClientOriginalName();
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
            'motto'=> $request->input('motto'),
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
            // add more fields as needed
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
            // add more fields as needed
        ]);
    return redirect('/homecontent')->with('success', 'lab contacts updated successfully!');

    }

    //editing the partner

    public function edit_partner(Request $request){
    
          $id = $request->input('id');
     
         DB::table('partners')
         ->where('id', $id)
         ->update([
             'name' => $request->fullname,
         ]);


         if ($request->hasFile('image')) {
            // this code uploads the picture from the form.
            $request->validate(['image' => 'required|image|mimes:png,jpg,jpeg|max:2048']);
            $picname = $request->file('image')->getClientOriginalName();
            $request->image->move(public_path('images/events'), $picname);
  
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
 
         $id = $request->input('id');
     
         DB::table('projects')
         ->where('id', $id)
         ->update([
             'name' => $request->projectName,
             'venue' => $request->venue,
             'participants' => $request->participants,
             'description' => $request->description,
             // add more fields as needed
         ]);

         //lets check if the image is not null and update the table.
      
         if ($request->hasFile('image')) {
            // this code uploads the picture from the form.
            $request->validate(['image' => 'required|image|mimes:png,jpg,jpeg|max:2048']);
            $picname = $request->file('image')->getClientOriginalName();
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
 
        $id = $request->input('id');
   
       DB::table('events')
       ->where('id', $id)
       ->update([
           'title' => $request->news,
           'venue' => $request->venue,
           'start_date' => $request->startdate,
           'description' => $request->description,
           'end_date' => $request->enddate,
           // add more fields as needed
       ]);

       //lets check if the image is not null and update the table.
    
       if ($request->hasFile('image')) {
          // this code uploads the picture from the form.
          $request->validate(['image' => 'required|image|mimes:png,jpg,jpeg|max:2048']);
          $picname = $request->file('image')->getClientOriginalName();
          $request->image->move(public_path('images/events'), $picname);

          DB::table('event_photos')
          ->where('event_id', $id)
          ->update([
              'photo' => $picname,
              // add more fields as needed
          ]);

      }

   return redirect('/newscontent')->with('success', 'event updated successfully!');

   }

   //editing the 

   //editing news info
   public function edit_publication(Request $request){
 
    $id = $request->input('id');

   DB::table('publications')
   ->where('id', $id)
   ->update([
       'title' => $request->title,
       'type' => $request->type,
       'author' => $request->author,
       'description' => $request->description,
       // add more fields as needed
   ]);

   //lets check if the image is not null and update the table.

   if ($request->hasFile('file')) {
      // this code uploads the picture from the form.
      $request->validate([
        'file' => 'required|mimes:csv,pdf,doc,docx,xls,xlsx|max:2048',
          ]);
    $filename = $request->file('file')->getClientOriginalName();
    $request->file('file')->move(public_path('documents/publications'), $filename);

      DB::table('publications')
      ->where('id', $id)
      ->update([
          'file_link' => $filename,
          // add more fields as needed
      ]);

  }

return redirect('/publicationscontent')->with('success', 'publication updated successfully!');

}

//editing the team members
 public function edit_team(Request $request){
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
       $request->validate(['image' => 'required|image|mimes:png,jpg,jpeg|max:2048']);
       $picname = $request->file('image')->getClientOriginalName();
       $request->image->move(public_path('images/team'), $picname);

       DB::table('members')
       ->where('id', $id)
       ->update([
           'photo' => $picname,
           // add more fields as needed
       ]);

   }

return redirect('/aboutuscontent')->with('success', 'event updated successfully!');

 }


}
