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
Route::get('/article/home', 'ArticleController@show')->name('article.show');
Route::get('/article/edit/home', 'ArticleController@edit')->name('article.edit');
Route::match(['put', 'patch'], '/article/home', 'ArticleController@update')->name('article.update');

Auth::routes();

Route::get('/', 'HomeController@index')->name('home');
