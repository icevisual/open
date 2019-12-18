@extends('open.layouts.template2')
@section('css')
    <link rel="stylesheet" href="{{asset('css/main.css') }}" />
    <style>
    .passworld-alter .alter-email{position: relative;text-align: left;padding-top: 30px;margin: 0 auto;width: 500px;}
    .passworld-alter .alter-email img{left: -85px;position: absolute;width: 56px;height: 56px;}
    .passworld-alter .alter-email h2{line-height: 1.5;font-size: 24px;color: #333333;margin-bottom: 20px;font-weight: 300;}
    .passworld-alter .alter-email p{font-size: 14px;color: #666666;margin-bottom: 10px;}
    .passworld-alter .alter-email button{width: 160px;height: 50px;background-color: #3cd3c3;border-radius: 5px;border: 0px;font-size: 18px;color: #FFFFFF;margin-bottom: 25px;}
    </style>
@stop
@section('js')
    <script type="text/javascript" src="{{asset('/js/jquery-1.9.1.min.js') }}" charset="utf-8"></script>
    <script type="text/javascript" src="{{asset('js/funcs.js') }}"></script>
    <script>
    var resendEmail = function(){
    	_ajax( {
            url:"{{route('api_sendRegisterEmail')}}",
            data:{},
            success: function( response ) {
                if(_judgeResult(response)){
                    _showMsg('发送成功');
                }else{
                	_showErrorMsg(response);
                }
            },
            error: function( response ){
            	_showErrorMsg(response);
            }
        });
    }
    var _emialHomeAddrDetect = function(email){
        var _hasEmailHome = function(home){
            $('#btn-login-email').click(function(){
                window.open(home);
            });
        };
        var _noEmailHome = function(){

        };
    	_ajax( {
            url:"{{route('api_emailHomeAddr')}}",
            data:{
                'email' : email
            },
            success: function( response ) {
                if(_judgeResult(response) && response.data.home){
                	_hasEmailHome(response.data.home);
                }else{
                	_noEmailHome();
                }
            },
            error: function( response ){
            	_noEmailHome();
            }
        });
    }
    _emialHomeAddrDetect('{{$registeringEmail}}');
    </script>
@stop
  
@section('content')
    <section class="smell-doc">
      <h2 class="title">
        <img src="/img/register2.png" />
      </h2>
      <div class="passworld-alter">
        <div id="alter-email" class="alter-email" style="display: block">
          <img src="/img/email.png" />
          <h2>感谢注册，确认邮件已发送至您的注册邮箱：
            <br />
            <span>{{$registeringEmail}}</span></h2>
          <p>请进入邮箱看邮件，并激活气味开放平台帐号。</p>
          <button class="btn" id="btn-login-email">登入邮箱</button>
          <div style="border: 1px dashed #d2d2d2;height: 145px; width: 435px;padding: 30px;">
            <p>没有收到邮件？</p>
            <p>1、请检查邮箱地址是否正确，您可以返回
              <a href="{{route('register',['step' => 'step1' ])}}" style="color:#f66000">重新填写
                <a></p>
            <p>2、检查你的垃圾邮件</p>
            <p>3、若仍未收到确认邮件，请尝试
              <a href="javascript:resendEmail();" style="color:#f66000">重新发送
                <a></p>
          </div>
        </div>
       </div>
    </section>
@stop
