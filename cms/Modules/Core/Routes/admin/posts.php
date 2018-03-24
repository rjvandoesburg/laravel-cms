<?php

Route::group(['prefix' => 'posts'], function () {
    Route::resource('categories', 'CategoriesController');
});

Route::resource('posts', 'PostsController');
