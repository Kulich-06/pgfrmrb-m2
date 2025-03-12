<?php

use App\Models\Season;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\UserApiController;
use App\Http\Controllers\ColorApiController;
use App\Http\Controllers\ClotchApiController;
use App\Http\Controllers\SeasonApiController;
use App\Http\Controllers\CategoryApiController;

/*
|--------------------------------------------------------------------------
| API Routes
|--------------------------------------------------------------------------
|
| Here is where you can register API routes for your application. These
| routes are loaded by the RouteServiceProvider and all of them will
| be assigned to the "api" middleware group. Make something great!
|
*/

Route::middleware('auth:sanctum')->get('/user', function (Request $request) {
    return $request->user();
});
Route::get('/categories',[CategoryApiController::class, 'index']); 
Route::get('/colors',[ColorApiController::class, 'index']);
Route::get('/clotches',[ClotchApiController::class, 'index']);
Route::get('/categories/{categories}/clotches',[ClotchApiController::class,'clotches']);
Route::get('/search',[ClotchApiController::class,'search']);
Route:: post('/categories',[CategoryApiController::class, 'store']);
Route:: post('/clotches',[ClotchApiController::class, 'store']);
Route:: post('/colors',[ColorApiController::class, 'store']);
Route::post('/register',[UserApiController::class,'store']);
Route:: get('/seasons',[SeasonApiController::class, 'index']);

