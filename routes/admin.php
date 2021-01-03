<?php

use Illuminate\Support\Facades\Route;

/*
|--------------------------------------------------------------------------
| ADMIN Routes
|--------------------------------------------------------------------------
| Custom routes created for admin users
|
 */

/**
 * Manage Short URLs
 */

// Route::post('/url/customize','\App\Modules\URLShortener\Controllers\UrlShortenerController@customizeShortUrl')->name('url-customize');

Route::group(['middleware' => ['auth:api']], function () {
    // create short url using url
    Route::post('/urls', '\App\Modules\URLShortener\Controllers\UrlShortenerController@generateShortUrl')->name('generate-url');
    
    // get all short urls
    Route::get('/urls', '\App\Modules\URLShortener\Controllers\UrlShortenerController@getAllUrls')->name('list-urls');

    // get short url details by slug
    Route::get('/urls/{slug}', '\App\Modules\URLShortener\Controllers\UrlShortenerController@getUrlBySlug')->name('get-url-by-slug');  
    
    // set redirect url by slug
    Route::put('/urls/{slug}', '\App\Modules\URLShortener\Controllers\UrlShortenerController@setUrlBySlug')->name('set-url-by-slug');  
    
    // delete short url by slug
    Route::delete('/urls/{slug}', '\App\Modules\URLShortener\Controllers\UrlShortenerController@deleteUrlBySlug')->where('slug', '.*')->name('delete-url-by-slug');  
});

Route::get('/greeting', function () {
    return 'Hello World';
});