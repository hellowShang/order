<?php

namespace App\Http\Controllers\Controller;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Session;

class CartController extends Controller
{
    // 商品数据展示
    public function goodsList(){
        // 查出商品数据 未下架的，未删除的
        $goodsInfo = DB::table('wechar_goods')->where(['goods_status' =>0,'is_up' => 1])->get();
        return view('goods.list',['goodsInfo' => $goodsInfo]);
    }

    // 加入购物车
    public function joinCart($goods_id){
        $arr = DB::table('wechar_goods')->where(['goods_id' =>$goods_id,'goods_status' => 0])->first();
        if($arr){
           if($arr->is_up){
               $info = [
                   'uid'            => Auth::id(),
                   'goods_id'       => $goods_id,
                   'goods_price'    => $arr->goods_price,
                   'buy_num'        => 1,
                   'session_id'     => Session::getId()
               ];

               $res = DB::table('wechar_cart')->insert($info);
               if($res){
                   header('Refresh:3;url=/cart/list');
                   die('加入购物车成功，3秒后自动跳转购物车页面');
               }else{
                   header('Refresh:3;url=/goods/list');
                   die('加入购物车失败');
               }
           }else{
               header('Refresh:3;url=/goods/list');
               die('该商品已下架，请关注后续详情');
           }
        }else{
            header('Refresh:3;url=/goods/list');
            die('暂无该商品信息');
        }
    }

    // 购物车数据展示
    public function cartList(){
        $cartInfo = DB::table('wechar_cart as c')
            ->join('wechar_goods as g','c.goods_id','=','g.goods_id')
            ->where(['uid' => Auth::id(),'session_id' =>Session::getId()])
            ->get();
        return view('cart.list',['cartInfo' => $cartInfo]);
    }
}
