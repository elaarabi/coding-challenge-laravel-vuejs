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

//Product's routes
Route::get('products', 'App\Http\Controllers\API\ProductController@index');
Route::get('product/{id}', 'App\Http\Controllers\API\ProductController@show');
Route::post('product', 'App\Http\Controllers\API\ProductController@store');
Route::put('product', 'App\Http\Controllers\API\ProductController@update');
Route::delete('product/{id}', 'App\Http\Controllers\API\ProductController@destroy');

//Categories routes
Route::get('categories', 'App\Http\Controllers\API\CategoryController@index');
Route::get('categories/search', 'App\Http\Controllers\API\CategoryController@search');
Route::get('category/{id}', 'App\Http\Controllers\API\CategoryController@show');
Route::post('category', 'App\Http\Controllers\API\CategoryController@store');
Route::put('category', 'App\Http\Controllers\API\CategoryController@update');
Route::delete('category/{id}', 'App\Http\Controllers\API\CategoryController@destroy');

