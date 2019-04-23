<?php

namespace App\Http\Controllers\Controller;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Illuminate\Support\Str;
use GuzzleHttp\Client;
use Illuminate\Support\Facades\Storage;
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

    // 图像素材下载
    public function media(){
        $media_id = request()->media_id;
        if($media_id){
            $access_token = getAccessToken();
            $url = "https://api.weixin.qq.com/cgi-bin/media/get?access_token=$access_token&media_id=$media_id";
            // 使用guzzle发送get请求
            $client = new Client();
            $response = $client-> get($url);
            // 获取响应头
            $responseInfo = $response->getHeaders();
            // 获取文件名
            $fileName = $responseInfo['Content-disposition'][0];
            // 文件新名字
            $newFileName = date("Ymd",time()).substr($fileName,-10);
            // 文件路径
            $path = "wechar/jsimages/".$newFileName;
            $res = Storage::put($path,$response->getBody());
            if($res){
                // TODO 请求成功
                echo 'ok';
            }else{
                // TODO 请求失败
            }
        }
    }
}
