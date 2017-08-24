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

Route::get('/', 'ProductController@index')->name('product');
Route::get('/user/profile/','ProductController@getProfile');

Route::get('/addToCart/{id}','ProductController@addToCart');
Route::get('/shoppingCart','ProductController@getCart');

Route::get('/checkout','ProductController@checkout')->name('checkout');
Route::post('/checkout','ProductController@postCheckout');
Route::get('/forget','ProductController@getForget');

Auth::routes();

Route::get('/home', 'HomeController@index')->name('home');
