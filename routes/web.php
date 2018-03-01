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

Route::get('/test', function(Request $request)
{
    $requesting_user= \App\User::first();
    $token          = $requesting_user->tokens()->first()->token;
    $cycles         = 100;
    $count          = 0;
    $total_time     = 0;
    foreach(\App\User::all() as $user)
    {
        $start = microtime(true);
        $return = file_get_contents('https://linkcloud.net/api/v1/links?api_token='.$token, false, stream_context_create(["ssl"=>[
            "verify_peer"=>false,
            "verify_peer_name"=>false,
        ]]));
        $time_diff = microtime(true) - $start;
        $total_time = $total_time + $time_diff;
        echo number_format($time_diff, 3).'s <br />';
        $count++;
        if($count >= $cycles)
            break;
    }

    echo $count.' requests made over '.number_format($total_time, 2).'s with an average of '.number_format(($total_time / $count), 4).'s per request.';
});
