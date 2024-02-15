<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\UserController;
use App\Http\Controllers\ArtistController;
use App\Http\Controllers\ThemeController;
use App\Http\Controllers\WebSeriesController;
use App\Http\Controllers\SeasonController;
use App\Http\Controllers\EpisodeController;
use App\Http\Controllers\Auth\LoginController;

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| Here is where you can register web routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| contains the "web" middleware group. Now create something great!
|
*/

Route::get('/', function () {
    return view('welcome');
});
Route::get('/register', [UserController::class, 'index'])->name('register');

Route::post('/user', [UserController::class, 'store'])->name('UserController.store');
Route::get('/login', [UserController::class, 'index2'])->name('login');
Route::post('/userlogin', [UserController::class, 'login'])->name('UserController.login');

Route::post('/showdetails', [UserController::class, 'userdetailsstore'])->name('UserController.userdetailsstore');
Route::get('/logout', [UserController::class, 'logout'])->name('logout');

//*****************************************************************************************************************************************************

Route::get('/artistlist', [ArtistController::class, 'index'])->name('StudioController.artistlist');
Route::get('/addartist', [ArtistController::class, 'add']);
Route::post('/addartists', [ArtistController::class, 'addartist'])->name('StudioController.addartist');

Route::get('/artist/{id}/edit', [ArtistController::class, 'edit'])->name('artist.edit');
Route::put('/artist/{id}', [ArtistController::class, 'update'])->name('artist.update');
Route::get('/artist/{id}/delete', [ArtistController::class, 'delete'])->name('artist.delete');



//************************************************************************************************************************************* */
Route::get('/themelist', [ThemeController::class, 'index'])->name('theme.list');
Route::get('/addtheme', [ThemeController::class, 'add']);
Route::post('/addthemes', [ThemeController::class, 'addtheme'])->name('theme.addtheme');

Route::get('/theme/{id}/edit', [ThemeController::class, 'edit'])->name('theme.edit');
Route::put('/theme/{id}', [ThemeController::class, 'update'])->name('theme.update');
Route::get('/theme/{id}/delete', [ThemeController::class, 'delete'])->name('theme.delete');



//************************************************************************************************************************************* */
Route::get('/weblist', [WebSeriesController::class, 'index'])->name('web.list');
Route::get('/addweb', [WebSeriesController::class, 'add']);
Route::post('/addwebs', [WebSeriesController::class, 'addweb'])->name('web.addweb');

Route::get('/web/{id}/edit', [WebSeriesController::class, 'edit'])->name('web.edit');
Route::put('/web/{id}', [WebSeriesController::class, 'update'])->name('web.update');
Route::get('/web/{id}/delete', [WebSeriesController::class, 'delete'])->name('web.delete');
Route::post('/update-status/{id}',  [WebSeriesController::class, 'updatestatus'])->name('web.updateStatus');

Route::get('/get-status/{id}', [WebSeriesController::class, 'getStatus'])->name('web.getStatus');
Route::get('/web/{id}/view', [WebSeriesController::class, 'view'])->name('web.view');


//************************************************************************************************************************************* */
Route::get('/seasonlist', [SeasonController::class, 'index'])->name('season.list');
Route::get('/addseason', [SeasonController::class, 'add']);
Route::post('/addseasons', [SeasonController::class, 'addseason'])->name('season.addseason');

Route::get('/season/{id}/edit', [SeasonController::class, 'edit'])->name('season.edit');
Route::put('/season/{id}', [SeasonController::class, 'update'])->name('season.update');
Route::get('/season/{id}/delete', [SeasonController::class, 'delete'])->name('season.delete');
//Route::post('/update-status/{id}',  [SeasonController::class, 'updatestatus'])->name('web.updateStatus');
Route::get('/season/{id}/view', [SeasonController::class, 'view'])->name('season.view');


//************************************************************************************************************************************* */


Route::get('/adminlogin', [LoginController::class, 'showLoginForm']);
Route::post('/adminlogin', [LoginController::class, 'login'])->name('login'); // Changed route name to 'adminlogin'
Route::post('/logout', [LoginController::class, 'logout'])->name('logout');
Route::get('/dashboard', [LoginController::class, 'dashboard'])->name('dashboard');
Route::post('/update-ustatus/{id}',  [LoginController::class, 'updatestatus'])->name('userupdateStatus');
Route::get('/get-user-status/{id}', [LoginController::class, 'getStatus'])->name('getuserStatus');
Route::post('/update-user-status/{id}', [LoginController::class, 'updateStatus'])->name('updateuserStatus');
//************************************************************************************************************************************* */
Route::get('/episodelist', [EpisodeController::class, 'index'])->name('episode.list');
Route::get('/addepisode', [EpisodeController::class, 'add']);
Route::post('/addepisodes', [EpisodeController::class, 'addepisode'])->name('episode.addepisode');

Route::get('/episode/{id}/edit', [EpisodeController::class, 'edit'])->name('episode.edit');
Route::put('/episode/{id}', [EpisodeController::class, 'update'])->name('episode.update');
Route::get('/episode/{id}/delete', [EpisodeController::class, 'delete'])->name('episode.delete');
//Route::post('/update-status/{id}',  [SeasonController::class, 'updatestatus'])->name('web.updateStatus');
Route::get('/episode/{id}/view', [EpisodeController::class, 'view'])->name('episode.view');
