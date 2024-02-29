<?php

use Illuminate\Support\Facades\Route;

Route::group(['namespace' => 'App\\Http\\Controllers\\Articles'], function() {
    Route::get('/', 'ArticleController@index')->name('index');
    Route::get('/edit/{file}', 'ArticleController@edit')->name('edit');
    Route::patch('/update/{file}', 'ArticleController@update')->name('update');
});
