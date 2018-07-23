<?php
/**
 * Created by PhpStorm.
 * User: zhaoyao
 * Date: 2018/4/20
 * Time: 21:53
 */
?>

@extends('layouts.foods')
@section('title', '支付')


@section('content')
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
                'chooseWXPay',
            ]
        });

        wx.ready(function () {

            wx.chooseWXPay({
                timestamp: '{{ $wOpt['timeStamp'] }}',
                nonceStr: '{{ $wOpt['nonceStr'] }}',
                package: '{{ $wOpt['package'] }}',
                signType: '{{ $wOpt['signType'] }}',
                paySign: '{{ $wOpt['paySign'] }}',
                success: function (res) {
                    // 支付成功后的回调函数
                    //alert(JSON.stringify(res));
                    if (res.errMsg == "chooseWXPay:ok") {
                        location.href = '{!! $successUrl !!}';
                    } else {
                        //alert('启动微信支付失败, 请检查你的支付参数. 详细错误为: ' + res.errMsg);
                        //history.go(-2);
                        location.href = '{{ route('foods.myOrder', ['param' => $param, 'cid' => $cid]) }}';
                    }
                }
            });
        });

    </script>
@endpush