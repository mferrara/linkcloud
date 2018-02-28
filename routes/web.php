<?php

/*
|--------------------------------------------------------------------------
| Application Routes
|--------------------------------------------------------------------------
|
| Here is where you can register all of the routes for an application.
| It's a breeze. Simply tell Laravel the URIs it should respond to
| and give it the controller to call when that URI is requested.
|
*/

Route::get('/',                 'WelcomeController@show')->name('home');

Route::get('/home',             'HomeController@show')->name('dashboard');
Route::get('/domains',          'DomainsController@index')->name('domains.index');
Route::get('/anchors',          'AnchorsController@index')->name('anchors.index');
Route::get('/links',            'LinksController@index')->name('links.index');

Route::post('/upload-links',    'LinksController@uploadLinks');

Route::get('/docs/api/v1',      'ApiV1DocumentationController@index')->name('docs.api.v1.index');
