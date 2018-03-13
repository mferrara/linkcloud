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
Route::post('/settings',        'UserController@updateSettings')->name('user.settings.update');

Route::get('/docs/api/v1',      'ApiV1DocumentationController@index')->name('docs.api.v1.index');

Route::get('/test-get-links', function(\Illuminate\Http\Request $request)
{
    $requesting_user= \App\User::first();
    $request_domain = 'linkcloud.test';
    if(\Illuminate\Support\Facades\App::environment() == 'production')
        $request_domain = 'linkcloud.net';

    $token          = $requesting_user->tokens()->first()->token;
    $cycles         = 100;
    $count          = 0;
    $total_time     = 0;

    if($request->has('cycles'))
        $cycles = $request->get('cycles');

    $uas = [
        'Mozilla/5.0 (compatible; Googlebot/2.1; +http://www.google.com/bot.html)',
        'Googlebot/2.1 (+http://www.googlebot.com/bot.html)',
        'Googlebot/2.1 (+http://www.google.com/bot.html)',
        'Mozilla/5.0 (compatible; bingbot/2.0; +http://www.bing.com/bingbot.htm)',
        'Mozilla/5.0 (Windows NT 6.1) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/41.0.2228.0 Safari/537.36',
        'Mozilla/5.0 (Windows NT 6.3; WOW64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/41.0.2226.0 Safari/537.36',
        'Mozilla/5.0 (compatible, MSIE 11, Windows NT 6.3; Trident/7.0; rv:11.0) like Gecko',
    ];

    $ips = [
        '66.249.64.21',
        '66.249.76.94',
        '66.249.76.46',
        '66.249.76.95',
        '66.249.76.40',
        '66.249.69.106',
        '66.249.76.117',
        '64.234.32.81',
        '64.234.32.89',
        '64.234.32.83',
    ];

    while($count < $cycles)
    {
        $request_ua     = $uas[array_rand($uas)];
        $request_ip     = $ips[array_rand($ips)];
        $request_url    = 'https://'.$request_domain.'/api/v1/links?api_token='.$token.'&ip='.urlencode($request_ip).'&ua='.urlencode($request_ua);

        $start = microtime(true);

        $return = file_get_contents($request_url, false, stream_context_create([
            "ssl"=>[
                "verify_peer"=>false,
                "verify_peer_name"=>false,
            ]
        ]));

        $time_diff = microtime(true) - $start;
        $total_time = $total_time + $time_diff;
        echo $return.'<br />';
        echo number_format($time_diff, 3).'s <br />';
        $count++;
        if($count >= $cycles)
            break;
    }

    if($count > 0)
        echo '<br />'.$count.' requests made over '.number_format($total_time, 2).'s with an average of '.number_format(($total_time / $count), 4).'s per request.';
    else
        echo 'None';
});
