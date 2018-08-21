<?php

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

Route::middleware('session')->get('/user', function (Request $request) {
    return $request->user();
});
Route::get('/go-scraper', 'LinkedinScraperController@index');
Route::post('/oauth/login', 'LinkedinScraperController@post');
Route::get('oauth/login', 'LinkedinScraperController@get');
Route::get('linkedin/me','LinkedinScraperController@getME');