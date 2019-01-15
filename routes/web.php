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

Route::group(array('namespace' => 'Report', 'prefix' => 'report'), function()
{
    Route::get('/runall', array('as'=>'runall', 'uses'=> 'ReportController@runAll'));
    Route::get('/apikeys', array('as'=>'apikeys', 'uses'=> 'ReportController@apiKeys'));
    Route::get('/download', array('as'=>'download', 'uses'=> 'ReportController@download'));
});