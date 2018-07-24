<?php
/**
 * Created by PhpStorm.
 * User: CH
 * Date: 2018/5/25
 * Time: 21:54
 */

@section('content')
    <link rel="stylesheet" href="https://cdn.bootcss.com/weui/1.1.2/style/weui.min.css">
    <link rel="stylesheet" href="{{ url('css/getback.css?v=1') }}">
    <style type="text/css">
        .detail_line {display: flex; color: white; width: 90%; margin: 25px auto 24px auto;}
        .detail_line_list{display: flex; color: white; width: 90%; margin: 10px auto 10px auto;}
        .line {flex: 1; position: relative; top: 3px; background: red; height: 1.5rem;}
        .line_text {color: black; padding: 0 0.5rem; font-size: 1.6rem; font-weight: 700;}
        .lineone{flex: 1; position: relative; top: 9px; background: gainsboro; height: 0.06rem;}
        .line_text_one{font-size: 1rem; color: gray;}
        .miaosha_list {display: flex; width: 90%; margin-left: 5%; padding-bottom: 1rem; border-bottom: 0.1rem dashed red; margin-bottom: 1rem;}
        .miaosha_listleft {flex: 0 0 17rem;}
        .miaosha_listleft img {width: 100%;}
        .miaosha_listright {flex: 1; display: flex; flex-direction: column; align-items: center; justify-content: space-around;}
        .miaosha_name {font-size: 1.8rem; font-weight: 700;}
        .miaosha_deils {border-radius: 1rem; padding: 0.5rem 0.8rem; background: ghostwhite; border: 0.06rem solid gainsboro;}
        .miaosha_num {font-size: 1.3rem; font-weight: 700;}
        .miaosha_money {font-size: 1.5rem; font-weight: 700; color: red;}
        .miaosha_money span {font-size: 1.8rem;}
        .go_miaosha {padding: 0.6rem 2.4rem; background: red; border-radius: 8rem; color: white; font-size: 1.4rem;}
        * {margin: 0;padding: 0;list-style-type: none;}
        .pingfen{margin-top: 1rem;}
        #star {position: relative;margin: 20px auto;height: 24px;}
        #star ul{float: left;display: inline;height: 19px;line-height: 19px;}
        #star ul {margin: 0 10px;}
        #star li {margin-left: 1rem;float: left;width: 24px;cursor: pointer;text-indent: -9999px;background:url({{ url('images/star.png') }}) no-repeat;}
        #star strong {color: #f60;padding-left: 10px;}
        #star li.on {background-position: 0 -28px;}
        .star_list{width: 100%; display: flex; align-items: center;}
        .payshop_top{display: flex; flex-direction: column; align-items: center; justify-content: center; font-size: 1.6rem; margin-top: 1rem;}
        .shopname{padding: 0.5rem 0 ;}
        .payshop_top img{width: 5rem; height: 5rem; border-radius: 50%;}
        .shopprice{font-size: 2rem; font-weight: 700;}
        .payshop_bottom{width: 90%; margin-left: 5%; display: flex; justify-content: space-between; height: 1.5rem; line-height: 1.5rem;}
        .payleftprice{font-size: 1.5rem; font-weight: 700;}
    </style>
@endsection