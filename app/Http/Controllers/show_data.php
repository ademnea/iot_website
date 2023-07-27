<?php

namespace App\Http\Controllers;

use App\Models\event_photos;
use App\Models\members;
use App\Models\news;
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
            
           $randomPhotos = members::inRandomOrder()->limit(6)->get();
            $partners = partners::all();

            return view('/about_us',compact('researchers','interns','partners','teams','contents','randomPhotos'));
       
           }


           public function fetch_events(){
        
            // we need a join for the pictures to be displayed too.

            $contents = website_content::all();

         //we need a query here to send a project and the pictures on that project.


         $onevents = DB::table('events')
         ->leftJoin('event_photos', 'events.id', '=', 'event_photos.event_id')
         ->select('events.*', 'event_photos.photo')
         ->where('events.status', '=', 'Ongoing')
         ->get()
         ->groupBy('id');
     
            $eventphotos = event_photos::all();

           // dd($eventphotos);
    
            $pastevents = DB::table('events')
                ->join('event_photos', 'events.id', '=', 'event_photos.event_id')
                ->select('events.*', 'event_photos.*')
                ->where('events.status', '=', 'Past')
                ->get();

            $randomPhotos = members::inRandomOrder()->limit(6)->get();
    
            return view('/news',compact('onevents','pastevents','contents','eventphotos','randomPhotos'));
       
           }


           public function fetch_publications(){
        
            // we need a join for the pictures to be displayed too.

     $prototypes = DB::table('prototypes')
            ->join('prototype_photos', 'prototypes.id', '=', 'prototype_photos.prototype_id')
            ->select('prototypes.*', 'prototype_photos.*')
            ->get();
       
     $contents = website_content::all();
     $randomPhotos = members::inRandomOrder()->limit(6)->get();
     $publications  = publications::all();
     
     return view('/publications',compact('publications','prototypes','contents','randomPhotos'));

    }


    public function fetch_projects(){
        
        // we need a join for the pictures to be displayed too.

        $contents = website_content::all();

        $onprojects = DB::table('projects')
            ->join('project_photos', 'projects.id', '=', 'project_photos.project_id')
            ->select('projects.*', 'project_photos.photo')
            ->get();


            $randomPhotos = members::inRandomOrder()->limit(6)->get();

        return view('/projects',compact('onprojects','contents','randomPhotos'));
   
       }

       public function fetch_home(){
        
        $randomPhotos = members::inRandomOrder()->limit(6)->get();
        $leader = DB::table('members')->where('role', '=', 'leader')->get();
        $partners = partners::all();
        $contents = website_content::all();

 
        // if we startup the system without data, redirect the admin to the login to enter some new data.
       if($contents->count() === 0){

          return view('auth/login',['contents' => $contents, 'status' => 'Login to insert new website content']);

       }


        return view('/index',compact('contents','leader','partners','randomPhotos'));
   
       }

       public function fetch_gallery(){

        $contents = website_content::all();

        $randomPhotos = members::inRandomOrder()->limit(6)->get();
        //dd($randomPhotos);
        return view('/gallery',compact('contents','randomPhotos'));

       }

       public function fetch_partners(){

        $contents = website_content::all();
        $partners = partners::all();
        $randomPhotos = members::inRandomOrder()->limit(6)->get();
        return view('/partners',compact('partners','contents','randomPhotos'));

       }

}
