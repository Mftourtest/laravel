<!doctype html>
<html>
	<head>
		<meta charset="UTF-8">
		<title></title>
		<meta name="viewport" content="width=device-width,initial-scale=1,minimum-scale=1,maximum-scale=1,user-scalable=no" />
		<link href="{{url('css/mui.min.css')}}" rel="stylesheet" />
		<link rel="stylesheet" type="text/css" href="{{url('css/reset.css')}}"/>
	</head>
	<style type="text/css">
		.reckony{
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
		.cancel_order{
			float:left;
    		margin-top:20px;
			text-align: left;
			padding-left: 1rem;
			display: flex;
			justify-content: space-between;
			color: red;
		}
		.print{
			float:right;
    		margin-top:20px;
			text-align: right;
			padding-right: 1rem;
			color: green;
		}
		.hasprint{
			float:right;
    		margin-top:20px;
			text-align: right;
			padding-right: 1rem;
			color: gray;
		}
		.reckony_desk{
			float:left;
    		margin-top:20px;
			margin-left:120px;
			text-align: center;
			color: orange;
		}
		.reckony_hight{
			margin: 15% 0;
			width: 100%;
			text-align: center;
			color: orange;
		}
		.reckony_title{
			margin: 5% 0;
			width: 100%;
			text-align: center;
			color: orange;
		}
		.reckony_list{
			padding: 1rem 0;
			display: flex;
			justify-content: space-between;
			border-bottom: 0.06rem solid gainsboro;
		}
		.reckonyleft{
			padding-left: 1rem;
			color: black;
		}
		.reckonyright{
			padding-right: 1rem;
		}
		.mui-icon-arrowright{
			font-size: 1rem;
			color: black;
		}
		.money{
			border-top: 0.06rem solid gainsboro;
		}
		.red{
			color: red;
		}
	</style>
	<body>
		<header class="mui-bar mui-bar-nav" style="background: white;">
			<a href="table?lang={{$lang}}"><span class="mui-icon mui-icon-back return" style="color:black"></span></a>
			<h1 class="mui-title"style="font-size: 1rem;">@lang('foods.waiter_check_out')</h1>
		</header>
		<div class="reckony">
		    <a href="javascript:if(confirm('@lang('foods.waiter_confirm_deletion')？'))location='cancelorder?desk_sn={{$desk_sn}}&lang={{$lang}}'">
			    <div class="cancel_order">@lang('foods.waiter_cancel_order')</div>
			</a>

            @if($is_print==1)
			<a href="manualprint?desk_sn={{$desk_sn}}&lang={{$lang}}">
			    <div class="print">@lang('foods.waiter_manual_printing')</div>	
			</a>
			@else
			    <div class="hasprint">@lang('foods.waiter_already_printing')</div>	
			@endif

			<div class="reckony_title">@lang('foods.biz_order_desk_sn')({{$desk_sn}})</div>			    

			<div class="reckony_list">
				<div class="reckonyleft">
					@lang('foods.waiter_order_amount')
				</div>
				<div class="reckonyright">
				฿{{$total_price}}
				</div>
			</div>
			<div class="reckony_list">
				<div class="reckonyleft">
					@lang('foods.waiter_discount')：
				</div>
				<div class="reckonyright">
					-฿{{$discount_price}}
				</div>
			</div>
			<div class="reckony_list">
				<div class="reckonyleft">
					@lang('foods.food_charge')：
				</div>
				<div class="reckonyright">
					฿{{$srv_price}}
				</div>
			</div>
			<div class="reckony_list">
				<div class="reckonyleft">
					@lang('foods.food_tax')：
				</div>
				<div class="reckonyright">
					฿{{$tax_price}}
				</div>
			</div>
{{--   			<a href="discount?desk_sn={{$desk_sn}}&lang={{$lang}}">
				<div class="reckony_list">
					<div class="reckonyleft">
						优惠折扣
					</div>
					<div class="reckonyright">
						<span class="mui-icon mui-icon-arrowright"></span>
					</div>
				</div>
			</a>   --}}
			
			@if($enomination==0)
			<a href="coupon?desk_sn={{$desk_sn}}&price=0&lang={{$lang}}">
				<div class="reckony_list">
					<div class="reckonyleft">
						@lang('foods.waiter_cash_coupon')
					</div>
					<div class="reckonyright">
						<span class="mui-icon mui-icon-arrowright"></span>
					</div>
				</div>
			</a>
			@else
			   <div class="reckony_list">
					<div class="reckonyleft">
						@lang('foods.waiter_cash_coupon')
					</div>
					<div class="reckonyright">
						-฿{{$enomination}}
					</div>
				</div>
			@endif

			<a href="menu?desk_sn={{$desk_sn}}&lang={{$lang}}">
				<div class="reckony_list">
					<div class="reckonyleft">
						@lang('foods.waiter_cancel_dish')
					</div>
					<div class="reckonyright">
						<span class="mui-icon mui-icon-arrowright"></span>
					</div>
				</div>
			</a>  

			<div class="reckony_list">
				<div class="reckonyleft red">
					@lang('foods.waiter_receivable')：
				</div>
				<div class="reckonyright red">
				   ฿{{$last_price}}
				</div>
			</div>
			<div class="reckony_title">@lang('foods.waiter_payment_method')</div>
			<a href="cash?desk_sn={{$desk_sn}}&last_price={{$last_price}}&lang={{$lang}}&price={{$enomination}}">
				<div class="reckony_list money">
					<div class="reckonyleft">
						@lang('foods.waiter_cash')
					</div>
					<div class="reckonyright">
						<span class="mui-icon mui-icon-arrowright"></span>
					</div>
				</div>
			</a>
			{{--  <a href="wechat?desk_sn={{$desk_sn}}&lang={{$lang}}&price={{$enomination}}">
				<div class="reckony_list">
					<div class="reckonyleft">
						@lang('foods.waiter_wechat')/@lang('foods.waiter_alipay')
					</div>
					<div class="reckonyright">
						<span class="mui-icon mui-icon-arrowright"></span>
					</div>
				</div>
			</a>  --}}
			{{-- <a href="alipay?desk_sn={{$desk_sn}}&lang={{$lang}}">
				<div class="reckony_list">
					<div class="reckonyleft">
						支付宝
					</div>
					<div class="reckonyright">
						<span class="mui-icon mui-icon-arrowright"></span>
					</div>
				</div>
			</a> --}}
		</div>
		<script src="{{url('js/mui.min.js')}}"></script>
		<script type="text/javascript">
			mui.init()
		</script>
	</body>

</html>