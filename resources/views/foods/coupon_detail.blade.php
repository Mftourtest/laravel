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
    	body,html{background: ghostwhite;}
  		.coudescribe{width: :100%;position: relative;display: flex;flex-direction: column;align-items: center;justify-content: center;}
		.coudescribe img{width: 100%;background-repeat: no-repeat;}
		.coudescribeone{position: absolute;top: 25%;text-align: center}
		.coupon {position: absolute;top: 50%;width: 90%;margin-top: 1rem;}
		.couponimg {width: 100%;}
		.couponleft {position: absolute;top: 0;left: 1%;text-align: center;}
		.coupontext {color: #00a0dc;font-size:3rem;}
		.coupont-manjian {color: #00a0dc;font-size: 1.2rem;}
		.couponright {position: absolute;top: 0.5rem;left: 30%;text-align: start;}
		.shopname {padding-bottom: 0.5rem;color: black;font-size: 1.6rem;}
		.coupontime {color: darkorange;font-size: 1.3rem;position: absolute;bottom: 0; left: 0.5rem;}	
		.coupontdescribe {padding-bottom: 1rem;color: gray;font-size: 1rem;}
		.goshop{width: 90%;margin-left: 5%;height: 4rem;background-color: #ff4500;color: white;font-size: 1.5rem;border-radius: 10px;text-align: center;line-height: 4rem;margin-top: 2.5rem;}
		.firend{width: 90%;margin-left: 5%;height: 4rem;background-color: #ffa500;color: white;font-size: 1.5rem;border-radius: 10px;text-align: center;line-height: 4rem;margin-top: 0.5rem;}
    </style>

    @if(!$isWechat)
        <header class="header">
            <a href="javascript:; " onclick="history.back(); "><span class="glyphicon glyphicon-menu-left tubiao"></span></a>
            <div class="getcoupon-title">优惠券详情</div>
        </header>
    @endif

    <div class="coudescribe">
        <img src="{{ url('images/discountbeijing.jpg') }}"/>
        <div class="coudescribeone">
	        <div class="decou">优惠卷</div>
	        <div class="decoutogoer">叫上小伙伴一起吧~</div>
        </div>
        <div class="coupon">
            <img class="couponimg" src="{{ url('images/discount.png') }}"/>
            <div class="couponleft">
	            <div class="coupontext">{{ $cate->money }} {{ $coupon->price }}</div>
	            <div class="coupont-manjian">满{{ $coupon->threshold }}可用</div>
            </div>
			<div class="couponright">
	            <div class="shopname">{{ $partner->title }}</div>
	            <div class="coupontdescribe">所有商品适用(酒水除外)</div>
            </div>
            <div class="coupontime">{{ date('Y-m-d', $coupon->endtime) }}过期</div>
        </div>
    </div>
    <a href="{{ route('foods', ['param'=>$param]) }}">
        <div class="goshop">去下单</div>
    </a>
    <a href="{{ route('foods.couponShare', ['param'=>$param]) }}">
        <div class="firend">分享到朋友圈</div>
    </a>

@endsection


@push('scripts')
    <script src="//cdn.bootcss.com/jquery/1.11.3/jquery.min.js"></script>
    <script src="//cdn.bootcss.com/bootstrap/3.3.5/js/bootstrap.min.js"></script>
@endpush