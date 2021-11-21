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

$router->group(['prefix' => 'api'], function () use ($router) {
    
    $router->post('login', 'AuthController@login');
    $router->post('register', 'AuthController@register');

    //password reset
    $router->group(['prefix' => 'password'], function () use ($router) {
        $router->post('/reset-request', 'RequestPasswordController@sendResetLinkEmail');
        $router->get('/reset', [ 'as' => 'password.reset', 'uses' => 'ResetPasswordController@reset' ]);
        $router->post('/update/{email}', ['uses' => 'ResetPasswordController@update']);
        $router->post('/change-password', 'UserController@changePassword');
    });

    //category
    $router->group(['prefix' => 'category'], function () use ($router) {
        $router->get('/',  ['uses' => 'CategoryController@index']);
        $router->post('/', ['uses' => 'CategoryController@create']);
        $router->get('/{id}',  ['uses' => 'CategoryController@detail']);
        $router->delete('/{id}', ['uses' => 'CategoryController@delete']);
        $router->post('/{id}', ['uses' => 'CategoryController@update']);
    });

    //order
    $router->group(['prefix' => 'order'], function () use ($router) {
        $router->get('/{skip}/index',  ['uses' => 'OrderController@index']);
        $router->post('/', ['uses' => 'OrderController@create']);
        $router->get('/{order_number}',  ['uses' => 'OrderController@detail']);
        $router->put('/{id}', ['uses' => 'OrderController@update']);
        $router->post('delete', ['uses' => 'OrderController@delete']);
        $router->get('/{order_number}/search',  ['uses' => 'OrderController@searchOrder']);

        //detail order
        $router->get('/{order_number}/detail-item',  ['uses' => 'OrderController@detailItem']);
        $router->post('/create-detail',  ['uses' => 'OrderController@createDetail']);
        $router->post('/delete-detail', ['uses' => 'OrderController@deleteDetail']);
    });

    $router->group(['prefix' => 'dashboard'], function () use ($router) {
        $router->get('/recent-order',  ['uses' => 'DashboardController@recentOrder']);
        $router->get('/total-order',  ['uses' => 'DashboardController@totalOrder']);
        $router->get('/total-category',  ['uses' => 'DashboardController@totalCategory']);
    });

});