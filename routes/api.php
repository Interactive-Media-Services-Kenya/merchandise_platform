<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;

/*
|--------------------------------------------------------------------------
| API Routes
|--------------------------------------------------------------------------
|
| Here is where you can register API routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| is assigned the "api" middleware group. Enjoy building your API!
|
*/

Route::middleware('auth:api')->get('/user', function (Request $request) {
    return $request->user();

});

    // Public
    // Login and Auth APi
    Route::post('/v1/login','Auth\LoginController@LoginApi')->name('api.login');
    Route::post('/v1/token','Auth\LoginController@verifyOTPApi')->name('api.verifyOTP');
    //Protected Routes
    Route::middleware('auth:sanctum')->group(function() {
        Route::get('/v1/user', 'Api\SPAApiController@user')->name('api.user');
        Route::post('/v1/logout-api','Auth\LoginController@logoutApi')->name('api.logoutApi');
        // ? Post request *** product_code ***, *** customer_name ***, *** customer_phone ***
        Route::post('/v1/issue-merchandise-ba','Api\SPAApiController@IssueProductBA')->name('api.issue-merchandise-ba');
        Route::get('/v1/outlets','Api\SPAApiController@outlets')->name('api.oulets');
        Route::post('/v1/merchandise_confirmation','Api\SPAApiController@productConfirmation')->name('api.productConfirmation');
        Route::get('/v1/merchandise_types','Api\SPAApiController@merchandise_types')->name('api.merchandise_types');
        Route::get('/v1/client_brands','Api\SPAApiController@client_brands')->name('api.client_brands');
        Route::get('/v1/storages','Api\SPAApiController@storages')->name('api.storages');
        Route::get('/v1/colors','Api\SPAApiController@colors')->name('api.colors');
        Route::get('/v1/batches','Api\SPAApiController@batches')->name('api.batches');
        Route::get('/v1/reject_reasons','Api\SPAApiController@rejectReasons')->name('api.reject_reasons');
        Route::post('/v1/batch_accept','Api\SPAApiController@batchAccept')->name('api.batch_accept');
        Route::get('/v1/batch_products/{batch}','Api\SPAApiController@batchProducts')->name('api.batch_products');
        Route::post('/v1/batch_reject','Api\SPAApiController@batchReject')->name('api.batch_reject');
        Route::get('/v1/sizes','Api\SPAApiController@sizes')->name('api.sizes');
        Route::post('/v1/upload_merchandise','Api\SPAApiController@uploadMerchandise')->name('api.upload_merchandise');

    });
