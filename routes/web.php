<?php

/*
|--------------------------------------------------------------------------
| Application Routes
|--------------------------------------------------------------------------
|
| Here is where you can register all of the routes for an application.
| It is a breeze. Simply tell Lumen the URIs it should respond to
| and give it the Closure to call when that URI is requested.
|
*/

$app->get('/', function () use ($app) {
    return $app->version();
});

// Generate random string
$app->get('appKey', function () {
    return str_random('32');
});

// route for creating access_token
$app->post('accessToken', 'AccessTokenController@createAccessToken');

$app->group(['middleware' => ['auth:api', 'throttle:60']], function () use ($app) {
    $app->post('users', 'UserController@store');
    $app->get('users', 'UserController@index');
    $app->get('users/{id}', 'UserController@show');
    $app->put('users/{id}', 'UserController@update');
    $app->delete('users/{id}', 'UserController@destroy');
});

