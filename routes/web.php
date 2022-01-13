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
Route::get('home', 'HomeController@show')->name('home');

Route::resource('user_content', 'UserContentController');
Route::resource('posts', 'PostController');
Route::get('posts/{post}/share', 'PostController@share')->name('posts.share');
Route::get('shares/{share}', 'ShareController@show');
Route::post('shares', 'ShareController@store')->name('shares.store');
Route::post('comments', 'CommentController@store')->name('comments.add');
Route::get('comments/{comment}', 'CommentController@show');

// API
Route::post('api/likes', 'LikeController@store');
Route::delete('api/likes', 'LikeController@remove');
Route::post('api/search/users', 'UserController@searchAux');
Route::post('api/users/ban', 'UserController@ban');
Route::post('api/users/unban', 'UserController@unban');

// Authentication
Route::get('login', 'Auth\LoginController@showLoginForm');
Route::post('login', 'Auth\LoginController@login')->name('login');
Route::post('logout', 'Auth\LoginController@logout')->name('logout');
Route::get('register', 'Auth\RegisterController@showRegistrationForm')->name('register');
Route::post('register', 'Auth\RegisterController@register');

// Search
Route::get('/search', 'SearchController@search');

//Profile
Route::get('users', 'UserController@index');
Route::get('profile', 'UserController@profile')->name('profile');
Route::get('users/{id}', 'UserController@show');
Route::get('profile/edit', 'UserController@showEdit')->name('profile/edit');
Route::patch('profile/edit', 'UserController@edit');

// Group
Route::resource('groups', 'GroupController');
Route::get('group/members', 'GroupController@showMembers');

