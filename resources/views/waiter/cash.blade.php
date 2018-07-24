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
		.cash{
			position: fixed;
			top: 0;
			left: 0;
			bottom: 0;
			right: 0;
			background: white;
		}
		.cash_title{
			height: 3rem;
			margin-top: 44px;
			line-height: 3rem;
			width: 100%;
			text-align: center;
			color: orange;
		}
		.cashlist{
			margin-top: 1rem;
			width: 100%;
			height: 3rem;
			line-height: 3rem;
			text-align: center;
			display: flex;
			color: white;
			justify-content: space-between;
			box-shadow: 0 0 7px 0 rgba(213,213,213,0.8)
		}
		.cashlistleft{
			padding-left: 1rem;
		}
		.cashlistright{
			padding-right: 1rem;
		}
		.last{
			position: fixed;
			bottom: 4rem;
			width: 100%;
			height: 3rem;
			line-height: 3rem;
			text-align: center;
			display: flex;
			justify-content: space-between;
			box-shadow: 0 0 7px 0 rgb(213,213,213)
		}
		.lastleft{
			padding-left: 1rem;
		}
		.lastright{
			padding-right: 1rem;
		}
		.bottom{
			position: fixed;
			bottom: 0;
			width: 100%;
			height: 3rem;
			line-height: 3rem;
			text-align: center;
			color: white;
			background: rgb(0, 180, 60);
		}
		.income{
			padding-right: 1rem;
			margin-top:20px;
			margin-left:20px;
		}
	</style>
	<body>
		<header class="mui-bar mui-bar-nav" style="background: white;">
			<a href="reckoning?desk_sn={{$desk_sn}}&lang={{$lang}}&price={{$enomination}}"><span class="mui-icon mui-icon-back return" style="color:black"></span></a>
			<h1 class="mui-title"style="font-size: 1rem;">@lang('foods.waiter_cash_receipts')</h1>
		</header>
		<div class="cash">
			<div class="cash_title">@lang('foods.biz_order_desk_sn'){{$desk_sn}}</div>
			<div class="cashlist" style="background: orange;">
				<div class="cashlistleft">@lang('foods.waiter_receivable')：</div>
				<div id="yingshou" class="cashlistright">{{$last_price}}</div>
			</div>
			<div class="income">				
				<input type="text" placeholder="@lang('foods.waiter_amount_payment')" id="shishou"/>			
			</div>
			
			<div class="last">
				<div class="lastleft">@lang('foods.waiter_give_change')：฿<span id="zhaoling">0</span>  </div>
				<div class="lastright">@lang('foods.x_biz_origin1')：฿<span id="repayValue">0</span></div>
			</div>
			<div class="bottom" onclick="window.location.href='cashpay?desk_sn={{$desk_sn}}&last_price={{$last_price}}&price={{$enomination}}&lang={{$lang}}';">@lang('foods.waiter_check_out')</div>
		</div>
		
		<script src="{{url('js/mui.min.js')}}"></script>
		<script src="{{url('js/jquery-3.1.1.min.js')}}" type="text/javascript" charset="utf-8"></script>
		<script type="text/javascript">
			mui.init()

			$(function(){    
                $('#shishou').bind('input propertychange', function() {    
	                var a = $(this).val();  
	                var b = $('#yingshou').text();  
	                var c= a - b; 		
	            $('#zhaoling').html(c);    
				$('#repayValue').html(a); 
            });   
            }) 
		</script>
	</body>

</html>