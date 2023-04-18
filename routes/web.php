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
Route::view('/projectscontent','projectscontent');
Route::view('/homecontent','homecontent');
Route::view('/publicationscontent','publicationscontent');
Route::view('/newscontent','newscontent');
Route::view('/aboutuscontent','aboutuscontent');
Route::view('/dashboard','dashboard');
