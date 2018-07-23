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
    <link rel="stylesheet" href="{{ url('css/food3_style.css') }}">
	<style type="text/css">
		body,
		html {padding: 0; margin: 0; background: ghostwhite; font-family: "微软雅黑";}
		.mian {width: 100%; height: 100%;}
		.mainImg {width: 100%; height: 10rem; position: relative;}
		.mainlist {width: 100%; height: 10rem; position: absolute; top: 0; left: 0; display: flex; align-items: center;}
		.shopImg {width: 5rem; height: 5rem; border-radius: 50%; margin-left: 1rem;}
		.shopList {width: 65%; height: 7rem; margin-left: 1rem;}
		.shopTitle {padding-bottom: 0.5rem; border-bottom: 0.06rem dashed white; margin-bottom: 0.5rem; display: flex;}
		.shopName {width: 100%; color: white; font-size: 1.4rem; overflow: hidden; text-overflow: ellipsis; white-space: nowrap;}
		.youhuiNum {position: absolute; right: 0.5rem; top: 5.3rem; width: 12%; padding: 0 4px; text-align: center; height: 1.8rem; line-height: 1.8rem; background: rgba(7, 17, 27, 0.4); border-radius: 20px; color: white;}
		.shopYouhui {font-size: 0.8rem; color: white; padding-bottom: 0.3rem; line-height: 1rem; width: 85%; overflow: hidden; text-overflow: ellipsis; white-space: nowrap;}
		.coupon_man {display: inline-block; background: red; width: 1rem; height: 1rem; text-align: center; line-height: 0.9rem; font-size: 0.6rem;}
		.goModel {width: 100%; height: 16rem; background: white; padding-top: 0.8rem;}
		.table_num {font-size: 1.3rem; width: 90%; margin-left: 5%; margin-bottom: 0.8rem;}
		.goModel_title {width: 90%; margin-left: 5%; font-size: 0.8rem; color: gray; margin-bottom: 0.8rem;}
		a {text-decoration: none;}
		.goList {width: 90%; margin-left: 5%; height: 2.5rem; border-radius: 4px; background: orange; color: white; text-align: center; line-height: 2.5rem;}
		.menu {width: 100%; height: 2rem; display: inline-block; display: flex; justify-content: space-between; color: black; margin-bottom: 10px;}
		.menu-list {margin-top: 0.8rem;}
		.menutext {display: flex; justify-content: space-between; line-height: 2rem; height: 2rem; width: 90%; margin-left: 5%; font-size: 1rem; border-bottom: 0.06rem solid gainsboro;}
		.zhaopai {display: flex; width: 100%; height: 8rem; overflow-x: auto;}
		.zhaopai::-webkit-scrollbar {display: none; width: 0; height: 0;}
		.zhaopai ul {width: 100%; list-style: none; list-style-type: disc; -webkit-margin-before: 0em; -webkit-margin-after: 0em; -webkit-margin-start: 0px; -webkit-margin-end: 0px; -webkit-padding-start: 0px; display: flex;}
		.xixiix {margin-top: 0.5rem; margin-right: 0.5rem; display: flex; flex-direction: column; font-size: 0.8rem; margin-left: 0.5rem;}
		.shopzhaopai {background-color: white; margin-top: 1rem;}
		.zhaopaiimg {width: 7rem; height: 6rem; position: relative;}
		.tuijian {padding-left: 0.5rem;}
		.imgimg {width: 100%; height: 100%;}
		.imgs {position: absolute; bottom: 0; width: 100%; height: 1.5rem; background-color: black; text-align: center; opacity: 0.7; color: white; line-height: 1.5rem;}
		.imgsimg {width: 1rem; height: 1rem; position: absolute; left: 0.2rem; top: 0.2rem;}
		.zhaopainame {display: -webkit-box; -webkit-box-orient: vertical; -webkit-line-clamp: 1; overflow: hidden;}
		.detail {position: fixed; top: 0; left: 0; z-index: 100; width: 100%; height: 100%; overflow: auto; background: rgba(7, 17, 27, 0.8); display: none;}
		.detail-Wrapper {width: 100%; min-height: 85%;}
		.detail_main {margin-top: 64px; padding-bottom: 64px;}
		.detail-close {position: relative; width: 32px; height: 32px; margin: -32px auto 0 auto; clear: both; font-size: 32px; text-align: center; line-height: 32px; color: white;}
		.detail_title{width: 100%; text-align: center; color: white; font-size: 1.4rem;}
		.detail_star{display: flex; margin-top: 5%; justify-content: center; padding-left: 1rem;}
		.starone{background-image: url(/static/food/img/star.png); background-repeat: no-repeat; width: 3rem; height: 3rem; background-position: 0 -24px;}
		.startwo{background-image: url(/static/food/img/star.png); background-repeat: no-repeat; width: 3rem; height: 3rem; background-position: 0 -24px;}
		.starthree{background-image: url(/static/food/img/star.png); background-repeat: no-repeat; width: 3rem; height: 3rem; background-position: 0 -24px;}
		.starfour{background-image: url(/static/food/img/star.png); background-repeat: no-repeat; width: 3rem; height: 3rem; background-position: 0 -24px;}
		.starfive{background-image: url(/static/food/img/star.png); background-repeat: no-repeat; width: 3rem; height: 3rem; background-position: 0 -24px;}
		.detail_line{display: flex; color: white; width: 80%; margin: 25px auto 24px auto;}
		.line{flex: 1; position: relative; top: -6px; border-bottom: 0.06rem solid rgba(255,255,255,0.2);}
		.youhuixinxi{width: 80%; margin: 25px auto 24px auto;}
		.wrapper_youhui{margin-top: 0.5rem;}
		.icon-keyboard_arrow_right{color: white;}
		.icon{margin-top: 0.5rem; color: black;}
	</style>

	<div class="main">
		<img class="mainImg" src="/static/food/img/index/153495763082924287.png" />
		<div class="mainlist">
			<img class="shopImg" src="/static/{$partner['image']}" />
			<div class="shopList">
				<div class="shopTitle">
					<div class="shopName">{$partner['title']}</div>
				</div>
				<div class="shopYouhui"><span class="coupon_man">减</span> 消费满500减20</div>
				<div class="shopYouhui"><span class="coupon_man">减</span> 消费满1000减100</div>
				<div class="shopYouhui"><span class="coupon_man">减</span> 消费满300减10</div>
				<span class="youhuiNum">5 <span class="icon-keyboard_arrow_right"></span></span>
			</div>
		</div>
		<div class="goModel">
			<div class="table_num">No.{$deskSn}</div>
			<div class="goModel_title">肚子空空如也，快去点餐吧~</div>
			<a href="{$host}foods?param={$param}">
				<div class="goList">点餐</div>
			</a>
			<div class="menu-wrapper">
				<div class="menu-list">
					<a href="{$host}foods/coupon?param={$param}" class="menu">
						<div class="menutext">
							<span>我的优惠券</span>
							<span class="icon icon-keyboard_arrow_right">
						</div>
					</a>
					<a href="{$host}foods/my_order?param={$param}" class="menu">
						<div class="menutext">
							<span>我的订单</span>
							<span class="icon icon-keyboard_arrow_right">
						</div>
					</a>
					<a href="{$host}foods/team?param={$param}" class="menu">
						<div class="menutext">
							<span>我的团购</span>
							<span class="icon icon-keyboard_arrow_right">
						</div>
					</a>
				</div>
			</div>
		</div>
		<div class="shopzhaopai">
			<div class="shopzhaopainame" style="font-size: 0.8rem;display: flex;margin-left:1rem;height: 2rem;line-height: 2rem;">
				<b>推荐菜品</b>
			</div>
			<div class="zhaopai">
				<ul>
					<a href="#">
						<li class="xixiix">
							<div class="zhaopaiimg">
								<img class="imgimg" src="/static/food/img/501975201201305341.png" />
								<div class="imgs">
									<span class="tuijian">6859人推荐</span>
									<img class="imgsimg" src="/static/food/img/595118133399536088.png" />
								</div>
							</div>
							<div class="zhaopainame" style="width: 6rem; text-align: center;color: black;">
								招牌双人餐
							</div>
						</li>
					</a>
					<a href="#">
						<li class="xixiix">
							<div class="zhaopaiimg">
								<img class="imgimg" src="/static/food/img/501975201201305341.png" />
								<div class="imgs">
									<span class="tuijian">6859人推荐</span>
									<img class="imgsimg" src="/static/food/img/595118133399536088.png" />
								</div>
							</div>
							<div class="zhaopainame" style="width: 6rem; text-align: center;color: black;">
								招牌双人餐招牌双人餐招牌双人餐招牌双人餐招牌双人餐招牌双人餐招牌双人餐
							</div>
						</li>
					</a>
					<a href="#">
						<li class="xixiix">
							<div class="zhaopaiimg">
								<img class="imgimg" src="/static/food/img/501975201201305341.png" />
								<div class="imgs">
									<span class="tuijian">6859人推荐</span>
									<img class="imgsimg" src="/static/food/img/595118133399536088.png" />
								</div>
							</div>
							<div class="zhaopainame" style="width: 6rem; text-align: center;color: black;">
								招牌双人餐
							</div>
						</li>
					</a>
					<a href="#">
						<li class="xixiix">
							<div class="zhaopaiimg">
								<img class="imgimg" src="/static/food/img/501975201201305341.png" />
								<div class="imgs">
									<span class="tuijian">6859人推荐</span>
									<img class="imgsimg" src="/static/food/img/595118133399536088.png" />
								</div>
							</div>
							<div class="zhaopainame" style="width: 6rem; text-align: center;color: black;">
								招牌双人餐
							</div>
						</li>
					</a>
				</ul>
			</div>
		</div>
	</div>
	<div class="detail">
		<div class="detail-Wrapper">
			<div class="detail_main">
				<div class="detail_title">魔方旅行</div>
				<div class="detail_star">
					<span class="star_all starone"></span>
					<span class="star_all startwo"></span>
					<span class="star_all starthree"></span>
					<span class="star_all starfour"></span>
					<span class="star_none starfive"></span>
				</div>
				<div class="detail_line">
					<div class="line"></div>
					<div class="line_text">优惠信息</div>
					<div class="line"></div>
				</div>
				<div class="youhuixinxi">
					<div class="shopYouhui wrapper_youhui"><span class="coupon_man">减</span> 消费满500减20</div>
					<div class="shopYouhui wrapper_youhui"><span class="coupon_man">减</span> 消费满1000减100</div>
					<div class="shopYouhui wrapper_youhui"><span class="coupon_man">减</span> 消费满300减10</div>
				</div>
			</div>
		</div>
		<div class="detail-close"><span class="icon-close"></span></div>
	</div>

@endsection


@push('scripts')
    <script src="//cdn.bootcss.com/jquery/1.11.3/jquery.min.js"></script>
    <script src="//cdn.bootcss.com/bootstrap/3.3.5/js/bootstrap.min.js"></script>
    <script>
        $('.youhuiNum').on('touchend', function(event) {
            $('.detail').fadeIn()

        })
        $('.detail-close').on('touchend', function() {
            $('.detail').fadeOut()
        })
    </script>
@endpush