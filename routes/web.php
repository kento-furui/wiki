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

Route::get('/', [TaxonController::class, 'index']);
Route::resource('eol', EolController::class);
Route::resource('taxon', TaxonController::class);