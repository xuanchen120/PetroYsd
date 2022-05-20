<?php

use Illuminate\Routing\Router;
use Illuminate\Support\Facades\Route;

//总后台
Route::group([
    'prefix'     => config('admin.route.prefix'),
    'namespace'  => 'XuanChen\Petro\Controllers\Admin',
    'middleware' => config('admin.route.middleware'),
], function (Router $router) {
    $router->get('petrocoupons', 'CouponController@index');
    $router->get('petrologs', 'LogController@index');
});

//手机端
Route::group([
    'prefix'    => 'api/V1',
    'namespace' => 'XuanChen\Petro\Controllers\Api',
], function (Router $router) {
    //中石油
    Route::post('petro/grant', 'IndexController@grant');                 //发券
    Route::post('petro/query', 'IndexController@query');                 //查询
    Route::post('petro/destroy', 'IndexController@destroy');             //作废
    Route::post('petro/notice', 'IndexController@notice');             //回调
    Route::post('petro/dyanmic', 'IndexController@checkCode');             //动态码
});
