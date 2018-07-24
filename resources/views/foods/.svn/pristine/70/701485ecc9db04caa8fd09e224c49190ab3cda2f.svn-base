<?php
/**
 * Created by PhpStorm.
 * User: zhaoyao
 * Date: 2018/4/20
 * Time: 21:53
 */
?>

@extends('layouts.foods')
@section('title', __('foods.food_order_details'))


@section('content')

    <link rel="stylesheet" href="{{ url('css/getback.css?v=1') }}">
    <style>
        .orderdetails-top{margin-top: 1rem; width: 100%; border-bottom: 0.06rem solid rgba(7,17,27,0.1);
            height: 3rem; line-height: 3rem; position: relative;}
        .table{text-align: start; padding-left: 1rem; color: #00a0dc; font-size: 1.5rem;}
        .orderdetails-time{position: absolute; right: 1rem; font-size: 1.5rem;}
        .orderdetails-content{
            border-bottom: 0.06rem solid rgba(7,17,27,0.1);}
        .clascontent-list{display: flex; justify-content: space-between; padding: 1rem 0; border-bottom: 0.06rem solid rgba(7,17,27,0.1)}
        .dataname{font-size: 1.5rem;}
        .orderde-left{padding: 1rem;}
        .oderde-right{padding: 1rem; font-size: 1.5rem;}
        .fuhao{font-size: 1.3rem;}
        .zongji{margin-top: 1rem; margin-bottom: 10rem; width: 100%; text-align: end; padding-right: 1rem; color:red; font-size: 1.7rem;}
        .order_detail{border-top: 0.06rem solid rgba(7,17,27,0.1)}
        .detail_list{display: flex; justify-content: space-between;font-size: 1.6rem; padding: 1rem;  border-bottom: 0.06rem solid rgba(7,17,27,0.1)}
    	.detail_right{font-size: 1.4rem; color: red;}
    	.detail_right span{font-size: 1.8rem;}
    </style>

    @if(!$isWechat)
    <header class="header">
        <a href="javascript:;" onclick="history.back();"><span class="glyphicon glyphicon-menu-left tubiao"></span></a>
        <div class="getcoupon-title">@lang('foods.food_order_details')</div>
    </header>
    @endif

    <div class="orderdetails">
        <div class="orderdetails-top">
            <span class="table">No. {{ $orderTeam[0]['detail']['desk_sn'] }}</span>
            <span class="orderdetails-time">{{ date('Y-m-d H:i:s', $orderTeam[0]['create_time']) }}</span>
        </div>
        <div class="orderdetails-content">
            @foreach($orderTeam as $ot)
            <div class="clascontent-list">
                <div class="orderde-left">
                    <span class="dataname">{{ $ot['detail']['title_'.$lang] }}</span>
                </div>
                <div class="oderde-right">
                    <span class="fuhao">{{ $cate->money }}</span> <span>{{ $ot->price }} x {{ $ot->quantity }}</span>
                </div>
            </div>
            @endforeach
        </div>
        <div style="height: 1.5rem; background: ghostwhite;"></div>
        <div class="order_detail">
        	<div class="detail_list">
        		<div class="detail_left">@lang('foods.food_coupon')</div>
        		<div class="detail_right">{{ $cate->money }} <span>{{ $coupon['price']?$coupon['price']:0 }}</span></div>
        	</div>
        	<div class="detail_list">
        		<div class="detail_left">@lang('foods.food_discount')</div>
        		<div class="detail_right">{{ $cate->money }} <span>{{ round($order->money * ($partner->discount==1?0:$partner->discount)) }}</span></div>
        	</div>
        	<div class="detail_list">
        		<div class="detail_left">@lang('foods.food_charge')</div>
        		<div class="detail_right">{{ $cate->money }} <span>{{ round($order->money * $partner->fee_srv) }}</span></div>
        	</div>
        	<div class="detail_list">
        		<div class="detail_left">@lang('foods.food_tax')</div>
        		<div class="detail_right">{{ $cate->money }} <span>{{ round($order->money * $partner->fee_tax)}}</span></div>
        	</div>
        </div>
        <div class="zongji">@lang('foods.food_pr_amount')：{{ $cate->money }} {{ $order->money }}</div>
    </div>
@endsection


@push('scripts')
    <script src="//cdn.bootcss.com/jquery/1.11.3/jquery.min.js"></script>
    <script src="//cdn.bootcss.com/bootstrap/3.3.5/js/bootstrap.min.js"></script>
@endpush