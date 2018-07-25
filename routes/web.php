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
    return view('welcome');
});

Auth::routes();

Route::get('/home', 'HomeController@index')->name('home');
Route::get('/test', 'TestController@index')->name('test');
Route::get('/test2', 'TestController@test2')->name('test2');

/**
 * zy route
 */
Route::group(['prefix'=>'foods'], function () {
    Route::get('/', 'Foods\FoodsController@index')->name('foods');
    Route::get('home', 'Foods\FoodsController@home')->name('foods.home');
    Route::any('confirm', 'Foods\FoodsController@confirm')->name('foods.confirm');
    Route::any('order', 'Foods\FoodsController@order')->name('foods.order');
    Route::any('pay', 'Foods\FoodsController@pay')->name('foods.pay');
    Route::get('my_order', 'Foods\FoodsController@myOrder')->name('foods.myOrder');
    Route::get('my_order_detail', 'Foods\FoodsController@myOrderDetail')->name('foods.myOrderDetail');
    Route::any('comment', 'Foods\FoodsController@comment')->name('foods.comment');
    Route::get('coupon', 'Foods\FoodsController@coupon')->name('foods.coupon');
    Route::get('coupon_detail', 'Foods\FoodsController@couponDetail')->name('foods.couponDetail');
    Route::any('coupon_share', 'Foods\FoodsController@couponShare')->name('foods.couponShare');
    Route::any('team', 'Foods\FoodsController@team')->name('foods.team');
    Route::any('done', 'Foods\FoodsController@done')->name('foods.done');
});

/**
 * xujin route
 */
Route::group(['prefix' => 'waiter'], function() {
    Route::any('index', 'Waiter\RestaurantController@index');
    Route::any('test', 'Waiter\RestaurantController@test');
    Route::any('order', 'Waiter\RestaurantController@food_info');
    Route::any('reckoning', 'Waiter\RestaurantController@non_checkout');
    Route::any('voucher', 'Waiter\RestaurantController@voucher');
    Route::any('placeorder', 'Waiter\RestaurantController@placeorder_info');
    Route::any('over', 'Waiter\RestaurantController@place_order');
    Route::any('coupon', 'Waiter\RestaurantController@coupon');
    Route::any('cash', 'Waiter\RestaurantController@cash');
    Route::any('cashpay', 'Waiter\RestaurantController@confirm_payment');
    Route::any('wechat', 'Waiter\RestaurantController@wechat');
    Route::any('alipay', 'Waiter\RestaurantController@alipay');
    Route::any('addroom', 'Waiter\RestaurantController@add_room');
    Route::any('adddesk', 'Waiter\RestaurantController@add_desk');
    Route::any('login', 'Waiter\RestaurantController@login_validator');
    Route::any('empty', 'Waiter\RestaurantController@empty_desk');
    Route::any('table', 'Waiter\RestaurantController@desk_info');
    Route::any('menu','Waiter\RestaurantController@menu');
    Route::any('orderover','Waiter\RestaurantController@orderover');
    Route::any('discount','Waiter\RestaurantController@discount');
    Route::any('cancelorder','Waiter\RestaurantController@cancel_order');
    Route::any('manualprint','Waiter\RestaurantController@manual_print');
});

/**
 * seafood route
 */
Route::group(['prefix' => 'seafood'], function() {
    Route::any('index', 'Waiter\SeafoodController@index');
    Route::any('test', 'Waiter\SeafoodController@test');
    Route::any('order', 'Waiter\SeafoodController@food_info');
    Route::any('reckoning', 'Waiter\SeafoodController@non_checkout');
    Route::any('voucher', 'Waiter\SeafoodController@voucher');
    Route::any('placeorder', 'Waiter\SeafoodController@placeorder_info');
    Route::any('over', 'Waiter\SeafoodController@place_order');
    Route::any('coupon', 'Waiter\SeafoodController@coupon');
    Route::any('cash', 'Waiter\SeafoodController@cash');
    Route::any('cashpay', 'Waiter\SeafoodController@confirm_payment');
    Route::any('wechat', 'Waiter\SeafoodController@wechat');
    Route::any('alipay', 'Waiter\SeafoodController@alipay');
    Route::any('addroom', 'Waiter\SeafoodController@add_room');
    Route::any('adddesk', 'Waiter\SeafoodController@add_desk');
    Route::any('login', 'Waiter\SeafoodController@login_validator');
    Route::any('empty', 'Waiter\SeafoodController@empty_desk');
    Route::any('table', 'Waiter\SeafoodController@desk_info');
    Route::any('menu','Waiter\SeafoodController@menu');
    Route::any('orderover','Waiter\SeafoodController@orderover');
    Route::any('discount','Waiter\SeafoodController@discount');
    Route::any('cancelorder','Waiter\SeafoodController@cancel_order');
    Route::any('manualprint','Waiter\SeafoodController@manual_print');
});


/**
 * licongmin route
 */
//登陆
Route::any('login', 'Cashier\TableController@login');
//注册
Route::any('register', 'Cashier\TableController@register');
//接口路由

Route::group(['middleware'=>'logins','prefix' => 'cashier'],function() {
            Route::any('room', 'Cashier\TableController@room');//返回餐厅房间信息
            Route::any('table', 'Cashier\TableController@table');//返回房间桌位信息
            Route::any('order', 'Cashier\TableController@order');//点击桌号返回订单信息
            Route::any('print', 'Cashier\TableController@print_order');//打印厨房下单
            Route::any('food_info', 'Cashier\TableController@food_info');//点餐-获取所有商户菜单分类和菜和规格
            Route::any('edit_coupon', 'Cashier\TableController@edit_coupon');//桌台-修改商家优惠
            Route::any('paymoney_info', 'Cashier\TableController@paymoney_info');//桌台-未结账返回结账信息页
            Route::any('voucher', 'Cashier\TableController@voucher');//团购-验证团购套餐，成功返回套餐名
            Route::any('today_orders', 'Cashier\TableController@today_orders');//订单-今天统计返回今天订单数

});

