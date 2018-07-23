<!doctype html>
<html>
	<head>
		<meta charset="UTF-8">
		<title></title>
		<meta name="viewport" content="width=device-width,initial-scale=1,minimum-scale=1,maximum-scale=1,user-scalable=no" />
		<link href="{{ url('css/mui.min.css')}}" rel="stylesheet" />
		<link rel="stylesheet" type="text/css" href="{{ url('css/reset.css')}}"/>
	</head>
	<style type="text/css">
		.table{
			position: fixed;
    		top: 44px;
    		left: 0;
    		right: 0;
    		bottom: 44px;
    		background-color: white;
    		font-family: "微软雅黑";
    		overflow: auto;
		}
		.table-title{
			width: 100%;
			padding: 3%;
			font-size: 1rem;
		}
		.tableWrapper{
			display: flex;
			flex-wrap: wrap;
		}
		.tablelist{
			margin-left: 2.5%;
			width: 30%;
			border: 0.06rem solid gainsboro;
			display: flex;
			flex-direction: column;
			align-items: center;	
			margin-top: 3%;		
		}
		.tableNum{
			padding: 15%;
			font-size: 1rem;
		}
		.tableState{
			text-align: center;
			width: 5rem;
			background: orange;
			padding: 5% 0;
			font-size: 1rem;
			margin-bottom: 10%;
			border-radius: 5px;
			color: white;
		}
		a {color: inherit;}
	</style>
	<body>
		<header class="mui-bar mui-bar-nav" style="background: white;">
			<a href="index?lang={{$lang}}"><span class="mui-icon mui-icon-back return" style="color:black"></span></a>
			<h1 class="mui-title"style="font-size: 1rem;">{{Session::get('partner_name')}}</h1>
			<div style="float:right;margin-top:15px;"><a href="table?lang={{$lang}}">@lang('foods.waiter_refresh')</a></div>
		</header>
		{{ csrf_field() }}
		<div class="table">
		    @foreach($areainfos as $areainfo)

			<div class="table-title">			
				{{ $areainfo['area_name'.$suffix] }}			
			</div>		
			<ul class="tableWrapper">
			@foreach($deskinfos as $deskinfo)
			@if($deskinfo['area_id']==$areainfo['id'])
				<li class="tablelist">
					<div class="tableNum">{{$deskinfo['desk_sn']}}</div>
					@if($deskinfo['desk_state']==1)
						<div class="tableState"><a href="order?lang={{$lang}}&desk_sn={{$deskinfo['desk_sn']}}">{{ $deskinfo['state_name'.$suffix] }}</a></div>
					@elseif($deskinfo['desk_state']==2)
					    <div class="tableState">{{$deskinfo['state_name'.$suffix]}}</div>
					@elseif($deskinfo['desk_state']==3)
					    <div class="tableState"><a href="reckoning?desk_sn={{ $deskinfo['desk_sn'] }}&lang={{$lang}}&price=0">{{ $deskinfo['state_name'.$suffix] }}</a></div>
					@endif
					@if($deskinfo['desk_state']==1)
						{{--  <div class="tableState"><a href="voucher?desk_sn={{ $deskinfo['desk_sn'] }}&lang={{$lang}}">@lang('foods.check_group_package')</a></div>  --}}
					@elseif($deskinfo['desk_state']==2)
						<div class="tableState"><a href="empty?desk_sn={{ $deskinfo['desk_sn'] }}&lang={{$lang}}">@lang('foods.waiter_clear')</a></div>
					@elseif($deskinfo['desk_state']==3)
						<div class="tableState"><a href="order?lang={{$lang}}&desk_sn={{$deskinfo['desk_sn']}}">@lang('foods.waiter_add_dish')</a></div>
					@endif
				</li>
				@endif
				@endforeach
			</ul>
			@endforeach
			<div style="height: 3rem;"></div> <!--  别删      -->
		</div>
		<script src="{{ url('js/mui.min.js')}}"></script>
		<script src="{{ url('js/jquery-3.1.1.min.js')}}" type="text/javascript" charset="utf-8"></script>
		<script type="text/javascript">
			mui.init()
			
			$(function(){
				var text = $('.tableState')
				for(var i = 0; i<text.length; i++){
					var table = text[i]
					if($(table).text() == '未结账'|| $(table).text() == 'Unpaid'|| $(table).text() == 'ยังไม่ชำระ')
					{
						$(table).css('background','red')
					}
					if($(table).text() == '空闲' || $(table).text() == 'Tables available'|| $(table).text() == 'ว่าง')
					{
						$(table).css('background','rgb(0, 180, 60)')
					}
					if($(table).text() == '验券'|| $(table).text() == 'Apply coupon'|| $(table).text() == 'ตรวจสอบ')
					{
						$(table).css('background','#00a0dc')
					}
					if($(table).text() == '清空'|| $(table).text() == 'Remove'|| $(table).text() == 'ลบทิ้ง')
					{
						$(table).css('background','LightSlateGray')
					}
					if($(table).text() == '加菜'|| $(table).text() == 'Add more food'|| $(table).text() == 'ออเดอร์เพิ่ม')
					{
						$(table).css('background','rgb(0, 180, 60)')
					}
					$(table).on('touchend',function(){

						if($(this).text() == '验证团购券废弃')
						{
							window.location.href="voucher?lang={{$lang}}";
						}
					})
				}
			})
		</script>
	</body>

</html>