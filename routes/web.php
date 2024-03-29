<?php

use Illuminate\Support\Facades\Route;
use Illuminate\Support\Facades\URL;

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
    if (!\Auth::check()) {
       return view('auth.login');
    }else{
        return redirect()->route('home');
    }
});

Auth::routes();

Route::get('otp', 'OTPController@index')->name('otp.index');
Route::post('otp', 'OTPController@store')->name('otp.post');
Route::get('otp/reset', 'OTPController@resend')->name('otp.resend');

// Route::get('/home', [App\Http\Controllers\HomeController::class, 'index'])->name('home');

Route::group(['middleware' => ['auth:web','otp']], function () {
    Route::get('/home', 'HomeController@index')->name('home');
    Route::post('/password/update-first-time-login', 'HomeController@updatePassword')->name('password.update-first-time');
    Route::get('/users/get','HomeController@users')->name('users.register');


    Route::get('/v1/products-issued-per-month', 'DashboardApiController@productsPerMonth')->name('api.products.issued-per-month');
    Route::get('/v1/products-issued-per-month-client/{id}', 'DashboardApiController@productsPerMonthClient')->name('api.products.issued-per-month.client');
    Route::get('/v1/products-issued-per-type', 'DashboardApiController@productsPerType')->name('api.products.issued-per-type');
    Route::get('/v1/products-issued-per-type-client/{id}', 'DashboardApiController@productsPerTypeClient')->name('api.products.issued-per-type.client');
    Route::get('/v1/products-issued-per-type-per-month', 'DashboardApiController@productsPerTypePerMonth')->name('api.products.issued-per-type-per-month');
    Route::get('/v1/activities', 'DashboardApiController@activitiesApi')->name('api.activitiesApi');

    //Import Users
    Route::get('import/users', 'UsersController@importUsers')->name('users.import');
    Route::get('import/agency', 'UsersController@getImportAgency')->name('import.agency');
    Route::get('import/teamleaders', 'UsersController@getImportTeamleader')->name('import.teamleader');
    Route::get('import/bas', 'UsersController@getImportBas')->name('import.bas');
    Route::post('import/users', 'UsersController@submitImport')->name('import.submit');


    Route::get('/ajax-outlet-search', 'OutletController@selectSearch')->name('outlet.select.search');

    // Permissions
    // Route::delete('permissions/destroy', 'PermissionsController@massDestroy')->name('permissions.massDestroy');
    Route::resource('permissions', 'PermissionController');

    // Roles
    // Route::delete('roles/destroy', 'RolesController@massDestroy')->name('roles.massDestroy');
    // Route::resource('roles', 'RolesController');

    // Users
    Route::delete('users/destroy', 'UsersController@massDestroy')->name('users.massDestroy');
    Route::resource('users', 'UsersController');
    Route::get('delete/user/{user}','UsersController@destroyUser')->name('users.destroyUser');

    //All Activities
    Route::get('activities', 'ActivityController@index')->name('activities');

    //Teamleaders
    Route::get('teamleaders/edit/{id}', 'UsersController@teamleadersEdit')->name('teamleaders.edit');
    Route::put('teamleaders/edit/{id}', 'UsersController@teamleadersUpdate')->name('teamleaders.update');
    Route::get('teamleaders', 'UsersController@teamleaders')->name('teamleaders');
    Route::get('agencies', 'UsersController@agencies')->name('agencies');
    Route::get('agencies/show/{id}', 'UsersController@agencyShow')->name('agencies.show');

    // Brand Ambassadors
    Route::get('brandambassadors', 'UsersController@brandambassadors')->name('brandambassadors');
    Route::get('brandambassadors/create', 'UsersController@brandambassadorCreate')->name('brandambassadors.create');
    Route::post('brandambassadors/BAstore', 'UsersController@BAstore')->name('brandambassador.store');
    Route::get('brandambassador/{ba}', 'UsersController@showBa')->name('brandambassador.show');

    //Show Batch with associated products route
    Route::get('batch/show/issued/{batch}','BatchController@showIssued')->name('batch.show.issued');

    Route::get('batch/show/{batch}','BatchController@show')->name('batch.show');
    Route::get('batches','BatchController@index')->name('batches.index');
    Route::get('batches/confirm/batch/{batch_code}','BatchController@confirmBatch')->name('batch.confirm');
    Route::post('batch/reject/{batch}','BatchController@rejectBatch')->name('reject.batch');

    // Categories
    Route::delete('categories/destroy', 'CategoriesController@massDestroy')->name('categories.massDestroy');
    Route::resource('categories', 'CategoriesController');
    Route::get('delete/category/{category}','CategoriesController@destroyCategory')->name('categories.destroyCategory');

    // Clients
    Route::delete('clients/destroy', 'ClientsController@massDestroy')->name('clients.massDestroy');
    Route::resource('clients', 'ClientsController');
    Route::get('delete/client/{client}','ClientsController@destroyClient')->name('clients.destroyClient');

    // Products
    Route::delete('products/destroy', 'ProductsController@massDestroy')->name('products.massDestroy');
    Route::get('products/product-codes', 'ProductCodeController@index')->name('products.product-codes');
    Route::get('products/product-codes/create', 'ProductCodeController@create')->name('products.product-codes.create');
    Route::post('products/product-codes/create', 'ProductCodeController@store')->name('products.product-codes.store');
    Route::get('products/upload-merchandise', 'ProductsController@createUpload')->name('products.create-upload');
    Route::post('products/upload-merchandise', 'ProductsController@uploadMerchandise')->name('products.upload-merchandise');
    Route::get('products/agency/{id}','ProductsController@indexAgency')->name('products.index.agency');
    Route::resource('products', 'ProductsController');
    Route::get('products/assignproducts/create', 'ProductsController@assignProductsCreate')->name('products.assign.create');
    Route::get('products/assignproducts/createba', 'ProductsController@assignProductsCreateBA')->name('products.assign.brandambbassador');
    Route::get('products/assignproducts/createtl', 'ProductsController@assignProductsCreateTL')->name('products.assign.teamleader');
    Route::post('products/assignproducts/storeTL', 'ProductsController@storeTL')->name('products.storeTL');
    Route::post('products/assignproducts/storeBA', 'ProductsController@storeBA')->name('products.storeBA');
    Route::post('products/assignproducts/storeagency', 'ProductsController@storeAgency')->name('products.storeagency');
    Route::post('product/reject/{product}','ProductsController@reject')->name('product.reject');
    Route::get('product/confirm/{product}','ProductsController@confirm')->name('product.confirm');
    Route::get('products/confirm/batch/{batch_code}','ProductsController@confirmBatch')->name('products.confirm.batch');
    Route::post('products/reject/batch/{batch_code}','ProductsController@rejectBatch')->name('products.reject.batch');
    Route::post('products/issue/product','ProductsController@issueProduct')->name('products.issue.product');
    Route::get('products/issue/product/{product}/{batch}','ProductsController@issueProductCustomer')->name('products.issue.product.customer');
    Route::post('products/issue/batch','ProductsController@issueBatch')->name('products.issue.batch');
    Route::get('delete/product/{product}','ProductsController@destroyProduct')->name('products.destroyProduct');
    Route::post('products/store/bas', 'ProductsController@storeBas')->name('products.storebas');


    //Reports

    Route::get('reports','ReportController@index')->name('reports');
    Route::get('reports/products','ReportController@products')->name('report.products');
    Route::get('reports/products/client','ReportController@productsClient')->name('report.products.client');
    Route::get('reports/clients','ReportController@clients')->name('report.clients');
    Route::get('reports/merchandise-type','ReportController@productTypes')->name('report.product-type');
    Route::get('reports/merchandise-type-client/{id}','ReportController@productTypesClient')->name('report.product-type.client');
    Route::get('reports/teamleaders','ReportController@teamleaders')->name('report.teamleaders');


    //Storages
    Route::resource('storages','StorageController');
    Route::get('delete/storage/{storage}','StorageController@destroyStorage')->name('storages.destroyStorage');

    //Outlets
    Route::get('outlet/delete/{id}','OutletController@destroyOutlet')->name('outlets.destroyOutlet');
    Route::resource('outlets','OutletController');


    //Brands
    Route::get('brand/delete/{id}','BrandController@destroyBrand')->name('brands.destroyBrand');
    Route::resource('brands','BrandController');


    //Campaigns
    Route::get('campaign/delete/{id}','CampaignController@destroyCampaign')->name('campaigns.destroyCampaign');
    Route::post('dynamic_dependent/fetch', 'ClientsController@fetch')->name('dynamicdependent.fetch');
    Route::post('dynamic_dependent/fetch/campaigns', 'ClientsController@fetchCampaign')->name('dynamicdependent.fetch.campaign');
    Route::resource('campaigns','CampaignController');
});

Route::get('/optimize', function() {
    \Artisan::call('optimize:clear');
    return "Cache is cleared";
});

Route::get('/migrate', function() {
    \Artisan::call('migrate');
    return "Migration successfull";
});
