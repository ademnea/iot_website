<?php

namespace App\Http\Controllers;
use App\Models\members;
use App\Models\partners;
use App\Models\projects;
use App\Models\publications;
use Illuminate\Support\Facades\DB;
use App\Models\website_content;
use Illuminate\Http\Request;

class retrieve_data extends Controller
{



    //function for home content
    public function fetch_home(){
        

        $leader = DB::table('members')->where('role', '=', 'leader')->get();
        $partners = partners::all();
        $contents = website_content::all();
        
        return view('/homecontent',compact('contents','leader','partners'));
   
       }

    //function fetches members
    public function fetch_team(){
        
        $contents = website_content::all();

        $users = members::where('status', 'Active')->get();
        
        return view('/aboutuscontent',compact('users','contents'));
   
       }

           //function fetches old team members who left
    public function fetch_oldteam(){
        

        $users = members::where('status', 'Inactive')->get();
        
        return view('/oldteam',compact('users'));
   
       }


       public function fetch_projects(){
        
        // we need a join for the pictures to be displayed too.

        $contents = website_content::all();

        $onprojects = DB::table('projects')
            ->join('project_photos', 'projects.id', '=', 'project_photos.project_id')
            ->select('projects.*', 'project_photos.photo')
            ->where('projects.status', '=', 'Ongoing')
            ->get();


        $pastprojects = DB::table('projects')
            ->join('project_photos', 'projects.id', '=', 'project_photos.project_id')
            ->select('projects.*', 'project_photos.photo')
            ->where('projects.status', '=', 'Past')
            ->get();

        return view('/projectscontent',compact('onprojects','pastprojects','contents'));
   
       }

       public function fetch_publications(){
        
               // we need a join for the pictures to be displayed too.
        $contents = website_content::all();

        $prototypes = DB::table('prototypes')
               ->join('prototype_photos', 'prototypes.id', '=', 'prototype_photos.prototype_id')
               ->select('prototypes.*', 'prototype_photos.*')
               ->get();
          

        $publications  = publications::all();
        
        return view('/publicationscontent',compact('publications','prototypes','contents'));
   
       }


       public function fetch_events(){
        
        // we need a join for the pictures to be displayed too.

        $contents = website_content::all();

        $onevents = DB::table('events')
            ->join('event_photos', 'events.id', '=', 'event_photos.event_id')
            ->select('events.*', 'event_photos.*')
            ->where('events.status', '=', 'Ongoing')
            ->get();


        $pastevents = DB::table('events')
            ->join('event_photos', 'events.id', '=', 'event_photos.event_id')
            ->select('events.*', 'event_photos.*')
            ->where('events.status', '=', 'Past')
            ->get();

        return view('/newscontent',compact('onevents','pastevents','contents'));
   
       }


       public function fetch_project_prototype(Request $request){

        $contents = website_content::all();

        $projects = projects::all();
        return view('/registerprototype',compact('projects','contents'));

       }


}
