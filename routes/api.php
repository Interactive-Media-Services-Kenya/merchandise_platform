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
    Route::get('/v1/products-issued-per-month', 'DashboardApiController@productsPerMonth')->name('api.products.issued-per-month');
    Route::get('/v1/products-issued-per-month-client/{id}', 'DashboardApiController@productsPerMonthClient')->name('api.products.issued-per-month.client');
    Route::get('/v1/products-issued-per-type', 'DashboardApiController@productsPerType')->name('api.products.issued-per-type');
    Route::get('/v1/products-issued-per-type-client/{id}', 'DashboardApiController@productsPerTypeClient')->name('api.products.issued-per-type.client');
    Route::get('/v1/products-issued-per-type-per-month', 'DashboardApiController@productsPerTypePerMonth')->name('api.products.issued-per-type-per-month');
    Route::get('/v1/activities', 'DashboardApiController@activitiesApi')->name('api.activitiesApi');
