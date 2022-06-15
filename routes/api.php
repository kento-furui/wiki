<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\EolController;
use App\Http\Controllers\IucnController;
use App\Http\Controllers\ImageController;

/*
|--------------------------------------------------------------------------
| API Routes
|--------------------------------------------------------------------------
|
| Here is where you can register API routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| is assigned the "api" middleware group. Enjoy building your API!
|
*/

Route::get('/ranks/{taxon}', [TaxonController::class, 'ranks']);

Route::post('/iucn/store', [IucnController::class, 'store']);
Route::post('/image/store/', [ImageController::class, 'store']);
Route::post('/eol/update/{eol}', [EolController::class, 'update']);
Route::post('/image/preferred/', [ImageController::class, 'preferred']);
