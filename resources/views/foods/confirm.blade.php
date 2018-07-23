<?php
/**
 * Created by PhpStorm.
 * User: zhaoyao
 * Date: 2018/4/20
 * Time: 21:53
 */
?>

@extends('layouts.foods')
@section('title', '确认订单')


@section('content')
    <link rel="stylesheet" href="{{ url('css/getback.css?v=1') }}">
    <style>
    	body,html{background: ghostwhite;}
		.settlement {width: 100%;}
		.settlement_list {width: 100%;padding: 10px 2rem;border-bottom: 0.06rem solid rgba(7, 17, 27, 0.1)}
		.settlement_top {width: 100%;display: flex;justify-content: space-between;padding: 1.5rem 2rem;align-items: center;}
		.shop_name {font-size: 1.8rem;font-weight: 700;}
		.settlement_table {font-size: 1.8rem;font-weight: 700;color: #00b43c;}
		.settlement_content {display: flex;justify-content: space-between;position: relative;}
		.footimg {width: 5rem;height: 5rem}
		.food {position: absolute;left: 6rem; width: 65%; overflow: hidden;text-overflow: ellipsis; white-space: nowrap;}
		.food_num {font-size: 1rem;color: #93999f;}
		.settlement_bottom {width: 100%; padding: 10px 2px;}
		.other_list {font-size: 1.4rem;display: flex;justify-content: space-between;align-items: center;border-bottom: 0.06rem solid rgba(7, 17, 27, 0.1);padding: 0 2rem; height: 5rem; background: white;}
		.other_coupon_num {color: red;}
		.other_coupon_text {display: flex;align-items: center;}
		.other_other {color: #93999f;display: inline-block;width: 15rem;overflow: hidden;text-overflow: ellipsis;white-space: nowrap;text-align: end;}
		.lastpay {background:white; z-index: 11; width: 100%; display: flex; position: fixed; bottom: 0; border-top: 0.06rem solid rgba(7, 17, 27, 0.1);
            font-size: 1.5rem;line-height: 5rem;}
		.lastpay_left {background: white; width: 50%;}
		.lastpay_center{background: orange; width: 50%;border: none; color: white; display: inline-block; text-align: center;}
		a,a:active,a:link,a:hover{color: white; text-decoration: none; font-size: 1.5rem}
		.lastpay_right {color: white;background: #00b43c;text-align: center; border: none;width: 50%;}
		.button_list{width: 50%; display: flex;justify-content: flex-end;}
		.lastpaypay {padding-left: 20px;}
		.beizhutianchu {font-size: 1.2rem;width: 100%;background-color: white;position: fixed;bottom: -13rem;height: auto;display: none;}
		.beizhu_top {width: 100%;height: 4rem;text-align: center;position: relative;background: #f3f5f7;line-height: 4rem;}
		.beizhu_content {width: 90%;margin-left: 5%;}
		.beizhuinp {font-size: 1.2rem;height: 3rem;width: 100%;border: 0 solid white;}
		.beizhu_bottom {width: 90%;margin-left: 5%;display: flex;flex-wrap: wrap;}
		.beizhu_text {padding: 10px;margin: 4px;background-color: rgba(0, 160, 220, 0.2);}
		.active {background: rgb(0, 160, 220);color: white;}
		.coupon {position: relative;width: 95%;margin-left: 2.5%;margin-top: 1rem;}
		.couponimg {width: 100%;}
		.couponleft {position: absolute;top: 0;left: 1%;text-align: center;}
		.coupontext {color: #00a0dc;font-size:3rem;}
		.coupont-manjian {color: #00a0dc;font-size: 1.2rem;}
		.couponright {position: absolute;top: 0.5rem;left: 30%;text-align: start;}
		.shopname {padding-bottom: 0.5rem;color: black;font-size: 1.6rem;}
		.coupontime {color: darkorange;font-size: 1.3rem;position: absolute;bottom: 0; left: 0.5rem;}	
		.coupontdescribe {padding-bottom: 1rem;color: gray;font-size: 1rem;}
		.btn {position: absolute;top: 6.5rem;right: 0rem;border: 0.06rem solid #00a0dc;background-color: white;color: #00a0dc;text-align: center;width: 5rem;font-size: 1rem;padding: 0.5rem 0;}
		.over {position: absolute;right: 1rem;color: #00b43c;}
		.addivew{position: fixed;top: 0;left: 0;right: 0;bottom: 0;background: ghostwhite;z-index: 1111;display: none;width: 100%;height: 100%;overflow: auto;}
    </style>


    @if(!$isWechat)
    <header class="header">
        <a href="javascript:;" onclick="history.back();"><span class="glyphicon glyphicon-menu-left tubiao"></span></a>
        <div class="getcoupon-title">@lang('foods.food_confirm2')</div>
    </header>
    @endif

    <form action="{{ route('foods.order', ['param'=>$param]) }}" method="post" enctype="application/x-www-form-urlencoded">
        <div class="settlement">
            <div class="settlement_top" style='margin-bottom: 1rem;background: white;'>
                <div class="shop_name">{{ $params['partner_title'] }}</div>
                <div class="settlement_table">No. {{ $params['desk_sn'] }}</div>
            </div>
            @foreach($foods as $i=>$food)
                <div class="settlement_list" style='background: white;'>
                    <div class="settlement_content">
                        <img class="footimg" src="{{ $cdn.$food->thumb }}"/>
                        <div class="food">
                            <div class="food_name">{{ empty($food['package']) ? $food['title'.$suffix] : $food['title'.$suffix].' [ '.$food['package']['name'.$suffix].' ]' }}</div>
                            <div class="food_num">x {{ $food['num'] }}</div>
                        </div>
                        <div class="food_price">{{ $cate->money }} {{ empty($food['package'])?$food['price']:$food['package']['price'] }}</div>
                    </div>
                    {{--$food--}}
                </div>
            @endforeach
            {{--
                    <div class="settlement_list">
                        <div class="settlement_content">
                            <img class="footimg" src="http://fuss10.elemecdn.com/d/b9/bcab0e8ad97758e65ae5a62b2664ejpeg.jpeg?imageView2/1/w/114/h/114"/>
                            <div class="food">
                                <div class="food_name">牛肉馅饼</div>
                                <div class="food_num">x1</div>
                            </div>
                            <div class="food_price">￥14</div>
                        </div>
                    </div>
            --}}
            
            <div class="settlement_bottom" style='margin-bottom: 4.3rem; margin-top: 1rem;'>
				<div class="other_list" style='background: white;'>
                    <div class="other_lastcoupon">@lang('foods.food_total')</div>
                    <div class="other_coupon_text">                  
                        <span class="other_coupon_num">{{ $cate->money }} {{ $priceTotal }}</span>
                    </div>
                </div>
                @if($op == 'payment_choice')
                    <div class="other_list">
                        <div class="other_coupon">@lang('foods.food_coupon')</div>
                        <div class="other_coupon_text youhui">
                            <span id="coupon" class="other_coupon_num">{{ count($myCoupons) }} 张可用</span>
                            <span class="glyphicon glyphicon-menu-right"></span>
                        </div>
                    </div>
                @else
                    <div class="other_list">
                        <div class="other_coupon">@lang('foods.food_coupon')</div>
                        <div class="other_coupon_text">
                            <span id="coupon" class="other_coupon_num">@lang('foods.food_discount') {{ $cate->money }} {{ empty($myCoupons)?0:$myCoupons[0]['price'] }}</span>
                            <span class="glyphicon glyphicon-menu-right"></span>
                        </div>
                    </div>
                @endif
                <div class="other_list" style='background: white;'>
                    <div class="other_lastcoupon">@lang('foods.food_discount'){{ $partner['discount']==1?0:$partner['discount']*100 }}%</div>
                    <div class="other_coupon_text">
                        <!--<span class="xiaoji">小计&nbsp;</span>-->
                        <span class="other_coupon_num">- {{ $cate->money }} <span id="discount">{{ $fee['discount'] }}</span></span>
                    </div>
                </div>
                <div class="other_list" style='background: white;'>
                    <div class="other_lastcoupon">@lang('foods.food_tax')</div>
                    <div class="other_coupon_text">
                        <span class="xiaoji">({{ $partner['fee_tax']*100 }}%)&nbsp;</span>
                        <span class="other_coupon_num">{{ $cate->money }} {{ $fee['tax'] }}</span>
                    </div>
                </div>
                <div class="other_list" style='background: white;'>
                    <div class="other_lastcoupon">@lang('foods.food_charge')</div>
                    <div class="other_coupon_text">
                        <span class="xiaoji">({{ $partner['fee_srv']*100 }}%)&nbsp;</span>
                        <span class="other_coupon_num">{{ $cate->money }} {{ $fee['srv'] }}</span>
                    </div>
                </div>
                <div class="other_list beizhu" style='background: white;'>
                    <div class="other_coupon">@lang('foods.food_remark')</div>
                    <div class="other_coupon_text">
                        <span class="other_other">@lang('foods.food_remark')</span>
                        <span class="glyphicon glyphicon-menu-right"></span>
                    </div>
                </div>
            </div>

        </div>
        <div class="lastpay">
            <div class="lastpay_left">
                <span class="lastpaypay" style="color: red;">
                    @lang('foods.food_total') {{ $cate->money }} <span id="money">{{ $params['money'] }}</span>
                    <input type="hidden" id="money1" value="{{ $params['money'] }}">
                </span>
            </div>
            <div class="button_list">
                @if($op == 'payment_choice')
				 <a href="{{ route('foods', ['param'=>$param]) }}" class="lastpay_center">@lang('foods.food_meal')</a>
                @endif
                <button type="submit" class="lastpay_right">@lang('foods.food_order')</button>
            </div>
        </div>
        <div class="beizhutianchu">
            <div class="beizhu_top">
                <span>@lang('foods.food_remark')</span>
                <span class="over">@lang('foods.food_done')</span>
            </div>
            <div class="beizhu_content">
                <input class="beizhuinp" placeholder="" disabled/>
                <input class="beizhuinp" type="hidden" name="remark" value="">
                <input type="hidden" name="op" value="{{ $op }}">
                <input type="hidden" name="order_id" value="">
                <input type="hidden" name="params" value="{{ $paramsB64 }}">
                <input type="hidden" name="foods" value="{{ $foodsB64 }}">
                <input type="hidden" name="coupon[id]" id="couponId" value="">
                <input type="hidden" name="coupon[price]" id="couponPrice" value="">
                {{ csrf_field() }}
            </div>
            <div class="beizhu_bottom">
                @foreach($remark['view'] as $i=>$view)
                    <span class="beizhu_text" data-rmk="{{ @$remark['ticket'][$i] }}">{{ $view }}</span>
                @endforeach
            </div>
        </div>
        
        <div class="addivew">
			<header class="header">
				<div class="getcoupon-title">优惠券</div>
                <button type="button" class="close" style="margin-right: 10px;"><span>&times;</span></button>
			</header>
            {{-- 我的优惠券 --}}
            @foreach($myCoupons as $myCoupon)
            <div class='coupon_list'>
                <div class="coupon use" data-id="{{ $myCoupon['id'] }}" data-price="{{ $myCoupon['coupon']['price'] }}">
                    <img class="couponimg" src="{{ url('images/discount.png') }}"/>
                    <div class="couponleft">
                        <div class="coupontext">{{ $cate->money }} {{ $myCoupon['coupon']['price'] }}</div>
                        <div class="coupont-manjian">满{{ $myCoupon['coupon']['threshold'] }}可用</div>
                    </div>
                    <div class="couponright">
                        <div class="shopname">{{ $partner->title }}</div>              
                         <div class="coupontdescribe">所有商品适用(酒水除外)</div>
                    </div>
                   <div class="coupontime">{{ date('Y-m-d H:i:s', $myCoupon['coupon']['endtime']) }}过期</div>
                  <!--  <button class="btn" data-id="{{ $myCoupon['id'] }}" data-price="{{ $myCoupon['coupon']['price'] }}">立即使用</button>-->
                </div>
            </div>    
            @endforeach
              <div style='height: 4rem;'></div>
		</div>
    </form>

@endsection


@push('scripts')
<script src="//cdn.bootcss.com/jquery/1.11.3/jquery.min.js"></script>
<script src="//cdn.bootcss.com/bootstrap/3.3.5/js/bootstrap.min.js"></script>
<script>
 
    /*$(function(){
        $('.beizhu_text').on('touchend',function(){
            $(this).addClass("active").siblings().removeClass("active");
            var text = ''
            text = $(this).text()
            var inp = $('.beizhuinp').val(text)
            $('.other_other').text(text)
        })
    })*/

    // 备注
    $('.beizhu').on('touchend', function () {
        $('.beizhutianchu').show()
        $('.beizhutianchu').animate({bottom: '4rem'})
    })
    $('.over').on('touchend', function () {
        $('.beizhutianchu').animate({bottom: '-19rem'})
        $('.beizhutianchu').hide()
    })

    // 优惠券
    $('.youhui').on('touchend', function () {
        $('.addivew').show()
        $('.addivew').animate({left: '0'})
    })

    // 选择优惠券后从新计算价格
    $('.use').on('touchend', function (e) {
        couponHide();
        var couponId = $(this).attr("data-id");
        var couponPrice = parseFloat($(this).attr("data-price"));
        var money1 = parseFloat($("#money1").val());
        var money = money1-couponPrice;
        console.log(couponPrice, money);
        $("#couponId").val(couponId);
        $("#couponPrice").val(couponPrice);
        $("#money").html(money);
        $("#coupon").html(couponPrice)
    });
    
    // 不使用优惠券 关闭
    $('.close').on('touchend', couponHide);

    // 隐藏优惠券
    function couponHide() {
        $('.addivew').animate({left: '0'});
        $('.addivew').hide();
    }

    // 整合备注
    var showValue = '';
    $('.beizhu_text').on('touchstart', function () {
        console.log($(this).hasClass("active"));
        if (!$(this).hasClass("active")) {
            $(this).addClass("active");
            showValue += $(this).attr("data-rmk") + '、';
        }
        else {
            $(this).removeClass("active");
            showValue = showValue.replace($(this).attr("data-rmk") + '、', '');
        }
        $(".beizhuinp").val(showValue);
        $(".other_other").html(showValue);
    })
</script>
@endpush