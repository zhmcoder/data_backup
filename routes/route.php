<?php

use Illuminate\Routing\Router;
use Illuminate\Support\Facades\Route;

Route::group([
    'prefix' => 'Api',
//    'namespace' => 'Andruby\\Pay\\Controllers',
    // 'middleware' => 'App\\Api\\Middleware\\verifySign' // todo 签名
], function (Router $router) {

});
