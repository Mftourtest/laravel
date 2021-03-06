<!doctype html>
<html>

	<head>
		<meta charset="UTF-8">
		<title></title>
		<meta name="viewport" content="width=device-width,initial-scale=1,minimum-scale=1,maximum-scale=1,user-scalable=no" />
		<link href="{{url('css/mui.min.css')}}" rel="stylesheet" />
		<link rel="stylesheet" type="text/css" href="{{url('css/reset.css')}}" />
	</head>
	<style type="text/css">
		.coupon {
			position: fixed;
			top: 44px;
			left: 0;
			right: 0;
			bottom: 44px;
			width: 100%;
			height: 100%;
			background-color: white;
			font-family: "微软雅黑";
			overflow: auto;
			font-size: 1rem;
		}
		
		.couponinp {
			width: 100%;
			padding: 5% 10%;
			display: flex;
		}
		
		.coubtn {
			height: 2.5rem;
			width: 6rem;
			margin: 0 0.5rem;
		}
		
		.couponone {
			position: relative;
			text-align: left;
			width: 60%;
			margin-left: 20%;
			margin-top: 1rem;
			background-color: #00a0dc;
			color: aliceblue;
		}
		
		.coupononebtn {
			height: 2.0rem;
			width: 5rem;
			margin: 0 0.5rem;
		}
		
		.coupontext {
			position: relative;
			text-align: center;
			width: 90%;
			margin-top: 1rem;
			color: red;
		}
		
		
		.shopname {
			position: absolute;
			top: 1.5rem;
			right: 8.5rem;
			color: black;
		}
		
		.coupontime {
			position: absolute;
			top: 3.5rem;
			right: 3.5rem;
			color: gray;

			background-repeat: no-repeat;
		}
		
		.coupontdescribe {
			position: absolute;
			top: 5.5rem;
			right: 2rem;
			color: gray;

		}
	</style>

	<body>
		<header class="mui-bar mui-bar-nav" style="background: white;">
			<a href="reckoning?desk_sn={{$desk_sn}}&lang={{$lang}}&price=0"><span class="mui-icon mui-icon-back return" style="color:black"></span></a>
			<h1 class="mui-title" style="font-size: 1rem;">@lang('foods.waiter_cash_coupon')</h1>
		</header>
		<div class="coupon">
			<form action="coupon?desk_sn={{$desk_sn}}&lang={{$lang}}&price={{$enomination}}" method="post">
				{{ csrf_field() }}
			<div class="couponinp">
				<input type="text" class="input" name="coupon_id"/>
				<button class="coubtn" type="submit">@lang('foods.waiter_confirm')</button>
			</div>
			</form>
			@if(!empty($orders))
			<div class="couponone">
				<div class="">{{$orders[0]['titleen']}}</div>
				<div class="">@lang('foods.biz_coupon_package')：{{$orders[0]['packagenameen']}}</div>
				<div class="">@lang('foods.biz_order_id')：{{$orders[0]['order_id']}}</div>
				<div class="">@lang('foods.food_pr_num')：{{$orders[0]['quantity']}}</div>
				<div class="">@lang('foods.waiter_expiry_time')：{{$expire_time}}</div>
				<div class="">------------------------------------</div>
				<div align="center"> <button onclick="window.location.href='reckoning?desk_sn={{$desk_sn}}&lang={{$lang}}&price={{$enomination}}'" class="coupononebtn">立即使用</button></div>
			</div>
			@else
			    <div class="coupontext">{{$msg}}</div>
			@endif
		</div>
		
		<script src="{{url('js/mui.min.js')}}"></script>
		<script src="{{url('js/jquery-3.1.1.min.js')}}" type="text/javascript" charset="utf-8"></script>
		<script type="text/javascript">
			mui.init()
		</script>
	</body>

</html>