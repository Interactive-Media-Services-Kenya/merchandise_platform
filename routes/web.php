<?php

use Illuminate\Support\Facades\Route;

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| Here is where you can register web routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| contains the "web" middleware group. Now create something great!
|
*/

Route::get('/', function () {
    return view('welcome');
});

Auth::routes();

// Route::get('/home', [App\Http\Controllers\HomeController::class, 'index'])->name('home');

Route::group(['middleware' => ['auth:web']], function () {
    Route::get('/home', 'HomeController@index')->name('home');
    // Permissions
    // Route::delete('permissions/destroy', 'PermissionsController@massDestroy')->name('permissions.massDestroy');
    // Route::resource('permissions', 'PermissionsController');

    // Roles
    // Route::delete('roles/destroy', 'RolesController@massDestroy')->name('roles.massDestroy');
    // Route::resource('roles', 'RolesController');

    // Users
    Route::delete('users/destroy', 'UsersController@massDestroy')->name('users.massDestroy');
    Route::resource('users', 'UsersController');
    Route::get('delete/user/{user}','UsersController@destroyUser')->name('users.destroyUser');


    //Teamleaders
    Route::get('teamleaders', 'UsersController@teamleaders')->name('teamleaders');

    // Brand Ambassadors
    Route::get('brandambassadors', 'UsersController@brandambassadors')->name('brandambassadors');


    // Categories
    Route::delete('categories/destroy', 'CategoriesController@massDestroy')->name('categories.massDestroy');
    Route::resource('categories', 'CategoriesController');
    Route::get('delete/category/{category}','CategoriesController@destroyCategory')->name('categories.destroyCategory');

    // Categories
    Route::delete('clients/destroy', 'ClientsController@massDestroy')->name('clients.massDestroy');
    Route::resource('clients', 'ClientsController');
    Route::get('delete/client/{client}','ClientsController@destroyClient')->name('clients.destroyClient');

    // Categories
    Route::delete('products/destroy', 'ProductsController@massDestroy')->name('products.massDestroy');
    Route::resource('products', 'ProductsController');
    Route::get('delete/product/{product}','ProductsController@destroyProduct')->name('products.destroyProduct');
    Route::post('products/store/bas', 'ProductsController@storeBas')->name('products.storebas');

});
