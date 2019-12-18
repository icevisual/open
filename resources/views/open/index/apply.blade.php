@extends('open.layouts.template3')

@section('css')
@parent
    <link rel="stylesheet" href="/css/nanoscroller.css" />
    <style>
.Agreement-in{z-index:999;position: fixed;top: 0px;left: 0px;right: 0px;bottom: 0px;background-color: rgba(0,0,0,0.3);}
.Agreement{width: 100%;display: block;position: fixed;top: 10%;z-index: 3;}
.Agreement .Agreement-content{position: relative;width: 900px;height: 600px;box-shadow: 0 0 0 10px rgba(0,0,0,0.1);margin: 0 auto;overflow: hidden;background-color: #FFFFFF;padding: 10px 30px;}
.Agreement .Clear{z-index: 4;position: absolute;right: 15px;top: 15px;width: 35px;height: 35px;cursor:pointer;}
.Agreement h3{text-align: center;color: #16aab5;}
.Agreement .hr{margin-top: 30px;margin-bottom: 20px;border-bottom: 2px solid #16aab5;}
.Agreement .Agreement-content .content-p{padding-right: 20px;white-space: pre-wrap;color: #bdbdbd;}
#grey-btn{    cursor: default;}
    </style>
<style>
#code.disabled{
    background-color: #DEDEDE !important;
    cursor: no-drop;
}
</style>
@stop
@section('js')
@parent
<script type="text/javascript" src="/js/funcs.js" ></script>
    <script type="text/javascript" src="/js/jquery.nanoscroller.js" ></script>
<script>
var _showProtocolContent = function(){
    $('.Agreement-in').removeClass('hide');
}
$(function(){
	$('.Agreement-in .Clear').click(function(){
		$('.Agreement-in').addClass('hide');
    });
	$(".nano").nanoScroller({alwaysVisible: true});
    
	$('.cbox').click(function(){
    	_submitBtnCheck();
    });
    var _submitBtnCheck = function(){
        var btn = $('#submit-btn');
        var greyBtn = $('#grey-btn');
        var _isOk = _checkNextBtnOk('#theForm');
        if(_isOk){
        	btn.removeClass('hide');
        	greyBtn.addClass('hide');
        }else{
        	btn.addClass('hide');
        	greyBtn.removeClass('hide');
        }
    }
    var _checkNextBtnOk = function(form){
        var _formInputEmptyNum = 0;
        var _notRequired = {
        	'company' : 1,
        };
        $(form).find('input').each(function(){
            if(!$(this).val() && !_notRequired[$(this).attr('name')] ){
            	_formInputEmptyNum ++ ;
            }
        });
        // || !_agreeProtocol()
        if(_formInputEmptyNum  ){
            return false;
        }
        return true;
    }

    $('form input').on("change blur focus",function(){
    	_submitBtnCheck();
    });

    $('form textarea').on("change blur focus",function(){
    	_submitBtnCheck();
    });

    $('form select').on("change blur focus",function(){
    	_submitBtnCheck();
    });
    
	var _initRegionSelect = {
	    _emptyOption : "<option value=''>--请选择--</option>", 
		_initSelect : function(target, data){
            var _target = $(target);
            var _element = this._emptyOption;
            for(var i in data){
            	_element += '<option value='+data[i]['cid']+'>' + data[i]['name'] + '</option>';
            }
            _target.html(_element);
        },
        _selectChange : function(fid,_reflushSelector){
            var _this = this;
            _ajax({
                url:"{{route('region')}}",
                data:{
                    'fid' : fid
            	},
            	success:function(response){
            		_this._initSelect(_reflushSelector,response.data);
                }
            });
        },
        _clearOptions : function(target){
        	$(target).html(this._emptyOption);
        },
        _clearRegionIds : function(){
            $('[name=regions]').val('');
        },
        _setRegionIds : function(selectorProvince,selectorCity,selectorDistrict){
            $('[name=regions]').val($(selectorProvince).val() + ',' + $(selectorCity).val() + ',' + $(selectorDistrict).val());
        },
        init : function(selectorProvince,selectorCity,selectorDistrict){
            var _this = this;
            _this._selectChange(3571,selectorProvince);
    		$(selectorProvince).change(function(){
    			_this._clearOptions(selectorDistrict);
    			_this._selectChange($(this).val(),selectorCity);
    			_this._clearRegionIds();
    	    });
    		$(selectorCity).change(function(){
    			_this._selectChange($(this).val(),selectorDistrict);
    			_this._clearRegionIds();
    	    });
    		$(selectorDistrict).change(function(){
    			_this._setRegionIds(selectorProvince,selectorCity,selectorDistrict);
    			if(!$(this).val() || '' == $(this).val()　){
    				_this._clearRegionIds();
                }
    	    });
        }
    }
	_initRegionSelect.init("#deliverprovince","#delivercity","#deliverarea");

	// 验证码，获取验证码（检测手机号码）=> 倒计时 => 重新发送
	var _sendSms = {
        _formShowInputError : function(_form,key,msg){
        	var _par = _form.find('input[name='+key+']').removeClass('form-valid-input').addClass('form-error-input').parent();
        	_par.find('i.form-error').removeClass('hide').find('span').html(msg);
        	_par.find('i.form-notice').addClass('hide');
        },
        _formClearInputError : function(_form,key){
        	var _par = _form.find('input[name='+key+']')
    	    .removeClass('form-error-input')
		    .addClass('form-valid-input')
		    .parent();
		    
        	_par.find('i.form-error').addClass('hide');
        	_par.find('i.form-notice').removeClass('hide');
        },
        _setWait : function(btnSelector,second){
            $(btnSelector).addClass('grey');
            $(btnSelector).html('获取验证码' + '<span>('+ second +')</span>');
            $(btnSelector).attr('disabled','disabled');
            var  interval = 1000;
            var _this = this;
            setTimeout(function(){
            	$(btnSelector).find('span').html('(' + --second + ')');
                //do something
                if(second){
                    setTimeout(arguments.callee,interval);
                }else{
                	_this._setRetry(btnSelector);
                }
            },interval)
        },
        _setRetry : function(btnSelector){
        	$(btnSelector).find('span').remove();
        	$(btnSelector).removeClass('grey');
        	$(btnSelector).removeAttr('disabled');
        },
        _setCodeInputEnable:function(_form){
        	_form.find('input[name=code]').removeAttr('disabled').removeClass('disabled');
        },
	    init : function(phoneSelector,btnSelector){
	       var _this = this;
		   $(btnSelector).click(function(event){
			   event.preventDefault();
			   $(btnSelector).attr('disabled','disabled');
			   var _phone = $(phoneSelector);
			   var _form = _phone.parents('form');
			   var phone = _phone.val();
			   if(!phone){
				   $(btnSelector).removeAttr('disabled');
				   return _this._formShowInputError(_form,'phone','请填写手机号码');
			   }
		    	_ajax( {
		            url:"{{route('api_sendApplySms')}}",
		            data: {
		                'phone' : phone
			        },
		            success: function( response ) {
		            	$(btnSelector).removeAttr('disabled');
		                if(_judgeResult(response)){
		                	_this._formClearInputError(_form,'phone');
		                	_this._setWait(btnSelector,response.data.interval);
		                	_this._setCodeInputEnable(_form);
		                }else{
		                	_this._formShowInputError(_form,'phone',response.msg);
		                	
		                }
		            },
		            error: function( response ){
		            	$(btnSelector).removeAttr('disabled');
		            	_this._formShowInputError(_form,'phone',response.msg);
		            }
		        });
			});
	   }
    };
	_sendSms.init('#phone','.verification');
	var _formErrorShow = function(_form,data){
    	_form.find('i.form-error').addClass('hide');
        var _foucsFirstError = false ;
    	_form.find('input').each(function(){
    	    var key = $(this).attr('name');
    		if(data[key]){
//         		if(false === _foucsFirstError){
//         			_form.find('input[name='+key+']').focus();
//         			_foucsFirstError = true;
//         		}
        		var _par = _form.find('input[name='+key+']').removeClass('form-valid-input').addClass('form-error-input').parent();
    			_par.find('i.form-error').removeClass('hide').find('span').html(data[key]);
    			_par.find('i.form-notice').addClass('hide');
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
		_par.find('i.form-notice').removeClass('hide');
		_submitBtnCheck();
    });
    $('#submit-btn').click(function(event){
        event.preventDefault();
//         if(!_agreeProtocol()){
//             return _showMsg('您必须同意协议才能继续执行');
//         }
        var _form = $('#'+$(this).attr('form'));
        var _formData = {};
        _form.find('input').each(function(){
        	_formData[$(this).attr('name')] = $(this).val();
        });
        _ajax( {
            url:"{{route('api_applyDeveloper')}}",
            data: _formData,
            success: function( response ) {
                if(_judgeResult(response)){
                    window.location.href = "{{route('wiki')}}";
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
    $('#address').change(function(){
        $('input[name=address]').val($(this).val());
    });
})
</script>
@stop
@section('content')
    <section class="smell-doc">
      <h2 class="title">
        <span class="doc">申请开发者</span>
      </h2>
      <div class="passworld-alter">
        <div id="alter" class="alter">
          <div id="pass" style="display: block;">
            <form id="theForm">
            <div class="pass-input">
              <span><label >真实姓名</label></span>
              <input type="text" name="truename" placeholder="请输入您的真实姓名" autocomplete="off" />
              <i class="form-notice"><span>不允许为空</span></i>
              <i class="form-error hide"><span>不允许为空</span></i>
            </div>
            <div class="pass-input">
              <span><label >手机号</label></span>
              <input type="text" name="phone" id="phone" placeholder="请输入您的手机号" autocomplete="off"  />
              <i class="form-notice"><span>请填写常用手机号</span></i>
              <i class="form-error hide"><span>请输入正确格式的手机号</span></i>
            </div>
            <div class="pass-input">
              <span><label >手机验证码</label></span>
              <input type="text" name="code" id="code" placeholder="请输入手机验证码" class="disabled" autocomplete="off"  disabled="disabled" />
              <button class="btn verification">获取验证码</button>
<!--               <button class="btn verificationTime" disabled="disabled">重新发送<span>(30)</span></button>  -->
<!--               <button class="btn verificationTimeOut">重新发送</button> -->
              <i class="form-error hide"><span>验证码不正确，请重新输入</span></i>
            </div>
            <div class="pass-input">
              <span><label >公司</label></span>
              <input type="text" name="company" placeholder="请填写有效的公司名称" autocomplete="off"  />
              <i class="form-error hide"><span>验证码不正确，请重新输入</span></i>
            </div>
            <div class="pass-input">
              <span><label >联系地址</label></span>
              <strong style="color: #333333;font-weight: 400;margin-right: 10px;">中国</strong>
              <select id="deliverprovince" name="deliverprovince"  class="select"></select>
              <select id="delivercity" name="delivercity" class="select"></select>
              <select id="deliverarea" name="deliverarea" class="select"></select>
              <input type="hidden" name="regions"/>
              <i class="form-error hide"><span>请填写正确的地址信息</span></i>
            </div>
            <div class="pass-input">
              <span>
                <label></label>
              </span>
              <textarea placeholder="详细地址" id="address" style="margin-right: 65px;float: left;width: 500px;height: 90px;border-radius: 4px;border: 1px solid #cfcfcf;padding: 10px;"></textarea>
              <input type="hidden" name="address"/>
              <i  class="form-notice hide"><span>请填写常用地址</span></i>
              <i  class="form-error hide"><span>请填写常用地址</span></i>
            </div>
            <div class="pass-input hide">
              <span>
                <label></label>
              </span>
              <span class="cbox" onclick="this.className=/checked/ig.test(this.className)?this.className.replace('checked',''):(this.className+' checked')"></span>
              <strong style="font-weight: 400;color: #999999;display: block;float: left;height: 20PX;line-height: 20PX;">我同意并遵守上述的
                <i style="font-style: normal;color: #16AAB5;cursor: pointer;" onclick="_showProtocolContent()">《气味开发平台开发者资质认证服务协议》</i>
              </strong>  
            </div>
            </form>
          </div>
          <button id="grey-btn" disabled="disabled" class="btn next">完成</button>
          <button class="btn submit hide" id="submit-btn" form="theForm" style="display: block;">完成</button>
        </div> 
      </div>
    </section>
    
    
    
    
    
    
    <div class="Agreement-in hide">
    <div class="Agreement">
      <div class="Agreement-content">
          <img class="Clear" src="/img/clear.png"/>
          <h3>气味开发平台开发者服务协议</h3>
          <div class="hr"></div>
          <div class="nano" style="height: 480px;">
          	
          
          <p class="content-p content">“体验地图”作为服务设计中的一种流程分析工具，可以从全局视角，图像化整个服务过程链、提炼用户与服务的触点、相关干系人的参与活动、用户的情感曲线走势。适合用做系统全面的服务蓝图规划或者服务优化的指引。有助于理清整个服务中人、产品和流程之间错综复杂的关系，通过发掘用户期望与现实感受的差值，并显像化一些无形的服务，寻找服务过程中的设计机会点，达到改善服务体验的目的。

比较完整的体验地图一般包括：

1.参与人：服务接收者（用户）和服务提供者（产品\服务\系统）；
1.服务触点：服务接收者和服务提供者之间的接触触点；
3.服务证据：与每个服务过程相关的有形证据；
4.服务阶段\场景：比如服务前\正式服务\服务后；
5.使用路径：用户使用服务的路径；
6.用户心智模型与行为：使用者体验过程中的期望、情绪、想法、感觉及反应；
7.产品体验分析：用户对产品的使用体验和痛点描述。

下图是一个比较完整的体验地图案例–欧洲铁路购票。

案例背景：一个美国经销商要提供一个独立的网站给北美的旅客预订整个欧洲的火车票，而不再是去许多分散的网站买票。他们已经有了一个很好的网站和联络中心，但他们希望了解他们跟客户的所有接触点，这样可以为旅客提供更好的旅程体验，也能更充分地了解他们需要投入的预算，设计和技术资源。设计师Chris Risdon通过移情演绎一个用户旅程地图（体验阶段\触点\用户行为\想法\感受\愉悦度），明确指导原则，分析用户痛点，最
          “体验地图”作为服务设计中的一种流程分析工具，可以从全局视角，图像化整个服务过程链、提炼用户与服务的触点、相关干系人的参与活动、用户的情感曲线走势。适合用做系统全面的服务蓝图规划或者服务优化的指引。有助于理清整个服务中人、产品和流程之间错综复杂的关系，通过发掘用户期望与现实感受的差值，并显像化一些无形的服务，寻找服务过程中的设计机会点，达到改善服务体验的目的。

比较完整的体验地图一般包括：

1.参与人：服务接收者（用户）和服务提供者（产品\服务\系统）；
1.服务触点：服务接收者和服务提供者之间的接触触点；
3.服务证据：与每个服务过程相关的有形证据；
4.服务阶段\场景：比如服务前\正式服务\服务后；
5.使用路径：用户使用服务的路径；
6.用户心智模型与行为：使用者体验过程中的期望、情绪、想法、感觉及反应；
7.产品体验分析：用户对产品的使用体验和痛点描述。

下图是一个比较完整的体验地图案例–欧洲铁路购票。

案例背景：一个美国经销商要提供一个独立的网站给北美的旅客预订整个欧洲的火车票，而不再是去许多分散的网站买票。他们已经有了一个很好的网站和联络中心，但他们希望了解他们跟客户的所有接触点，这样可以为旅客提供更好的旅程体验，也能更充分地了解他们需要投入的预算，设计和技术资源。设计师Chris Risdon通过移情演绎一个用户旅程地图（体验阶段\触点\用户行为\想法\感受\愉悦度），明确指导原则，分析用户痛点，最
          “体验地图”作为服务设计中的一种流程分析工具，可以从全局视角，图像化整个服务过程链、提炼用户与服务的触点、相关干系人的参与活动、用户的情感曲线走势。适合用做系统全面的服务蓝图规划或者服务优化的指引。有助于理清整个服务中人、产品和流程之间错综复杂的关系，通过发掘用户期望与现实感受的差值，并显像化一些无形的服务，寻找服务过程中的设计机会点，达到改善服务体验的目的。

比较完整的体验地图一般包括：

1.参与人：服务接收者（用户）和服务提供者（产品\服务\系统）；
1.服务触点：服务接收者和服务提供者之间的接触触点；
3.服务证据：与每个服务过程相关的有形证据；
4.服务阶段\场景：比如服务前\正式服务\服务后；
5.使用路径：用户使用服务的路径；
6.用户心智模型与行为：使用者体验过程中的期望、情绪、想法、感觉及反应；
7.产品体验分析：用户对产品的使用体验和痛点描述。

下图是一个比较完整的体验地图案例–欧洲铁路购票。

案例背景：一个美国经销商要提供一个独立的网站给北美的旅客预订整个欧洲的火车票，而不再是去许多分散的网站买票。他们已经有了一个很好的网站和联络中心，但他们希望了解他们跟客户的所有接触点，这样可以为旅客提供更好的旅程体验，也能更充分地了解他们需要投入的预算，设计和技术资源。设计师Chris Risdon通过移情演绎一个用户旅程地图（体验阶段\触点\用户行为\想法\感受\愉悦度），明确指导原则，分析用户痛点，最
          “体验地图”作为服务设计中的一种流程分析工具，可以从全局视角，图像化整个服务过程链、提炼用户与服务的触点、相关干系人的参与活动、用户的情感曲线走势。适合用做系统全面的服务蓝图规划或者服务优化的指引。有助于理清整个服务中人、产品和流程之间错综复杂的关系，通过发掘用户期望与现实感受的差值，并显像化一些无形的服务，寻找服务过程中的设计机会点，达到改善服务体验的目的。

比较完整的体验地图一般包括：

1.参与人：服务接收者（用户）和服务提供者（产品\服务\系统）；
1.服务触点：服务接收者和服务提供者之间的接触触点；
3.服务证据：与每个服务过程相关的有形证据；
4.服务阶段\场景：比如服务前\正式服务\服务后；
5.使用路径：用户使用服务的路径；
6.用户心智模型与行为：使用者体验过程中的期望、情绪、想法、感觉及反应；
7.产品体验分析：用户对产品的使用体验和痛点描述。

下图是一个比较完整的体验地图案例–欧洲铁路购票。

案例背景：一个美国经销商要提供一个独立的网站给北美的旅客预订整个欧洲的火车票，而不再是去许多分散的网站买票。他们已经有了一个很好的网站和联络中心，但他们希望了解他们跟客户的所有接触点，这样可以为旅客提供更好的旅程体验，也能更充分地了解他们需要投入的预算，设计和技术资源。设计师Chris Risdon通过移情演绎一个用户旅程地图（体验阶段\触点\用户行为\想法\感受\愉悦度），明确指导原则，分析用户痛点，最
          </p>
        
      </div>    
      </div>
      </div>
    </div>
    
@stop



