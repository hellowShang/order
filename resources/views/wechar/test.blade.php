<!doctype html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">

    <title>Laravel</title>

    <!-- Fonts -->
    <link href="https://fonts.googleapis.com/css?family=Nunito:200,600" rel="stylesheet">

    <!-- Styles -->
    <style>
        html, body {
            background-color: #fff;
            color: #636b6f;
            font-family: 'Nunito', sans-serif;
            font-weight: 200;
            height: 100vh;
            margin: 0;
        }

        .full-height {
            height: 100vh;
        }

        .flex-center {
            align-items: center;
            display: flex;
            justify-content: center;
        }

        .position-ref {
            position: relative;
        }

        .top-right {
            position: absolute;
            right: 10px;
            top: 18px;
        }

        .content {
            text-align: center;
        }

        .title {
            font-size: 84px;
        }

        .links > a {
            color: #636b6f;
            padding: 0 25px;
            font-size: 13px;
            font-weight: 600;
            letter-spacing: .1rem;
            text-decoration: none;
            text-transform: uppercase;
        }

        .m-b-md {
            margin-bottom: 30px;
        }
    </style>

</head>
<body>
<button id="btn">选择照片</button>
<div id="img"></div>

<script src="/js/jquery.js"></script>
<script src="http://res.wx.qq.com/open/js/jweixin-1.4.0.js"></script>
<script>
    $(function(){
        // 通过config接口注入权限验证配置
        wx.config({
            debug: true, // 开启调试模式,调用的所有api的返回值会在客户端alert出来，若要查看传入的参数，可以在pc端打开，参数信息会通过log打出，仅在pc端时才会打印。
            appId: "{{$appid}}", // 必填，公众号的唯一标识
            timestamp:"{{$timestamp}}", // 必填，生成签名的时间戳
            nonceStr: "{{$noncestr}}", // 必填，生成签名的随机串
            signature: "{{$signature}}",// 必填，签名
            jsApiList: ['chooseImage','uploadImage'] // 必填，需要使用的JS接口列表
        });

        $('#btn').click(function(){
            // 通过ready接口处理成功验证
            wx.ready(function(){

                // 图像接口
                wx.chooseImage({
                    count: 3, // 默认9
                    sizeType: ['original', 'compressed'], // 可以指定是原图还是压缩图，默认二者都有
                    sourceType: ['album', 'camera'], // 可以指定来源是相册还是相机，默认二者都有
                    success: function (res) {
                        var localIds = res.localIds; // 返回选定照片的本地ID列表，localId可以作为img标签的src属性显示图片

                        var img = '';
                        $.each(localIds,function(index,imgsrc){

                            // 图片展示
                            var image = "<img src='" + imgsrc + "' class='img' width='200' height='200'>"+"<br />";
                            $('#img').append(image);

                            // 图片路径拼接
                            img += imgsrc + ',';

                            // 本地图片上传接口
                            wx.uploadImage({
                                localId: imgsrc, // 需要上传的图片的本地ID，由chooseImage接口获得
                                isShowProgressTips: 1, // 默认为1，显示进度提示
                                success: function (d) {
                                    var serverId = d.serverId; // 返回图片的服务器端ID
                                    alert(d);

                                    // // 下载图片接口
                                    // wx.downloadImage({
                                    //     serverId: '', // 需要下载的图片的服务器端ID，由uploadImage接口获得
                                    //     isShowProgressTips: 1, // 默认为1，显示进度提示
                                    //     success: function (res) {
                                    //         var localId = res.localId; // 返回图片下载后的本地ID
                                    //     }
                                    // });
                                }
                            });


                        });
                        // alert(img);
                    }
                });
            });
        });

    });
</script>
</body>
</html>
