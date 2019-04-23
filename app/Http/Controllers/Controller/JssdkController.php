<?php

namespace App\Http\Controllers\Controller;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Illuminate\Support\Str;

class JssdkController extends Controller
{
    // 微信jssdk
    public function test(){
        // 1. 准备jsapi_ticket、noncestr、timestamp和url
            // 获取jsapi_ticket
            $jsapi_ticket = getJsapiTicket();
            $noncestr=Str::random(10);
            $timestamp=time();
            $url= $_SERVER['REQUEST_SCHEME'].'://'.$_SERVER['HTTP_HOST'].$_SERVER['REQUEST_URI'];

        // 2. 字典序排序
            $str = "jsapi_ticket=$jsapi_ticket&noncestr=$noncestr&timestamp=$timestamp&url=$url";

        // 3. 对string1进行sha1签名，得到signature：
            $signature = sha1($str);

        // 4. 数据传递到视图
        $data = [
            'appid'  => env('WECHAR_APPID'),
            'noncestr'      => $noncestr,
            'timestamp'     => $timestamp,
            'url'           => $url,
            'signature'     => $signature
        ];
        return view('wechar.test',$data);
    }

    // 素材下载
    public function media(){
        $media_id = request()->media_id;
        echo $media_id;
    }
}
