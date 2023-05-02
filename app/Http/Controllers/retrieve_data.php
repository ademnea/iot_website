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
        
        $users = members::all();
        
        return view('/aboutuscontent',compact('users'));
   
       }


       public function fetch_projects(){
        
        // we need a join for the pictures to be displayed too.

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

        return view('/projectscontent',compact('onprojects','pastprojects'));
   
       }

       public function fetch_publications(){
        
               // we need a join for the pictures to be displayed too.

        $prototypes = DB::table('prototypes')
               ->join('prototype_photos', 'prototypes.id', '=', 'prototype_photos.prototype_id')
               ->select('prototypes.*', 'prototype_photos.*')
               ->get();
          

        $publications  = publications::all();
        
        return view('/publicationscontent',compact('publications','prototypes'));
   
       }


       public function fetch_events(){
        
        // we need a join for the pictures to be displayed too.

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

        return view('/newscontent',compact('onevents','pastevents'));
   
       }


       public function fetch_project_prototype(Request $request){

        $projects = projects::all();
        return view('/registerprototype',compact('projects'));

       }


}
