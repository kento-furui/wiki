<?php

use App\Http\Controllers\PageController;
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

Route::get('/', [TaxonController::class, 'index']);

//Route::get('/login', [AuthController::class, 'index'])->name('login');
//Route::get('/logout', [AuthController::class, 'logout'])->name('logout');
//Route::post('/login', [AuthController::class, 'login'])->name('log_user_in');
//Route::get('/register', [AuthController::class, 'register'])->name('register');
//Route::post('/register', [AuthController::class, 'store'])->name('store_new_user');

Route::get('/taxon/rand', [TaxonController::class, 'rand']);
Route::get('/taxon/sumall/{taxon}', [TaxonController::class, 'sumall']);
Route::get('/taxon/recurse/{taxon}', [TaxonController::class, 'recurse']);
Route::get('/taxon/extinct/{taxon}', [TaxonController::class, 'extinct']);
Route::get('/taxon/represent/{taxon}', [TaxonController::class, 'represent']);

Route::resource('page', PageController::class);  
Route::resource('taxon', TaxonController::class);