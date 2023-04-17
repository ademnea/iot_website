<?php

use Illuminate\Support\Facades\Route;

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
Route::view('/login', 'login');
Route::view('/news', 'news');
Route::view('/about', 'about_us');
Route::view('/projects', 'projects');