<?php

/** @var \Laravel\Lumen\Routing\Router $router */

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

$router->get('/', function () use ($router) {
    return $router->app->version();
});


$user_routes = "user.";
$transfer_routes = "transfer.";

$router->post('/user', [
    'as' => $user_routes.'store', 'uses' => 'UserController@store'
]);

$router->get('/user', [
    'as' => $user_routes.'index', 'uses' => 'UserController@index'
]);

$router->get('/user/{id}', [
    'as' => $user_routes.'show', 'uses' => 'UserController@show'
]);

$router->put('/user/{id}', [
    'as' => $user_routes.'update', 'uses' => 'UserController@update'
]);

$router->delete('/user/{id}', [
    'as' => $user_routes.'destroy', 'uses' => 'UserController@destroy'
]);

$router->post('/transfer', [
    'middleware' => 'transfer',
    'as' => $transfer_routes.'transfer', 'uses' => 'TransferController@transfer'
]);

$router->get('/transfer', [
    'as' => $transfer_routes.'index', 'uses' => 'TransferController@index'
]);

$router->get('/transfer/{id}', [
    'as' => $transfer_routes.'show', 'uses' => 'TransferController@show'
]);

$router->get('/transfer/refund/{id}', [
    'as' => $transfer_routes.'refund', 'uses' => 'TransferController@refund'
]);

$router->delete('/transfer/{id}', [
    'as' => $transfer_routes.'destroy', 'uses' => 'TransferController@destroy'
]);
