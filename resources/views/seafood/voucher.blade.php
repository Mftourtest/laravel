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
		.voucher{
			position: fixed;
    		top: 44px;
    		left: 0;
    		right: 0;
    		bottom: 0;
    		background: white;
		}
		.voucherinp{
			width: 100%;
			padding: 5% 10%;
			display: flex;
		}
		.voucherbtn{
			height: 2.5rem;
			width: 6rem;
			margin: 0 0.5rem;
		}
		.voucherdis{
			display: none;
		}
		.vouchercount{
			padding: 50% 30%;
			margin-top: 1.5rem;
			position: relative;
			overflow: auto;
		}

		.vouchercounttop{
			position: absolute;
			top: 1rem;
			left: 0;
			width: 100%;
			text-align: center;
			font-size: 1rem;
		}
		.vouchercountbottom{
			width: 100%;
			position: absolute;
			top: 3rem;
			left: 0;
		
		}
		.voucherlist{
			display: flex;
			justify-content: space-between;
			align-items: center;
			margin-top: 1rem;
			margin-bottom: 1rem;
		}
		.voucherlistleft{
			width: 70%;
			padding-left: 3rem;
			overflow: hidden;
			text-overflow:ellipsis;
			white-space: nowrap;
		}
		.voucherlistright{
			padding-right: 3rem;
		}
		.voucherlast{
			width: 100%;
			text-align: center;
			margin: 1.5rem 0;
			color: red;
		}
		.goshop{
			display: none;
			margin-top: 1rem;
			width: 50%;
			height: 2.5rem;
			margin-left: 25%;
			border: 0.06rem solid gainsboro;
			border-radius: 5px;
			text-align: center;
			line-height: 2.5rem;
			color: black;
			font-size: 1rem;
		}
	</style>
	<body>
		<header class="mui-bar mui-bar-nav" style="background: white;">
			<a href="table?lang={{$lang}}"><span class="mui-icon mui-icon-back return" style="color:black"></span></a>
			<h1 class="mui-title"style="font-size: 1rem;">@lang('foods.check_group_package')</h1>
		</header>
		<div class="voucher">
			<form action="voucher?desk_sn={{$desk_sn}}&lang={{$lang}}" method="post">
				{{ csrf_field() }}
			    <div class="voucherinp">
			    	<input type="text" name="voucher_id" class="voucherinput"/>
				    <button type="submit" class="voucherbtn">@lang('foods.waiter_confirm')</button>
			    </div>
			</form>
			@if(!empty($res))
			<div style="color:red;" align="center">{{$res['msg']}}</div>
			@endif
{{--  			<div class="voucherdis">
				<div class="vouchercount mui-card">
					<div class="vouchercounttop">
						双人团购套餐
					</div>
					<div class="vouchercountbottom">
						<div class="voucherlist">
							<div class="voucherlistleft">三文鱼</div>
							<div class="voucherlistright">1</div>
						</div>
						<div class="voucherlist">
							<div class="voucherlistleft">三文鱼</div>
							<div class="voucherlistright">1</div>
						</div>
						<div class="voucherlist">
							<div class="voucherlistleft">三文鱼</div>
							<div class="voucherlistright">1</div>
						</div>
						<div class="voucherlist">
							<div class="voucherlistleft">三文鱼</div>
							<div class="voucherlistright">1</div>
						</div>
						<div class="voucherlist">
							<div class="voucherlistleft">三文鱼</div>
							<div class="voucherlistright">1</div>
						</div>
						
						<div class="voucherlast">
							金额：￥123142
						</div>
					</div>
				</div>
			</div>  --}}
		</div>
		<script src="{{url('js/mui.min.js')}}"></script>
		<script src="{{url('js/jquery-3.1.1.min.js')}}" type="text/javascript" charset="utf-8"></script>
		<script type="text/javascript">
			mui.init()
		</script>
	</body>

</html>