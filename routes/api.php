<?php

use Illuminate\Http\Request;
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
 * To register and get a new access token
 */
Route::post('register','App\Modules\UserManagement\UserController@create');

Route::get('/urls','\App\Modules\URLShortener\Controllers\UrlShortenerController@getAllUrls')->name('list-urls');