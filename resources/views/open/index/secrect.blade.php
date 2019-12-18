@extends('open.layouts.template3')

@section('css')
@parent
<style>
.relative{position: relative;}
.table{width: 100%;text-align: center;border-spacing: 0px;color: #666666;border-collapse: collapse}
.table thead{background-color: #eeeeee;}
.table thead tr{height: 50px;}
.table tbody tr td .btn-enable{text-align: center;display: inline-block;background-color: #14a3ae;width: 70px;height: 40px;line-height: 40px;color: #FFFFFF;border-radius: 5px;}
.table tbody tr td .btn-disable{text-align: center;display: inline-block;background-color: #dcdcdc;width: 70px;height: 40px;line-height: 40px;border-radius: 5px;}
.table tbody tr .Enable{color: #16aab5;}
.table tbody tr .Disable{color: #b51616;}
.table tbody tr{height: 175px;border: 1px solid #eeeeee;}
.table tbody tr.bor-bottom{text-align: center;border-bottom: 0px;}
.table tbody tr .createKey{display: block;background-color: #16AAB5;width: 100px;height: 40px;text-align: center;line-height: 40px;margin: 0 auto;color: #FFFFFF;border-radius: 4px;}
.table tbody tr .key{overflow: hidden;margin: 0 auto;width: 82%;margin-bottom: 10px;}
.table tbody tr .key .label{display: block;float: left;width: 40px;height: 40px;line-height: 40px;}
.table tbody tr .key .input{display: block;float: left;width: 500px;height: 40px;border-radius: 4px;border: 1px solid #dddddd;padding-left: 20px;}
.table tbody tr .key .btn-show{color: #FFFFFF;position: absolute;width: 45px;height: 25px;display: block;font-style: normal;text-align: center;line-height: 25px;background-color: #c9c9c9;border-radius: 4px;top: 7.5px;right: 15px;}
.Shady-login .br{height: 100px;}
.Shady-login .passkey-title{font-size: 20px;margin-bottom: 26px;text-align: center;}
.Shady-login .passkey-btn{margin-top: 40px;width: 120px;height: 50px;display: block;text-align: center;border-radius: 6px;line-height: 50px;font-size: 18px;}
.Shady-login .passkey-btn.passbtn-clear{background-color: #dcdcdc;color: #999999;float: left;margin-left: 50px;}
.Shady-login .passkey-btn.passbtn-submit{background-color: #14a3ae;color: #FFFFFF;float: right;margin-right: 50px;}
.Shady-login .passkey-pass{margin-top: 60px;}
.Shady-login .passkey-hr{border-bottom: 1px solid #dcdcdc;margin-bottom: 35px;width: 400px;}
</style>
@stop

@section('js')
@parent
<script src="{{asset('js/main.js') }}"></script>
<script src="{{asset('js/funcs.js') }}"></script>
<script>

var _globalCache = {
    set : function(key,value){
        this[key] = value;
        return true;
    },
    get : function(key){
        return this[key];
    },
    has : function(key){
        return (this[key] ) ? true :false;
    }
};



  var _showPassInputDialog = function(){
    $('#passkeyShow').css('display', 'block');
    $('.Shady').css('display', 'block');
  }
  var _hideDialog = function(){
	  $('body').css('overflow', 'auto');
	    $('.Shady').css('display', 'none');
	    $('.Shady-login').css('display', 'none');
  }
  $('.passbtn-clear').click(function(){
	  _hideDialog();
});
  $('.access-stop').click(function() {
	var accessKey = $(this).parents('tr').find('input[name=access]').val();
	_globalCache.set('accessKey',accessKey);
    $('#passkeyStop').css('display', 'block');
    $('.Shady').css('display', 'block');
  });
  
  $('.access-enable').click(function() {
    var accessKey = $(this).parents('tr').find('input[name=access]').val();
    _globalCache.set('accessKey',accessKey);
    	 
    $('#passkeyEnable').css('display', 'block');
    $('.Shady').css('display', 'block');
  });
  var _craeteAccessKeyPair = function(){
	    _ajax({
	        url:"{{route('api_createAccessKey')}}",
	        data:{},
	        success:function(response){
		        if(_judgeResult(response)){
		        	window.location.reload();
			    }else{
			    	_showErrorMsg(response);
				}
		    },
		    error:function(response){
		        _showErrorMsg(response);
			}
		});
  }
  var _disbaleAccessKeyPair = function(target){
	  var accessKey = _globalCache.get('accessKey');
	    _ajax({
            url:"{{route('api_disableAccessKey')}}",
            data:{
                'access' : accessKey
    	    },
            success:function(response){
            	if(_judgeResult(response)){
    	        	window.location.reload();
    		    }else{
    		    	_showErrorMsg(response);
    			}
    	    },
    	    error:function(response){
    	        _showErrorMsg(response);
    		}
	});
  }

  var _enbaleAccessKeyPair = function(target){
	  var accessKey = $(target).parents('tr').find('input[name=access]').val();
	    _ajax({
            url:"{{route('api_enableAccessKey')}}",
            data:{
                'access' : accessKey
    	    },
            success:function(response){
            	if(_judgeResult(response)){
    	        	window.location.reload();
    		    }else{
    		    	_showErrorMsg(response);
    			}
    	    },
    	    error:function(response){
    	        _showErrorMsg(response);
    		}
	});
  }
  
  var _deleteAccessKeyPair = function(target){
	  var accessKey = _globalCache.get('accessKey');
	  var _password = $(target).parent().find('.input-password');
	  var password = _password.val();
	  if(!password){
		    return _password.focus();
	  }
	    _ajax({
            url:"{{route('api_deleteAccessKey')}}",
            data:{
                'access' : accessKey,
                'password' : password
    	    },
            success:function(response){
            	if(_judgeResult(response)){
    	        	window.location.reload();
    		    }else{
    		    	_showErrorMsg(response);
    			}
    	    },
    	    error:function(response){
    	        _showErrorMsg(response);
    		}
	});
}

  var _reqShowAccessKeySecretToken = function(target){
        var _password = $(target).parent().find('.input-password');
        var password = _password.val();
        if(!password){
            return _password.focus();
        }
        _ajax({
            url:"{{route('api_applyShowSecretKeyToken')}}",
            async : false,
            data:{
                'password' : password
            },
            success:function(response){
            	if(_judgeResult(response)){
            		_hideDialog();
            		_globalCache.set('token',response.data.token)
            		_showAccessKeySecret();
        	    }else{
        	    	_showErrorMsg(response);
        		}
            },
            error:function(response){
                _showErrorMsg(response);
        	}
        });
 }
  
  var _showAccessKeySecret = function(){
	  _ajax({
          url:"{{route('api_showSecretKey')}}",
          data:{
              'access' : _globalCache.get('accessKey'),
              'token' : _globalCache.get('token')
  	    },
        success:function(response){
          	if(_judgeResult(response)){
              	var btn = $('[data-access='+_globalCache.get('accessKey')+']');
              	btn.parent()
              	   .find('input[type=password]')
              	   .attr('type','text')
              	   .val(response.data.secret);
              	btn.addClass('hide');
//               	btn.parent().find('btn-hide-secret').removeClass('hide');
      		}else{
  		    	_showErrorMsg(response);
  			}
  	    },
  	    error:function(response){
  	        _showErrorMsg(response);
  		}
	});   
  }
//   var _clickShowAccessKeySecret = function(target){
// 	  var accessKey =  $(target).data('access');
// 	  _globalCache.set('accessKey',accessKey);
// 	  console.log(accessKey);
// 	  if(_globalCache.has('token')){
// 		  _showAccessKeySecret();
// 	  }else{
// 		  _showPassInputDialog();
// 	  }
//   }

  var _clickShowAccessKeySecret = function(target){
	  var _input = $(target).parent().find('input')
	  if(_input.attr('type') == 'password'){
		  _input.attr('type','text');
		  $(target).html('隐藏');
      }else if(_input.attr('type') == 'text'){
		  _input.attr('type','password');
		  $(target).html('显示');
      }
  }
</script>
@stop

@section('content')
    <section class="smell-doc">
      <h2 class="title">
        <span class="doc">密钥管理</span>
      </h2>
      <div class="passworld-alter" style="display: block;">
        <div id="alter" class="alter">
        	<div class="title">
        	  <img class="icon" src="../img/gantanhao.png" />
        	  <span id="pass-title" style="display: block;">一个账号最多拥有两对密钥（Access/Secret Key）；更换密钥时，请创建第二个密钥；删除密钥前须停用；出于安全考虑，建议您周期性地更换密钥。</span>
        	</div>
        	<hr/>
        	
        	<table class="table">
        	  <thead>
        	    <tr>
        	      <th>创建时间</th>
        	      <th>Access/Secret Key</th>
                <th>状态</th>
                <th style="width:180px;">操作</th>
              </tr>
        	  </thead>
        	  <tbody>
        	    @foreach($list as $item)
        	    <tr>
        	      <td>{{substr($item['created_at'],0,10)}}</td>
        	      <td>
        	        <div class="key">
        	          <span class="label">AK:</span>
        	          <input class="input" type="text" name="access"  value="{{$item['access']}}" readonly="readonly"/>
        	        </div>
                  <div class="key relative">
                    <span class="label">SK:</span>
                    <input class="input" type="password" value="{{$item['secret']}}"  readonly="readonly"/>
                    <i class="btn-show btn" onclick="_clickShowAccessKeySecret(this)">显示</i>
                  </div>
        	      </td>
        	      @if($item['status'] == 1)
            	      <td><span class="Enable">使用中</span></td>
            	      
            	       @if($usingCount == 1)
            	      <td><a class="btn-disable" style="cursor: default;">停用</a></td>
            	      @else
            	       <td><a class="btn btn-disable access-stop" >停用</a></td>
            	       @endif
            	  @else
                	  <td><span class="Disable">停用</span></td>
                        <td>
                          <a class="btn btn-disable access-enable" >删除</a>
                          <a class="btn btn-enable" onclick="_enbaleAccessKeyPair(this);">启用</a>
                        </td>
        	      @endif
        	    </tr>
        	    @endforeach
        	    
        	    @if($count < 2)
              <tr class="bor-bottom">
                <td colspan="4">
                  <a class="createKey btn" onclick="_craeteAccessKeyPair()">创建密匙</a>
                </td>
              </tr>
              @endif
        	  </tbody>
        	</table>
      	</div>
      </div>  
    </section>
@stop

@section('footer')
    @parent
    <div class="Shady-login" id="passkeyStop" style="display: none;">
      <div class="k">
        <img class="clear" src="../img/clear.png"/>
        <div class="center">
          <p class="br"></p>
          <p class="passkey-title">您确定禁用此密钥吗？</p>
          <p class="passkey-title">禁用前请确认该密钥没有被使用！</p>
          <a class="passkey-btn passbtn-clear btn">取消</a>
          <a class="passkey-btn passbtn-submit btn" onclick="_disbaleAccessKeyPair()">确认</a>
        </div>
      </div>
    </div>
    <div class="Shady-login" style="display: none;" id="passkeyEnable">
      <div class="k">
        <img class="clear" src="../img/clear.png"/>
        <div class="center">
          <p class="passkey-pass passkey-title">密码认证</p>
          <div class="passkey-hr"></div>
          <p class="passkey-pass-title">请输入您的密码 : </p>
          <p><input type="password" class="input-password" /></p>
          <a class="passkey-btn passbtn-clear btn">取消</a>
          <a class="passkey-btn passbtn-submit btn" onclick="_deleteAccessKeyPair(this)">确认</a>
        </div>
      </div>
    </div>
    <div class="Shady-login" style="display: none;" id="passkeyShow">
      <div class="k">
        <img class="clear" src="../img/clear.png"/>
        <div class="center">
          <p class="passkey-pass passkey-title">密码认证</p>
          <div class="passkey-hr"></div>
          <p class="passkey-pass-title">请输入您的密码 : </p>
          <p><input type="password" class="input-password" /></p>
          <a class="passkey-btn passbtn-clear btn">取消</a>
          <a class="passkey-btn passbtn-submit btn" onclick="_reqShowAccessKeySecretToken(this)">确认</a>
        </div>
      </div>
    </div>
    <div class="Shady" style="display: none;"></div>
@stop