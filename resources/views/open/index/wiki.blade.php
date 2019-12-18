@extends('open.layouts.template3')
@section('js')
	@parent
	<script>
    </script>
@stop
@section('css')
	<link rel="stylesheet" href="/css/main.css" />
	<style>
.apply-btn{border-radius: 4px;margin-left: 15px;font-weight:100;font-size: 14px;text-align:center;line-height:45px;float: right;display: block;width: 130px;height: 45px;}
.apply{color: #FFFFFF;background-color: #14a3ae;}
.apply-ing{position: relative;border: 1px solid #d2d2d2;color: #16aab5;background-color: #e5e5e5;}
.apply-btn i {right: -3px;bottom:-3px;height: 22px;width: 21px;position: absolute;}
.apply-icon-ing{background: url(/img/kfz-time.png) no-repeat;}
.apply-authenticated{position: relative;border: 1px solid #14a3ae;color: #16aab5;background-color: #FFFFFF;}
.apply-icon-authenticated{background: url(/img/kfz-cg.png) no-repeat;}
.apply-message{float: right;font-size: 14px;line-height: 45px;font-weight:100;color: #d60000;}
	</style>
@stop

@section('content')
<section class="smell-doc">
      <h2 class="title">
<!--       1:未申请 2:已申请 3:申请通过 4:申请未通过 -->
      @if($applyStatus == 1 || $applyStatus == 4 )
      <a class="apply-btn apply" href="{{route('apply',['type' => 'developer'])}}" >申请开发者</a>
      @endif
      @if($applyStatus == 4 )
      <a class="apply-message">（认证失败，{{$lastFailReason}}，请重新申请）</a>
      @endif 
      
      @if($applyStatus == 2 )
      <a class="apply-btn apply-ing"><span>审核中</span><i class="apply-icon-ing"></i></a>
      @endif 
      @if($applyStatus == 3 )
      <a class="apply-btn apply-authenticated"><span>开发者已认证</span><i class="apply-icon-authenticated"></i></a>
      @endif 
        
        
        <span class="doc">API接口文档</span>
        <span class="passworld-alter" style="display: none;">修改密码</span></h2>
      <div class="passworld-alter" style="display: none;">
        <div id="alter" class="alter">
        	<div class="title">
        	  <img class="icon" src="../img/gantanhao.png" />
        	  <span id="pass-title">定期更换密码可以让您的账户更加安全；建议密码采用字母和数字混合,并且不短于6位</span>
        	</div>
        	<hr/>
        	<div id="pass">
        	  <div class="pass-input">
              <span><label for="email">当前密码</label></span>
              <input type="text" placeholder="请输入您的当前密码" />
              <i><img src="../img/gantanhao2.png" /><span>当前密码输入错误</span></i>
            </div>
          	<div class="pass-input">
              <span><label for="email">新密码</label></span>
              <input type="text" placeholder="请输入您的新密码" />
              <!--<i><img src="../img/gantanhao2.png" /><span>该邮箱账号不存在,请重新输入</span></i>-->
            </div>
            <div class="pass-input">
              <span><label for="email">再次输入密码</label></span>
              <input type="text" placeholder="请再次您的输入新密码" />
              <i><img src="../img/gantanhao2.png" /><span>两次密码输入不一致</span></i>
            </div>
          </div>
        	<button class="btn" id="updatePassSuccess">确定</button>
      	</div>
      	<div id="alter-success" class="alter-success">
      	  <div class="bg-img"><img src="../img/cg.png" /></div>
      	  <h2>您已成功修改密码！</h2>
      	  <button class="btn noMargin">返回开发中心</button>
      	  <p><span id="pass-g-time">5s</span>之后，自动跳往API文档页面</p>
      	</div>
      </div>  
     <!--  
      <iframe width="1200"  height="863" style="border: 0px;" src="https://moonbingbing.gitbooks.io/openresty-best-practices/content/index.html"></iframe>
     -->
      <iframe width="1200"  height="863" style="border: 0px;" src="{{$documentation_host}}"></iframe>
      
    </section>

@stop

