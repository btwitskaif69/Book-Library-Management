<?php

use Illuminate\Support\Facades\Route;

Route::get('/', fn () => view('dashboard'));
Route::get('/books/create', fn () => view('books.create'));
Route::get('/books/edit/{id}', fn () => view('books.edit'));
Route::get('/login', fn () => view('auth.login'));
Route::get('/register', fn () => view('auth.register'));
