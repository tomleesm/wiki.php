<?php

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
Route::get('/articles/create', 'ArticleController@create')->name('articles.create');
Route::post('/articles', 'ArticleController@store')->name('articles.store');
Route::get('/articles/{title}', 'ArticleController@show')->name('articles.show');
Route::get('/articles/{title}/edit', 'ArticleController@edit')->name('articles.edit');
Route::match(['put', 'patch'], '/articles/{title}', 'ArticleController@update')->name('articles.update');
Route::post('/preview', 'ArticleController@preview');

Route::get('/images/{image}', 'ImageController@show')->name('images.show');
Route::post('/upload/image', 'ArticleController@uploadImage');

Route::get('/', 'HomeController@index');
