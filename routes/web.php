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
Route::view('/projects', 'projects'); //->middleware(['auth']);

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
Route::view('/editproject','editproject');
Route::view('/editteam','editteam');
Route::view('/editpublications','editpublications');
Route::view('/editnews','editnews');
Route::view('/editpartner','editpartner');
Route::view('/edittopbar','edittopbar');
Route::view('/editcontacts','editcontacts');




//routes to controllers
Route::post('/insertmission', 'App\Http\Controllers\insert_data@insert_home_form1');
Route::post('/insertpartner', 'App\Http\Controllers\insert_data@insert_partner');
Route::post('/insert_news', 'App\Http\Controllers\insert_data@insert_news');
Route::post('/submit-project', 'App\Http\Controllers\insert_data@insert_project');
Route::post('/insert_publication', 'App\Http\Controllers\insert_data@insert_publication');
Route::post('/insert_team', 'App\Http\Controllers\insert_data@insert_team');
Route::post('/insertcontacts', 'App\Http\Controllers\insert_data@insert_contacts');