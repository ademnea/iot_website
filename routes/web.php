<?php

use App\Http\Controllers\AdminAccountController;
use App\Http\Controllers\EventController;
use App\Http\Controllers\HomeContentController;
use App\Http\Controllers\MemberController;
use App\Http\Controllers\MessageController;
use App\Http\Controllers\PartnerController;
use App\Http\Controllers\ProjectController;
use App\Http\Controllers\PrototypeController;
use App\Http\Controllers\PublicationController;
use App\Http\Controllers\PublicPageController;
use Illuminate\Support\Facades\Route;

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| Every URL is the one the site has always used (admins' bookmarks and the forms depend on
| them); the controllers behind them are grouped by content type. Login, logout, two-factor
| authentication, password reset and the profile page are registered by Fortify and Jetstream.
|
*/

// ---- Public site -----------------------------------------------------------------------------

Route::controller(PublicPageController::class)->group(function () {
    Route::get('/', 'fetch_home');
    Route::get('/about', 'fetch_team');
    Route::get('/projects', 'fetch_projects');
    Route::get('/publications', 'fetch_publications');
    Route::get('/news', 'fetch_events');
    Route::get('/partners', 'fetch_partners');
    Route::get('/gallery', 'fetch_gallery');
    Route::get('/project_gallery', 'fetch_project_gallery');
    Route::get('/team_gallery', 'fetch_team_gallery');
    Route::get('/prototypes_gallery', 'fetch_prototype_gallery');
    Route::get('/events_gallery', 'fetch_events_gallery');
});

// The contact form: 5 messages per minute per visitor (its own limit, see RouteServiceProvider).
Route::post('/insert_message', [MessageController::class, 'store'])->middleware('throttle:contact');

// Logging in is handled by Fortify (GET/POST /login, with 2FA and a 5-per-minute limit).
// The old address still works for bookmarks and links.
Route::redirect('/oldlogin', '/login');

// ---- Admin area (login required) -------------------------------------------------------------

Route::middleware(['auth'])->group(function () {

    Route::view('/dashboard', 'dashboard')->name('dashboard');

    // Add forms that need no data from the database.
    Route::view('/register_home', 'register_home');
    Route::view('/register_projects', 'register_projects');
    Route::view('/register_publications', 'register_publications');
    Route::view('/register_news', 'register_news');
    Route::view('/register_team', 'register_team');

    // Sidebar → Home: lab details, logo, banner, contacts, social links, leader's message.
    Route::controller(HomeContentController::class)->group(function () {
        Route::get('/homecontent', 'index');
        Route::post('/insertmission', 'storeInfo');
        Route::post('/insertcontacts', 'storeContacts');
        Route::get('/edithome', 'editInfo');
        Route::post('/editmission_route', 'updateInfo');
        Route::get('/editlogo', 'editLogo');
        Route::post('/editlogo_route', 'updateLogo');
        Route::get('/editbanner', 'editBanner');
        Route::post('/editbanner_route', 'updateBanner');
        Route::get('/edittopbar', 'editSocials');
        Route::post('/edittopbar_route', 'updateSocials');
        Route::get('/editcontacts', 'editContacts');
        Route::post('/editlabinfo_route', 'updateContacts');
        Route::get('/editleader', 'editLeader');
        Route::post('/editleader_route', 'updateLeader');
        Route::post('/clearhomefield', 'clearField');
    });

    // Partners (added and listed on the Home admin page).
    Route::controller(PartnerController::class)->group(function () {
        Route::post('/insertpartner', 'store');
        Route::get('/editpartner', 'edit');
        Route::post('/editpartner_route', 'update');
        Route::post('/deletepartner', 'destroy');
    });

    // Sidebar → Projects.
    Route::controller(ProjectController::class)->group(function () {
        Route::get('/projectscontent', 'index');
        Route::post('/submit-project', 'store');
        Route::get('/editproject', 'edit');
        Route::post('/editproject_route', 'update');
        Route::post('/deleteproject', 'destroy');
    });

    // Sidebar → Products: publications and prototypes.
    Route::controller(PublicationController::class)->group(function () {
        Route::get('/publicationscontent', 'index');
        Route::post('/insert_publication', 'store');
        Route::get('/editpublications', 'edit');
        Route::post('/editpublication_route', 'update');
        Route::post('/deletepublication', 'destroy');
    });
    Route::controller(PrototypeController::class)->group(function () {
        Route::get('/registerprototype', 'create');
        Route::post('/insertprototype', 'store');
    });

    // Sidebar → News.
    Route::controller(EventController::class)->group(function () {
        Route::get('/newscontent', 'index');
        Route::post('/insert_news', 'store');
        Route::get('/editnews', 'edit');
        Route::post('/editnews_route', 'update');
        Route::post('/deletenews', 'destroy');
    });

    // Sidebar → Current Team and Past Team.
    Route::controller(MemberController::class)->group(function () {
        Route::get('/aboutuscontent', 'index');
        Route::get('/oldteam', 'past');
        Route::post('/insert_team', 'store');
        Route::get('/editteam', 'edit');
        Route::post('/editmember_route', 'update');
        Route::post('/deleteuser', 'deactivate');
        Route::post('/restoreuser', 'restore');
    });

    // Dashboard → Add another admin. Only a logged-in admin can register another admin.
    Route::controller(AdminAccountController::class)->group(function () {
        Route::get('/register', 'create')->name('register');
        Route::get('/registeradminform', 'create');
        Route::post('/registeradmin', 'store');
    });
});
