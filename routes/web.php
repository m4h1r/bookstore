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

Route::get('/', function () {
    $books = \App\Entities\Book::where('publish', 1)->latest()->get();

    return view('index', compact('books'));
});

Auth::routes();

Route::group(['middleware' => 'auth'], function() {
    Route::group(['prefix' => 'wallet', 'as' => 'wallet.'], function() {
        Route::get('/', 'HomeController@wallet')->name('index');
        Route::get('{id}/buy', 'HomeController@wallet_buy')->name('buy');
    });

    Route::group(['prefix' => 'library', 'as' => 'library.'], function() {
        Route::get('/', 'HomeController@library')->name('index');
        Route::get('/{slug}', 'HomeController@book')->name('book');

        Route::get('/{id}/buy', 'HomeController@book_buy')->name('book.buy');
    });
    
    Route::group(['prefix' => 'admin', 'as' => 'admin.', 'namespace' => 'Admin', 'middleware' => 'admin'], function() {
        Route::resource('users', 'UserController')->except(['show']);
        Route::resource('roles', 'RoleController')->except(['show']);
        Route::resource('plans', 'PlanController')->except(['show']);
        Route::resource('authors', 'AuthorController')->except(['show']);
        Route::resource('books', 'BookController');
    });
});
