<?php

namespace App\Http\Controllers;
use App\Models\members;
use App\Models\partners;
use App\Models\projects;
use App\Models\publications;
use Illuminate\Support\Facades\DB;
use App\Models\website_content;
use Illuminate\Http\Request;

class show_data extends Controller
{
        //function fetches members
        public function fetch_team(){
        
           $contents = website_content::all();
           //$teams = members::all();
           //Non interns are displayed first as below
           
           $teams = DB::table('members')->whereNotIn('role', ['intern'])->get();
           $researchers = DB::table('members')->where('role', '=', 'researcher')->get();
           $interns = DB::table('members')->where('role', '=', 'intern')->get();
            

            $partners = partners::all();

            return view('/about_us',compact('researchers','interns','partners','teams','contents'));
       
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
    
            return view('/news',compact('onevents','pastevents','contents'));
       
           }


           public function fetch_publications(){
        
            // we need a join for the pictures to be displayed too.

     $prototypes = DB::table('prototypes')
            ->join('prototype_photos', 'prototypes.id', '=', 'prototype_photos.prototype_id')
            ->select('prototypes.*', 'prototype_photos.*')
            ->get();
       
     $contents = website_content::all();

     $publications  = publications::all();
     
     return view('/publications',compact('publications','prototypes','contents'));

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

        return view('/projects',compact('onprojects','pastprojects','contents'));
   
       }

       public function fetch_home(){
        
        $leader = DB::table('members')->where('role', '=', 'leader')->get();
        $partners = partners::all();
        $contents = website_content::all();

 
        // if we startup the system without data, redirect the admin to the login to enter some new data.
       if($contents->count() === 0){

          return view('auth/login',['contents' => $contents, 'status' => 'Login to insert new website content']);

       }


        return view('/index',compact('contents','leader','partners'));
   
       }


       public function fetch_partners(){

        $contents = website_content::all();
        $partners = partners::all();
        return view('/partners',compact('partners','contents'));

       }

}
