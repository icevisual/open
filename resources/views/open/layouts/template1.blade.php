<!doctype html>
<html lang='ch-ZN'>
  <head>
    <meta charset='utf-8'>
    <title>气味王国-开放平台</title>
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="renderer" content="webkit">
    <meta name="viewport" content="width=device-width,initial-scale=1,maximum-scale=1,user-scalable=no">
    <meta name="Cache-Control" content="max-age=7200">
    <meta name="keywords" content="">
    <meta name="description" content="">
    <link rel="shortcut icon" href="{{asset('images/favicon.ico') }}"/>
@section('css')
    <link rel="stylesheet" href="{{asset('css/smell.css') }}"/>
    <link rel="stylesheet" href="{{asset('css/main.css') }}" />
@show
    <!--[if lte IE 8]>    
    <script>
    document.createElement('header').style.display = 'block';
    document.createElement('section').style.display = 'block';
    document.createElement('footer').style.display = 'block';
    document.createElement('nav').style.display = 'block';
    </script>
    <![endif]-->    
  </head>
  <body class="smell-doc-body">
@section('nav')
     <header class="smell-header">
       <div class="loginOrRegisterContainer">
         <div class="loginOrRegisterBlock">
           <a class="Welcome">欢迎进入气味王国开放平台！</a>
           <a class="a" href="http://www.qiweiwangguo.com/" target="_blank" style="color: #16aab5;">气味王国官网</a>
           <span class="span">|</span>
           @if(\Auth::guest())
               <a class="a" href="/register">[&nbsp;注册&nbsp;]</a>
               <span class="span">|</span>
               <a class="a" href="/developer">[&nbsp;登入&nbsp;]</a>
           @else
               <a class="a"href="{{route('api_logout')}}">[&nbsp;退出&nbsp;]</a>
               <span class="span">|</span>
               <span class="span">{{\Auth::getUser()->account}}</span>
           @endif
         </div>
       </div>
      <div class="header-nav">
        <h1 class="logo" onclick="location.href='{{route('index')}}'">气味王国-开放平台</h1>
        <nav class="nav">
          <ul>
            <li id="header-nav-index">
              <a href="{{route('index')}}">气味王国</a></li>
            <!--<li id="header-nav-device">
              <a href="{{route('device')}}">设备展示</a></li>-->
            <li id="header-nav-developer">
              <a href="{{route('developer')}}">开发中心</a></li>
          </ul>
        </nav>
      </div>
    </header>
@show
@yield('content')
@section('footer')
    @include('open.layouts.footer')
@show
    <script>
    var nav = document.getElementById('header-nav-{{\Route::getCurrentRoute()->getName()}}');
    if(nav)nav.className = 'active';
    var _CsrfToken = '{{csrf_token()}}';
    </script>
@section('js')
    <script src="{{asset('js/jquery-1.9.1.min.js') }}"></script>
    <script src="{{asset('js/main.js') }}"></script>
    <script src="{{asset('js/smell.js') }}"></script>
@show
  </body>
</html>
