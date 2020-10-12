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
Route::get('/articles/{title}', 'ArticleController@show')->name('article.show');
Route::get('/edit/{title}', 'ArticleController@edit')->name('article.edit');
Route::match(['put', 'patch'], '/update/{title}', 'ArticleController@update')->name('article.update');
Route::post('/render-markdown', 'ArticleController@renderMarkdown');
Route::post('/upload/image', 'ArticleController@uploadImage');
Route::get('/images/{image}', 'ArticleController@showImage');

Route::get('/home', 'HomeController@index')->name('home');
Route::get('/', 'HomeController@index');
