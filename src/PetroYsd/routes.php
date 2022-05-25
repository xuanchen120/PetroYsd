<?php

use Illuminate\Routing\Router;
use Illuminate\Support\Facades\Route;

//总后台
Route::group([
    'prefix'     => config('admin.route.prefix'),
    'namespace'  => 'XuanChen\PetroYsd\Controllers\Admin',
    'middleware' => config('admin.route.middleware'),
], function (Router $router) {
    $router->get('petro_new/coupons', 'CouponController@index');
    $router->get('petro_new/logs', 'LogController@index');
});

//手机端
Route::group([
    'prefix'    => 'api/V1/petro/v2',
    'namespace' => 'XuanChen\PetroYsd\Controllers\Api',
], function (Router $router) {
    //中石油
    Route::post('grant', 'IndexController@grant');                 //发券
    Route::post('query', 'IndexController@query');                 //查询
    Route::post('destroy', 'IndexController@destroy');             //作废
    Route::post('notice', 'IndexController@notice')->name('petro_new.notice');             //回调
    Route::post('grant_notice', 'IndexController@grantNotice')->name('petro_new.grant_notice');             //回调
});
