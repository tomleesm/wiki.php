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
Route::get('input/username', 'Auth\LoginController@showInputUsernameForm');
Route::post('input/username', 'Auth\LoginController@validateUsername');
Route::get('input/password', 'Auth\LoginController@showInputPasswordForm');
Route::get('login', 'Auth\LoginController@showLoginForm');
Route::post('login', 'Auth\LoginController@login')->name('login');
Route::get('logout', 'Auth\LoginController@logout');

// register
Route::get('register', 'Auth\RegisterController@showRegistrationForm');
Route::post('register', 'Auth\RegisterController@register');

// Password reset
Route::get('password/reset', 'Auth\ForgotPasswordController@showLinkRequestForm');
Route::post('password/email', 'Auth\ForgotPasswordController@sendResetLinkEmail');
Route::get('password/reset/{token}', 'Auth\ResetPasswordController@showResetForm');
Route::post('password/reset', 'Auth\ResetPasswordController@reset');

Route::get('/', 'HomeController@index')->name('home');
