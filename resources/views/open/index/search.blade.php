<!DOCTYPE html>
<html lang="zh-cn">
	<head>
		<meta http-equiv="Content-Type" content="text/html; charset=UTF-8">
		<title>气味王国-开放平台</title>
		<link rel="stylesheet" href="/css/main.css" />
    <script src="/js/jquery-1.9.1.min.js"></script>
    <script type="text/javascript" src="/js/main.js"></script>
    <link rel="stylesheet" href="/css/nanoscroller.css" />
    <script type="text/javascript" src="/js/jquery.nanoscroller.js" ></script>
    <link rel="shortcut icon" href="{{asset('images/favicon.ico') }}"/>
	</head>
	<body>
	  <script>

	  var searchClick = function(){
		    var word = document.getElementById('keyword');
			window.location.href="{{route('search')}}" + "?keyword=" + word.value;
		}
	  var keydown = function(e){
			console.log(e.keyCode );
			if(e.keyCode  == 13){
				searchClick();
		    }
		}  
	    $(function(){
	      
	      
	      $('.sub').click(function() {
	        var length = window.location.href.indexOf('?') === -1 ? window.location.href.length : window.location.href.indexOf('?') + 1;
	        var href = window.location.href.substr(0, length) + 'keyword=' + $('.key').val();
	        // console.log(href);
	        window.location.href = href;
	      });
	      $('.line > ul').on('mouseover', 'li', function(){
          $(this).find('.title').css('display', 'block');
        });
        $('.line > ul').on('mouseout', 'li', function(){
          $(this).find('.title').css('display', 'none');
        });
//      $('.line > ul').on('click', 'li.liBlock', function(){
//        $('body').css('overflow', 'hidden');
//        $('.Shady').css('display', 'block');
//        $('.Shady-smellSearch').css('display', 'block');
//        $(".nano").nanoScroller({alwaysVisible: true});
//      });
        $('ul').on('click', 'li.tags', function(e) {
          var length = window.location.href.indexOf('?') === -1 ? window.location.href.length : window.location.href.indexOf('?') + 1;
          var href = window.location.href.substr(0, length) + 'keyword=' + $(this).html();
          // console.log(href);
          window.location.href = href;
          e.stopPropagation(); //防止冒泡
          return false;
        });
        $('.more-k .more').on('click', function(e) {
          page = page + 1;
          getValue();
        });
        
	      function getQueryString(name) {  // 取URL参数
          const Url = window.location.href.substr(window.location.href.indexOf('?') + 1);
          console.log(window.location.href);
          const reg = new RegExp('(^|&)'.concat(name).concat('=([^&]*)(&|$)'));
          const r = Url.substr(Url.search(reg)).match(reg);
          console.log(r);
          if (r !== null) {
            // return unescape(r[2]);
            return decodeURI(r[2]);
          }
          return '';
        }
	      var key = getQueryString('keyword');
	      var page = 1;
	      var pageN = 8;
	      var heightArr = [0 ,0 ,0 ,0];
	      var heightArrLength = [1 ,1 ,1 ,1];
	      var newline = 0;
	      var imgLength = 0;
	      var index = 0;
	      function getValue() {
	        $.ajax({
            url: "http://api.qiweiwangguo.com/open/v1/smell",    //请求的url地址
            dataType: "json",   //返回格式为json
  //        headers: {
  //          "Content-Type": "text/plain",
  //          // "Content-Encoding": "GBK"
  //        },
            contentType:"application/x-www-form-urlencoded; charset=utf-8", 
            async: true, //请求是否异步，默认为异步，这也是ajax重要特性
            data: { keyword: key, p: page, n: pageN },    //参数值
            type: "GET",   //请求方式
            beforeSend: function() {
                //请求前的处理
            },
            success: function(req) {
              console.log(req);
              if (req.data.length === 0) {
                return;
              }
              //请求成功时处理
              var li = '';
              for (var i = 0; i < req.data.length; i++) {
                var obj = req.data[i];
                var str = '';
                if (obj.tags !== undefined) {
                  for (var j = 0; j < obj.tags.length; j++) {
                    str = str + '<li class="tags">' + obj.tags[j] + '</li>';
                  }
                }
                var img = '<img style=";display:block;margin:0px auto;" src=' + 'http://api.qiweiwangguo.com/' +obj.thumb + '>'
                if (!obj.thumb) {
                  img = '<img style="height:'+obj.height+';display:block;margin:0px auto;" src="/img/default.png">';
                }
                var span = '<span class="title">'
                + '<div class="title-k">'
                + '<div class="label">'
                + '<span>标签</span>'
                + '</div>'
                + '</div>'
                + '<div class="content">'
                + '<ul>'
                + str
                +'</ul>'
                +'</div>'
                + '</span>';
                
                if (str === '') {
                  span = '';
                }
                
                var styleS = 'style="border-radius: 10px;border: 1px solid #d2d2d2;overflow: hidden;';
                if (parseInt(imgLength / 4) !== newline) {
                  newline++;
                }
                var indexArr = [56,342,628,914];
                if (newline === 0) {
                  heightArr[i % 4] = heightArr[i % 4] + (obj.height + 50);
                  styleS+= 'left:' + indexArr[i % 4] + 'px;top:' + 40 + 'px;';
                } else {
                  index = 0;
                  var tempHeightArr = heightArr.concat();
                  tempHeightArr[0] += heightArrLength[0] * 40;
                  tempHeightArr[1] += heightArrLength[1] * 40;
                  tempHeightArr[2] += heightArrLength[2] * 40;
                  tempHeightArr[3] += heightArrLength[3] * 40;
                  var tempIndex = tempHeightArr[0];
                  for (var j = 0; j < tempHeightArr.length;j++) {
                    if (tempIndex > tempHeightArr[j]) {
                      tempIndex = tempHeightArr[j];
                      index = j;
                    }
                  }
                  console.log(index + 1);
                  console.log(heightArr);
                  heightArr[index] = heightArr[index] + (obj.height + 50);
                  styleS+= 'left:' + indexArr[index] + 'px;top:' + (heightArr[index] - (obj.height + 50) + 40 + (heightArrLength[index] * 40)) + 'px;';
                  heightArrLength[index]++;
                }
                var dom = '<li class="liBlock" title="' + obj.cn_name + '" '+styleS+'" >'
                + img
                +'<div class="liBlockName">'+ obj.cn_name +'</div>'
                + span
                + '</li>'
                li = li + dom;
                imgLength++;
              }
              document.getElementById('labelKey').innerHTML = key;
              document.getElementById('labelMsg').innerHTML = req.msg;
              document.getElementById('smell-ul').innerHTML = document.getElementById('smell-ul').innerHTML + li;
              var tempHeightArr = heightArr.concat();
              tempHeightArr[0] += heightArrLength[0] * 40;
              tempHeightArr[1] += heightArrLength[1] * 40;
              tempHeightArr[2] += heightArrLength[2] * 40;
              tempHeightArr[3] += heightArrLength[3] * 40;
              tempHeightArr.sort();
              document.getElementById('smell-ul').style.height = (tempHeightArr[3] + 80) + 'px';
              flagKey = true;
              
            },
            complete: function() {
                //请求完成的处理
            },
            error: function() {
                //请求出错处理
            }
          });
	      }
	      getValue();
	      
	      var v = document.documentElement.clientHeight,
  o = $(".content-develop"),
  s = !1,
  p = $(".backtop"),
  q = $(".smell-footer"),
  w = q.offset().top,
  x = o.length === 0 ? 100 : o.offset().top,
  t = !0,
  u = !0;
  var flagKey = true;
  window.onscroll = function() {
      if (flagKey) {
        var a = q.offset().top,
        b = document.documentElement.scrollTop || document.body.scrollTop,
        c = v + b;
        w !== a ? w = a: 0,
        b > x ? u && (p.fadeIn(), u = !1) : u || (p.fadeOut(), u = !0),
        c > w ? (y = c - w + 45, p.css({
            bottom: y + "px"
        }), t = !1) : t || p.css({
            bottom: "45px"
        });
        b1 = document.documentElement.clientHeight;
        if ((b1 + b) > a) {
          page = page + 1;
          getValue();
          flagKey = false;
        }
      }  
  },
  p.bind("click",
  function() {
        return "Microsoft Internet Explorer" == navigator.appName && "8." == navigator.appVersion.match(/8./i) ? void window.scrollTo(0, 0) : void(s || $("body, html").animate({
            scrollTop: "0px"
        },
        800,
        function() {
            s = !1
        }))
    })
	      
	      
	      
	      
	      
	    });
	  </script>
	  <header class="smell-header">
      <div class="header-nav">
        <h1 class="logo" onclick="location.href='{{route('index')}}'">气味王国-开放平台</h1>
    <div class="smell-search">     
      <div>
        <input type="text" id="keyword" value="" class="key" onkeydown="return keydown(event)" placeholder="请输入要搜索的关键词">
        <button type="button"  class="sub"><span>搜索</span></button>
      </div>
    </div>
      </div>
    </header>
    <section>
      <div class="center-one">
        <div class="k">
          "<span class="label" id="labelKey"></span>"的搜索结果(<span id="labelMsg"></span>条)
        </div>
      </div>
      <div class="center-two">
        <div class="k">
           <div class="line">
             <ul id="smell-ul"></ul>
           </div>
           <!--<div class="more-k">
             <i class="more"></i>
           </div>-->
          <style>
            .center-two .k .backtop {
              margin-left: 620px;
            }
          </style>
          <div class="backtop" style="display: none; bottom: 365px;">
              <div class="btnTo"></div>
          </div>
        </div>
      </div>
    </section>
    @include('open.layouts.footer')
    <div class="Shady-smellSearch">
      <div class="content">
        <div class="k">
          <h2 class="cname">苹果</h2>
          <div class="ename">Apple</div>
          <hr class="hr"/>
          <div class="label">标签</div>
          <div class="nano" style="height: 430px;">
            <ul class="content">
              <li>水果</li>
              <li>水果</li>
              <li>水果</li>
              <li>水果</li>
              <li>水果</li>
              <li>水果</li>
              <li>苹果苹果味</li>
              <li>苹果苹果味</li>
              <li>水果</li>
              <li>水果</li>
              <li>水果</li>
              <li>水果</li>
              <li>水果</li>
              <li>水果</li>
              <li>苹果苹果味</li>
              <li>苹果苹果味</li>
              <li>水果</li>
              <li>水果</li>
              <li>水果</li>
              <li>水果</li>
              <li>水果</li>
              <li>水果</li>
              <li>苹果苹果味</li>
              <li>苹果苹果味</li><li>水果</li>
              <li>水果</li>
              <li>水果</li>
              <li>水果</li>
              <li>水果</li>
              <li>水果</li>
              <li>苹果苹果味</li>
              <li>苹果苹果味</li><li>水果</li>
              <li>水果</li>
              <li>水果</li>
              <li>水果</li>
              <li>水果</li>
              <li>水果</li>
              <li>苹果苹果味</li>
              <li>苹果苹果味</li><li>水果</li>
              <li>水果</li>
              <li>水果</li>
              <li>水果</li>
              <li>水果</li>
              <li>水果</li>
              <li>苹果苹果味</li>
              <li>苹果苹果味</li><li>水果</li>
              <li>水果</li>
              <li>水果</li>
              <li>水果</li>
              <li>水果</li>
              <li>水果</li>
              <li>苹果苹果味</li>
              <li>苹果苹果味</li><li>水果</li>
              <li>水果</li>
              <li>水果</li>
              <li>水果</li>
              <li>水果</li>
              <li>水果</li>
              <li>苹果苹果味</li>
              <li>苹果苹果味</li><li>水果</li>
              <li>水果</li>
              <li>水果</li>
              <li>水果</li>
              <li>水果</li>
              <li>水果</li>
              <li>苹果苹果味</li>
              <li>苹果苹果味</li><li>水果</li>
              <li>水果</li>
              <li>水果</li>
              <li>水果</li>
              <li>水果</li>
              <li>水果</li>
              <li>苹果苹果味</li>
              <li>苹果苹果味</li><li>水果</li>
              <li>水果</li>
              <li>水果</li>
              <li>水果</li>
              <li>水果</li>
              <li>水果</li>
              <li>苹果苹果味</li>
              <li>苹果苹果味</li><li>水果</li>
              <li>水果</li>
              <li>水果</li>
              <li>水果</li>
              <li>水果</li>
              <li>水果</li>
              <li>苹果苹果味</li>
              <li>苹果苹果味</li><li>水果</li>
              <li>水果</li>
              <li>水果</li>
              <li>水果</li>
              <li>水果</li>
              <li>水果</li>
              <li>苹果苹果味</li>
              <li>苹果苹果味</li><li>水果</li>
              <li>水果</li>
              <li>水果</li>
              <li>水果</li>
              <li>水果</li>
              <li>水果</li>
              <li>苹果苹果味</li>
              <li>苹果苹果味</li><li>水果</li>
              <li>水果</li>
              <li>水果</li>
              <li>水果</li>
              <li>水果</li>
              <li>水果</li>
              <li>苹果苹果味</li>
              <li>苹果苹果味</li><li>水果</li>
              <li>水果</li>
              <li>水果</li>
              <li>水果</li>
              <li>水果</li>
              <li>水果</li>
              <li>苹果苹果味</li>
              <li>苹果苹果味</li><li>水果</li>
              <li>水果</li>
              <li>水果</li>
              <li>水果</li>
              <li>水果</li>
              <li>水果</li>
              <li>苹果苹果味</li>
              <li>苹果苹果味</li><li>水果</li>
              <li>水果</li>
              <li>水果</li>
              <li>水果</li>
              <li>水果</li>
              <li>水果</li>
              <li>苹果苹果味</li>
              <li>苹果苹果味</li><li>水果</li>
              <li>水果</li>
              <li>水果</li>
              <li>水果</li>
              <li>水果</li>
              <li>水果</li>
              <li>苹果苹果味</li>
              <li>苹果苹果味</li><li>水果</li>
              <li>水果</li>
              <li>水果</li>
              <li>水果</li>
              <li>水果</li>
              <li>水果</li>
              <li>苹果苹果味</li>
              <li>苹果苹果味</li><li>水果</li>
              <li>水果</li>
              <li>水果</li>
              <li>水果</li>
              <li>水果</li>
              <li>水果</li>
              <li>苹果苹果味</li>
              <li>苹果苹果味</li><li>水果</li>
              <li>水果</li>
              <li>水果</li>
              <li>水果</li>
              <li>水果</li>
              <li>水果</li>
              <li>苹果苹果味</li>
              <li>苹果苹果味</li><li>水果</li>
              <li>水果</li>
              <li>水果</li>
              <li>水果</li>
              <li>水果</li>
              <li>水果</li>
              <li>苹果苹果味</li>
              <li>苹果苹果味</li><li>水果</li>
              <li>水果</li>
              <li>水果</li>
              <li>水果</li>
              <li>水果</li>
              <li>水果</li>
              <li>苹果苹果味</li>
              <li>苹果苹果味</li>
              <div style="clear: both;">&nbsp;</div>
            </ul>
          </div>
           
        </div>
        <div class="v">
          <img class="smellClear" src="../img/clear.png"/>
          <img width="600" height="600" src="../img/apple.jpg" />
        </div>
      </div>  
    </div>
    <div class="Shady"></div>
	</body>
	<script>
$(function(){
    $(".nano").nanoScroller({alwaysVisible: true});
});
	</script>
</html>
