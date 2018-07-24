<!DOCTYPE html>
<html>
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width,initial-scale=1,minimum-scale=1,maximum-scale=1,user-scalable=no" />
    <title></title>
    <script src="js/mui.min.js"></script>
    <script src="js/jquery-3.1.1.min.js" type="text/javascript" charset="utf-8"></script>
    <link href="css/mui.min.css" rel="stylesheet"/>
    <link rel="stylesheet" type="text/css" href="css/reset.css"/>
    <style type="text/css">
    	#main{
    		position: fixed;
    		top: 0;
    		left: 0;
    		right: 0;
    		bottom: 0;
    		width: 100%;
    		height: 100%;
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
			<div class="chinese languagelist">
				中文
			</div>
			<div class="thai languagelist">
				泰文
			</div>
			<div class="english languagelist">
				English
			</div>
		</div>
		<div class="logo">
			<h1>海獭生活服务员</h1>
		</div>
		<div class="login">
		<form action="reg" method="POST">
		{{csrf_field()}}
		<div class="login">
			<input type="text" placeholder="餐厅账号" class="mui-input" name="account"/>
			<div class="mui-input-row mui-password">
				<input type="password" placeholder="请输入密码" class="mui-input-password" name="password">
			</div>
			<input class="btn" type="submit" value="注册" />
		</div>
		</form>
	<div> 
</body>
</html>
