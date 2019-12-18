@extends('open.layouts.template2')
@section('css')
    <link rel="stylesheet" href="{{asset('css/main.css') }}" />
@stop

@section('js')
    <script>
    var goWiki = function(){
        window.location.href = "{{route('wiki')}}";
    }
    setTimeout(goWiki,5000);
    </script>
@stop
  
@section('content')
    <section class="smell-doc">
      <h2 class="title">
        <img src="/img/register3.png"/>
      </h2>
      <div class="passworld-alter">
        <div id="alter-success" class="alter-success" style="display: block">
          <div class="bg-img">
            <img src="/img/cg.png" /></div>
          <h2>恭喜您注册成功！</h2>
          <p style="margin-bottom: 10px;">您的气味开放平台的登录账号为您的邮箱：
            <span style="color:#f66000;">{{$registeringEmail}}</span></p>
          <p style="margin-bottom: 0px;">现在您可以进入：
            <span onclick="goWiki();" style="color:#f66000;text-decoration: underline;">API文档</p>
          <button onclick="goWiki();" class="btn">API接口文档</button>
          <p>
            <span id="pass-g-time">5s</span>之后，自动跳往API接口文档页面</p></div>
      </div>
    </section>
@stop
