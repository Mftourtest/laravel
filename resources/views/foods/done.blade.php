<?php
/**
 * Created by PhpStorm.
 * User: zhaoyao
 * Date: 2018/4/20
 * Time: 21:53
 */
?>

@extends('layouts.foods')
@section('title', '')


@section('content')
    <link rel="stylesheet" href="{{ url('css/getback.css?v=1') }}">
    <style type="text/css">
        .submitorder {width: 70%; margin-left: 15%; margin-top: 3rem; display: flex;}
        .submitorder-img {display: inline-block; vertical-align: top; width: 20%; height: 20%;}
        .submitorder-text {padding: 3rem 2rem; font-size: 1.5rem;}
        .textbottom {display: flex; justify-content: center;}
        .test-t {width: 100%; text-align: center; font-size: 1.5rem;}
        .detail_line{display: flex;color: white;width: 90%;margin: 25px auto 24px auto;}
		.line{flex: 1;position: relative;top: 3px;background: red;height: 1.5rem;}
		.line_text{color: black;padding: 0 0.5rem;font-size: 1.6rem;font-weight: 700;}
        .miaosha_list {display: flex; flex-direction: column; width: 90%; margin-left: 5%; padding-bottom: 1rem; border-bottom: 0.1rem dashed red; margin-bottom: 1rem;}
        .miaosha_listleft {flex: 0 0 17rem;}
        .miaosha_listleft img {width: 100%;}
        .miaosha_listright {flex: 1; display: flex; flex-direction: column;/* align-items: center;*/ justify-content: space-around;}
        .miaosha_name {font-size: 1.8rem; font-weight: 700;}
        .miaosha_deils {border-radius: 1rem; padding: 0.5rem 0.8rem; background: ghostwhite; border: 0.06rem solid gainsboro;}
        .miaosha_num {font-size: 1.3rem; font-weight: 700;}
        .miaosha_money {font-size: 2rem; font-weight: 700; color: red;}
        .miaosha_money span {font-size: 2.5rem;}
        .miaosha_money e {font-size: 1rem;}
        .go_miaosha {padding: 0.6rem 2.4rem; background: red; border-radius: 8rem; color: white; font-size: 1.4rem;}
    </style>

    @if(1 || !$isWechat)
    <header class="header">
        <a href="javascript:;" onclick="history.go(-2);"><span class="glyphicon glyphicon-remove"></span></a>
        <div class="getcoupon-title">@lang('foods.food_order')</div>
    </header>
    @endif

    <div class="submitorder">
        <img class="submitorder-img" src="{{ url('images/cook.png') }}"/>
        <div class="submitorder-text">@lang('foods.food_backup')</div>
    </div>

	{{--<div class="textbottom">
        <img src="{{ url('images/QRcode.jpg') }}"/>
    </div>
    <div class="test-t">扫一扫 泰国吃喝玩乐1折起</div>--}}


	<div class="detail_line">
		<div class="line"></div>
		<div class="line_text">限量秒杀</div>
		<div class="line"></div>
	</div>
	@foreach($team as $i => $item)
	<div class="miaosha_list">
		<div class="miaosha_listleft">
			<img src="{{ $cdn . $item->image1 }}"/>
		</div>
		<div class="miaosha_listright">
			<div class="miaosha_name">{{ $item->title }}</div>
			{{--<div class="miaosha_deils">多口味烟油套装</div>
			<div class="miaosha_num">234128件已售</div>--}}
            <div style="display: flex; justify-content: space-between; margin: 0.5rem 0;">
                <div class="miaosha_money">疯狂抢 <e>￥</e><span>{{ $item->team_price }}</span></div>
                <a href="http://www.mofangtour.com/mobiletest/product.php?id={{ $item->id }}">
                    <div class="go_miaosha">马上抢购</div>
                </a>
            </div>
		</div>
	</div>
	@endforeach
	{{--<div class="miaosha_list">
		<div class="miaosha_listleft">
			<img src="img/9dd8dd519ed7d636c09a173ca62915b.png"/>
		</div>
		<div class="miaosha_listright">
			<div class="miaosha_name">仿真电子烟</div>
			<div class="miaosha_deils">多口味烟油套装</div>
			<div class="miaosha_num">234128件已售</div>
			<div class="miaosha_money">疯狂抢 ￥<span>29.8</span></div>
			<a href="">
				<div class="go_miaosha">马上抢购</div>
			</a>
		</div>
	</div>--}}
@endsection


@push('scripts')
    <script src="//cdn.bootcss.com/jquery/1.11.3/jquery.min.js"></script>
    <script src="//cdn.bootcss.com/bootstrap/3.3.5/js/bootstrap.min.js"></script>
    <script>
        
    </script>
@endpush