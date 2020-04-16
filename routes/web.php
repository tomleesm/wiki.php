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
Route::get('/read/{title}', 'ArticleController@show')->name('article.show');
Route::get('/edit/{title}', 'ArticleController@edit')->name('article.edit')->middleware('auth');
Route::match(['put', 'patch'], '/update/{title}', 'ArticleController@update')->name('article.update');

// Authentication
Route::get('input/username', 'Auth\LoginController@showInputUsernameForm')->name('signin');
Route::post('input/username', 'Auth\LoginController@validateUsername');
Route::get('input/password', 'Auth\LoginController@showInputPasswordForm');
Route::get('login', 'Auth\LoginController@showLoginForm');
Route::post('login', 'Auth\LoginController@login')->name('login');
Route::post('logout', 'Auth\LoginController@logout')->name('logout');

// register
Route::get('register', 'Auth\RegisterController@showRegistrationForm')->name('register');
Route::post('register', 'Auth\RegisterController@register');

// Password reset
Route::get('password/reset', 'Auth\ForgotPasswordController@showLinkRequestForm')->name('password.request');
Route::post('password/email', 'Auth\ForgotPasswordController@sendResetLinkEmail')->name('password.email');
Route::get('password/reset/{token}', 'Auth\ResetPasswordController@showResetForm')->name('password.reset');
Route::post('password/reset', 'Auth\ResetPasswordController@reset')->name('password.update');
Route::get('password/confirm', 'Auth\ConfirmPasswordController@showConfirmForm')->name('password.confirm');
Route::post('password/confirm', 'Auth\ConfirmPasswordController@confirm');

Route::get('/home', 'HomeController@index')->name('home');
Route::get('/', 'HomeController@index');

Route::get('/search', 'ArticleController@search')->name('article.search');
