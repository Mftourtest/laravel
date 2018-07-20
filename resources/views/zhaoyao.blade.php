<?php
/**
 * Created by PhpStorm.
 * User: zhaoyao
 * Date: 2018/4/20
 * Time: 21:53
 */
?>

@extends('layouts.foods')
@section('title', '首页')


@section('content')
    <style>
    	/*修改样式*/
    	body{background: ghostwhite;}
    	.nav-pills>li+li{margin-left: 0;}
    	.row{width: 100%; margin: 0; background: white;}
    	.my-tab{width: 33.3%;}
    	#partner{background: ghostwhite;}
    	.list-group-item:first-child{  border-top-left-radius: 0; border-top-right-radius: 0; }
    	.list-group-item:last-child{border-bottom-right-radius: 0;border-bottom-left-radius: 0;}
    	.col-xs-9{margin-left: 1.5%;width:73.5%;}
    	.shoptext{ align-items: flex-start; justify-content: center;display: flex;flex-direction: column;height: 10rem; color: white;margin-left: 1rem;margin-top: 0.8rem;}
        .container{max-width: 768px; padding: 0;}
        .btn.focus, .btn:focus, .btn:hover{color:white}
        .head{width: 768px; height: 135px; background: white; display: flex;}
        .head-bg{width: 768px; height: 135px; position: absolute;z-index: 0; filter: blur(10px); -webkit-filter: blur(10px)}
        .head-img{width: 64px; height: 64px; margin: 30px 0 30px 30px;; z-index: 1; box-shadow: 0 0 5px #666;}
        .nav1{height: 40px; background: white;}
        .nav2{text-align: center; position: fixed; top: 40px; bottom: 51px; overflow-y: scroll; overflow-x: hidden;}
        .title{font-size: 16px; font-weight: bolder; z-index: 1;}
        .nav{border-bottom: 0.06rem solid gainsboro; background: white;}
        .nav-pills>li>a{border-radius: 0; height: 60px; display: flex; justify-content: center; align-items: center;
            background: #f4f5f7;}
        .nav-pills>li.active>a, .nav-pills>li.active>a:focus, .nav-pills>li.active>a:hover{background: white; color: #00a0dc;}
        .list-group-item{height: 100px; border-left: none; border-right: none; display: flex; padding: 0; align-items: center;margin-bottom: 0; border-top: none; border-bottom: 0.06rem solid #ddd;}
        .list-group-itemtwo{height: 100px; border-left: none; border-right: none; display: flex; padding: 1rem; border-bottom: 0.06rem solid gainsboro;}
        .btn-blue{color: white; background: #5bc0de; border-radius: 25px;}
        .btn-blue2{line-height: 18px; font-size: 14px;}
        .btn-blue3{color: #00a0dc; background: white; border-radius: 25px; border: 1px solid #00a0dc; padding: 0 7px}
        .num{width: 40px; padding: 2px 0 0 0; text-align: center;}
        .calc{display: flex; position: absolute; right: 0;bottom: 10px;}
        .label1{padding: 10px; margin: 5px; line-height: 48px; font-size: 14px;}
        .modal-price{float: left; line-height: 34px; margin-left: 10px; font-size: 18px; color: red;}
        .calc2{width: 100px; float: right; position: absolute;bottom: 1.8rem;}
        .comment{display: flex; flex-direction: column; height: auto;}
        .comment-header{display: flex; justify-content: space-between; margin-bottom: 10px; line-height: 30px;}
        .comment-header img{width: 30px; height: 30px; border-radius: 50%; margin-right: 10px;}
        .shoptanxiangqing{position: fixed;left:0;right: 0;top: 0;bottom: 0;background: rgba(7,17,27,0.8); z-index: 111111; display: flex; justify-content: center; display: none;}
        .shopxiangtitle{font-size: 2rem; color: white;padding-top: 2rem;}
        .partner{padding: 1.5rem 0;}
        .tabpanel_list{margin-top:2rem;width: 100%; border-top: 0.06rem solid #ddd; background: white;}
        .activety{padding:1.5rem 1rem;display: flex;  border-bottom: 0.06rem solid #ddd;}
        .activety span{padding-left: 1rem;}
        .tabpanel_listone{width: 100%;padding-left: 5%;}
        .activety_jian{width: 2rem;height: 2rem;text-align: center;line-height: 2rem;background: red;font-size: 1rem; color: white;}
        .activety_zhe{width: 2rem;height: 2rem;text-align: center;line-height: 2rem;background: violet;font-size: 1rem; color: white;}
        .activety_ding{width: 2rem;height: 2rem;text-align: center;line-height: 2rem;background: greenyellow;font-size: 1rem; color: white;}
        .group_list{margin-top: 1rem;}
        .tab-pane{background: white;}
        .list-group{margin: 0;}
    </style>
    <link rel="stylesheet" href="{{ url('css/cart.css?v=1') }}">
 	<div class="shoptanxiangqing">
 		<div class="shopxiangtitle">魔方旅行</div>
 	</div>
    <div class="container">
        <div class="row" style="position: fixed; z-index: 2;">
            <div class="col-xs-12" style="text-align: center;">
               <!-- <div class="head">
                  
                    <img src="{{ $cdn.$partner->image }}" class="head-bg">
                    <img src="{{ $cdn.$partner->image }}" class="head-img" >
                    <div class="shoptext">
                    	<div class="title">{{ $partner->title }}</div>
                    </div>
                </div>-->
                <div class="nav1">
                    <!-- 上侧导航 -->
                    <ul class="nav nav-pills nav4" style="margin-bottom: -1px;">
                        <li class="my-tab active"><a href="#good" data-toggle="tab" style="height: auto;">@lang('foods.food_index')</a></li>
                        <li class="my-tab"><a href="#comment" data-toggle="tab" style="height: auto;">@lang('foods.food_comment')</a></li>
                        <li class="my-tab"><a href="#partner" data-toggle="tab" style="height: auto;">@lang('foods.food_partner')</a></li>
                    </ul>
                </div>
            </div>
        </div>
        <div class="tab-content" style="position: relative; padding-top: 40px; background: ghostwhite;">
            <div class="tab-pane active" id="good">
                <div class="row">
                    <div class="col-xs-3">
                        <div style="">
                            <!-- 左侧导航 -->
                            <ul id="mytab" class="nav nav-pills nav-stacked nav2">
                                @foreach($categorys as $i=>$category)
                                    <li class="{{ $i==0?'active':'' }}"><a href="#cate_{{ $category->id }}" data-toggle="tab">{{ $category['name'.$suffix] }}</a></li>
                                @endforeach
                            </ul>
                        </div>
                    </div>
                    <div class="col-xs-9">
                        <!-- 右侧菜品 -->
                        <div class="tab-content">
                            @foreach($categorys as $i=>$category)
                                <div class="tab-pane {{ $i==0?'active':'' }}" id="cate_{{ $category->id }}">
                                    <ul class="list-group" style="margin-bottom: 40px;">
                                        @foreach($category->foods as $j=>$food)
                                            <li class="list-group-item">
                                                <img src="{{ $cdn.$food->thumb }}" alt="{{ $food->id }}" style="width: 60px; height: 60px;">
                                                <div style="padding: 0 10px;">
                                                    <div style="width: 14rem; overflow: hidden;text-overflow: ellipsis;white-space: nowrap;">{{ $food['title'.$suffix] }}</div>
                                                    <div>{{ $cate->money }}{{ $food->price }}</div>
                                                </div>
                                                <div style="position: absolute;right: 1rem;bottom: 1rem;">
                                                    @if(!empty($food->pack))
                                                        <button class="btn btn-xs btn-blue pack" data-toggle="modal" data-target=".bs-example-modal-sm" style="padding: 0.2rem 1rem; ">@lang('foods.biz_order_title1')</button>
                                                        <div data-id="{{ $food->id }}" style="display: none;">
                                                            @foreach($food->packages as $k=>$pack)
                                                                <input type="hidden" data-id="{{ $food->id.'_'.$pack->id }}" data-name="{{ $pack['name'.$suffix] }}" data-price="{{ $pack->price }}">
                                                            @endforeach
                                                        </div>
                                                    @else
                                                    <div class="calc calc_{{ $food->id }}" data-id="{{ $food->id }}" data-title="{{ $food['title'.$suffix] }}" data-price="{{ $food->price }}">
                                                        <span class="glyphicon glyphicon-minus-sign icon-color2" style="opacity: 0;"></span>
                                                        <div class="num">0</div>
                                                        <span class="glyphicon glyphicon-plus-sign icon-color2"></span>
                                                    </div>
                                                    @endif
                                                </div>
                                            </li>
                                        @endforeach
                                    </ul>
                                </div>
                            @endforeach
                        </div>
                        {{--模态框--}}
                        <div class="modal fade bs-example-modal-sm" tabindex="-1" role="dialog" aria-labelledby="mySmallModalLabel">
                            <div class="modal-dialog modal-sm" role="document">
                                <div class="modal-content">
                                    <div class="modal-header">
                                        <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
                                        <h4 class="modal-title">菜品名称</h4>
                                    </div>
                                    <div class="modal-body">
                                        <p>One fine body&hellip;</p>
                                        <span class="label label-default">Default</span>
                                        <span class="label label-primary">Primary</span>
                                        <span class="label label-success">Success</span>
                                        <span class="label label-info">Info</span>
                                        <span class="label label-warning">Warning</span>
                                        <span class="label label-danger">Danger</span>
                                        <span class="label label-default">Default</span>
                                        <span class="label label-primary">Primary</span>
                                        <span class="label label-success">Success</span>
                                        <span class="label label-info">Info</span>
                                        <span class="label label-warning">Warning</span>
                                        <span class="label label-danger">Danger</span>
                                    </div>
                                    <div class="modal-footer">
                                        <div class="modal-price"></div>
                                        <button id="toCart" type="button" class="btn btn-primary" style="display: none;" disabled>加入购物车</button>
                                        <div class="calc calc2" data-id="" data-title="" data-price="" style="display: none;">
                                            <span class="glyphicon glyphicon-minus-sign icon-color2" style="opacity: 0;"></span>
                                            <div class="num">0</div>
                                            <span class="glyphicon glyphicon-plus-sign icon-color2"></span>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="row shopcart">
                    <div class="col-xs-12">
                        <form action="{{ route('foods.confirm', ['param'=>$param]) }}" method="post" enctype="application/x-www-form-urlencoded">
                            <div class="content">
                                <div class="content-left">
                                    <div class="cart-wrapper">
                                        <div class="cart">
                                            <span class="glyphicon glyphicon-shopping-cart icon-color1"></span>
                                        </div>
                                        <div class="num1" style="display: none;">0</div>
                                    </div>
                                    <div class="price">￥<span>0</span></div>
                                </div>

                                <div class="content-right">
                                    <input id="submit" type="submit" class="pay not-enough" value="@lang('foods.food_confirm')" disabled>
                                </div>

                                <div class="shopcart-List" style="display: none;">
                                    <div class="list-header">
                                        <span class="title1">购物车</span>
                                        <span class="empty">清空</span>
                                        <input type="hidden" name="params[partner_id]" value="{{ $partner->id }}">
                                        <input type="hidden" name="params[team_id]" value="{{ $teamId }}">
                                        <input type="hidden" name="params[partner_title]" value="{{ $partner->title }}">
                                        <input type="hidden" name="params[desk_sn]" value="{{ $deskSn }}">
                                        {{ csrf_field() }}
                                    </div>
                                    <div class="list-content">
                                        <ul id="list" class="list-group">
                                            {{--<li class="list-group-item cart-list">
                                                <div class="name">牛肉馅饼</div>
                                                <div class="price">￥<span>14</span></div>
                                                <div class="controller">
                                                    <span class="glyphicon glyphicon-minus-sign icon-color2" style="display: none;"></span>
                                                    <div class="num">1</div>
                                                    <span class="glyphicon glyphicon-plus-sign icon-color2"></span>
                                                </div>
                                            </li>--}}
                                        </ul>
                                    </div>
                                </div>
                            </div>
                        </form>
                        <div class="list-mask" style="display: none;"></div>
                    </div>
                </div>
            </div>

            {{-- 评论 --}}
            <div role="tabpanel" class="tab-pane" id="comment">
                <ul class="list-group group_list">
                    @foreach($comments as $comment)
                        <li class="list-group-itemtwo comment">
                            <div class="comment-header">
                                <div>
                                    <img src="{{ $comment->headimgurl }}">
                                    {{ urldecode($comment->nickname) }}
                                </div>
                                <div>{{ date('Y-m-d H:i:s', $comment->createtime) }}</div>
                            </div>
                            <div style="padding-left: 4rem;padding-bottom: 1rem;"  @if($comment->thumb)style=" margin-bottom: 10px; padding-left: 4rem"@endif>{{ $comment->content }}</div>
                            @if($comment->thumb)
                                <div><img src="{{ url($comment->thumb)?url($comment->thumb):$cdn.$comment->thumb }}" style="width: 60px; height: 60px; margin-left: 4rem;"></div>
                            @endif
                        </li>
                    @endforeach
                </ul>
            </div>

            <style>
                .partner{height: auto;}
            </style>

            {{-- 商家信息 --}}
            <div role="tabpanel" class="tab-pane" id="partner">
                <ul class="list-group">
                    @if($partner->title)<li class="list-group-item partner tabpanel_listone" style='border-top: 0.06rem solid #ddd; margin-top: 2rem;'>{{ $partner->title }}</li>@endif
                    @if($partner->address)<li class="list-group-item partner tabpanel_listone">{{ $partner->address }}</li>@endif
                    @if($partner->mobile)<li class="list-group-item partner tabpanel_listone">{{ $partner->mobile }}</li>@endif
                    @if($partner->hours)<li class="list-group-item partner tabpanel_listone">{{ $partner->hours }}</li>@endif
                    @if($partner->wifi)<li class="list-group-item partner tabpanel_listone">WIFI: {{ explode('@@@@', $partner->wifi)[0] }} | {{ explode('@@@@', $partner->wifi)[1] }}</li>@endif
                </ul>
              <!--  <div style="width: 100%;height: 1rem;background: ghostwhite;"></div>-->
               <div class="tabpanel_list">
               		<div class="activety">
	               		<div class="activety_jian">减</div>
		               	<span>满500减14，满1000减100(在线支付专享)</span>
	               </div>
	               <div class="activety">
	               		<div class="activety_zhe">折</div>
		               	<span>折扣商品5折起(在线支付专享)</span>
	               </div>
	               <div class="activety" >
	               		<div class="activety_ding">订</div>
		               	<span>下单多减1元</span>
	               </div>
               </div>
            </div>
        </div>


    </div>
@endsection


@push('scripts')
    <script src="//cdn.bootcss.com/jquery/1.11.3/jquery.min.js"></script>
    <script src="//cdn.bootcss.com/bootstrap/3.3.5/js/bootstrap.min.js"></script>
    <script>
        $("#mytab a").click(function (e) {
            e.preventDefault();
            $(this).tab("show");
        });

        // 弹出初始化
        var cartList = $(".shopcart-List");
        var mask = $(".list-mask");
        var high = $('#list').children().length;

        // 隐藏
        function cartHide() {
            cartList.animate({top: high*400+'px'}).hide("fast");
            mask.hide();
        }

        // 弹出购物车图层
        $(".cart").click(function (e) {
            //console.log(high);
            if(cartList.is(':hidden') && $('#list').children().length>0){
                mask.show();
                cartList.show("fast");
                cartList.animate({top: '0px'});
            } else {
                cartHide();
            }
        });

        // 点击mask 隐藏
        $(".list-mask").on("click", cartHide);

        // 点击规格套餐
        $(".pack").on("click", function (e) {
            $(".modal-body").empty();
            $(".calc2").hide();
            var id = $(this).next().attr("data-id");
            var packages = $(this).next().children();
            //$(".modal-body").append('<input type="hidden" name="fid" value="'+id+'">');
            packages.each(function (i, e) {
                console.log(i, $(this).attr("data-name"), $(this).attr("data-price"));
                /*if (i == 0) {
                    $(".modal-price").html($(this).attr("data-price"));
                }*/ //' + (i==0?'label-primary':'label-info') + '
                $label = '<span class="label label-info label1" data-id="' + $(this).attr("data-id") + '" data-price="' + $(this).attr("data-price") + '">' + $(this).attr("data-name") + '</span>\n';
                $(".modal-body").append($label);
            })
        });

        // 规格标签点击 label1
        $(".modal-body").on("click", ".label1", function (e) {
            var id = $(this).attr("data-id");
            var price = parseFloat($(this).attr("data-price"));
            var title = $(this).text();
            $(".calc2").attr("data-id", id);
            $(".calc2").attr("data-price", price);
            $(".calc2").attr('data-title', title);
            $(".calc2").removeClass().addClass("calc calc2 calc_"+id);
            //$("#toCart").removeAttr("disabled");

            $(this).removeClass("label-info").addClass("label-primary").siblings().removeClass("label-primary").addClass("label-info");
            $(".modal-price").html($(this).attr("data-price"));

            $(".calc2").show();
            var num = !$("#food_"+id).find(".num").html()?0:$("#food_"+id).find(".num").html();
            console.log('zynum:', num);
            $(".calc2").find(".num").html(num);

            /*if ($("#list").find("#food_"+id).attr("id") == 'food_'+id) {
                $("#toCart").hide().next().show();
            } else {
                $("#toCart").show().next().hide();
            }*/
        });

        // 加入购物车
        /*$("#toCart").on("click", function (e) {
            // 有错误 用单选check判断不对,不适合第二次选择套餐 想想其他方法
            //var price = parseFloat($('input[name="pack"]:checked').val());
            //var name = $('input[name="pack"]:checked')[0].nextSibling.nodeValue;
            //var id = $('input[name="fid"]').val();
            var id = $(".label-primary").attr("data-id");
            var name = $(".label-primary").text();
            var price = parseFloat($(".label-primary").attr("data-price"));
            console.log(id, name, price);
            //$('.bs-example-modal-sm').modal('hide');

            $(this).hide().next().show();
            cartAdd(id, name, price, 1);

            var priceTotal = parseFloat($(".content-left .price").find("span").html());
            priceTotal += price
            $(".content-left .price").find("span").html(priceTotal.toFixed(2))
        });
        $("#toCart").on("touchstart", {'op':"plus"}, balance);*/







        /**
         * zhaoyao cart
         * @param $this
         * @param $op
         */
        function cartAdd($id, $title, $price, $n) {
            //console.log("cartAdd: " +  $(".cart").find("#food_" + $id).attr("#food_" + $id));
            if ($("#list").find("#food_"+$id).attr("id") == 'food_'+$id) {
                console.log("finded",'food_'+$id, $("#list").find("#food_"+$id).attr("id"));
            } else {
                html =
                    '<li class="list-group-item cart-list" id="food_' + $id + '">\n' +
                        '<div class="name">' + $title + '</div>\n' +
                        '<div class="price">￥<span>' + $price + '</span></div>\n' +
                        '<div class="calc controller calc_' + $id + '" data-id="' + $id + '" data-price="' + $price + '">\n' +
                            '<span class="glyphicon glyphicon-minus-sign icon-color2"></span>\n' +
                            '<div class="num">' + $n + '</div>\n' +
                            '<span class="glyphicon glyphicon-plus-sign icon-color2"></span>\n' +
                            '<input type="hidden" class="num2" name="goods[' + ($id) + '][num]" value="' + $n + '">' +
                            '<input type="hidden" name="goods[' + ($id) + '][id]" value="' + $id + '">' +
                        '</div>\n' +
                    '</li>';
                /*html = '';
                    '<div class="caidan" id="food_' + $id + '">\n' +
                    '<div class="caidanleft">\n' +
                    '<div class="shangpinming title">' + $title + '</div>\n' +
                    '<input type="hidden" name="goods[' + ($id-1) + '][id]" value="' + $id + '">' +
                    '</div>\n' +
                    '<div class="shangpinjiage price">{$partner["money"]}<span>' + $price + '</span></div>\n' +
                    '<div class="caidanright calc calc_' + $id + '" data-id="' + $id + '">\n' +
                    '<div class="jianhao"><img class="quxiao" src="/static/food/img/227060959731570053.png"/></div>\n' +
                    '<div class="shuliang num">' + $n + '</div>\n' +
                    '<input type="hidden" class="num1" name="goods[' + ($id-1) + '][num]" value="' + $n + '">' +
                    '<div class="jiahao"><img src="/static/food/img/705511411329123112.png"/></div>\n' +
                    '</div>\n' +
                    '</div>';*/
                $("#list").append(html);
            }
        }

        // 有用  加减
        function balance(e) { // $this, $op
            // 初始化
            var op = e.data.op;
            var id = $(this).parent().attr("data-id");

            var title = $(this).parent().attr("data-title");
            var n = parseInt($(this).parent().children(".num").html()); //
            var nTotal = parseInt($(".num1").html());
            var price = parseFloat($(this).parent().attr("data-price"));
            var priceTotal = parseFloat($(".content-left .price").find("span").html());

            // 处理+ - 法
            if (op == "plus") {
                $("#submit").removeAttr("disabled");
                ++ n;
                ++ nTotal;
                priceTotal += price;
                cartAdd(id, title, price, n);

            } else { // -
                if (n > 0) {
                    --n;
                    --nTotal;
                    priceTotal -= price;
                    //n == 0 ? $("#food_"+id).remove() :'';
                }
            }

            // 图标状态
            if (nTotal > 0) {
                $(".num1").show();
                $(".glyphicon-shopping-cart").addClass("icon-color2")
                $("#submit").addClass("enough");//.val("去结算");
            } else {
                $(".num1").hide();
                $(".glyphicon-shopping-cart").removeClass("icon-color2")
                $("#submit").removeClass("enough").attr("disabled", true);//.val("请选餐");
                $(".shopcart-List").animate({top: '500px'}).hide("fast");
            }

            // 控制减号图标显示和隐藏
            if (n > 0) {
                $(".calc_"+id).find(".glyphicon-minus-sign").css("opacity", 1);//show();
            } else {
                $(".calc_"+id).find(".glyphicon-minus-sign").css("opacity", 0)//hide();
                $("#food_"+id).remove();
            }

            // 回填数据
            console.log('id: ' + id);
            console.log('n: ' + n);
            console.log('nTotal: ' + nTotal);
            console.log('price: ' + price);
            console.log('priceTotal: ' + priceTotal);
            console.log('-------------------------');

            $(".calc_"+id).find(".num").html(n); //$(this).parent().find(".num").html(n);
            $(".calc_"+id).find("input[class=num2]").val(n); //$(this).parent().find(".num").html(n);
            $(".num1").html(nTotal);
            $(".content-left .price").find("span").html(priceTotal.toFixed(2))
        }

        // + - 操作
        $(".glyphicon-plus-sign").on("touchstart", {'op':"plus"}, balance);
        $(".glyphicon-minus-sign").on("touchstart", {'op':"minus"}, balance);
        $("#list").on("touchstart", ".glyphicon-plus-sign", {'op':"plus"}, balance);
        $("#list").on("touchstart", ".glyphicon-minus-sign", {'op':"minus"}, balance);

        // 清空购物车
        $(".empty").on("touchstart", function (e) {
            $(".calc").find(".num").html(0);
            $(".num1").html(0);
            $(".content-left .price").find("span").html("0.00");
            $("#list").empty();
            $("#submit").attr("disabled", true);
            $(".shopcart-List").animate({top: '500px'}).hide("fast");
            $(".list-mask").hide();
            $(".glyphicon-minus-sign").css("opacity", 0);//.hide("slow");
        });

        //--------------------------------
        
        
 
    </script>
@endpush