@extends('open.layouts.template2')
@section('css')
    <link rel="stylesheet" href="{{asset('css/main.css') }}" />
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
@stop
@section('js')
    <script type="text/javascript" src="{{asset('/js/jquery-1.9.1.min.js') }}" charset="utf-8"></script>
    <script type="text/javascript" src="{{asset('js/funcs.js') }}"></script>
    <script type="text/javascript" src="/js/jquery.nanoscroller.js" ></script>
    <script>
    var reflushCaptcha = function(){
    	document.getElementById('captcha-img').src='{{route('api_captcha')}}?'+Math.random();
    }
    var _showProtocolContent = function(){
        $('.Agreement-in').removeClass('hide');
        $(".nano").nanoScroller({alwaysVisible: true});
//         window.open('');
    }
    $(function(){
    	$('.Agreement-in .Clear').click(function(){
    		$('.Agreement-in').addClass('hide');
        });
    	
        
        $('.cbox').click(function(){
        	_submitBtnCheck();
        });
        var _submitBtnCheck = function(){
            var btn = $('#submit-btn');
            var greyBtn = $('#grey-btn');
            var _isOk = _checkNextBtnOk('#register-form');
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
            $(form).find('input').each(function(){
                if(!$(this).val() || '' == $(this).val()){
                	_formInputEmptyNum ++ ;
                }
            });
            if(_formInputEmptyNum || !_agreeProtocol() ){
                return false;
            }
            return true;
        }
        var _formErrorShow = function(_form,data){
        	_form.find('i.form-error').addClass('hide');
            var _foucsFirstError = false ;
        	_form.find('input').each(function(){
        	    var key = $(this).attr('name');
        		if(data[key]){
//             		if(false === _foucsFirstError){
//             			_form.find('input[name='+key+']').focus();
//             			_foucsFirstError = true;
//             		}
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
        	reflushCaptcha();
        }

        $('form input').on('change focus blur',function(){
            var _val = $(this).val();
            if(!_val){
                $(this).removeClass('form-valid-input');
            }
        });
        
        $('form input').change(function(){
        	_submitBtnCheck();
        });
        $('form input').focus(function(){
        	$(this).removeClass('form-error-input');
        	var _par = $(this).parent();
        	_par.find('i.form-error').addClass('hide');
			_par.find('i.form-notice').removeClass('hide');

			_submitBtnCheck();
        });
        $('#submit-btn').click(function(event){
            event.preventDefault();
            if(!_agreeProtocol()){
                return _showMsg('您必须同意协议才能继续执行');
            }
            var _form = $('#'+$(this).attr('form'));
            var _formData = {};
            _form.find('input').each(function(){
            	_formData[$(this).attr('name')] = $(this).val();
            });
            _ajax( {
                url:"{{route('api_register')}}",
                data: _formData,
                success: function( response ) {
                    if(_judgeResult(response)){
                        window.location.href = "{{route('register',['step' => 'step2' ])}}";
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
        <img src="/img/register1.png"  />
      </h2>
      <div class="passworld-alter">
        <div id="alter" class="alter">
          
          <div id="email">
            <form id="register-form" action="" method="post">
            <div class="pass-input">
              <span>
                <label>邮箱</label></span>
              <input name="email" type="text" autocomplete="off" placeholder="请输入您的绑定邮箱号" value="{{$registeringEmail}}"/>
              <i class="form-error hide"><span>请输入正确格式的电子邮件</span></i>
            </div>
            <div class="pass-input">
              <span>
                <label>密码</label></span>
              <input name="password" type="password" autocomplete="off" placeholder="请输入您的密码" />
              <i class="form-notice">
                <span>密码由字母、数字或英文符号组成，最短 6 位，区分大小写</span></i>
              <i class="form-error hide">
                <span></span></i>
            </div>
            <div class="pass-input ">
              <span>
                <label>再次输入密码</label></span>
              <input name="repassword" type="password" autocomplete="off"  placeholder="请再次输入您的密码" />
              <i class="form-error hide"><span>两次密码输入不一致</span></i>
            </div>
            <div class="pass-input">
              <span>
                <label>验证码</label></span>
              <input name="code" style="width: 150px" autocomplete="off"  type="text" placeholder="请输入您的验证码" />
              <img id="captcha-img" src="{{route('api_captcha')}}" onclick="reflushCaptcha()"  style="float: left;;width: 60px;height: 45px;background-color: #FFFFF;margin-right: 10PX;;" />
              <a href="javascript:reflushCaptcha();" style="display: block;float: left;line-height: 45px;margin-right: 38PX;">换一张</a>
              <i class="form-error hide">
                <span>验证码不正确，请重试</span></i>
            </div>
            <div class="pass-input">
              <span>
                <label></label>
              </span>
              <span class="cbox" onclick="this.className=/checked/ig.test(this.className)?this.className.replace('checked',''):(this.className+' checked');"></span>
              <strong style="font-weight: 400;color: #999999;display: block;float: left;height: 20PX;line-height: 20PX;">我同意并遵守
                <i style="font-style: normal;color: #16AAB5;    cursor: pointer;" onclick="_showProtocolContent()">《气味开放平台开发者服务协议》</i></strong>
              <i class="form-error hide">
                <span>请勾选协议</span></i>
            </div>
             </form>
          </div>
          <button id="grey-btn" class="btn next" disabled="disabled">下一步</button>
<!--           <button class="btn next-cg">下一步</button> -->
          <button id="submit-btn" form="register-form" class="btn submit hide" style="display: block;">下一步</button>
         
        </div>
      </div>
    </section>
    
    
    <div class="Agreement-in hide">
    <div class="Agreement">
      <div class="Agreement-content">
          <img class="Clear" src="/img/clear.png"/>
          <h3>气味开放平台开发者服务协议</h3>
          <div class="hr"></div>
          <div class="nano" style="height: 480px;">
          	
          
          <p class="content-p content">杭州码客信息技术有限公司（以下简称“甲方”）在此特别提醒您（用户）在注册成为用户之前，请认真阅读本《用户协议》（以下简称“协议”），确保您充分理解本协议中各条款。请您审慎阅读并选择接受或不接受本协议。除非您接受本协议所有条款，否则您无权注册、登录或使用本协议所涉服务。您的注册、登录、使用等行为将视为对本协议的接受，并同意接受本协议各项条款的约束。“用户”是指注册、登录、使用本服务的个人。
一、总则
1.1　乙方应当同意本协议的条款并按照页面上的提示完成全部的注册程序。乙方在进行注册程序过程中点击"同意"按钮即表示乙方与甲方公司达成协议，完全接受本协议项下的全部条款。
1.2　乙方注册成功后，甲方将给予每个乙方一个乙方帐号及相应的密码，该乙方帐号和密码由乙方负责保管；乙方应当对以其乙方帐号进行的所有活动和事件负法律责任。
1.3　乙方可以使用甲方各个频道单项服务，当乙方使用甲方各单项服务时，乙方的使用行为视为其对该单项服务的服务条款以及甲方在该单项服务中发出的各类公告的同意。
1.4　甲方会员服务协议以及各个频道单项服务条款和公告可由甲方公司更新，且无需另行通知。您在使用相关服务时,应关注并遵守其所适用的相关条款。
您在使用甲方提供的各项服务之前，应仔细阅读本服务协议。如您不同意本服务协议及/或随时对其的修改，您可以主动取消甲方提供的服务；您一旦使用甲方服务，即视为您已了解并完全同意本服务协议各项内容，包括甲方对服务协议所做的任何修改，并成为甲方乙方。
二、注册信息和隐私保护
2.1　甲方帐号（即甲方乙方ID）的所有权归甲方，乙方完成注册申请手续后，获得甲方帐号的使用权。乙方应提供及时、详尽及准确的个人资料，并不断更新注册资料，符合及时、详尽准确的要求。所有原始键入的资料将引用为注册资料。如果因注册信息不真实而引起的问题，并对问题发生所带来的后果，甲方不负任何责任。
2.2　乙方不应将其帐号、密码转让、出售或出借予他人使用，如果甲方发现使用者并非帐号注册者本人，甲方有权停止继续服务。如乙方发现其帐号遭他人非法使用，应立即通知甲方。因黑客行为或乙方违反本协议规定导致帐号、密码遭他人非法使用的，由乙方本人承担因此导致的损失和一切法律责任，甲方不承担任何责任。

三、使用规则
3.1　乙方在使用甲方服务时，必须遵守中华人民共和国相关法律法规的规定，乙方应同意将不会利用本服务进行任何违法或不正当的活动，包括但不限于下列行为∶
（1）下载、展示、张贴、传播或以其它方式传送含有下列内容之一的信息：
1） 反对宪法所确定的基本原则的；
2） 危害国家安全，泄露国家秘密，颠覆国家政权，破坏国家统一的；
3） 损害国家荣誉和利益的；
4） 煽动民族仇恨、民族歧视、破坏民族团结的；
5） 破坏国家宗教政策，宣扬邪教和封建迷信的；
6） 散布谣言，扰乱社会秩序，破坏社会稳定的；
7） 散布淫秽、色情、赌博、暴力、凶杀、恐怖或者教唆犯罪的；
8） 侮辱或者诽谤他人，侵害他人合法权利的；
9） 含有虚假、有害、胁迫、侵害他人隐私、骚扰、侵害、中伤、粗俗、猥亵、或其它道德上令人反感的内容；
10） 含有中国法律、法规、规章、条例以及任何具有法律效力之规范所限制或禁止的其它内容的；
（2）不得为任何非法目的而使用网络服务系统；
（3）不利用甲方服务从事以下活动：
1）未经允许，进入计算机信息网络或者使用计算机信息网络资源的；
2） 未经允许，对计算机信息网络功能进行删除、修改或者增加的；
3） 未经允许，对进入计算机信息网络中存储、处理或者传输的数据和应用程序进行删除、修改或者增加的；
4） 故意制作、传播计算机病毒等破坏性程序的；
5） 其他危害计算机信息网络安全的行为。
3.2　乙方违反本协议或相关的服务条款的规定，导致或产生的任何第三方主张的任何索赔、要求或损失，包括合理的律师费，乙方同意赔偿甲方与合作公司、关联公司，并使之免受损害。对此，甲方有权视乙方的行为性质，采取包括但不限于删除乙方发布信息内容、暂停使用许可、终止服务、限制使用、回收甲方帐号、追究法律责任等措施。对恶意注册甲方帐号或利用甲方帐号进行违法活动、捣乱、骚扰、欺骗、其他乙方以及其他违反本协议的行为，甲方有权回收其帐号。同时，甲方公司会视司法部门的要求，协助调查。
3.3　乙方不得对本服务任何部分或本服务之使用或获得，进行复制、拷贝、出售、转售或用于任何其它商业目的。
3.4　乙方须对自己在使用甲方服务过程中的行为承担法律责任。乙方承担法律责任的形式包括但不限于：对受到侵害者进行赔偿，以及在甲方公司首先承担了因乙方行为导致的行政处罚或侵权损害赔偿责任后，乙方应给予甲方公司等额的赔偿。
四、服务内容
4.1　甲方网络服务的具体内容由甲方根据实际情况提供。
4.2　除非本服务协议另有其它明示规定，甲方所推出的新产品、新功能、新服务，均受到本服务协议之规范。
4.3　为使用本服务，您必须能够自行经有法律资格对您提供互联网接入服务的第三方，进入国际互联网，并应自行支付相关服务费用。此外，您必须自行配备及负责与国际联网连线所需之一切必要装备，包括计算机、数据机或其它存取装置。
4.4　鉴于网络服务的特殊性，乙方同意甲方有权不经事先通知，变更、中断或终止部分或全部的网络服务（包括收费网络服务）。甲方不担保网络服务不会中断，对网络服务的及时性、安全性、准确性也都不作担保。
4.5　甲方需要定期或不定期地对提供网络服务的平台或相关的设备进行检修或者维护，如因此类情况而造成网络服务（包括收费网络服务）在合理时间内的中断，甲方无需为此承担任何责任。甲方保留不经事先通知为维修保养、升级或其它目的暂停本服务任何部分的权利。
4.6 本服务或第三人可提供与其它国际互联网上之网站或资源之链接。由于甲方无法控制这些网站及资源，您了解并同意，此类网站或资源是否可供利用，甲方不予负责，存在或源于此类网站或资源之任何内容、广告、产品或其它资料，甲方亦不予保证或负责。因使用或依赖任何此类网站或资源发布的或经由此类网站或资源获得的任何内容、商品或服务所产生的任何损害或损失，甲方不承担任何责任。
4.7 乙方明确同意其使用甲方网络服务所存在的风险将完全由其自己承担。乙方理解并接受下载或通过甲方服务取得的任何信息资料取决于乙方自己，并由其承担系统受损、资料丢失以及其它任何风险。甲方对在服务网上得到的任何商品购物服务、交易进程、招聘信息，都不作担保。
4.8 乙方须知：甲方提供的各种挖掘推送服务中（包括甲方新首页的导航网址推送），推送给乙方曾经访问过的网站或资源之链接是基于机器算法自动推出，甲方不对其内容的有效性、安全性、合法性等做任何担保。
4.9　6个月未登陆的帐号，甲方保留关闭的权利。
4.10　甲方有权于任何时间暂时或永久修改或终止本服务（或其任何部分），而无论其通知与否，甲方对乙方和任何第三人均无需承担任何责任。
4.11　终止服务
您同意甲方得基于其自行之考虑，因任何理由，包含但不限于长时间未使用，或甲方认为您已经违反本服务协议的文字及精神，终止您的密码、帐号或本服务之使用（或服务之任何部分），并将您在本服务内任何内容加以移除并删除。您同意依本服务协议任何规定提供之本服务，无需进行事先通知即可中断或终止，您承认并同意，甲方可立即关闭或删除您的帐号及您帐号中所有相关信息及文件，及/或禁止继续使用前述文件或本服务。此外，您同意若本服务之使用被中断或终止或您的帐号及相关信息和文件被关闭或删除，甲方对您或任何第三人均不承担任何责任。
五、知识产权和其他合法权益（包括但不限于名誉权、商誉权）
5.1　乙方专属权利
甲方尊重他人知识产权和合法权益，呼吁乙方也要同样尊重知识产权和他人合法权益。若您认为您的知识产权或其他合法权益被侵犯，请按照以下说明向甲方提供资料∶
请注意：如果权利通知的陈述失实，权利通知提交者将承担对由此造成的全部法律责任（包括但不限于赔偿各种费用及律师费）。如果上述个人或单位不确定网络上可获取的资料是否侵犯了其知识产权和其他合法权益，甲方建议该个人或单位首先咨询专业人士。
为了甲方有效处理上述个人或单位的权利通知，请使用以下格式（包括各条款的序号）：
1. 权利人对涉嫌侵权内容拥有知识产权或其他合法权益和/或依法可以行使知识产权或其他合法权益的权属证明；
2. 请充分、明确地描述被侵犯了知识产权或其他合法权益的情况并请提供涉嫌侵权的第三方网址（如果有）。
3. 请指明涉嫌侵权网页的哪些内容侵犯了第2项中列明的权利。
4. 请提供权利人具体的联络信息，包括姓名、身份证或护照复印件（对自然人）、单位登记证明复印件（对单位）、通信地址、电话号码、传真和电子邮件。
5. 请提供涉嫌侵权内容在信息网络上的位置（如指明您举报的含有侵权内容的出处，即：指网页地址或网页内的位置）以便我们与您举报的含有侵权内容的网页的所有权人/管理人联系。
6. 请在权利通知中加入如下关于通知内容真实性的声明： “我保证，本通知中所述信息是充分、真实、准确的，如果本权利通知内容不完全属实，本人将承担由此产生的一切法律责任。”
7. 请您签署该文件，如果您是依法成立的机构或组织，请您加盖公章。
5.2　对于乙方通过甲方服务（包括但不限于贴吧、知道、MP3、影视等）上传到甲方网站上可公开获取区域的任何内容，乙方同意甲方在全世界范围内具有免费的、永久性的、不可撤销的、非独家的和完全再许可的权利和许可，以使用、复制、修改、改编、出版、翻译、据以创作衍生作品、传播、表演和展示此等内容（整体或部分），和/或将此等内容编入当前已知的或以后开发的其他任何形式的作品、媒体或技术中。
5.3　甲方拥有本网站内所有资料的版权。任何被授权的浏览、复制、打印和传播属于本网站内的资料必须符合以下条件：
（1） 所有的资料和图象均以获得信息为目的；
（2） 所有的资料和图象均不得用于商业目的；
（3） 所有的资料、图象及其任何部分都必须包括此版权声明；
（4） 本网站（www.qiweiwangguo.com）所有的产品、技术与所有程序均属于甲方知识产权，在此并未授权。
（5） “王冠”, “甲方”及相关图形等为甲方的注册商标。
（6） 未经甲方许可，任何人不得擅自（包括但不限于：以非法的方式复制、传播、展示、镜像、上载、下载）使用。否则，甲方将依法追究法律责任。
六、青少年乙方特别提示
青少年乙方必须遵守全国青少年网络文明公约：
要善于网上学习，不浏览不良信息；要诚实友好交流，不侮辱欺诈他人；要增强自护意识，不随意约会网友；要维护网络安全，不破坏网络秩序；要有益身心健康，不沉溺虚拟时空。
七、其他
7.1　本协议的订立、执行和解释及争议的解决均应适用中华人民共和国法律。
7.2　如双方就本协议内容或其执行发生任何争议，双方应尽量友好协商解决；协商不成时，任何一方均可向甲方所在地的人民法院提起诉讼。
7.3　甲方未行使或执行本服务协议任何权利或规定，不构成对前述权利或权利之放弃。
7.4　如本协议中的任何条款无论因何种原因完全或部分无效或不具有执行力，本协议的其余条款仍应有效并且有约束力。
          
          
          
          
              </p>
        
      </div>    
      </div>
      </div>
    </div>
    
@stop
