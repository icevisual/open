@extends('open.layouts.template1') 


@section('css')
@parent
    <style>
    .passworld-alter .alter-email{position: relative;text-align: left;padding-top: 30px;margin: 0 auto;width: 500px;}
    .passworld-alter .alter-email img{left: -85px;position: absolute;width: 56px;height: 56px;}
    .passworld-alter .alter-email h2{line-height: 1.5;font-size: 24px;color: #333333;margin-bottom: 20px;font-weight: 300;}
    .passworld-alter .alter-email p{font-size: 14px;color: #666666;margin-bottom: 10px;}
    .passworld-alter .alter-email button{width: 160px;height: 50px;background-color: #3cd3c3;border-radius: 5px;border: 0px;font-size: 18px;color: #FFFFFF;margin-bottom: 25px;}
    </style>
@stop

@section('js')
<script type="text/javascript" src="/js/jquery-1.9.1.min.js"
	charset="utf-8"></script>
<script type="text/javascript" src="/js/main.js"></script>
<script type="text/javascript" src="/js/funcs.js"></script>
<script>
_headerNavShow('developer');

var responseHandle = function(response){
	var _target = $('i.form-error');
	if(_judgeResult(response)){
		var home  = response.data.home;
		$('#alter').css({
			'display':'none'
	    })
	    $('#Send-Email-Over').removeClass('hide').find('.forget-emial').html($('input[name=email]').val());
		$('#Send-Email-Over #btn-login-email').click(function(){
		    window.open(home);
	    });
    }else{
    	_target.removeClass('hide');
    	_target.find('span').html(response.msg);

    	_target.parent()
    	       .find('input')
    	       .removeClass('form-valid-input')
    	       .addClass('form-error-input');
    }
}

var sendForgetEmail = function(){

    var _email = $('input[name=email]');
    var email = _email.val();
    if(! email){
        return _email.focus();
    }
    _ajax({
        url:"{{route('api_sendForgetEmail')}}",
        data:{
            'email':email
        },
        success:function(response){
        	responseHandle(response);
        }
    });
}

$(function(){





    $('input').focus(function(){
    	$(this).removeClass('form-error-input');
    	var _par = $(this).parent();
    	_par.find('i.form-error').addClass('hide');
		_par.find('i.form-notice').removeClass('hide');
    });
	
})
</script>
@stop @section('content')
<section class="smell-doc">
	<h2 class="title">
		<span class="passworld-alter">忘记密码</span>
	</h2>
	<div class="passworld-alter">
		<div id="alter" class="alter">
			<div class="title">
				<img class="icon" src="../img/gantanhao.png" /> <span
					id="email-title">请填写您的绑定邮箱地址</span> <span id="pass-title">密码由字母、数字或英文符号组成，最短6位，区分大小写</span>
			</div>
			<hr />
			<div id="email">
				<div class="pass-input">
					<span><label for="email">邮箱</label></span> <input type="text"
						name="email" placeholder="请输入您的绑定邮箱号" /> <i
						class="form-error hide"><span>该邮箱账号不存在,请重新输入</span></i>
				</div>
			</div>
			<button class="btn" onclick="sendForgetEmail()">确定</button>
		</div>

		<div id="Send-Email-Over" class="alter-email hide" >
          <img src="/img/email.png" />
          <h2>验证邮件已发送至您的邮箱：
            <br />
            <span class="forget-emial">123</span></h2>
          <p>请至邮箱查收验证邮件，进行邮箱确认操作。</p>
          <button class="btn" id="btn-login-email">登入邮箱</button>
        </div>


	</div>
</section>
@stop
