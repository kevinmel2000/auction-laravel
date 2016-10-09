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

Route::get('/', 'HomeController@getIndex');


Route::group(['middleware' => ['web']], function() {
    Route::post('/validationRules', 'SystemController@getRules');
    Route::post('/validateFiled', 'SystemController@validateFiled');
    Route::post('/routes', 'SystemController@getRoutes');
    Route::post('/translation', 'SystemController@getTranslation');
    Route::post('/setUrl', 'SystemController@setRedirectUrl');
    Route::post('/registerAuction', ['as' => 'register.auction', 'uses' => 'SystemController@registerAuction']);

    Route::get('/registration', ['as' => 'registrationView', 'uses' => 'HomeController@getRegistration']);
    Route::get('/login', ['as' => 'loginView', 'uses' => 'HomeController@getLogin']);
    Route::get('/cr/{token?}', 'HomeController@confirmation');
    Route::get('/logout', 'HomeController@logout');

    Route::post('/registration', ['as' => 'registration', 'uses' => 'HomeController@postRegistration']);
    Route::post('/login', ['as' => 'login', 'uses' => 'HomeController@postLogin']);

    Route::get('product/{id}', ['as' => 'product.page', 'uses' => 'ProductsController@page']);
    Route::get('product/information/{id}', ['as' => 'product.information', 'uses' => 'ProductsController@information']);
    
    Route::get('auction', ['as' => 'auction.page', 'uses' => 'HomeController@auction']);
    Route::get('gamezone', ['as' => 'gamezone.page', 'uses' => 'HomeController@gameZone']);
    Route::post('auction', ['as' => 'auction.search', 'uses' => 'HomeController@search']);

    Route::group(['middleware' => ['auth']], function() {
        Route::group(['middleware' => ['admin']], function () {
            Route::get('/users/index', ['as' => 'users.index', 'uses' => 'UsersController@index']);

            Route::post('/users/table', ['as' => 'user.table', 'uses' => 'UsersController@table']);

            Route::resource('names', 'NamesController');
            Route::post('/names/table', ['as' => 'names.table', 'uses' => 'NamesController@table']);
            
            Route::resource('categories', 'CategoriesController');
            Route::post('/categories/table', ['as' => 'categories.table', 'uses' => 'CategoriesController@table']);

            Route::resource('products', 'ProductsController');
            Route::post('/products/table', ['as' => 'products.table', 'uses' => 'ProductsController@table']);
            
            Route::resource('templates', 'TemplatesController');
            Route::post('/templates/table', ['as' => 'templates.table', 'uses' => 'TemplatesController@table']);
            Route::get('/templates/upload', ['as' => 'templates.upload', 'uses' => 'TemplatesController@upload']);
        });
        
        Route::get('/profile/index', ['as' => 'profile.index', 'uses' => 'ProfileController@index']);
        Route::get('/profile/upload', ['as' => 'profile.upload.create', 'uses' => 'ProfileController@createUpload']);
        
        Route::post('/profile/upload', ['as' => 'profile.upload.store', 'uses' => 'ProfileController@storeUpload']);
        Route::post('/profile/upload/vk', ['as' => 'profile.vk.avatar.store', 'uses' => 'ProfileController@storeVKAvatar']);
        Route::post('/profile/search', ['as' => 'profile.vk.user.search', 'uses' => 'ProfileController@searchVKUser']);

        Route::put('/profile/profile', ['as' => 'profile.profile.update', 'uses' => 'ProfileController@updateProfile']);
        Route::put('/profile/data', ['as' => 'profile.data.update', 'uses' => 'ProfileController@updateData']);
        Route::put('/profile/password', ['as' => 'profile.password.update', 'uses' => 'ProfileController@updatePassword']);
        Route::put('/profile/address', ['as' => 'profile.address.update', 'uses' => 'ProfileController@updateAddress']);
        Route::put('/profile/notification', ['as' => 'profile.notification.update', 'uses' => 'ProfileController@updateNotification']);

        Route::delete('/profile/upload', ['as' => 'profile.upload.destroy', 'uses' => 'ProfileController@destroyUpload']);
        Route::get('/packages', ['as' => 'profile.bets.buy', 'uses' => 'ProfileController@getBetBuy']);

        Route::get('myauction', ['as' => 'myauction.page', 'uses' => 'HomeController@myAuction']);

        Route::group(['prefix' => 'payment/robokassa'], function () {
            Route::post('/', ['as' => 'payment.robokassa', 'uses' => 'PaymentController@payViaRobokassa']);
            Route::get('success',  ['as' => 'payment.robokassa.success', 'uses' => 'PaymentController@robokassaSuccess']);
            Route::get('fail',     ['as' => 'payment.robokassa.fail', 'uses' => 'PaymentController@robokassaFail']);
        });
    });
});

Route::get('/verify', ['as' => 'vk-login', 'uses' => 'HomeController@getVKLogin']);

Route::group(['middleware' => ['node']], function() {
    Route::get('node/getUser', 'SystemController@getUser');
    Route::get('/node/products/status', ['as' => 'products.status', 'uses' => 'ProductsController@updateStatus']);
});

Route::get('payment/robokassa/result',   ['as' => 'payment.robokassa.result', 'uses' => 'PaymentController@robokassaResult']);

