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

Route::middleware(['auth', 'admin'])->group(function () {
	Route::resource('users', 'App\Http\Controllers\UserController');
});

Route::middleware(['auth'])->group(function () {
	Route::resource('books', 'App\Http\Controllers\BookController');
	Route::resource('authors', 'App\Http\Controllers\AuthorController');    

    Route::get('/dashboard', function () {
        return view('dashboard');
    })->name('dashboard');
});

require __DIR__.'/auth.php';
