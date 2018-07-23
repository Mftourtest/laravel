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
		.discount{
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
		}
		.mui-radio label{
			font-size: 1rem;
		}
		.goover{
			height: 3rem;
			background: orange;
			text-align: center;
			width: 100%;
			line-height: 3rem;
			position: fixed;
			bottom: 0;
			color: white;
		}
	</style>
	<body>
		<header class="mui-bar mui-bar-nav" style="background: white;">
			<a href="reckoning?desk_sn={{$desk_sn}}&lang={{$lang}}"><span class="mui-icon mui-icon-back return" style="color:black"></span></a>
			<h1 class="mui-title"style="font-size: 1rem;">优惠</h1>
		</header>
		<div class="discount">
			<div class="mui-card">
				<form class="mui-input-group" action="reckoning" method="post">
				{{ csrf_field() }}
					<div class="mui-input-row mui-radio">
						<label>店铺优惠：无优惠</label>
						<input name="radio1" type="radio" value="1" checked>
					</div>
					<div class="mui-input-row mui-radio">
						<label>店铺优惠：9.0折</label>
						<input name="radio1" type="radio" value="0.9">
					</div>
					<div class="mui-input-row mui-radio">
						<label>店铺优惠：8.0折</label>
						<input name="radio1" type="radio" value="0.8" >
					</div>
					<div class="mui-input-row mui-radio">
						<label>店铺优惠：7.0折</label>
						<input name="radio1" type="radio" value="0.7" >
					</div>
					<div class="mui-input-row mui-radio">
						<label>店铺优惠：6.0折</label>
						<input name="radio1" type="radio" value="0.6" >
					</div>
					<div class="mui-input-row mui-radio">
						<label>店铺优惠：5.0折</label>
						<input name="radio1" type="radio" value="0.5">
					</div>
					<div class="mui-input-row mui-radio">
						<label>店铺优惠：4.0折</label>
						<input name="radio1" type="radio" value="0.4">
					</div>
					<div class="mui-input-row mui-radio">
						<label>店铺优惠：3.0折</label>
						<input name="radio1" type="radio" value="0.3">
					</div>
					<div class="mui-input-row mui-radio">
						<label>店铺优惠：2.0折</label>
						<input name="radio1" type="radio" value="0.2">
					</div>
					<div class="mui-input-row mui-radio">
						<label>店铺优惠：1.0折</label>
						<input name="radio1" type="radio" value="0.1">
					</div> 
					<input type="hidden" name="desk_sn" value="{{$desk_sn}}">
					<input type="submit" class="goover"  value="">确定折扣
				</form>
			</div>
		</div>
		<script src="{{url('js/mui.min.js')}}"></script>
		<script type="text/javascript">
			mui.init()
		</script>
	</body>

</html>