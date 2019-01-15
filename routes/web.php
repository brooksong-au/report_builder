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
    return $router->app->version();
});

$router->group(['namespace' => 'Report', 'prefix' => 'report'], function () use ($router){
    $router->get('/runall', ['as' => 'runall', 'uses' => 'ReportController@runAll']);
    $router->get('/apikeys', ['as' => 'apikeys', 'uses' => 'ReportController@apiKeys']);
    $router->get('/download', ['as' => 'download', 'uses' => 'ReportController@download']);
});