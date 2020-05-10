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

$router->get('/', function () use ($router) {
    return 'Primeira API REST com Lumen... ' . $router->app->version();
});

$router->group(['prefix' => 'usuarios'], function() use($router){

    $router->get('/', 'UserController@index');
    $router->get('/{user}', 'UserController@show');

    $router->post('/','UserController@store');
    $router->put('/{user}', 'UserController@update');
    $router->delete('/{user}', 'UserController@destroy');

});

$router->group(['prefix' => 'pay'], function() use($router){

    $router->post('/', 'PaymentController@pay');

    $router->get('/{user}', 'PaymentController@withdraw');
    $router->post('/{user}','PaymentController@deposit');


});