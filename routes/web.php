<?php

use Illuminate\Support\Facades\Route;
use Illuminate\Support\Facades\Auth;
use Illuminate\Auth\Middleware\Authenticate;
/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| Here is where you can register web routes for your application. These
| routes are loaded by the RouteServiceProvider and all of them will
| be assigned to the "web" middleware group. Make something great!
|
*/


//routes to all the pages begin here.


// routes for displaying/ showing data to the outside pages.
// to pick data from the database
Route::get('/about', 'App\Http\Controllers\show_data@fetch_team');
Route::get('/', 'App\Http\Controllers\show_data@fetch_home');
Route::get('/projects', 'App\Http\Controllers\show_data@fetch_projects');
Route::get('/publications', 'App\Http\Controllers\show_data@fetch_publications');
Route::get('/news', 'App\Http\Controllers\show_data@fetch_events');
Route::get('/partners', 'App\Http\Controllers\show_data@fetch_partners');
Route::get('/gallery', 'App\Http\Controllers\show_data@fetch_gallery');

//dummy route
Route::get('/project_gallery','App\Http\Controllers\show_data@fetch_project_gallery');
Route::get('/team_gallery','App\Http\Controllers\show_data@fetch_team_gallery');
Route::get('/prototypes_gallery','App\Http\Controllers\show_data@fetch_prototype_gallery');
Route::get('/events_gallery','App\Http\Controllers\show_data@fetch_events_gallery');

//these routes handle the login functionality.
Route::get('/logmein', 'App\Http\Controllers\iot_admin_login@login');
Route::post('/mylogin', 'App\Http\Controllers\iot_admin_login@login');
Route::get('/oldlogin', 'App\Http\Controllers\iot_admin_login@logmein')->name('login');

Route::get('/registeradminform', 'App\Http\Controllers\iot_admin_login@registerme');
Route::get('/register', 'App\Http\Controllers\iot_admin_login@registerme')->name('register');
Route::post('/registeradmin', 'App\Http\Controllers\insert_data@insert_admin');

//admin side protected routes
Route::middleware(['auth'])->group(function () {
    
Route::view('/dashboard','dashboard')->name('dashboard');
    
Route::view('/projectscontent','projectscontent');
Route::view('/homecontent','homecontent');
Route::view('/publicationscontent','publicationscontent');
Route::view('/newscontent','newscontent'); 
Route::view('/aboutuscontent','aboutuscontent');
Route::view('/register_projects','register_projects');
Route::view('/register_publications','register_publications');
Route::view('/register_news','register_news');
Route::view('/register_home','register_home');
Route::view('/register_team','register_team');


//routes for editing content
Route::view('/edithome','edithome');
Route::view('/editleader','editleader');
Route::view('/editproject','editproject');
Route::view('/editteam','editteam');
Route::view('/editpublications','editpublications');
Route::view('/editnews','editnews');
Route::view('/editpartner','editpartner');
Route::view('/edittopbar','edittopbar');
Route::view('/editcontacts','editcontacts');
Route::view('/editlogo','editlogo');
Route::view('/editbanner','editbanner');


//routes to insert data into the database
Route::post('/insertmission', 'App\Http\Controllers\insert_data@insert_home_form1');
Route::post('/insertpartner', 'App\Http\Controllers\insert_data@insert_partner');
Route::post('/insert_news', 'App\Http\Controllers\insert_data@insert_news');
Route::post('/submit-project', 'App\Http\Controllers\insert_data@insert_project');
Route::post('/insert_publication', 'App\Http\Controllers\insert_data@insert_publication');
Route::post('/insert_team', 'App\Http\Controllers\insert_data@insert_team');
Route::post('/insertcontacts', 'App\Http\Controllers\insert_data@insert_contacts');
Route::post('/insertprototype', 'App\Http\Controllers\insert_data@insert_prototype');



//routes to pick data from the database
Route::get('/aboutuscontent', 'App\Http\Controllers\retrieve_data@fetch_team');
Route::get('/homecontent', 'App\Http\Controllers\retrieve_data@fetch_home');
Route::get('/projectscontent', 'App\Http\Controllers\retrieve_data@fetch_projects');
Route::get('/publicationscontent', 'App\Http\Controllers\retrieve_data@fetch_publications');
Route::get('/newscontent', 'App\Http\Controllers\retrieve_data@fetch_events');
Route::get('/registerprototype','App\Http\Controllers\retrieve_data@fetch_project_prototype');
Route::get('/oldteam','App\Http\Controllers\retrieve_data@fetch_oldteam');


//controller routes for updating database content
Route::post('/editmission_route', 'App\Http\Controllers\edit_data@edit_mission');
Route::post('/editlogo_route', 'App\Http\Controllers\edit_data@edit_logo');
Route::post('/editbanner_route', 'App\Http\Controllers\edit_data@edit_banner');
Route::post('/editleader_route', 'App\Http\Controllers\edit_data@edit_leader');
Route::post('/editpartner_route', 'App\Http\Controllers\edit_data@edit_partner');
Route::post('/edittopbar_route', 'App\Http\Controllers\edit_data@edit_topbar');
Route::post('/editlabinfo_route', 'App\Http\Controllers\edit_data@edit_labcontacts');
Route::post('/editproject_route', 'App\Http\Controllers\edit_data@edit_project');
Route::post('/editnews_route', 'App\Http\Controllers\edit_data@edit_event');
Route::post('/editpublication_route', 'App\Http\Controllers\edit_data@edit_publication');
Route::post('/editmember_route', 'App\Http\Controllers\edit_data@edit_team');


//controllers for deleting stuff
Route::post('/deleteuser', 'App\Http\Controllers\delete_stuff@delete_member');

//controllers for rstoring stuff
Route::post('/restoreuser', 'App\Http\Controllers\restore_stuff@restore_member');

});

//inserting feedback message into the database
Route::post('/insert_message', 'App\Http\Controllers\insert_data@insert_message');
