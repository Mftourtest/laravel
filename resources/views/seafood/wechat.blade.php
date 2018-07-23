<!doctype html>
<html>

	<head>
		<meta charset="UTF-8">
		<title></title>
		<meta name="viewport" content="width=device-width,initial-scale=1,minimum-scale=1,maximum-scale=1,user-scalable=no" />
		<link href="{{url('css/mui.min.css')}}" rel="stylesheet" />
	</head>
	<style type="text/css">
		.wechat{
			position: fixed;
    		top: 44px;
    		left: 0;
    		right: 0;
    		bottom: 0;
    		background: white;
    		font-size: 1.2rem;
		}
		.wechatpay{
			width: 100%;
			height: 100%;
			display: flex;
			justify-content: center;
			align-items: center;
			flex-direction: column;
		}
		.wechatpay img{
			margin-top: -12rem;
			margin-bottom: 1rem;
		}
	</style>
	<body>
		<header class="mui-bar mui-bar-nav" style="background: white;">
			<a href="reckoning?desk_sn={{$desk_sn}}&price={{$enomination}}&lang={{$lang}}"><span class="mui-icon mui-icon-back return" style="color:black"></span></a>
			<h1 class="mui-title"style="font-size: 1rem;">@lang('foods.waiter_wechat')/@lang('foods.waiter_alipay')</h1>
		</header>
		<div class="wechat">
			<div class="wechatpay">
				<img src="{{url('img/QRcode.jpg')}}"/>				<!--图片要活的数据-->
				@lang('foods.waiter_scan_qr')
			</div>
		</div>
		<script src="{{url('js/mui.min.js')}}"></script>
		<script type="text/javascript">
			mui.init()
		</script>
	</body>

</html>