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
Route::view('/', 'index');
Route::view('/partners', 'partners');
Route::view('/publications', 'publications');
Route::view('/news', 'news');
Route::view('/about', 'about_us');
Route::view('/projects', 'projects')->middleware(['auth']);

Route::get('/login', function () {
    return view('login');
})->name('login');


//admin side routes
Route::middleware(['auth'])->group(function () {
    // Protected routes go here
});

Route::view('/projectscontent','projectscontent');
Route::view('/homecontent','homecontent');
Route::view('/publicationscontent','publicationscontent');
Route::view('/newscontent','newscontent');
Route::view('/aboutuscontent','aboutuscontent');
Route::view('/dashboard','dashboard');
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
Route::view('/registerprototype','registerprototype');


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


//controller routes for updating database content
Route::post('/editmission_route', 'App\Http\Controllers\edit_data@edit_mission');
Route::post('/editlogo_route', 'App\Http\Controllers\edit_data@edit_logo');
Route::post('/editleader_route', 'App\Http\Controllers\edit_data@edit_leader');
Route::post('/editpartner_route', 'App\Http\Controllers\edit_data@edit_partner');
Route::post('/edittopbar_route', 'App\Http\Controllers\edit_data@edit_topbar');
Route::post('/editlabinfo_route', 'App\Http\Controllers\edit_data@edit_labcontacts');
Route::post('/editproject_route', 'App\Http\Controllers\edit_data@edit_project');
Route::post('/editnews_route', 'App\Http\Controllers\edit_data@edit_event');
Route::post('/editpublication_route', 'App\Http\Controllers\edit_data@edit_publication');
Route::post('/editmember_route', 'App\Http\Controllers\edit_data@edit_team');



// routes for displaying/ showing data to the outside pages.
// to pick data from the database
Route::get('/about', 'App\Http\Controllers\show_data@fetch_team');
Route::get('/', 'App\Http\Controllers\show_data@fetch_home');
Route::get('/projects', 'App\Http\Controllers\show_data@fetch_projects');
Route::get('/publications', 'App\Http\Controllers\show_data@fetch_publications');
Route::get('/news', 'App\Http\Controllers\show_data@fetch_events');
Route::get('/partners', 'App\Http\Controllers\show_data@fetch_partners');