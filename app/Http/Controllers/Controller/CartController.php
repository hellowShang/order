<?php

namespace App\Http\Controllers\Controller;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Session;
use Illuminate\Support\Facades\Redis;

class CartController extends Controller
{
    // 商品数据详情
    public function goodsDetail($goods_id){
        // 商品数据详情
        $detail = DB::table('wechar_goods')->where('goods_id',$goods_id)->first();

        // 数据库的点击量操作
//        $hot = $detail->hot + 1;
//        DB::table('wechar_goods')->where('goods_id',$goods_id)->update(['hot' => $hot]);
//        return view('goods.detail',['detail' => $detail]);

        // redis缓存的点击量操作（浏览量）
        $key = "view:goods_id:".$goods_id;
        Redis::incr($key);
        $detail->hot = Redis::get($key);

        // 浏览量排名
        $sorce_key = 'ss:goods_id:sorce';
        //             键        编号(点击量)     元素
        Redis::zAdd($sorce_key,Redis::get($key),$goods_id);

        // 顺序排序 zRangeByScore
//        $list = Redis::zRangeByScore($sorce_key,0,300,['withscores' => true]);
//        dd($list);

        //  浏览历史
        $history_key = 'ss:uid:'.Auth::id();
        Redis::zAdd($history_key,time(),$goods_id);

        return view('goods.detail',['detail' => $detail]);
    }

    // 商品数据展示
    public function goodsList(){
        $sorce_key = 'ss:goods_id:sorce';
        // 倒序排序 zRevRange
        $info = Redis::zRevRange($sorce_key,0,300,true);
        if($info){
            $goods_id = '';
            foreach($info as $k=> $v){
                $goods_id .= $k.',';
            }
            $goods_id = rtrim($goods_id,',');
            // 查出商品数据 未下架的，未删除的 根据浏览量排序
            $goodsInfo = DB::table('wechar_goods')->where(['goods_status' =>0,'is_up' => 1,])-> orderByRaw("field(goods_id,$goods_id)")->get();
        }else{
            $goodsInfo = DB::table('wechar_goods')->where(['goods_status' =>0,'is_up' => 1,])->get();
        }

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

    // 浏览历史
    public function history(){
        $history_key = 'ss:uid:'.Auth::id();
        $history = Redis::zRevRange($history_key,0,100000000,true);
        $goodsInfo = [];
        foreach($history as $k=> $v){
            $goodsInfo[] = json_decode(json_encode(DB::table('wechar_goods')->where(['goods_status' =>0,'is_up' => 1,'goods_id' =>$k])->first()),true);
        }
        return view('goods.history',['goodsInfo' => $goodsInfo]);
    }
}
