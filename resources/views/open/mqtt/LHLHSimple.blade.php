
<!DOCTYPE html>
<html>
  <head>
    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <title>气味王国 | 设备控制</title>
    <!-- Tell the browser to be responsive to screen width -->
    <meta content="width=device-width, initial-scale=1, maximum-scale=1, user-scalable=no" name="viewport">
    <!-- Bootstrap 3.3.5 -->
    <link rel="stylesheet" href="/bootstrap/css/bootstrap.min.css">
    <!-- Font Awesome -->
    <link rel="stylesheet" href="/dist/css/font-awesome.min.css">
    <!-- Ionicons -->
    <link rel="stylesheet" href="/dist/css/ionicons.min.css">
    <!-- Theme style -->
    <link rel="stylesheet" href="/dist/css/AdminLTE.min.css">
    <!-- AdminLTE Skins. Choose a skin from the css/skins
         folder instead of downloading all of them to reduce the load. -->
    <link rel="stylesheet" href="/dist/css/skins/_all-skins.min.css">
    <!-- HTML5 Shim and Respond.js IE8 support of HTML5 elements and media queries -->
    <!-- WARNING: Respond.js doesn't work if you view the page via file:// -->
    <!--[if lt IE 9]>
        <script src="../../dist/js/html5shiv.min.js"></script>
        <script src="../../dist/js/respond.min.js"></script>
    <![endif]-->
    <style >
    .back-class{
      float: left;
	    background-color: transparent;
	    background-image: none;
	    padding: 15px 15px;
	    font-family: fontAwesome;
	    text-decoration: none;
	    box-sizing: border-box;
	    color: #fff;
    }
    a:hover {
		    outline: none;
		    text-decoration: none;
		    color: #fff;
		}
     </style>
</head>
  <body class="hold-transition skin-blue sidebar-mini">
    <div class="wrapper" id="formDemo">

      <header class="main-header">
        <!-- Logo -->
<!--         <a href="../../index2.html" class="logo"> -->
<!--           mini logo for sidebar mini 50x50 pixels -->
<!--           <span class="logo-mini"><b>A</b>LT</span> -->
<!--           logo for regular state and mobile devices -->
<!--           <span class="logo-lg"><b>Admin</b>LTE</span> -->
<!--         </a> -->
        <!-- Header Navbar: style can be found in header.less -->
        <nav class="navbar navbar-static-top" role="navigation">
        
          <a class="back-class" role="button" @click="backForword">
           <i class="fa fa-chevron-left"></i>
          </a>
<!--           <div class="logo" style="background-color: initial;width:initial;"> -->
<!-- 	          <span class="logo-lg" v-html="pageConfig.runtime.topTitle"></span> -->
<!-- 	        </div> -->
          <!-- Sidebar toggle button-->
          <div class="navbar-custom-menu ">
            <ul class="nav navbar-nav">
            
              <li class="dropdown messages-menu" >
                <a href="javascript:;" class="dropdown-toggle" >
                  <span class="logo-lg" style="font-size:20px;" v-html="pageConfig.runtime.topTitle"></span>
                </a>
              </li>
              
              <!-- Tasks: style can be found in dropdown.less -->
              <li class="dropdown tasks-menu hide">
                <a href="#" class="dropdown-toggle" data-toggle="dropdown">
                  <i class="fa fa-align-justify"></i>
<!--                   <span class="label label-danger">9</span> -->
                </a>
                <ul class="dropdown-menu">
                  <li class="header">载入配置</li>
                  <li>
                    <!-- inner menu: contains the actual data -->
                    <ul class="menu">
                      <li><!-- Task item -->
                        <a href="#">
                          <h3>配置1
                            <span class="pull-right">2017-03-19 11:00:11</span>
                          </h3>
                        </a>
                      </li><!-- end task item -->
                    </ul>
                  </li>
<!--                   <li class="footer"> -->
<!--                     <a href="#">View all tasks</a> -->
<!--                   </li> -->
                </ul>
              </li>
              
            </ul>
          </div>
        </nav>
      </header>

      <!-- Content Wrapper. Contains page content -->
      <div class="content-wrapper">
        <!-- Content Header (Page header) -->
        <section class="content-header">
<!--           <h1> -->
<!--             Simple Tables -->
<!--             <small>preview of simple tables</small> -->
<!--           </h1> -->
<!--           <ol class="breadcrumb"> -->
<!--             <li><a href="#"><i class="fa fa-dashboard"></i> Home</a></li> -->
<!--             <li><a href="#">Tables</a></li> -->
<!--             <li class="active">Simple</li> -->
<!--           </ol> -->
        </section>

        <!-- Main content -->
        <section class="content" >
          <div class="row">
            <div class="col-md-6">
              <horizontal-form v-show="isShowWindow('connect_form')" :data-selector="pageConfig.connect_form" @formvalidate="connectServer" @formnewenv="changeWindow('new_env_form')" @formreset="cacheClear" ></horizontal-form>
              
              <horizontal-form v-show="isShowWindow('new_env_form')" :data-selector="pageConfig.new_env_form"  @formvalidate="AddNewEnv" @formback="changeWindow('connect_form')"></horizontal-form>
              
               <device-list  v-show="isShowWindow('device_list')"  :data-selector="pageConfig.device_list" @connectdevice="ConnectDevice" @reflushdevice="reflushDevice"></device-list>
              
            </div><!-- /.col -->
             

            <div class="col-md-6">
<div class="box box-primary">
  <div class="box-header with-border">
    <h3 class="box-title">气味列表</h3>
  </div><!-- /.box-header -->
</div>
<div  class="box hide1" id="smell-list">

  @foreach(range(1,4) as $k => $v)
  <div class="box-body" style="padding-top: 0;min-height:105px;">
    <ul class="products-list product-list-in-box" id="ul-0{{$k + 1}}">
      <li class="item" style="padding: 0;" >
        <div style="padding:5px;">
          <span class="product-title">第 0{{$k + 1}} 号气味 </span>
          <div class="pull-right" >
              <button class="btn btn-info btn-flat play-smell" data-id="0{{$k + 1}}">播放</button>
              <button class="btn btn-warning btn-flat stop-play" data-id="0{{$k + 1}}">停止</button>
          </div>
          <hr style="margin-bottom: 10px;"/>
          <div class="progress progress-xs active hide">
            <div class="progress-bar progress-bar-success progress-bar-striped" role="progressbar" aria-valuenow="50" aria-valuemin="0" aria-valuemax="100" :style="item.progress.style" st1yle="width: 80%">
              <span class="sr-only">20% Complete</span>
            </div>
          </div>
          <div style="display: -webkit-box;display: inline-flex;">
              <div style="padding-top: 6px;padding-right: 10px;">播放时长</div>
              <div class="input-group" style="width: 133px;">
                <span class="input-group-addon reduce-increase-time" data-step="-1" >-</span>
                <input type="text" name="playTime" value="10" class="form-control" style="text-align: right;">
                <span class="input-group-addon reduce-increase-time" data-step="1" >+</span>
              </div>
              <div style="padding-top: 6px;padding-left: 10px;">秒</div>
          </div>
        </div>
      </li><!-- /.item -->
    </ul>
  </div><!-- /.box-body -->
  @endforeach
</div>
</div><!-- /.col -->


            
          </div><!-- /.row -->
          
        </section><!-- /.content -->
      </div><!-- /.content-wrapper -->
      <footer class="main-footer">
<!--         <div class="pull-right hidden-xs"> -->
<!--           <b>Version</b> 2.3.0 -->
<!--         </div> -->
<!--         <strong>Copyright &copy; 2014-2015 <a href="http://almsaeedstudio.com">Almsaeed Studio</a>.</strong> All rights reserved. -->
      </footer>

      
      <!-- Add the sidebar's background. This div must be placed
           immediately after the control sidebar -->
      <div class="control-sidebar-bg"></div>
    </div><!-- ./wrapper -->
    <script src="/js/require.js"></script>
    <script>
require.config({
  urlArgs: "v=1",//"bust=" +  (new Date()).getTime(),
  paths : {
      'SDK' : '/demo/dist/OpenSDK-v1.0.0.min',
      'jQuery' : '/plugins/jQuery/jQuery-2.1.4.min', 
      'bootstrap' : '/bootstrap/js/bootstrap.min',
      'jQuery.slimscroll' : '/plugins/slimScroll/jquery.slimscroll.min',
      'fastclick' : '/plugins/fastclick/fastclick.min',
      'ALTApp' : '/dist/js/app.min',
      'Utilsc' : '/dist/js/Utils',
  },
  shim : {
      'jQuery' : {
          exports : '$'
      },
      'jQuery.slimscroll' : ['jQuery'],
      'bootstrap' : ['jQuery'],
      'ALTApp' : ['jQuery','jQuery.slimscroll','bootstrap','fastclick'],
  }
});
define('initialize',[
  'jQuery',
  'ALTApp','Utilsc'],function($) {
});
require(['initialize','SDK'], function() {
  var $ = require('jQuery'),
      SDK  = require('SDK'),
      Utils = require('Utilsc');


//   var payloadArray = new Uint8Array([1,2,3,4,5,6,7]);
//   alert((Array(payloadArray)).slice(0, 2));
  
  
//   window.onerror=function(){
//       var error = {};
//       for(var i in arguments){
//           error[i] = arguments[i];
//       }
//       $.ajax({
//          'url' : '/api/mqtt/jsErrorReport',
//          'type' : 'post',
//          'method' : 'post',
//          'data' : {
//              'error' : error
//          },
//       });
//       return true;
//   }
//   console.oldLog = console.log;
//   console.log = function() {
//       console.oldLog.apply(console, arguments);
//       $.ajax({
//           'url' : '/api/mqtt/jsErrorReport',
//           'type' : 'post',
//           'method' : 'post',
//           'data' : {
//               'error' : arguments
//           },
//        });
//     } 
  
  var isInited = false;
  var initPageEvent = function(instance){

      if(isInited) return;
      isInited = true;
      
      $('.play-smell').click(function(){
          var smellID = $(this).data('id');
          var playSecond = $('#ul-' + smellID).find('input[name=playTime]').eq(0).val();
          playSecond = parseInt(playSecond);
          if(playSecond <= 1){
              playSecond = 1;
              $('#ul-' + smellID).find('input[name=playTime]').eq(0).val(playSecond);
          }
          if(playSecond >= 199){
              playSecond = 199;
              $('#ul-' + smellID).find('input[name=playTime]').eq(0).val(playSecond);
          }
          instance.playSmell({
              'actions' : [ {
                  'bottle' : smellID + '',
                  'duration' : playSecond,
                  'power' : 5
              }]
          },function(sequence,decode,app){
              if(app.protoRoot.SrErrorCode.SEC_SUCCESS == decode.code){
              }else if(app.protoRoot.SrErrorCode.SEC_ACCEPT == decode.code){       
              }else {
                  app.logger.error(decode.msg);
              }
          },function(sequence,error,app){
              app.logger.error(error,app);
          });
      });
      $('.stop-play').click(function(){
          var smellID = $(this).data('id');
          instance.stopPlay([smellID],function(sequence,decode,app){
              if(app.protoRoot.SrErrorCode.SEC_SUCCESS == decode.code){
              }else if(app.protoRoot.SrErrorCode.SEC_ACCEPT == decode.code){       
              }else {
                  app.logger.error(decode.msg);
              }
          },function(sequence,error,app){
              app.logger.error(error,app);
          });
      });
      $('.reduce-increase-time').click(function(){
          var step = $(this).data('step');
          step = parseInt(step);
          var timeInput = $(this).parent().find('input').eq(0);
          var curVal = timeInput.val();
          curVal = parseInt(curVal);
          if(!curVal) curVal = 0;
          curVal += step;
          if(curVal <= 1) curVal = 1;
          if(curVal >= 199) curVal = 199;
          timeInput.val(curVal);
      });
  }
  var instance = null;
  var deviceAccess = 'LHLHUE4001';
  deviceAccess = 'aaaaaaaaaa';
  SDK.connect({
      'accessKey' : 'QHfsbRfqEakwqFEOQI9V',
      'accessSecret' : 'UXLAS0qQWmgpTCMV01FV',
      'logLevel' : 'error',
      'mqtt' : {
          'basepath' : 'http://open.qiweiwangguo.com/',
          'hostname' : '120.26.109.169', // 正式
          'port' : '8083'
      },
      'apiTimeout' : 10,
      'event' : {
          'onSuccess' : function(){
              console.log('Server Connect Success');
              instance = SDK.usingDevice(deviceAccess,{
                  'onSuccess' : function(app){
                      console.log('usingDevice '+deviceAccess+' onSuccess');
                  },
                  'onFailure' : function(app){
                      alert('设备连接失败');
                  }
              });
              initPageEvent(instance);
          },
          'onFailure' :function(){
              alert('连接失败');
          },
          'onConnectionLost' : function(){
              console.log('失去连接',arguments);
              alert('失去连接');
          }
      }
  });
})
    </script>
  </body>
</html>
