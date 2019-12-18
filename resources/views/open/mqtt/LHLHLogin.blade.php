
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
        
          <a class="back-class" role="button" >
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
                  <span class="logo-lg" style="font-size:20px;" >联合利华登录</span>
                </a>
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
              
              <horizontal-form :data-selector="pageConfig.auth_form" ></horizontal-form>
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
        urlArgs: "bust=" +  (new Date()).getTime(),
        paths : {
//            'SDK' : '../lib/Controller-SDK',
            'SDK' : '/demo/dist/OpenSDK-v1.0.0.min',
            'Vue' : '../../js/vue.min',
            'jQuery' : '/plugins/jQuery/jQuery-2.1.4.min', 
            'bootstrap' : '/bootstrap/js/bootstrap.min',
            'jQuery.slimscroll' : '/plugins/slimScroll/jquery.slimscroll.min',
            'fastclick' : '/plugins/fastclick/fastclick.min',
            'ALTApp' : '/dist/js/app.min',
            
            'HorizontalForm' : '/dist/js/Components/HorizontalForm',
            'CommonTableList' : '/dist/js/Components/CommonTableList',
            'SmellPlayList' : '/dist/js/Components/SmellPlayList',
            'DeviceList' : '/dist/js/Components/DeviceList',
            
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
        'Vue','jQuery',
        'HorizontalForm','CommonTableList','SmellPlayList','DeviceList',
        'ALTApp','Utilsc'],function(Vue,$) {
        var EVue = Vue.extend({
            'methods' : {
                'removeTableRow' : function(key,row){
                    this.pageConfig[key].data.list.splice(row,1);
                },
                'isMarked' : function(runtimeKey,key){
                    if(undefined === key){
                        return (undefined !== this.runtime[runtimeKey] && false !== this.runtime[runtimeKey]);
                    }
                    return (undefined !== this.runtime[runtimeKey][key] && false !== this.runtime[runtimeKey][key]);
                },
                'markWithKey' : function(runtimeKey,key,data){
                    if(undefined === data){
                        this.runtime[runtimeKey][key] = true;
                    }else{
                        this.runtime[runtimeKey][key] = data;
                    }
                },
                'mark' : function(runtimeKey,data){
                    if(undefined === data){
                        this.runtime[runtimeKey] = true;
                    }else{
                        this.runtime[runtimeKey] = data;
                    }
                },
                'getMarkedData' : function(runtimeKey,key){
                    if(undefined === key){
                        return this.runtime[runtimeKey];
                    }
                    return this.runtime[runtimeKey][key];
                },
                'unmark' : function(runtimeKey,key){
                    if(undefined === key){
                        return this.runtime[runtimeKey] = false;
                    }
                    this.runtime[runtimeKey][key] = false;
                }, 
                'formReset' : function(formTag){
                    var fields = this.pageConfig[formTag].fields;
                    for(var i in fields){
                        if(undefined !== fields[i]['default']){
                            fields[i].value = fields[i]['default'];
                        }else{
                            fields[i].value = '';
                        }
                    }
                },
                'formFieldReset' : function(formTag,field,defaultValue){
                    if(undefined === this.pageConfig[formTag].fields[field]){
                        return false;
                    }
                    var defaultVal = this.pageConfig[formTag].fields[field]['default'];
                    defaultVal = undefined === defaultValue ? defaultVal : defaultValue;
                    if(undefined === defaultVal){
                        this.pageConfig[formTag].fields[field].value = '';
                    }else{
                        this.pageConfig[formTag].fields[field].value = defaultVal;
                    }
                },
                'formSelectInit' : function(formTag,field,data){
                    this.pageConfig[formTag].fields[field].data = data;
                },
                'appendFormSelect' : function(formTag,field,data){
                    if(data instanceof Array ){
                        for(var i in data){
                            this.pageConfig[formTag].fields[field].data.push(data[i]); 
                        }
                    }else{
                        this.pageConfig[formTag].fields[field].data.push(data);
                    }
                },
                'appendTableData' : function(tableTag,data){
                    this.pageConfig[tableTag].data.list.push(data);
                },
                'getTableData' : function(tableTag,row){
                    if(undefined === row){
                        return this.pageConfig[tableTag].data.list;
                    }
                    return this.pageConfig[tableTag].data.list[row];
                },
                'updateTableRow' : function(tableTag,row,data){
                    for(var i in data){
                        this.pageConfig[tableTag].data.list[row][i] = data[i];
                    }
                }
            }
        });
        return EVue;
    });
    require(['initialize'], function(EVue) {
        var $ = require('jQuery'),
            Vue = require('Vue'),
            Utils = require('Utilsc');

        var pageConfig = {
            "auth_form": {
                "attrs": {
                    "caption": "登录",
                    "formColor": "box-info",
                    "buttons": [
                        {
                            "name": "登录",
                            "class": "btn-info",
                            "event": "submit"
                        }
                    ],
                    "action": {
                        "uri": "/LHLH/login",
                        "method": "POST",
//                         "isAjax" : false,
                        "success": {
                            "redirect": "/LHLH/controller"
                        }
                    }
                },
                "fields": {
                    "username": {
                        "name": "用户名",
                        "type": "input",
                        "attrs": {
                            "type": "text",
                            "placeholder": "用户名"
                        },
                        "value": ""
                    },
                    "password": {
                        "name": "密码",
                        "type": "input",
                        "attrs": {
                            "type": "password",
                            "placeholder": "密码"
                        },
                        "value": ""
                    },
                    "_token": {
                        "name": "",
                        "type": "input",
                        "attrs": {
                            "type": "hidden",
                            "placeholder": ""
                        },
                        "value": "{{csrf_token()}}"
                    }
                }
            }
        };
        var vm = new EVue({
            'el' : '#formDemo',
            'data' : {
                'pageConfig' : pageConfig ,
            },
            'methods' : {
                
            }
        });
    })
    </script>
  </body>
</html>
