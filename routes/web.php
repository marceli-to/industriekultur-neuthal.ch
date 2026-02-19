<?php

use Illuminate\Support\Facades\Route;

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

// Redirects
Route::redirect('/outdoor365', '/entdecken/outdoor365', 301);
Route::redirect('/baumwollexpress', '/besuchen/rund-ums-neuthal#baumwollexpress', 301);

Route::statamic('/work/kategorie/{category?}', 'work.index', [
  'layout' => 'layout.default',
  'title' => 'Work',
]);