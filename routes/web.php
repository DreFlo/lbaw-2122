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
// Home
Route::get('/', 'Auth\LoginController@home');
Route::get('home', 'HomeController@show');

// Cards
Route::get('cards', 'CardController@list');
Route::get('cards/{id}', 'CardController@show');

Route::resource('user_content', 'UserContentController');
Route::resource('posts', 'PostController');
Route::resource('shares', 'ShareController');
Route::post('comments/store', 'CommentController@store')->name('comments.add');
Route::post('likes/add', 'LikeController@store')->name('likes.add');
Route::post('likes/remove', 'LikeController@remove')->name('likes.remove');

// API
Route::put('api/cards', 'CardController@create');
Route::delete('api/cards/{card_id}', 'CardController@delete');
Route::put('api/cards/{card_id}/', 'ItemController@create');
Route::post('api/item/{id}', 'ItemController@update');
Route::delete('api/item/{id}', 'ItemController@delete');

// Authentication
Route::get('login', 'Auth\LoginController@showLoginForm');
Route::post('login', 'Auth\LoginController@login')->name('login');
Route::post('logout', 'Auth\LoginController@logout')->name('logout');
Route::get('register', 'Auth\RegisterController@showRegistrationForm')->name('register');
Route::post('register', 'Auth\RegisterController@create');

// Search
Route::get('/search', 'SearchController@search');

//Profile
Route::get('profile', 'UserController@profile');
Route::get('users/{id}', 'UserController@show');
Route::get('profile/edit', 'UserController@showEdit');
Route::patch('profile/edit', 'UserController@edit');
