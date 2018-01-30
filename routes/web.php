<?php

Auth::routes();

//前台
Route::get('/','HomeController@index');
Route::get('/topic/id/{id}','HomeController@topic');
Route::get('/posts/id/{id}','HomeController@posts');
Route::post('/posts','HomeController@posts')->name('posts');
Route::post('/lists','HomeController@lists')->name('lists');
Route::post('/topic','HomeController@topic');

//后台
Route::prefix('admin')->group(function(){
    Route::prefix('category')->group(function(){
        Route::post('/store','CategoryController@store');
        Route::post('/update','CategoryController@update');
        Route::post('/show','CategoryController@show');
        Route::post('/destroy','CategoryController@destroy');
    });
    
    Route::prefix('posts')->group(function(){
        Route::get('/create','PostsController@create')->name('create');
        Route::get('/edit/id/{id}','PostsController@edit');
        Route::post('/show','PostsController@show');
        Route::post('/store','PostsController@store');
        Route::post('/update','PostsController@update');
        Route::post('/destroy','PostsController@destroy');
        Route::post('/restore','PostsController@restore');
        Route::post('/upload','PostsController@upload')->name('upload');
    });
});