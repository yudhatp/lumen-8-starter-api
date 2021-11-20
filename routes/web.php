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

    //$router->get('tracking/{nota}', 'TrackingController@trackingByNota');

    //master data
    /*$router->group(['prefix' => 'barang'], function () use ($router) {
        $router->get('/',  ['uses' => 'BarangController@index']);
        $router->post('/', ['uses' => 'BarangController@create']);
        $router->get('/{id}',  ['uses' => 'BarangController@detail']);
        $router->delete('/{id}', ['uses' => 'BarangController@delete']);
        $router->post('/{id}', ['uses' => 'BarangController@update']);
    });*/

    $router->group(['prefix' => 'category'], function () use ($router) {
        $router->get('/',  ['uses' => 'CategoryController@index']);
        $router->post('/', ['uses' => 'CategoryController@create']);
        $router->get('/{id}',  ['uses' => 'CategoryController@detail']);
        $router->delete('/{id}', ['uses' => 'CategoryController@delete']);
        $router->post('/{id}', ['uses' => 'CategoryController@update']);
    });

    $router->group(['prefix' => 'status'], function () use ($router) {
        $router->get('/',  ['uses' => 'StatusController@index']);
        $router->post('/', ['uses' => 'StatusController@create']);
        $router->get('/{id}',  ['uses' => 'StatusController@detail']);
        $router->delete('/{id}', ['uses' => 'StatusController@delete']);
        $router->post('/{id}', ['uses' => 'StatusController@update']);
    });

    //order
    $router->group(['prefix' => 'order'], function () use ($router) {
        $router->get('/',  ['uses' => 'OrderController@index']);
        $router->post('/', ['uses' => 'OrderController@create']);
        $router->get('/{order_number}',  ['uses' => 'OrderController@detail']);
        //$router->delete('/{id}', ['uses' => 'TransactionController@delete']);
        $router->put('/{id}', ['uses' => 'OrderController@update']);
        $router->post('delete', ['uses' => 'OrderController@delete']);

        //detail order
        $router->get('/{order_number}/detail-item',  ['uses' => 'OrderController@detailItem']);
        $router->post('/create-detail',  ['uses' => 'OrderController@createDetail']);
        $router->post('/delete-detail', ['uses' => 'OrderController@deleteDetail']);
    });

    //$router->post('hapus-transaction-status', ['uses' => 'TransactionController@deleteStatus']);
    //$router->get('transaction-total-pendapatan',  ['uses' => 'TransactionController@totalPendapatan']);
    $router->get('transaction-selesai',  ['uses' => 'TransactionController@selesai']);
    $router->get('transaction-belum-selesai',  ['uses' => 'TransactionController@belumSelesai']);
    //$router->get('transaction-detail-status/{nota}',  ['uses' => 'TransactionController@detailStatus']);
    //$router->post('transaction-create-status',  ['uses' => 'TransactionController@createStatus']);

    
    
});