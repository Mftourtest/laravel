<?php
/**
 * Created by PhpStorm.
 * User: zhaoyao
 * Date: 2018/4/20
 * Time: 21:53
 */
?>

@extends('layouts.foods')
@section('title', '刷卡支付')


@section('content')
    <div class="panel panel-default">
        <div class="panel-heading" id="header"></div>
        <table class="table" id="table" style="display: none;">
            <tr>
                <th>微信支付订单号</th>
                <th>订单金额</th>
            </tr>
            <tr>
                <td id="transaction_id"></td>
                <td id="total_fee"></td>
            </tr>
        </table>
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
                'chooseWXPay',
                'scanQRCode',
                'onMenuShareTimeline',
                'onMenuShareAppMessage',
            ]
        });

        wx.ready(function () {

            wx.scanQRCode({
                needResult: 1, // 默认为0，扫描结果由微信处理，1则直接返回扫描结果，
                scanType: ["qrCode","barCode"], // 可以指定扫二维码还是一维码，默认二者都有
                success: function (res) {
                    var result = res.resultStr; // 当needResult 为 1 时，扫码返回的结果
                    console.log(result);
                    alert(result);
                    $.ajax({
                        url: '{{ $successUrl }}',
                        type: 'POST',
                        dataType: 'JSON',
                        data: {
                            auth_code: result,
                            _token  : '{{ csrf_token() }}',
                        },
                        success: function (d, s) {
                            console.log(d, s);
                            alert(s);
                            $("#header").html(s);
                            $("#transaction_id").html(d.transaction_id);
                            $("#total_fee").html('￥' + parseFloat(d.total_fee)*0.01);
                            $("#table").fadeIn("fast");
                        }
                    });
                }
            });

            // 分享给朋友
            wx.onMenuShareAppMessage({
                title: '刷卡支付', // 分享标题
                desc: '分享朋友圈，领取无限制现金劵，欢迎打赏店长！', // 分享描述
                link: "{{ route('foods.scanQr', ['param'=>$param]) }}", // 分享链接，该链接域名或路径必须与当前页面对应的公众号JS安全域名一致
                imgUrl: "{{ url('images/dyyy.png') }}", // 分享图标
                type: '', // 分享类型,music、video或link，不填默认为link
                dataUrl: '', // 如果type是music或video，则要提供数据链接，默认为空
                success: function () {
                    // 用户确认分享后执行的回调函数
                    console.log('share success!');
                    shareReceive();
                },
                cancel: function () {
                    // 用户取消分享后执行的回调函数
                    console.log('share cancel!');
                }
            });

            // 分享到朋友圈
            wx.onMenuShareTimeline({
                title: '刷卡支付', // 分享标题
                link: "{{ route('foods.scanQr', ['param'=>$param]) }}", // 分享链接，该链接域名或路径必须与当前页面对应的公众号JS安全域名一致
                imgUrl: "{{ url('images/dyyy.png') }}", // 分享图标
                success: function () {
                    // 用户确认分享后执行的回调函数
                    console.log('share success!');
                    shareReceive();
                },
                cancel: function () {
                    // 用户取消分享后执行的回调函数
                    console.log('share cancel!');
                }
            });

            /*wx.chooseWXPay({
                timestamp: '{ $wOpt['timeStamp'] }}', // 支付签名时间戳，注意微信jssdk中的所有使用timestamp字段均为小写。但最新版的支付后台生成签名使用的timeStamp字段名需大写其中的S字符
                nonceStr: '{ $wOpt['nonceStr'] }}', // 支付签名随机串，不长于 32 位
                package: '{ $wOpt['package'] }}', // 统一支付接口返回的prepay_id参数值，提交格式如：prepay_id=***）
                signType: '{ $wOpt['signType'] }}', // 签名方式，默认为'SHA1'，使用新版支付需传入'MD5'
                paySign: '{ $wOpt['paySign'] }}', // 支付签名
                success: function (res) {
                    // 支付成功后的回调函数
                    //alert(JSON.stringify(res));
                    if (res.errMsg == "chooseWXPay:ok") {
                        location.href = '{ $successUrl }}';
                    } else {
                        //alert('启动微信支付失败, 请检查你的支付参数. 详细错误为: ' + res.errMsg);
                        history.go(-2);
                        //location.href = "{$failUrl}}";
                    }
                }
            });*/
        });

    </script>
@endpush