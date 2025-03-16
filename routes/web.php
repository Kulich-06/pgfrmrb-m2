<?php


use Illuminate\Support\Facades\Route;
use App\Http\Controllers\UserController;
use App\Http\Controllers\ColorController;
use App\Http\Controllers\ClotchController;
use App\Http\Controllers\SearchController;
use App\Http\Controllers\CategoryController;
use App\Http\Controllers\CollectionController;

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


Route::get('/register', function () {return view('register');})->name('register.form');
Route::post('/register', [UserController::class, 'store'])->name('register.store');

Route::get('/login', function () {return view('login');})->name('login.form');
Route::post('/login', [UserController::class, 'login'])->name('login.store');

Route::middleware(['auth'])->group(function () {
    Route::get('/profile', [UserController::class, 'profile'])->name('profile');
    Route::get('/logout', [UserController::class, 'logout'])->name('logout'); });

Route::get('/', [ClotchController::class, 'index'])->name('index');;

Route::get('/color/create/', [ColorController::class, 'create'])->name('color.create');
Route::post('/color', [ColorController::class, 'store'])->name('color.store');

Route::get('/clotch/create/', [ClotchController::class, 'create'])->name('clotch.create');
Route::post('/clotch', [ClotchController::class, 'store'])->name('clotch.store');
Route::get('/clotches', [ClotchController::class, 'index'])->name('clotch.index');
Route::delete('/clotch/{id}', [ClotchController::class, 'destroy'])->name('clotch.destroy');

Route::get('/category/create/', [CategoryController::class, 'create'])->name('category.create');
Route::post('/category', [CategoryController::class, 'store'])->name('category.store');

Route::get('/collection', [CollectionController::class, 'index'])->name('collection.index');
Route::get('/collection/create/', [CollectionController::class, 'create'])->name('collection.create');
Route::post('/collection', [CollectionController::class, 'store'])->name('collection.store');
Route::delete('/collections/{id}', [CollectionController::class, 'destroy'])->name('collection.destroy');
Route::get('/collections/{id}', [CollectionController::class, 'show'])->name('collection.show');


Route::get('/collection/{id}/select-clothes', [ClotchController::class, 'selectForCollection'])->name('clotch.select');

