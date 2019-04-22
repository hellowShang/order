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


// 商品数据展示
Route::get('/goods/list', 'Controller\CartController@goodsList');

// 加入购物车
Route::get('/cart/add/{goods_id}', 'Controller\CartController@joinCart');

// 购物车页面
Route::get('/cart/list', 'Controller\CartController@cartList');

// 生成订单
Route::get('/order/submitOrder', 'Controller\OrderController@create');

// 订单展示
Route::get('/order/list', 'Controller\OrderController@orderList');

// 订单支付
Route::get('/wechar/pay/{order_sn}', 'Controller\WecharController@pay');

// 异步通知
Route::post('/wechar/notify', 'Controller\WecharController@notify');

// 查询支付状态
Route::get('/order/payStatus/{order_sn}', 'Controller\OrderController@payStatus');

// 支付成功
Route::get('/order/success/{order_sn}','Controller\OrderController@success');


Route::get('/order/msg',function(){
    $res = DB::table('wechar_order')->where(['uid' => 1,'order_sn' =>'1809_20190422005882b593bbc5114d0'])->update(['pay_status' => 1]);
    dd($res);
});