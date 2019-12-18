@extends('open.layouts.template2')


@section('js')
    <script type="text/javascript" src="{{asset('/js/jquery-1.9.1.min.js') }}" charset="utf-8"></script>
    <script type="text/javascript" src="{{asset('js/funcs.js') }}"></script>
    <script>
    $(function(){
        var _formErrorShow = function(_form,data){
        	_form.find('i.form-error').addClass('hide');
            var _foucsFirstError = false ;
        	_form.find('input').each(function(){
        	    var key = $(this).attr('name');
        		if(data[key]){
            		_form.find('input[name='+key+']')
            		    .removeClass('form-valid-input')
            		    .addClass('form-error-input')
            		    .parent()
            	        .find('i.form-error')
            	        .removeClass('hide')
            	        .find('span')
            	        .html(data[key]);
                }else{
                	_form.find('input[name='+key+']')
                	    .removeClass('form-error-input')
    				    .addClass('form-valid-input')
    				    .parent()
    				    .find('i')
    				    .addClass('hide');
                }
            });
        }
        $('form input').focus(function(){
        	$(this).removeClass('form-error-input');
        	var _par = $(this).parent();
        	_par.find('i.form-error').addClass('hide');
        });
        $('#submit-btn').click(function(event){
            event.preventDefault();
            var _form = $('#'+$(this).attr('form'));
            var _formData = {};
            _form.find('input').each(function(){
            	_formData[$(this).attr('name')] = $(this).val();
            });
            _ajax( {
                url:"{{route('api_resetForgottenPasswd')}}",
                data: _formData,
                success: function( response ) {
                    if(_judgeResult(response)){
                        $('#alter').addClass('hide');
                        $('#alter-success').show();
                        setTimeout(function(){
                        	window.location.href="{{route('developer')}}";
                        },5000);
                    }else if(response['data']){
                    	_formErrorShow(_form,response.data);
                    }else{
                    	_showErrorMsg(response);
                    }
                },
                error: function( response ){
                	_formErrorShow(_form,response.data);
                }
            });
        });
    })
    </script>
@stop

@section('content')
    <section class="smell-doc">
      <h2 class="title">
        <span class="passworld-alter">忘记密码</span></h2>
      <div class="passworld-alter">
        <div id="alter" class="alter">
          <div class="title">
            <img class="icon" src="/img/gantanhao.png" />
            <span id="pass-title" style="display:block;">密码由字母、数字或英文符号组成，最短6位，区分大小写</span>
          </div>
          <hr/>
          <div id="pass" style="display:block;">
            <form id="theForm">
            <div class="pass-input">
              <span><label for="email">新密码</label></span>
              <input type="password" name="password" placeholder="请输入您的新密码" />
              <i class="form-error hide"><span>两次密码输入不一致</span></i>
            </div>
            <div class="pass-input">
              <span><label for="email">再次输入密码</label></span>
              <input type="password"  name="repassword" placeholder="请再次输入您的新密码" />
              <i class="form-error hide"><span>两次密码输入不一致</span></i>
            </div>
            <div >
              <input type="hidden"  name="token" value="{{$token}}"/>
            </div>
            
            </form>
          </div>
          <button class="btn" id="submit-btn" form="theForm">确定</button>
        </div>
        <div id="alter-success" class="alter-success">
          <div class="bg-img"><img src="/img/cg.png" /></div>
          <h2>您的密码重置成功！</h2>
          <p>您现在可以返回开发中心使用新密码登录气味王国开发平台</p>
          <button class="btn" onclick="window.location.href='{{route('developer')}}'">返回开发者中心</button>
          <p><span id="pass-g-time">5s</span>之后，自动跳往开发者中心</p>
        </div>
      </div>  
    </section>
@stop 
