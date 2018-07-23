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

    <link rel="stylesheet" href="{{ url('css/getback.css?v=1') }}">
    <style>
    	body,html{background: ghostwhite;}
        .coupon {position: relative;width: 95%;margin-left: 2.5%;margin-top: 1rem;}
		.couponimg {width: 100%;}
		.couponleft {position: absolute;top: 0;left: 1%;text-align: center;}
		.coupontext {color: #00a0dc;font-size:3rem;}
		.coupont-manjian {color: #00a0dc;font-size: 1.2rem;}
		.couponright {position: absolute;top: 0.5rem;left: 30%;text-align: start;}
		.shopname {padding-bottom: 0.5rem;color: black;font-size: 1.6rem;}
		.coupontime {color: darkorange;font-size: 1.3rem;position: absolute;bottom: 0; left: 0.5rem;}	
		.coupontdescribe {padding-bottom: 1rem;color: gray;font-size: 1rem;}
		a .btn {display: inline-block;width: 6rem;color: #00a0dc;font-size: 1rem;}
		.btn {position: absolute;top: 2.5rem;right: 1rem;background-color: white;}
    </style>

    @if(!$isWechat)
    <header class="header">
        <a href="javascript:;" onclick="history.back();"><span class="glyphicon glyphicon-menu-left tubiao"></span></a>
        <div class="getcoupon-title">领取优惠券</div>
    </header>
    @endif

    @foreach($coupons as $coupon)
        <div class="coupon">
            <img class="couponimg" src="{{ url('images/discount.png') }}"/>
            <div class="couponleft">
	            <div class="coupontext">{{ $cate->money }} {{ $coupon->price }}</div>
	            <div class="coupont-manjian">满{{ $coupon->threshold }}可用</div>
            </div>
            <div class="couponright">
	            <div class="shopname">{{ $coupon->ptitle }}</div>
	            <div class="coupontdescribe">所有商品适用(酒水除外)</div>
            </div>
            <div class="coupontime">{{ date('Y-m-d', $coupon->endtime) }}过期</div>
            <a href="{{ route('foods.couponDetail', ['param'=>$param, 'id'=>$coupon->id]) }}">
                <div class="btn">立即领取</div>
            </a>
        </div>
    @endforeach

@endsection


@push('scripts')
    <script src="//cdn.bootcss.com/jquery/1.11.3/jquery.min.js"></script>
    <script src="//cdn.bootcss.com/bootstrap/3.3.5/js/bootstrap.min.js"></script>
@endpush