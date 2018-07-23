@extends('layouts.foods')
@section('title', '评价商家')

@section('content')
	<link rel="stylesheet" href="https://cdn.bootcss.com/weui/1.1.2/style/weui.min.css">
	<link rel="stylesheet" href="{{ url('css/getback.css?v=1') }}">

	<style type="text/css">
		.detail_line {display: flex; color: white; width: 90%; margin: 25px auto 24px auto;}
		.detail_line_list{display: flex; color: white; width: 90%; margin: 10px auto 10px auto;}
		.line {flex: 1; position: relative; top: 3px; background: red; height: 1.5rem;}
		.line_text {color: black; padding: 0 0.5rem; font-size: 1.6rem; font-weight: 700;}
		.lineone{flex: 1; position: relative; top: 9px; background: gainsboro; height: 0.06rem;}
		.line_text_one{font-size: 1rem; color: gray;}
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
		* {margin: 0;padding: 0;list-style-type: none;}
		.pingfen{margin-top: 1rem;}
		#star {position: relative;margin: 20px auto;height: 24px;}
		#star ul{float: left;display: inline;height: 19px;line-height: 19px;}
		#star ul {margin: 0 10px;}
		#star li {margin-left: 1rem;float: left;width: 24px;cursor: pointer;text-indent: -9999px;background:url({{ url('images/star.png') }}) no-repeat;}
		#star strong {color: #f60;padding-left: 10px;}
		#star li.on {background-position: 0 -28px;}
		.star_list{width: 100%; display: flex; align-items: center;}
		.payshop_top{display: flex; flex-direction: column; align-items: center; justify-content: center; font-size: 1.6rem; margin-top: 1rem;}
		.shopname{padding: 0.5rem 0 ;}
		.payshop_top img{width: 5rem; height: 5rem; border-radius: 50%;}
		.shopprice{font-size: 2rem; font-weight: 700;}
		.payshop_bottom{width: 90%; margin-left: 5%; display: flex; justify-content: space-between; height: 1.5rem; line-height: 1.5rem;}
		.payleftprice{font-size: 1.5rem; font-weight: 700;}
	</style>

	@if(!$isWechat)
	<header class="header">
		<a href="order.html"><span class="glyphicon glyphicon-menu-left tubiao"></span></a>
		<div class="getcoupon-title">评价商家</div>
	</header>
	@endif

	<div class="payshop">
		<div class="payshop_top">
			<img src="{{ $cdn . $partner->image }}"/>
			<div class="shopname">{{ $partner->title }}</div>
			<div class="shopprice">{{ $cate->money }} {{ $params['money'] }}</div>
		</div>
		<div class="payshop_bottom">
			<div class="payleft">原价</div>
			<div class="payleftprice">￥ {{ $params['origin'] }}</div>
		</div>
		<div class="payshop_bottom" style="margin-top: 0.5rem;">
			<div class="payleft">@lang('foods.food_rate')</div>
			<div class="payleftprice">{{ $cate->parities }}</div>
		</div>
	</div>

	<div class="pingfen">
		<div class="detail_line_list">
			<div class="line lineone"></div>
			<div class="line_text line_text_one">写下你的评论</div>
			<div class="line lineone"></div>
		</div>
		<div class="star_list">
			<div id="star">
				<ul>
					<li>1</li>
					<li>2</li>
					<li>3</li>
					<li>4</li>
					<li>5</li>
				</ul>
			</div>
		</div>
	</div>

	<div style="height: 1rem; background: ghostwhite;"></div>

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
			<img src="img/9dd8dd519ed7d636c09a173ca62915b.png" />
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
	<script type="text/javascript">
		var len = $("#star li").length;
		console.log(len);
		$("#star li").on("touchend", function (e) {
			console.log($(this).html());
			var n = parseInt($(this).html());
            $("#star li").removeClass("on");
			for (var i=0; i<n; i++) {
                $("#star li:eq(" + i + ")").addClass("on");
			}
            location.href = '{{ route('foods.comment', ['param'=>$param]) }}' + '&level_1=' + n;
        });
	</script>
@endpush