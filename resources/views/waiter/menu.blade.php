<!Doctype html>
<html>
	<head>
		<meta charset="UTF-8">
		<title></title>
		<meta name="viewport" content="width=device-width,initial-scale=1,minimum-scale=1,maximum-scale=1,user-scalable=no" />
		<link href="{{url('css/mui.min.css')}}" rel="stylesheet" />
		<link rel="stylesheet" type="text/css" href="{{url('css/reset.css')}}"/>
	</head>
	<style type="text/css">
		.placeolder{
			position: fixed;
			top:44px;
			left: 0;
			bottom: 0;
			right: 0;
			background: white;
			overflow: auto;
		}
		.ordertable{
			width: 100%;
			text-align: center;
			padding: 1rem;
			color: orange;
		}
		.orderlist{
			font-size: 0.7rem;
            width: 100%;
            border-bottom: 0.06rem solid #c8c7cc;
            display: flex;
            flex-direction: column;
            padding: 1rem;
		}
		.ordername{
			text-overflow: ellipsis;
            font-size: 1.4em;
		}
		.orderdata{
			display: flex;
            justify-content: flex-end;
            align-items: center;
            margin-top: 1rem;
		}
		.orderprice{
			margin-left: 2rem;
		}
		.listlist{
			color: red;
		}
		.beizhu{
			display: flex;
			justify-content: space-between;
			border-top: 0.06rem solid gainsboro;
			height: 3rem;
			line-height: 3rem;
			font-size: 0.9rem;
		}
		.beizhuleft{
			padding-left: 1rem;
		}
		.beizhuright{
			padding-right: 1rem;
			width: 50%;
			text-align: end;
			overflow: hidden;
			text-overflow:ellipsis;
			white-space: nowrap;
		}
		.textarea{
			font-size: 0.9rem;
			margin-bottom: 3rem;
		}
		.goover{
			height: 2rem;
			background: orange;
			text-align: center;
			width: 20%;
			line-height: 3rem;
			bottom: 0;
			color: white;
			float: right;
		}
	</style>
	<body>
		<header class="mui-bar mui-bar-nav" style="background: white;">
			<a href="reckoning?desk_sn={{$desk_sn}}&price=0&lang={{$lang}}"><span class="mui-icon mui-icon-back return" style="color:black"></span></a>
			<h1 class="mui-title"style="font-size: 1rem;">@lang('foods.waiter_point_menu')</h1>
		</header>
		<form id="form1" action="menu?desk_sn={{$desk_sn}}&lang={{$lang}}" method="POST">
		<div class="placeolder">
			<div class="ordertable">@lang('foods.biz_order_desk_sn')({{$desk_sn}})</div>
			@foreach($ordertemps as $i=>$ordertemp)
			<div class="orderlist">
				<div class="ordername"><input type="checkbox" name="ordertemp_id[{{$i}}]" value="{{$ordertemp['id']}}" />{{$ordertemp['title'.$suffix]}}</div>
				<div class="orderdata">
					<span class="ordernum">x{{$ordertemp['number']}}</span>
					<span class="orderprice">฿{{$ordertemp['price']*$ordertemp['number']}}</span>
				</div>
			</div>
		    @endforeach
			<div class="orderlist listlist">
				<div class="ordername heji">@lang('foods.food_total')</div>
				<div class="orderdata">
					<span class=" ordernum ">x{{$allnum}}</span>
					<span class=" orderprice hejiprice">฿{{$allprice}}</span>
				</div>
			</div>
			{{-- <div class="beizhu">
				<div class="beizhuleft">备注</div>
				<div class="beizhuright"></div>
			</div> --}}
			{{-- <textarea name="remark" rows="5" cols="20" class="textarea" value="{{$ordertemps[0]['remark']}}"></textarea> --}}
			<input type="hidden" name="_token" value="<?php echo csrf_token(); ?>">
			    <input id="submit" class="goover" type="submit" value="@lang('foods.waiter_cancel_dish')">
		</div>
		
		</form>
		<script src="{{url('js/mui.min.js')}}"></script>
		<script src="{{url('js/jquery-3.1.1.min.js')}}" type="text/javascript" charset="utf-8"></script>
		<script type="text/javascript">
			mui.init()
			$(function(){
				$(".textarea").keyup(function(){
				 	$(".beizhuright").html($(".textarea").val())
				 });

			   /*  $("#click").click(function(){
                $("#form1").submit(function(){
				$.ajax({
                type: "POST",//方法类型
                dataType: "text",//预期服务器返回的数据类型
                url: "over" ,//url
                data: $('#form1').serialize(),
                success: function (result) {
                    console.log(result);//打印服务端返回的数据(调试用)
                    if (result.resultCode == 200) {
                        alert("SUCCESS");
                    }
                    ;
                },
                error : function() {
                    alert("异常！");
                }
            });
            });
            }); */
			})



		</script>
	</body>

</html>