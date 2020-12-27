<?php

use Illuminate\Support\Facades\Route;

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

/**
 * Manage Short URLs
 */
// load short url
Route::post('/urls', '\App\Modules\URLShortener\Controllers\UrlShortenerController@generateShortUrl')->name('generate-url');

// Route::post('/url/customize','\App\Modules\URLShortener\Controllers\UrlShortenerController@customizeShortUrl')->name('url-customize');
