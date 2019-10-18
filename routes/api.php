<?php

use Illuminate\Http\Request;

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

Route::post('/scraping/url/{search}','ScrapingController@store');

Route::get('/scraping/{search}','ScrapingController@show');

Route::get('/scraping/url/{search}','ScrapingController@example');

Route::get('/pages','ScrapingController@index');

Route::delete('/scraping/{search}', 'ScrapingController@destroy');

Route::middleware('auth:api')->get('/user', function (Request $request) {
    return $request->user();
});
