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



Auth::routes();

Route::get('/home', 'HomeController@index')->name('home');
Route::post('/tag/create','AdminController@addTag');
Route::get('/get_tags','AdminController@getTags');
Route::post('/edit_tag','AdminController@editTag');
Route::post('/delete_tag','AdminController@destroy');
Route::post('/app/upload_category','AdminController@uploadImg');
Route::post('/app/add_category','AdminController@addCategory');

Route::get('/get_categories','AdminController@getCategories');
Route::post('/app/deleteCatImg','AdminController@deleteCatImg');
Route::post('/app/editCategory','AdminController@editCategory');
Route::post('/app/deleteCategory','AdminController@deleteCategory');




Route::get('{any}', function () {
  return view('welcome');
});