<?php

use swo\route\Route;
// http路由
Route::get('welcome',function(){
    return 'aa';
});

Route::get('index','IndexController@index');

Route::get('test','IndexController@test');