<?php
/**
 * Created by PhpStorm.
 * User: zhaoyao
 * Date: 2018/4/20
 * Time: 21:53
 */
?>

@extends('layouts.foods')
@section('title', __('foods.food_coupon'))


@section('content')

    <link rel="stylesheet" href="{{ url('css/getback.css?v=1')}}">
    <style type="text/css">
        .text{color: white; margin-top: -60px; padding-top: 80%; width: 100%; height: 100%;
            font-size: 20px; text-align: center; background: url({{ url('images/share_bg.png') }}); background-size: cover;}
        .two{margin-top: 5%;}
        .three{color: #e89000;}
        .share{color: white; font-size: 20px; border: red; background-color: orangered;
            border-radius: 40px; margin-top:15%; height: 3rem; width: 90%;}
        .mask{position: fixed; top: 0; width: 100%; height: 100%; background: rgba(0,0,0,.8);
            text-align: center; padding: 60px 0; display: none;}
    </style>

    @if(!$isWechat)
        <header class="header">
            <a href="javascript:; " onclick="history.back(); "><span class="glyphicon glyphicon-menu-left tubiao"></span></a>
            <div class="getcoupon-title">分享领卷</div>
        </header>
    @endif

    <div class="text">
        <div class="textone">分享到朋友圈，邀请朋友领卷</div>
        <div class="textone two">每个朋友领取一次</div>
        <div class="textone two">
            <span>您可以领取一张</span><span class="three">无限制现金卷</span>
        </div>
        <button id="share" class="share">分享到朋友圈</button>
        <p style="height: 200px;"></p>
    </div>
    <div class="mask">
        <img src="{{ url('images/share.png') }}" style="width: 70%;">
    </div>

@endsection


@push('scripts')
    <script src="//cdn.bootcss.com/jquery/1.11.3/jquery.min.js"></script>
    <script src="//cdn.bootcss.com/bootstrap/3.3.5/js/bootstrap.min.js"></script>

    <script src="https://res.wx.qq.com/open/js/jweixin-1.2.0.js"></script>
    <script>
        // 通过config接口注入权限验证配置
        wx.config({
            debug       : false,
            appId       : '{{ $signPackage["appId"] }}',
            timestamp   : '{{ $signPackage["timestamp"] }}',
            nonceStr    : '{{ $signPackage["nonceStr"] }}',
            signature   : '{{ $signPackage["signature"] }}',
            jsApiList   : [
                // 所有要调用的 API 都要加到这个列表中
                'onMenuShareTimeline',
                'onMenuShareAppMessage',
            ]
        });

        wx.ready(function () {

            // 分享给朋友
            wx.onMenuShareAppMessage({
                title   : '免费领现金', // 分享标题
                desc    : '分享朋友圈，领取无限制现金劵', // 分享描述
                link    : "{{ route('foods.couponShare', ['param'=>$param]) }}", // 分享链接，该链接域名或路径必须与当前页面对应的公众号JS安全域名一致
                imgUrl  : "{{ url('images/share_thumb.png') }}", // 分享图标
                type    : '', // 分享类型,music、video或link，不填默认为link
                dataUrl : '', // 如果type是music或video，则要提供数据链接，默认为空
                success : function () {
                    // 用户确认分享后执行的回调函数
                    console.log('share success!');
                    shareReceive();
                },
                cancel  : function () {
                    // 用户取消分享后执行的回调函数
                    console.log('share cancel!');
                }
            });

            // 分享到朋友圈
            wx.onMenuShareTimeline({
                title   : '免费领现金', // 分享标题
                link    : "{{ route('foods.couponShare', ['param'=>$param]) }}", // 分享链接，该链接域名或路径必须与当前页面对应的公众号JS安全域名一致
                imgUrl  : "{{ url('images/share_thumb.png') }}", // 分享图标
                success : function () {
                    // 用户确认分享后执行的回调函数
                    console.log('share success!');
                    shareReceive();
                },
                cancel  : function () {
                    // 用户取消分享后执行的回调函数
                    console.log('share cancel!');
                }
            });

        });
    </script>

    <script>
        function shareReceive() {
            $.ajax({
                url     : '{{ route('foods.couponShare', ['param'=>$param]) }}',
                type    : 'POST',
                dataType: 'JSON',
                data    : {
                    _token  : '{{ csrf_token() }}',
                    op      : 'ajax',
                    coupon_id   : "{{ $coupon ? $coupon->id : 0 }}",
                },
                success : function (d, s) {
                    console.log(d, s);
                    if (d.id) {
                        location.href = '{{ route('foods', ['param'=>$param]) }}';
                    } else {
                        alert("目前没有分享券可领！");
                    }
                },
                complete : function (d, s) {
                    console.log(d, s);
                }
            });
        }

        $("#share").on("touchend", function (e) {
            $(".mask").fadeIn("fast");
        });
        $(".mask img").on("touchend", function (e) {
            $(".mask").fadeOut("fast");
        });
    </script>
@endpush