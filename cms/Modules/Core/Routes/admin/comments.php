<?php

Route::resource('comments', 'CommentsController', ['only' => ['index']]);