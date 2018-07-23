<?php
/**
 * Created by PhpStorm.
 * User: zhaoyao
 * Date: 2018/4/20
 * Time: 21:53
 */
?>

@extends('layouts.foods')
@section('title', __('foods.biz_nav3'))


@section('content')

    <link rel="stylesheet" href="{{ url('css/getback.css?v=1') }}">
    <style>
        .orderlist{ width: 90%; margin-left: 5%; height: 10rem;  border-bottom: 0.06rem solid rgba(7,17,27,0.1);  font-size: 1.5rem; display: flex; padding: 1rem 0;   justify-content: space-between;}
        .orderleft{ width: 100%; height: 100%; display: flex;}
       	.left_left{width: 2.5rem;height: 2.5rem; background: wheat;}
       	.left_left img{width: 2.5rem;height: 2.5rem;}
       	.left_right{display: flex;flex-direction: column;margin-left: 0.5rem;}
       	.shopname{font-size:1.8rem; font-weight: 700;}
        .orderright{width: 23%; height: 100%;  display: flex;flex-direction: column;align-items: center;justify-content: space-between;}
        .oder_zhuangtai{font-size: 1.4rem; color:orange ;}
        .time{ font-size: 1.4rem;color: gray; }
        .price{font-size: 1.4rem;}
        .gopin{ width: 7rem;  height: 3rem;  border: 0.06rem solid  orange;  background-color: white; 
            font-size: 1.4rem;  color:orange;  line-height: 3rem;  padding: 0 0 1rem 0 ; }
        .pad{padding-bottom: 0.5rem;}
        a:active{text-decoration: none;}
        a{text-decoration: none;}
    </style>

    @if(!$isWechat)
    <header class="header">
        <a href="javascript:;" onclick="history.back();"><span class="glyphicon glyphicon-menu-left tubiao"></span></a>
        <div class="getcoupon-title">订单</div>
    </header>
    @endif

    @foreach($orders as $order)
        <a href="{{ route('foods.myOrderDetail', ['param'=>$param, 'order_id'=>$order->id]) }}">
            <div class="orderlist">
                <div class="orderleft">
                	<!--@lang('foods.food_order_no')：{{ $order->id }}-->
                	<div class="left_left">
  						<img src="{{ $cdn.$partner->image }}">
                	</div>  
                	<div class="left_right">
                		<span class="shopname pad">{{ $partner->title }}</span>
	                    <span class="time pad">下单时间：{{ date('Y-m-d H:i:s', $order->create_time) }}</span>
	                    <span class="price time pad">总价：{{ $cate->money }} {{ $order->money }}</span>
                	</div>     
    			</div>
                <div class="orderright"> 
                	<div class="oder_zhuangtai">已完成</div>
                    @if(empty($order->comment_time))
                    <a href="{{ route('foods.comment', ['param'=>$param, 'order_id'=>$order->id]) }}">
                        <button class="gopin">去评论</button>
                    </a>
                    @endif
                </div>
            </div>
        </a>
    @endforeach

@endsection


@push('scripts')
    <script src="//cdn.bootcss.com/jquery/1.11.3/jquery.min.js"></script>
    <script src="//cdn.bootcss.com/bootstrap/3.3.5/js/bootstrap.min.js"></script>
@endpush