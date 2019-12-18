@extends('open.layouts.template1')
@section('nav')
    <header class="smell-header">
      <div class="header-nav">
        <h1 class="logo" onclick="location.href='{{route('index')}}'">气味王国-开放平台</h1>
        <nav class="user">
          <ul>
            <li class="name" id="name">
              <span>{{\Auth::getUser()->account}}</span>
              <span class="pass-btn" style="display: none;">
                  <div class="san"></div>
                  <a class="updatePass top-radius" id="updatePass" href="{{route('password')}}">修改密码</a>
                  <div class="hr-p"><div class="hr-c"></div></div>
                  <a href="{{route('secrect')}}" class="updatePass">密钥管理</a>
                  <div class="hr-p"><div class="hr-c"></div></div>
                  <a href="{{route('wiki')}}" class="active1 bottom-radius">API文档</a>
              </span>
            </li>
            <li><a href="{{route('api_logout')}}">[&nbsp;退出&nbsp;]</a></li>
            <li class="line">|</li>
            <li><a href="{{route('index')}}">首页</a></li>
          </ul>
        </nav>
      </div>
   </header>
@stop

@section('js')
    <script src="{{asset('js/jquery-1.9.1.min.js') }}"></script>
    <script>
      $('#name').on('mouseenter',function(){
        $(this).find('.pass-btn').css('display', '');
      });
      $('#name').on('mouseleave',function(){
        $(this).find('.pass-btn').css('display', 'none');
      });
    </script>
@stop
    