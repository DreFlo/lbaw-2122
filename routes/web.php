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
Route::get('contacts', 'HomeController@showContacts')->name('contacts');
Route::get('about', 'HomeController@showAbout')->name('about');

Route::resource('user_content', 'UserContentController')->whereNumber('user_content');
Route::resource('posts', 'PostController')->whereNumber('post');
Route::get('posts/{post}/share', 'PostController@share')->whereNumber('post')->name('posts.share');
Route::get('shares/{share}', 'ShareController@show')->whereNumber('share');
Route::post('shares', 'ShareController@store')->name('shares.store')->whereNumber('share');
Route::post('comments', 'CommentController@store')->name('comments.add');
Route::get('comments/{comment}', 'CommentController@show')->whereNumber('comment');

// API
Route::post('api/likes', 'LikeController@store');
Route::delete('api/likes', 'LikeController@remove');
Route::post('api/search/users', 'UserController@searchAux');
Route::post('api/users/ban', 'UserController@ban');
Route::post('api/users/unban', 'UserController@unban');
Route::post('api/accept_friend_request', 'FriendRequestController@accept');
Route::post('api/deny_friend_request', 'FriendRequestController@deny');

// Authentication
Route::get('login', 'Auth\LoginController@showLoginForm');
Route::post('login', 'Auth\LoginController@login')->name('login');
Route::post('logout', 'Auth\LoginController@logout')->name('logout');
Route::get('register', 'Auth\RegisterController@showRegistrationForm')->name('register');
Route::post('register', 'Auth\RegisterController@register');
Route::get('forget-password', 'Auth\ForgotPasswordController@showForgetPasswordForm')->name('forget.password.get');
Route::post('forget-password', 'Auth\ForgotPasswordController@submitForgetPasswordForm')->name('forget.password.post'); 
Route::get('reset-password/{token}', 'Auth\ForgotPasswordController@showResetPasswordForm')->name('reset.password.get');
Route::post('reset-password', 'Auth\ForgotPasswordController@submitResetPasswordForm')->name('reset.password.post');

// Search
Route::get('/search', 'SearchController@search');

//Profile
Route::get('users', 'UserController@index');
Route::get('profile', 'UserController@profile')->name('profile');
Route::get('users/{id}', 'UserController@show')->whereNumber('id');
Route::delete('users/{user}', 'UserController@destroy')->whereNumber('id')->name('users.destroy');
Route::get('profile/edit', 'UserController@showEdit')->name('profile/edit');
Route::patch('profile/edit', 'UserController@edit');

// Notifications
Route::get('notifications/post', 'NotificationController@post');
Route::get('notifications/request', 'NotificationController@request');

// Group
Route::resource('groups', 'GroupController')->whereNumber('group');
Route::get('groups/{group}/create_post', 'PostController@createInGroup')->whereNumber('group');
Route::get('group/members', 'GroupController@showMembers');
