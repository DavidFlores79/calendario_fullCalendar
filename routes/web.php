<?php

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

Route::get('/', function () {
    return view('welcome');
});

Auth::routes();

Route::get('get-catalogos', [App\Http\Controllers\HomeController::class, 'getCatalogos']);


Route::middleware(["auth"])->group(function () {
    
    Route::get('/home', [App\Http\Controllers\HomeController::class, 'index'])->name('home');

    Route::get('events', [App\Http\Controllers\HomeController::class, 'getEvents']);
    Route::get('events/{id}', [App\Http\Controllers\HomeController::class, 'show']);
    Route::post('events', [App\Http\Controllers\HomeController::class, 'createEvent']);
    Route::put('events', [App\Http\Controllers\HomeController::class, 'updateEvent']);
    Route::delete('events', [App\Http\Controllers\HomeController::class, 'deleteEvent']);
});

