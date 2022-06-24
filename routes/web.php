<?php

use App\Http\Controllers\TaxonController;
use Illuminate\Support\Facades\Route;

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

//Route::get('/login', [AuthController::class, 'index'])->name('login');
//Route::get('/logout', [AuthController::class, 'logout'])->name('logout');
//Route::post('/login', [AuthController::class, 'login'])->name('log_user_in');
//Route::get('/register', [AuthController::class, 'register'])->name('register');
//Route::post('/register', [AuthController::class, 'store'])->name('store_new_user');

Route::get('/', [TaxonController::class, 'index']);
Route::get('/ja/{taxon}', [TaxonController::class, 'ja']);
Route::get('/en/{taxon}', [TaxonController::class, 'en']);
Route::get('/sum/{taxon}', [TaxonController::class, 'sum']);
Route::get('/map/{taxon}', [TaxonController::class, 'map']);
Route::get('/page/{taxon}', [TaxonController::class, 'show']);
Route::get('/tree/{taxon}', [TaxonController::class, 'tree']);
Route::get('/taxon/{taxon}', [TaxonController::class, 'taxon']);
Route::get('/media/{taxon}', [TaxonController::class, 'media']);
Route::get('/extinct/{taxon}', [TaxonController::class, 'extinct']);
Route::get('/represent/{taxon}', [TaxonController::class, 'represent']);