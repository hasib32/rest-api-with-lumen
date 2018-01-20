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
    $app->post('users', [
        'uses'       => 'UserController@store',
        'middleware' => "scope:users,users:create"
    ]);
    $app->get('users',  [
        'uses'       => 'UserController@index',
        'middleware' => "scope:users,users:list"
    ]);
    $app->get('users/{id}', [
        'uses'       => 'UserController@show',
        'middleware' => "scope:users,users:read"
    ]);
    $app->put('users/{id}', [
        'uses'       => 'UserController@update',
        'middleware' => "scope:users,users:write"
    ]);
    $app->delete('users/{id}', [
        'uses'       => 'UserController@destroy',
        'middleware' => "scope:users,users:delete"
    ]);
});

