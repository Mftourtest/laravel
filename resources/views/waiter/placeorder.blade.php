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
			<a href="javascript:history.back()"><span class="mui-icon mui-icon-back return" style="color:black"></span></a>
			<h1 class="mui-title"style="font-size: 1rem;">@lang('foods.food_order')</h1>
		</header>
		<form id="form1" action="over?lang={{$lang}}" method="POST">
		<div class="placeolder">
			<div class="ordertable">@lang('foods.biz_order_desk_sn')({{$desk_sn}})</div>
			@foreach($goods as $good)
			<div class="orderlist">
				<div class="ordername">{{$good['title']}}</div>
				<div class="orderdata">
					<span class="ordernum">x{{$good['num']}}</span>
					<span class="orderprice">฿{{$good['price']*$good['num']}}</span>
				</div>
			</div>
		    @endforeach
			<div class="orderlist listlist">
				<div class="ordername heji">@lang('foods.food_total')</div>
				<div class="orderdata">
					<span class=" ordernum ">x{{$allnum}}</span>
					<span class=" orderprice hejiprice">￥{{$allprice}}</span>
				</div>
			</div>
			<div class="beizhu">
				<div class="beizhuleft">@lang('foods.x_biz_remark')</div>
				<div class="beizhuright"></div>
			</div>
			<textarea name="remark" rows="5" cols="20" class="textarea" placeholder="@lang('foods.waiter_enter_remark')"></textarea>
			<input type="hidden" name="desk_sn" value="{{$desk_sn}}">
			@for($i=0;$i<$food_num;$i++)
			    <input type="hidden" name="goods[{{$i}}][id]" value="{{$goods[$i]['id']}}">
				<input type="hidden" name="goods[{{$i}}][num]" value="{{$goods[$i]['num']}}">
				<!-- <input type="hidden" name="goods[{{$i}}][title]" value="{{$goods[$i]['title']}}"> -->
				<input type="hidden" name="goods[{{$i}}][price]" value="{{$goods[$i]['price']}}">
			@endfor
			<input type="hidden" name="_token" value="<?php echo csrf_token(); ?>">
			    <input id="submit" class="goover" type="submit" value="@lang('foods.food_order')">
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