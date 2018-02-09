<?php
use Laravel\Lumen\Routing\Router;

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
/** @var \Laravel\Lumen\Routing\Router $router */
$router->get('/', function () {
    return app()->version();
});

// Generate random string
$router->get('appKey', function () {
    return str_random('32');
});

// route for creating access_token
$router->post('accessToken', 'AccessTokenController@createAccessToken');

$router->group(['middleware' => ['auth:api', 'throttle:60']], function () use ($router) {
    $router->post('users', [
        'uses'       => 'UserController@store',
        'middleware' => "scope:users,users:create"
    ]);
    $router->get('users',  [
        'uses'       => 'UserController@index',
        'middleware' => "scope:users,users:list"
    ]);
    $router->get('users/{id}', [
        'uses'       => 'UserController@show',
        'middleware' => "scope:users,users:read"
    ]);
    $router->put('users/{id}', [
        'uses'       => 'UserController@update',
        'middleware' => "scope:users,users:write"
    ]);
    $router->delete('users/{id}', [
        'uses'       => 'UserController@destroy',
        'middleware' => "scope:users,users:delete"
    ]);
});

