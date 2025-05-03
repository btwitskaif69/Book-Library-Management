<?php

use Illuminate\Support\Facades\Route;
use Illuminate\Support\Facades\Auth;


Route::get('/', fn () => view('dashboard'));
Route::get('/dashboard', fn () => view('dashboard'));
Route::get('/books/create', fn () => view('books.create'));
Route::get('/books/edit/{id}', fn () => view('books.edit'));
Route::get('/login', fn () => view('auth.login'));
Route::get('/register', fn () => view('auth.register'));

// Logout route
Route::post('/logout', function () {
    Auth::logout(); // Log out the user
    return redirect('/login'); // Redirect to login page
})->name('logout');
