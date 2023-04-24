<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class insert_data extends Controller
{
    
    //this function inserts data for mission, objectives, and word from the team leader.
   public function insert_home_form1(Request $request){

      // return $request->input();
     // $radio = $request->input('homecontent');

                $mydata = new home;
                $mydata->type = $request->homecontent;
                $mydata->content = $request->content;
                $mydata->save();

                return redirect('/homecontent')->with('success', 'home content added successfully!');

   }

   public function insert_contacts(Request $request){

    // return $request->input();

              $mydata = new home;
              $mydata->type = $request->homecontent;
              $mydata->content = $request->content;
              $mydata->save();

              return redirect('/homecontent')->with('success', 'home content added successfully!');

 }


  // this function inserts partners into the database
   public function insert_partner(Request $request){

            //this code uploads the picture from the form.
        $request->validate(['image' => 'required|image|mimes:png,jpg,jpeg|max:2048']);
        $picname = $request->file('image')->getClientOriginalName();
        $request->image->move(public_path('images/partners'), $picname);

        $mydata = new partners;
        $mydata->Name = $request->fullname;
        $mydata->image  = $request->image;
        $mydata->save();

        return redirect('/homecontent')->with('success', 'Partner added successfully!');

   // return $request->input();

}


    public Function insert_news(Request $request){

                    //this code uploads the picture from the form.
            $request->validate(['image' => 'required|image|mimes:png,jpg,jpeg|max:2048']);
            $picname = $request->file('image')->getClientOriginalName();
            $request->image->move(public_path('images/events'), $picname);

            $mydata = new Event;
            $mydata->title = $request->title;
            $mydata->description = $request->description;
            $mydata->StartDate = $request->start_date;
            $mydata->EndDate = $request->end_date;
            $mydata->location = $request->venue;
            $mydata->receipt_image_url = $picname;
            $mydata->save();

            return redirect('/newscontent')->with('success', 'Event added successfully!');

       // return $request->input();
}

public Function insert_project(Request $request){

                //this code uploads the picture from the form.
            $request->validate(['image' => 'required|image|mimes:png,jpg,jpeg|max:2048']);
            $picname = $request->file('image')->getClientOriginalName();
            $request->image->move(public_path('images/projects'), $picname);

            $mydata = new Project;
            $mydata->Project_Name = $request->ProjectName;
            $mydata->Project_Venue = $request->venue;
            $mydata->Start_Date = $request->start_date;
            $mydata->Duration = $request->duration;
            $mydata->Participants = $request->participants;
            $mydata->Description = $request->description;
            $mydata->Project_Website = $request->project_link;
            $mydata->receipt_image_url = $picname;
            $mydata->save();

            return redirect('/projectscontent')->with('success', 'project added successfully!');

   // return $request->input();
}


public Function insert_publication(Request $request){

                    //this code uploads the publication file from the form.
                   // Validate and capture document file
            $request->validate([
                'file' => 'required|mimes:csv,pdf,doc,docx,xls,xlsx|max:2048',
            ]);
            $filename = $request->file('file')->getClientOriginalName();
            $request->file('file')->move(public_path('documents/publications'), $filename);

            $mydata = new Publication;
            $mydata->title = $request->title;
            $mydata->type = $request->type;
            $mydata->author = $request->author;
            $mydata->file_path = $filename;
            $mydata->save();

            return redirect('/publicationscontent')->with('success', 'publication added successfully!');

    //return $request->input();
}


public Function insert_team(Request $request){

                //this code uploads the picture from the form.
            $request->validate(['image' => 'required|image|mimes:png,jpg,jpeg|max:2048']);
            $picname = $request->file('image')->getClientOriginalName();
            $request->image->move(public_path('images/team'), $picname);

            $mydata = new users;
            $mydata->admin_id = $request->admin;
            $mydata->firstname = $request->firstname;
            $mydata->lastname = $request->lastname;
            $mydata->email = $request->email;
            $mydata->phone = $request->phone;
            $mydata->designation = $request->phone;
            $mydata->role = $request->phone;
            $mydata->image = $picname;
            $mydata->save();
    
          return redirect('/aboutuscontent')->with('success', $request->firstname.' '.'has been registered successfully!');

    //return $request->input();
}



}
