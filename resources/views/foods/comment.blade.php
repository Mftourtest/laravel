<?php
/**
 * Created by PhpStorm.
 * User: zhaoyao
 * Date: 2018/4/20
 * Time: 21:53
 */
?>

@extends('layouts.foods')
@section('title', '我的评论')


@section('content')
    <link rel="stylesheet" href="{{ url('css/getback.css?v=1') }}">
    <link rel="stylesheet" href="https://cdn.bootcss.com/weui/1.1.2/style/weui.min.css">
    <link rel="stylesheet" href="http://cdn.static.runoob.com/libs/bootstrap/3.3.7/css/bootstrap.min.css">
    <style type="text/css">
    	* {margin: 0;padding: 0;list-style-type: none;}
		.pingfen{margin-top: 1rem;}
		#star {position: relative;margin: 20px auto;height: 24px;}		
		#star ul{float: left;display: inline;height: 19px;line-height: 19px;}		
		#star ul {margin: 0 10px;}		
		#star li {margin-left: 1rem;float: left;width: 24px;cursor: pointer;text-indent: -9999px;background:url({{ url('images/star.png') }}) no-repeat;}
		#star strong {color: #f60;padding-left: 10px;}	
		#star li.on {background-position: 0 -28px;}
        .weui_uploader_bd {overflow: initial;}
        .weui-cell{padding: 0 10px;}
        .weui_cells {margin-top: 0; border-bottom: 0.06rem solid rgba(7, 17, 27, 0.1);}
        .weui-uploader__bd{display: flex;}
        .comment-div {margin-top: 2rem; width: 95%; margin-left: 2.5%;}
        .gocomment {margin-top: 2rem; width: 95%; margin-left: 2.5%; height: 4rem;
            background-color: #1aad19; border: 0; border-radius: 5px; color: white;}
        .weui-cells:after, .weui-cells:before{position: relative;}
    </style>

    @if(!$isWechat)
        <header class="header">
            <a href="order.html"><span class="glyphicon glyphicon-menu-left tubiao"></span></a>
            <div class="getcoupon-title">我的评论</div>
        </header>
    @endif

    <div class="container">
        <form action="{{ route('foods.comment', ['param'=>$param]) }}" method="post" enctype="multipart/form-data">
        	<div class="pingfen">
				<div class="pingfentitle" style="padding-left: 1rem;">评分</div>
				<div id="star">
					<ul>
                        <li>1</li>
                        <li>2</li>
                        <li>3</li>
                        <li>4</li>
                        <li>5</li>
					</ul>
				</div>
			</div>
            <div class="weui-gallery" id="gallery">
                <span class="weui-gallery__img" id="galleryImg"></span>
                <div class="weui-gallery__opr">
                    <a href="javascript:" class="weui-gallery__del">
                        <i class="weui-icon-delete weui-icon_gallery-delete"></i>
                    </a>
                </div>
            </div>

            <div class="weui-cells weui-cells_form">
                <div class="weui-cell">
                    <div class="weui-cell__bd">
                        <div class="weui-uploader">
                            <div class="weui-uploader__hd">
                                <p class="weui-uploader__title">图片上传</p>
                            </div>
                            <div class="weui-uploader__bd">
                                <ul class="weui-uploader__files" id="uploaderFiles">

                                </ul>
                                <div class="weui-uploader__input-box">
                                    <input type="file" name="thumb" id="uploaderInput" class="weui-uploader__input zjxfjs_file" accept="image/*" multiple="">
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <div class="form-group comment-div">
                <textarea name="content" class="form-control" placeholder="请输入评论内容" rows="5"></textarea>
            </div>

            <input type="hidden" name="order_id" value="{{ $orderId }}">
            <input type="hidden" id="level_1" name="level_1" value="{{ $level_1 }}">
            {{ csrf_field() }}
            <button type="submit" class="gocomment">上传</button>
        </form>

    </div>
@endsection


@push('scripts')
    <script src="//cdn.bootcss.com/jquery/1.11.3/jquery.min.js"></script>
    <script src="//cdn.bootcss.com/bootstrap/3.3.5/js/bootstrap.min.js"></script>
    <script src="https://cdn.bootcss.com/jquery-weui/1.2.0/js/jquery-weui.min.js"></script>

    <script type="text/javascript">
        var len = $("#star li").length;
        console.log(len);
        var n = '{{ $level_1 }}';
        
        $(function () {
            n && coverStar(n);
        });
        
        $("#star li").on("touchend", function (e) {
            console.log($(this).html());
            n = parseInt($(this).html());
            $('#level_1').val(n);
            coverStar(n);
        });
        
        function coverStar(n) {
            $("#star li").removeClass("on");
            for (var i=0; i<n; i++) {
                $("#star li:eq(" + i + ")").addClass("on");
            }
        }
    </script>

    <script type="text/javascript">
        $(function() {
            var tmpl = '<li class="weui-uploader__file" style="background-image:url(#url#)"></li>',
                $gallery = $("#gallery"),
                $galleryImg = $("#galleryImg"),
                $uploaderInput = $("#uploaderInput"),
                $uploaderFiles = $("#uploaderFiles");

            $uploaderInput.on("change", function(e) {
                var src, url = window.URL || window.webkitURL || window.mozURL,
                    files = e.target.files;
                for(var i = 0, len = files.length; i < len; ++i) {
                    var file = files[i];

                    if(url) {
                        src = url.createObjectURL(file);
                    } else {
                        src = e.target.result;
                    }

                    $uploaderFiles.append($(tmpl.replace('#url#', src)));
                }
            });
            var index; //第几张图片
            $uploaderFiles.on("click", "li", function() {
                index = $(this).index();
                $galleryImg.attr("style", this.getAttribute("style"));
                $gallery.fadeIn(100);
            });
            $gallery.on("click", function() {
                $gallery.fadeOut(100);
            });
            //删除图片
            $(".weui-gallery__del").click(function() {
                $uploaderFiles.find("li").eq(index).remove();
            })
        });
        /*window.onload = function() {

			var oStar = document.getElementById("star");
			var aLi = oStar.getElementsByTagName("li");
			var oUl = oStar.getElementsByTagName("ul")[0];
			var oSpan = oStar.getElementsByTagName("span")[1];
			var oP = oStar.getElementsByTagName("p")[0];
			var i = iScore = iStar = 0;


			for(i = 1; i <= aLi.length; i++) {
				aLi[i - 1].index = i;


				aLi[i - 1].onmouseover = function() {
					fnPoint(this.index);
					$('#level_1').val(this.index)
					console.log($('#level_1').val())
				};

	
				aLi[i - 1].onmouseout = function() {
					fnPoint();

				};


			}
			//评分处理
			function fnPoint(iArg) {
				//分数赋值
				iScore = iArg || iStar;
				for(i = 0; i < aLi.length; i++) aLi[i].className = i < iScore ? "on" : "";
			}

		}; */
    </script>
    
@endpush