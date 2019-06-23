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

//Route::middleware('auth:api')->get('/user', function (Request $request) {
//    return $request->user();
//});


Route::prefix('user')->group(function(){
    Route::post('register', 'User\UserController@register');
    Route::get('info/{id}', 'User\UserController@getUserData');
    Route::post('logout', 'User\UserController@logout');
    Route::put('update/{id}', 'User\UserController@updateUserInfo');
    Route::post('updatePassword/{id}', 'User\UserController@updateUserPassword');
    Route::put('delete/{id}', 'User\UserController@softDeleteUser');
    Route::get('all', 'User\UserController@getAllUsers');
});

Route::resource('languages', 'LanguageController')->only(['index', 'show', 'store', 'update', 'destroy']);
Route::resource('product-type', 'ProductTypeController')->only(['index', 'show', 'store', 'update', 'destroy']);
Route::resource('countries', 'CountryController')->only(['index', 'show', 'store', 'update', 'destroy']);
Route::resource('tags', 'TagController')->only(['index', 'show', 'store', 'update', 'destroy']);
Route::resource('branch-type', 'BranchTypeController')->only(['index', 'show', 'store', 'update', 'destroy']);
Route::resource('branch', 'BranchController')->only(['index', 'show', 'store', 'destroy']);

Route::prefix('branch')->group(function () {
    Route::post('update/{id}', 'BranchController@update');
    Route::get('branch-type/{typeId}', 'BranchController@getByTypeId');
});

Route::resource('agreements', 'AgreementController')->only(['index', 'show', 'store', 'update', 'destroy']);
Route::prefix('agreements')->group(function () {
   Route::get('person-contact/{contactId}', 'AgreementController@getByContactId');
});

Route::resource('divisions', 'DivisionController')->only(['index', 'show', 'store', 'update', 'destroy']);