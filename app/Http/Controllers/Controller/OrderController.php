<?php

namespace App\Http\Controllers\Controller;

use App\Model\OrderModel;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Session;

class OrderController extends Controller
{
    // 订单生成
    public function create(){
        // 订单号
        $order_sn = OrderModel::generateOrderSn(Auth::id());

        // 计算商品总价
        $goods = DB::table('wechar_cart')->where(['uid' => Auth::id(),'session_id' => Session::getId()])->get();
        $amount = 0;
        foreach($goods as $k=>$v){
            $amount += $v->goods_price;
        }
        $info = [
            'uid'         => Auth::id(),
            'order_sn'    => $order_sn,
            'order_amount'=> $amount,
            'create_time' => time()
        ];

        // 订单信息入库
        $order_id = OrderModel::insertGetId($info);

        // 订单商品详情入库
        foreach($goods as $k=>$v){
            $arr = [
                'uid' => Auth::id(),
                'oid' => $order_id,
                'goods_id' => $v->goods_id,
                'buy_num' => $v-> buy_num,
                'create_time' => time()
            ];

            $res = DB::table('wechar_order_detail')->insert($arr);
        }

        if($order_id && $res){
            header('Refresh:3;url=/order/list');
            die('下单成功');
        }else{
            header('Refresh:3;url=/cart/list');
            die('下单失败');
        }
    }

    // 订单展示
    public function orderList(){
        $orderInfo = DB::table('wechar_order as o')
            ->join('wechar_order_detail as d','o.oid','=','d.oid')
            ->join('wechar_goods as g','g.goods_id','=','d.goods_id')
            ->where(['o.uid' =>Auth::id()])
            ->get();
        return view('order.list',['orderInfo' => $orderInfo]);
    }

    // 支付状态查询
    public function payStatus($order_sn){
        if(!$order_sn){
            die(json_encode(['font' => '订单不存在', 'code' =>   5]));
        }
        $arr = DB::table('wechar_order')->where(['order_sn' => $order_sn])->first();
        if($arr->pay_status == 1){
            echo json_encode(['msg' => 'ok','order_sn' => $arr->order_sn]);
        }
    }

    // 支付成功，消息显示
    public function success($order_sn){
        return view('order.success',['order_sn' => $order_sn]);
    }
}
