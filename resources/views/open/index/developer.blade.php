@extends('open.layouts.template1')
  
  
@section('css')
    <link rel="stylesheet" href="{{asset('css/main.css') }}" />
    <link rel="stylesheet" href="{{asset('css/drag.css') }}" />
    <style>
    .btn-api-doc {
        position: absolute;
        top: 220px;
        left: 213px;
        width: 200px;
        height: 45px;
        border-radius: 20px;
        background-color: #16aab5;
        border: 0px;
        color: #FFFFFF;
        font-size: 22px;
        font-weight: 600;
    }
    </style>
@stop

@section('content') 
    <div class="smell-header">
      <div class="header-background-img">
        <div class="login">
          <div class="from">
            @if($isLogin)
            <button class="btn btn-api-doc " onclick="window.location.href='{{route('wiki')}}'">API 接口文档</button>
            @else
            <button href="javascript:;" id="btn-login" class="btn btn-login">登录</button>
            <button href="javascript:;" id="btn-register" class="btn btn-register register">注册</button></div>
            @endif
        </div>
      </div>
    </div>
    <div class="content-develop">
      <div class="content-text">
        <div class="guid-layout">
          <div class="guid">
            <div class="top">
              <img src="../img/safety.png" width="60" height="75" alt="">
              <span>安全</span></div>
            <p>API 全程 HTTPS 加密，并通过数据签名机制保证通信的完整和安全性</p>
          </div>
          <div class="guid">
            <div class="top">
              <img src="../img/convenient.png" width="60" height="75" alt="">
              <span>便捷</span></div>
            <p>易用的 SDK 和详尽的文档，短短数行代码实现与平台的对接，大大缩短开发周期</p>
          </div>
          <div class="guid">
            <div class="top">
              <img src="../img/robust.png" width="60" height="75" alt="">
              <span>健壮</span></div>
            <p>自动化日志记录和数据备份，精准定位各项指标，保障数据安全</p>
          </div>
        </div>
      </div>
    </div>
    <div class="content-app-display">
      <div class="content-title">
        <h2>应用展示</h2>
        <img src="../img/line.png" height="3" width="80" alt="">
        <p class="subhead">APPLICATION DISPLAY</p></div>
      <div class="content-text">
        <ul class="app-display">
          <li>
            <img src="../img/app-display1.jpg" width="290" height="475" alt="">
            <p class="mask">游戏</p></li>
          <li>
            <img src="../img/app-display2.jpg" width="290" height="475" alt="">
            <p class="mask">电影</p></li>
          <li>
            <img src="../img/app-display3.jpg" width="290" height="475" alt="">
            <p class="mask">音乐</p></li>
          <li>
            <img src="../img/app-display4.jpg" width="290" height="475" alt="">
            <p class="mask">社交</p></li>
        </ul>
        <div class="backtop">
          <div class="btnTo"></div>
        </div>
      </div>
    </div>
@stop
@section('js')
    <script type="text/javascript" src="{{asset('js/jquery-1.9.1.min.js') }}"></script>
    <script type="text/javascript" src="{{asset('js/main.js') }}"></script>
    <script type="text/javascript" src="{{asset('js/drag.js') }}"></script>
    <script type="text/javascript" src="{{asset('js/scroll.js') }}"></script>
    <script type="text/javascript" src="{{asset('js/funcs.js') }}"></script>
    <script type="text/javascript">

    var _rememberMe = {
    	key:'remember_account',
    	set:function(account){
    		this._setCookie(this.key, account,30 );
        },
        get:function(){
            return this._getCookie(this.key);
        },
        clear:function(){
            return this._clearCookie(this.key);
        },
        run:function(){
            var _rAccount = this.get();
            if(_rAccount){
            	$('input[name=username]').val(_rAccount);
                $('input[name=remember]').attr('checked','checked');
            }
        },
    	_setCookie : function(cname, cvalue, exdays) {
            var d = new Date();
            d.setTime(d.getTime() + (exdays*24*60*60*1000));
            var expires = "expires="+d.toUTCString();
            document.cookie = cname + "=" + cvalue + "; " + expires;
        },
        _getCookie : function(cname) {
            var name = cname + "=";
            var ca = document.cookie.split(';');
            for(var i=0; i<ca.length; i++) {
                var c = ca[i];
                while (c.charAt(0)==' ') c = c.substring(1);
                if (c.indexOf(name) != -1) return c.substring(name.length, c.length);
            }
            return "";
        },
        _clearCookie : function (name) {  
            this._setCookie(name, "", -1);  
        },
    };
    
    
    $(function(){

        var dragObj = $('#drag').drag();
        $('#btn-login').click(function(){ 
        // 登入按键被按下
            clearUsernamePwd();
            clearDrag();
        });
	    var clearDrag = function(){
	    	$('#drag').remove();
	    	$('input[name=password]').parent().after('<p class="v" id="drag"></p>');
		    dragObj = $('#drag').drag();
		}
		var clearUsernamePwd = function(){
			$('.Shady-login').find('input[name=username]').val('');
			$('.Shady-login').find('input[name=password]').val('');
			_rememberMe.run();
	    }
    	
    	
        $("#mask").click(function () {
            $("#mask, #popup-captcha-mobile").hide();
        });
        $(".Shady-login-btn").click(function (event) {
        	event.preventDefault();
            var _username = $("input[name=username]");
            var _password = $("input[name=password]");
            var username = _username.val();
            var password = _password.val();
            var remember = $("input[name=remember]:checked").length;
            if(! username){
                return _username.focus();
            }
            if(! password){
                return _password.focus();
            }

            if(! dragObj.isDragOk()){
            	return _showMsg('请拖动滑块验证');
            }
            
            if(remember){
            	_rememberMe.set(username);
            }else{
            	_rememberMe.clear();
            }

            _ajax({
                url:"{{route('api_login')}}",
                data:{
                    'account':username,
                    'password':password,
                    'remember':remember,
                },
                success:function(response){
                    if(_judgeResult(response)){
                        if(response['data'] && response['data']['to']){
                        	window.location.href=response['data']['to'];
                        }else{
                        	window.location.href="{{route('wiki')}}";
                        }
                    }else{
                    	clearDrag();
                    	_showErrorMsg(response);
                    }
                }
            });
        });
      
        $('.register').click(function(){
            location.href = "{{route('register')}}";
        });
        
    })
    </script>
@stop
@section('footer')
    @parent
    <div class="Shady-login">
      <div class="k">
        <img class="clear" src="/img/clear.png" />
        <div class="center">
          <h2>欢迎登录气味王国账户</h2>
          <p>
            <input type="text" name="username" placeholder="邮箱" autocomplete="off" /></p>
          <p>
            <input type="password" name="password" placeholder="密码" autocomplete="off" /></p>
          <p class="v" id="drag"></p>
    
          <p class="s">
            <span class="remember-account">
              <input type="checkbox" name="remember" id="checkbox" value="" style="vertical-align: middle;" />
              <label for="checkbox">记住账户</label></span>
            <a href="{{route('forget')}}" class="forget-passworld">忘记密码?</a></p>
          <p>
            <button class="btn Shady-login-btn">登录</button>
            <button class="btn Shady-register-btn register" onclick="window.location.href='{{route('register')}}'">注册</button></p>
        </div>
      </div>
    </div>
    <div class="Shady"></div>
@stop
