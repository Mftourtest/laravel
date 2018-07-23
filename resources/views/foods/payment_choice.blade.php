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

    <link rel="stylesheet" href="{{ url('css/mui.min.css') }}">
    <link rel="stylesheet" href="{{ url('css/getback.css?v=1') }}">
    <style type="text/css">
        body,html{background: ghostwhite;}
        .mui-bar{background: white;}
        .mui-card {margin: 0; margin-top: 2.7rem; overflow: initial; box-shadow: 0 0 0 ;}
        .mui-checkbox input[type=checkbox]:checked:before,
        .mui-radio input[type=radio]:checked:before {color: #00a0dc;}
        .mui-input-group .mui-input-row { }
        .mui-input-row .mui-radio {background: white;}
        .mui-radio img {width: 2.5rem; height: 2.5rem; margin:0.5rem 1rem 1rem 0.5rem;}
        .mui-radio label {display: initial;}
        .mui-input-group{border-bottom: 0.06rem solid rgba(7,17,27,0.1);}
        .zhifu {height: 3rem; line-height: 3rem; margin-top: -3.2rem; color: black; font-size: 1.4rem; }
        .paylast{position: fixed; bottom: 0;}
        .shopname{font-size: 2rem;color: black;}
        .total{line-height: 6rem; display: flex; justify-content: center; color: red; margin-top: 1rem;}
        .payint{width: 80%; margin-left: 10%; margin-top: 2rem; display: flex; background: white;margin-bottom: 1rem; }
        .payput{height: 2.5rem; }
        .sure{ background: #00a0dc; color: white; line-height: 2.8em; text-align: center;}
        .paylast{width: 100%; height: 5rem; color: white; text-align: center; background: #00a0dc; font-size: 1.6rem;
            border: none; border-radius: 0;}
        .checkout{display: flex; justify-content: space-between; height: 5rem; line-height: 5rem; padding: 0 2rem;
            border-bottom: 0.06rem solid gainsboro;}
        .checkout span, .mui-input-group .mui-input-row{height: 5rem; line-height: 5rem;}
    </style>

    @if(!$isWechat)
    <header class="mui-bar mui-bar-nav">
        <a href="javascript:;" onclick="history.back();"><span class="mui-icon mui-icon-back return" style="color:black"></span></a>
        <h1 class="mui-title">支付</h1>
    </header>
    @endif

    <div class="mui-card">
        <form action="{{ route('foods.order', ['param'=>$param]) }}" method="post" class="mui-input-group">
            {{--<div class="total">
                <div class="shopname">
                    <span style='color: red'>应付：</span>
                </div>               	 
            </div>--}}
            <div style='height: 2rem; background: ghostwhite;'></div>
            <div class="checkout">
                <span>@lang('foods.food_ntPay')</span>
                <span>{{ $cate->money }} <span id="money">{{ $params['money']-$coupon['price'] }}</span></span>
            </div>
            <div class="checkout">
                <span>@lang('foods.food_rate') (1 : <span id="parities">{{ $cate->parities }}</span>)</span>
                <span>￥ <span id="origin">{{$params['origin']-round($coupon['price']/$cate->parities, 2)}}</span></span>
            </div>
            <div class="checkout">
                <span>@lang('foods.waiter_cash_coupon')</span>
                <span class="glyphicon glyphicon-menu-down"></span>
            </div>
            <div class="payint">
                <input type="text" class="payput" placeholder="Please enter the code" value="" style="border: 0.06rem solid #00a0dc;">
                <div class="sure" style="width:8rem;">@lang('foods.check_group_package')</div>
            </div>
            <div style='height: 1rem; background: ghostwhite;'></div>

            @if($paySwitch[0] && $isWechat)
            {{--<div class="mui-input-row mui-radio">
                <label class="label">
                    <img src="{{ url('images/zhifubao.png') }}"/>
                    <span class="zhifu">@lang('foods.waiter_alipay')</span>
                </label>
                <input name="radio" type="radio" value="alipay">
            </div>--}}
            @endif

            @if($paySwitch[1] && $isWechat)
            <div class="mui-input-row mui-radio">
                <label class="label">
                    <img src="{{ url('images/weixin.png') }}"/>
                    <span class="zhifu">@lang('foods.waiter_wechat')</span>
                </label>
                <input name="radio" type="radio" value="wechat">
            </div>
            @endif

            @if($paySwitch[2])
            <div class="mui-input-row mui-radio">
                <label class="label">
                    <img src="{{ url('images/xianjin.png') }}"/>
                    <span class="zhifu">@lang('foods.waiter_cash')</span>
                </label>
                <input name="radio" type="radio" value="cash" checked>
            </div>
            @endif



            <input type="hidden" name="op" value="order_pay">
            <input type="hidden" name="params" value="{{ $paramsB64 }}">
            <input type="hidden" name="foods" value="{{ $foodsB64 }}">
            <input type="hidden" name="coupon[id]" value="{{ $coupon['id'] }}">
            <input type="hidden" name="coupon[price]" value="{{ $coupon['price'] }}">
            <input type="hidden" name="team[price]" id="team_price" value="0">
            <input type="hidden" name="team[cid]" id="team_cid" value="0">
            {{ csrf_field() }}
            
            <button type="submit" class="paylast">支付</button>
        </form>
    </div>

@endsection


@push('scripts')
    <script src="//cdn.bootcss.com/jquery/1.11.3/jquery.min.js"></script>
    <script src="//cdn.bootcss.com/bootstrap/3.3.5/js/bootstrap.min.js"></script>
    <script src="{{ url('js/mui.min.js') }}"></script>
    <script>
        mui.init()

        $(function(){
        	$(".payint").hide()
        	$('.checkout').on('touchend',function(){
        		$(".payint").slideToggle();
        	})
        })

        $(function(){
        	$('.sure').on('touchend',function(){
        	    var code = $(".payput").val();
                console.log('sure', code);
        	    $.ajax({
                    url     : "{{ route('foods.team', ['param'=>$param]) }}",
                    type    : "POST",
                    dataType: "JSON",
                    data    : {
                        _token  : '{{ csrf_token() }}',
                        op      : 'djq',
                        code    : code,
                    },
                    success : function(d, s) {
                        console.log(d, s);
                        $('.payput').val("");
                        $('.payput').attr("placeholder", d.msg);

                        if (code == d.code) {
                            var teamCid = $("#team_cid").val();
                            if (teamCid != d.code) {
                                var teamPrice = d.team.team_price;
                                var origin = parseFloat($("#origin").html());
                                var money = parseFloat($("#money").html());
                                var parities = parseFloat($("#parities").html());

                                origin -= teamPrice;
                                money -= teamPrice*parities;

                                $("#origin").html(origin.toFixed(2));
                                $("#money").html(money.toFixed(2));
                                $("#team_cid").val(d.code);
                                $("#team_price").val(teamPrice);
                            }
                        }
                    }
                });
        	})
        })
    </script>
@endpush