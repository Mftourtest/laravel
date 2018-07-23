<!DOCTYPE html>
<html>
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width,initial-scale=1,minimum-scale=1,maximum-scale=1,user-scalable=no" />
    <title></title>
    <script src="{{ url('js/mui.min.js')}}"></script>
    <script src="{{ url('js/jquery-3.1.1.min.js')}}" type="text/javascript" charset="utf-8"></script>
    <link href="{{ url('css/mui.min.css')}}" rel="stylesheet"/>
    <link rel="stylesheet" type="text/css" href="{{ url('css/reset.css')}}"/>
    <style type="text/css">
    	#main{
    		position: fixed;
    		top: 0;
    		left: 0;
    		right: 0;
    		bottom: 0;
    		width: 100%;
    		height: 100%;
			overflow:auto; 
            margin: 0 auto;
    		background-color: white;
    		font-family: "微软雅黑";
    		font-size: 1rem
    	}
    	.language{
    		background: white;
    		display: flex;
    		justify-content: space-around;
    		padding: 10% 0;
    	}
    	.languagelist{
    		padding: 2% 0;
    		width: 25%;
    		text-align: center;
    		border-radius: 5px;
    	}
    	.chinese{
    		background-color: orange;
    		color: white;
    	}
    	.thai{
    		background: red;
    		color: white;
    	}
    	.english{
    		background: green;
    		color: white;
    	}
    	.logo{
    		padding: 5%;
    		display: flex;
    		flex-direction: column;
    		justify-content: center;
    		align-items: center;
    	}
    	.logo img{
    		width: 50%;
    	}
    	.logo h1{
    		font-size: 1.5rem;
    	}
    	.login{
    		display: flex;
    		flex-direction: column;
    		padding: 10% 5%;
    	}
    	.mui-password{
    		margin-top: 5%;
    	}
    	.btn{
    		height: 2.5rem;
    		margin-top: 10%;
    	}
    </style>
    <script type="text/javascript" charset="utf-8">
      	$(function(){
      		$('.btn').on('touchend',function(){
      			 window.location.href="table.html"
      		})
      	})
    </script>
</head>
<body>
	<div id="main">
		<div class="language">
			<div class="chinese languagelist" onclick="window.location.href='index?lang=zh_cn'">
				中文
			</div>
			<div class="thai languagelist" onclick="window.location.href='index?lang=vi'">
				ภาษาไทย
			</div>
			<div class="english languagelist" onclick="window.location.href='index?lang=en_us'">
				English
			</div>
		</div>
		<div class="logo">
			<img src="{{ url('img/logo.jpg') }}"/>
			<div style="height:10px"></div>
			<h1>@lang('foods.sea_otter_life_waiter')</h1>
		</div>
		<form action="login?lang={{$lang}}" method="POST">
		{{csrf_field()}}
		<div class="login">
			@if(!empty($cookies['account']))
			    <input type="text" placeholder="@lang('foods.please_enter_account')" class="mui-input" name="account" value="{{$cookies['account']}}"/>
			    <div class="mui-input-row mui-password">
				    <input type="password" placeholder="@lang('foods.biz_login_password')" class="mui-input-password" name="password" value="{{$cookies['password']}}">
			    </div>
			@else
			    <input type="text" placeholder="@lang('foods.please_enter_account')" class="mui-input" name="account"/>
			    <div class="mui-input-row mui-password">
				    <input type="password" placeholder="@lang('foods.biz_login_password')" class="mui-input-password" name="password">
				</div>
			@endif
			{{-- <input value="remember me" type="checkbox" name="remember">Remember Me</input>		 --}}
			@if(count($errors)>0)  
                            @if(is_object($errors))  
                                @foreach($errors->all() as $error)  
                                <p style="color:red">{{$error}}</p>  
                                @endforeach  
                            @else  
                            <p style="color:red">{{$errors}}</p>  
                        @endif  
                    @endif  
                    @if(session('msg'))  
                        <p style="color:red">{{session('msg')}}</p>  
                    @endif  
			<input class="btn" type="submit" value="@lang('foods.biz_login_btn')" />
		</div>
		</form>
	<div>
</body>
</html>
