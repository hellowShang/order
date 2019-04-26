<?php

namespace App\Model;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Str;
class OrderModel extends Model
{
    protected $table = 'wechar_order';
    public $timestamps = false;
    public $primaryKey = 'oid';


    // 生成订单号
    public static function generateOrderSn($uid){
        $order_sn = '1809_'.date('YmdHi');

        $str = time().$uid.rand(1111,9999).Str::random(16);
        $order_sn .= substr(md5($str),5,15);

        return $order_sn;
    }
}
