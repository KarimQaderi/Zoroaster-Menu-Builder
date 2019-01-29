<?php

    use Illuminate\Support\Facades\Route;
    Route::get('/destroy' , 'MenuController@destroy')->name('destroy');
    Route::get('/find' , 'MenuController@find')->name('find');
    Route::get('/save-items' , 'MenuController@saveItems')->name('saveItems');
    Route::post('/new-item' , 'MenuController@createNew')->name('new-item');
    Route::post('/update' , 'MenuController@update')->name('update');
