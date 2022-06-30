<?php

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

Route::get('/iucn/store/{id}', [IucnController::class, 'store']);
Route::get('/image/store/{id}', [ImageController::class, 'store']);

Route::post('/eol/update/{eol}', [EolController::class, 'update']);
Route::post('/image/update/{id}', [ImageController::class, 'update']);

// Route::post('/iucn/store', [IucnController::class, 'store']);
// Route::post('/image/store/', [ImageController::class, 'store']);
// Route::post('/image/preferred/', [ImageController::class, 'preferred']);
