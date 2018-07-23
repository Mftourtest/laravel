<?php
/**
 * Created by PhpStorm.
 * User: zhaoyao
 * Date: 2018/4/20
 * Time: 21:53
 */
?>

@extends('layouts.foods')
@section('title', '我的团购')


@section('content')
    <link rel="stylesheet" href="{{ url('css/getback.css?v=1')}}">
    <style type="text/css">
        .testcoupon{width: 100%; height: 14rem; border-bottom: 0.06rem solid rgba(7,17,27,0.1); margin-top: 4rem;}
        .texttop{margin-top: 1.5rem; display: flex; align-items: center;}
        .testinp{margin-left: 15%; line-height: 5rem; font-size: 1.5rem;}
        .inp{border-radius: 0; height: 4rem; border: 1px solid #00a0dc; padding: 1rem;}
        .gotest{margin-top: 2rem; height: 4rem; width: 80%; margin-left: 10%; background-color: #00a0dc;
			line-height: 4rem; text-align: center; color: white; border-radius: 4px; border: none;}
        .textdetail{width: 90%;margin-left: 5%;margin-top: 1rem;display: none;}
		.textdetail_title{font-size: 2rem;font-weight: 700;padding-bottom: 0.5rem;}
		.textimg_title{display: flex;justify-content: space-between;font-size: 1rem;}
		.spantwo{color: gray;}
		.textimg{margin-top: 1rem;display: flex;justify-content: space-between;}
		.textimg img{width: 48%;height: 12rem;}
		.textxiangqing{margin-top: 3rem;}
		.texttitletwo{font-size: 1.5rem;font-weight: 700;}
		ul{-webkit-margin-after: 1em;-webkit-padding-start: 20px;-webkit-margin-before: 1em;}
		.foodname{float: left;max-width: 70%;overflow: hidden;text-overflow:ellipsis;white-space: nowrap;font-weight: 700;}
		.textli{position: relative;}
		.food_price{position: absolute;right: 0;}
		.beizhutwo{font-size:1.5rem;font-weight: 700;}
		.tishi{margin-top: 3rem;}
		.youxiaoqi{margin-top: 1rem;}
		.shijian{font-weight: 700;}
		#feiyong, #xuzhi{margin: 2rem 0;}
        /*.textbottom{display: flex; justify-content: center;}
        .test-t{width: 100%; text-align: center; font-size: 1.5rem;}
        .testwrapper{position: fixed; top: 25%; height: 55%; width: 80%; left: 10%; background-color: white; display: none; z-index: 1; border-radius: 4px;}
        .tianchu{position: fixed; top: 0; left: 0; bottom: 0; right: 0; height: 100%; width: 100%; background: rgba(7,17,27,0.6); display: none;}
        .glyphicon-remove{color: white; padding: 0.5rem; position: absolute; right: 0.5rem; top: 0.5rem;}
        .testwrappertop{display: flex; height: 3rem; line-height: 2rem; justify-content: center; position: relative; align-items: center; background: #808080;}
        .testtest{color: white;}
        .testwrapperconten{display: flex; flex-direction: column;}
        .testwrapperlist{padding: 0.5rem;}*/
    </style>

    @if(!$isWechat)
        <header class="header">
            <a href="index.html"><span class="glyphicon glyphicon-menu-left tubiao"></span></a>
            <div class="getcoupon-title">我的团购</div>
        </header>
    @endif

	<div class="testcoupon">
		<div class="texttop">
			<div class="testinp">团购验证码：</div>
			<input class="inp" id="code" name="code" placeholder="请输入团购验证码"/>
		</div>
		<button type="submit" id="submit" class="gotest">确定</button>
	</div>

    <div class="textdetail">
		<div id="title" class="textdetail_title">超值三人套餐</div>
		<div class="textimg_title">
			<span class="spanone">随时退 | 免预约 | 过期自动退</span>
			<span class="spantwo">已售7</span>
		</div>
		<div class="textimg">
			<img src="http://www.mofangtour.com/static/team/2018/0115/15159845731061.jpg"/>
			<img src="http://www.mofangtour.com/static/team/2018/0115/15159845731061.jpg"/>
		</div>
		<div id="feiyong"></div>
		<div id="xuzhi"></div>
		{{--<div class="textxiangqing">
			<div class="texttitletwo">超值三人套餐</div>
			<ul>
				<li class="textli">
					<div class="foodname">红烧扁口</div>
					<span style="color: gray; margin-left: 0.5rem;">1份</span>
					<span class="food_price">￥98</span>
				</li>
				<li class="textli">
					<div class="foodname">红烧扁口</div>
					<span style="color: gray; margin-left: 0.5rem;">1份</span>
					<span class="food_price">￥98</span>
				</li>
				<li class="textli">
					<div class="foodname">红烧扁口</div>
					<span style="color: gray; margin-left: 0.5rem;">1份</span>
					<span class="food_price">￥98</span>
				</li>
				<li class="textli">
					<div class="foodname">红烧扁口</div>
					<span style="color: gray; margin-left: 0.5rem;">1份</span>
					<span class="food_price">￥98</span>
				</li>
				<li class="textli">
					<div class="foodname">红烧扁口</div>
					<span style="color: gray; margin-left: 0.5rem;">1份</span>
					<span class="food_price">￥98</span>
				</li>
				<li class="textli">
					<div class="foodname">米饭</div>
					<span style="color: gray; margin-left: 0.5rem;">1份</span>
					<span class="food_price">￥3</span>
				</li>
			</ul>
		</div>
		<div class="beizhutwo">
			<span>备注：不放辣椒</span>
		</div>
		<div class="fuwu">
			<ul>
				<li class="textli"><div class="foodname">免费提供餐巾纸</div></li>
				<li class="textli"><div class="foodname">休息厅免费</div></li>
			</ul>
		</div>
		<div class="tishi textdetail_title">温馨提示</div>
		<div class="youxiaoqi">
			<div class="quantime">有效期</div>
			<ul>
				<li class="textli"><div class="shijian">2017.7.23 至 2018.1.21 (周末、法定节假日通用)</div></li>
			</ul>
			<div class="quantime">使用时间</div>
			<ul>
				<li class="textli"><div class="shijian">11:00-24:00</div></li>
			</ul>
			<div class="quantime">使用规则</div>
			<ul>
				<li class="textli"><div class="shijian">无需预约，消费高峰时可能需要等位</div></li>
				<li class="textli"><div class="shijian">每张团购券建议3人使用</div></li>
				<li class="textli"><div class="shijian">可免费使用包间</div></li>
				<li class="textli"><div class="shijian">可免费使用包间</div></li>
				<li class="textli"><div class="shijian">可免费使用包间</div></li>
				<li class="textli"><div class="shijian">可免费使用包间</div></li>
				<li class="textli"><div class="shijian">可免费使用包间</div></li>
				<li class="textli"><div class="shijian">可免费使用包间</div></li>
			</ul>
		</div>--}}
	</div>
<!--    <div class="textbottom">
        <img src="img/QRcode.jpg"/>
    </div>
    <div class="test-t">扫一扫 泰国吃喝玩乐1折起</div>
    <div class="testwrapper">
        <div class="testwrappertop">
            <span class="testtest">验证成功</span>
            <span class="glyphicon glyphicon-remove"></span>
        </div>
        <div class="testwrapperconten">
            <span class="testwrapperlist">订单编号：124124123</span>
            <span class="testwrapperlist">项目名称：魔方旅行</span>
            <span class="testwrapperlist">套餐名称：魔方旅行</span>
            <span class="testwrapperlist">价格：￥1231</span>
            <span class="testwrapperlist">数量：1</span>
            <span class="testwrapperlist">使用日期：2018.2.1</span>
            <span class="testwrapperlist">姓名：ZZY</span>
            <span class="testwrapperlist">备注信息：啊啊啊啊啊啊啊啊</span>
        </div>
    </div>
    <div class="tianchu"></div>-->
@endsection


@push('scripts')
    <script src="//cdn.bootcss.com/jquery/1.11.3/jquery.min.js"></script>
    <script src="//cdn.bootcss.com/bootstrap/3.3.5/js/bootstrap.min.js"></script>
    <script type="text/javascript">
        $(function () {
            /*$('.gotest').on('touchend', function () {
                $('.testwrapper').show()
                $('.tianchu').show()
            })
            $('.tianchu').on('touchend', function () {
                $('.testwrapper').hide()
                $('.tianchu').hide()
            })
            $('.glyphicon-remove').on('touchend', function () {
                $('.testwrapper').hide()
                $('.tianchu').hide()
            })*/
           
			/*$('.gotest').on("touchend",function(){
				$('.textdetail').show();
			});*/

            $('#submit').on('touchend',function(){
                var code = $("#code").val();
                console.log('code:', code);
                $.ajax({
                    url     : "{{ route('foods.team', ['param'=>$param]) }}",
                    type    : "POST",
                    dataType: "JSON",
                    data    : {
                        _token  : '{{ csrf_token() }}',
                        op      : 'foods_team',
                        code    : code,
                    },
                    success : function(d, s) {
                        console.log(d, s);
                        $("#code").val("");
                        $("#code").attr("placeholder", d.msg);

                        if (code == d.code) {
                            var teamCid = $("#team_cid").val();
                            if (teamCid != d.code) {
                                $("#title").html(d.team.title);
                                $(".textimg img:eq(0)").attr("src", '{{ $cdn }}' + d.team.image1);
                                $(".textimg img:eq(1)").attr("src", '{{ $cdn }}' + d.team.image2);
                                $("#feiyong").html(d.team.feiyong);
                                $("#xuzhi").html(d.team.xuzhi);
                                $('.textdetail').show();
                            }
                        }
                    }
                });
            })
        })
    </script>
@endpush