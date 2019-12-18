<!DOCTYPE html>
<html>
	<head>
		<meta charset="UTF-8">
		<title></title>
        <style>
        *{box-sizing: border-box;font-family: "微软雅黑";}
        a{text-decoration: underline;color: #3399CC;}
        body,html,h1,h3,p{margin: 0px;padding: 0px;color: #333333;}
        .mail{background-color: #f4f4f4;height:100%;width:100%;min-width:900px;padding-bottom:100px;}
        .header{width: 100%;height: 100px;margin-bottom: 40px;padding-top: 20px;}
        .header .logo{display: block;height: 100px;width: 235px;margin: 0 auto;}
        .section{position: relative;box-shadow: 1px 2px 20px rgba(0,0,0,0.3);height: 550px;width: 820px;background-color: #FFFFFF;margin: 0 auto;padding: 65px 100px 0px 100px;}
        .section .title-h1{margin-bottom: 50px;}
        .section .title-h3{margin-bottom: 25px;}
        .section .a{border-radius: 4px;background-color: #16aab5;width: 180px;height: 55px;display: block;text-align: center;line-height: 55px;text-decoration: none;color: #FFFFFF;margin-bottom: 30px;}
        .btn-qwwg:hover{outline: none;cursor:pointer;}
        .btn-qwwg:focus{outline: none;cursor:pointer;}
        .btn-qwwg:active{-webkit-box-shadow: inset 0px 0px 5px rgba(0, 0, 0, .7);box-shadow: inset 0px 0px 5px rgba(0, 0, 0, .7);}
        .section .title-p{margin-bottom: 15px;}
        .section .title-a{display: block;height: 60px;word-wrap: break-word;overflow: hidden;}
        .section .title-admin{position: absolute;left: 100px;bottom: 50px;}
        .section .title-mail{position: absolute;left: 100px;bottom: 25px;color:#32939c;}
        </style>
    <!--[if lte IE 10]>    
    <script>
    document.createElement('header').style.display = 'block';
    document.createElement('section').style.display = 'block';
    document.createElement('footer').style.display = 'block';
    document.createElement('nav').style.display = 'block';
    </script>
    <![endif]-->  
	</head>
	<body>
	<div class="mail">
	  <div class="header">
	    <img class="logo" src="http://open.qiweiwangguo.com/images/header-logo.png">
	  </div>
	  <div class="section">
	   <h1 class="title-h1">尊敬的 {{$username}}，您好：</h1>
	    <h3 class="title-h3">感谢您使用气味王国开放平台。</h3>
	    <h3 class="title-h3">请点击以下按钮进行邮箱验证，以验证密码修改 ：</h3>
	    <a  class="btn-qwwg a" href="{{$link}}">马上验证</a>
	    <p class="title-p">如果您无法点击以上按钮，请复制以下链接到浏览器里直接打开（该链接在 24 小时内有效，24 小时后需要重新发送）：</p>
	    <a class="title-a" href="{{$link}}">{{$link}}</a>
	    <p class="title-admin">气味王国管理员</p>
	    <a class="title-mail" href="javascript:;">qiweiwangguo@scentrealm.com</a>
	  </div>
	  </div>
	</body>
</html>

