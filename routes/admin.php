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


// Route::post('/url/customize','\App\Modules\URLShortener\Controllers\UrlShortenerController@customizeShortUrl')->name('url-customize');

// show all urls
Route::get('/urls','\App\Modules\URLShortener\Controllers\UrlShortenerController@getAllUrls')->name('list-urls');

Route::group(['middleware' => ['auth:api']], function () {
    // create short url
    Route::post('/urls', '\App\Modules\URLShortener\Controllers\UrlShortenerController@generateShortUrl')->name('generate-url');
});