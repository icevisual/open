
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
              
              <div class="box box-primary">
                <div class="box-header with-border">
                  <h3 class="box-title">登录</h3>
                </div><!-- /.box-header -->
                <!-- form start -->
                <form role="form" action="{{route('post_LHLH_Simple_login')}}" method="POST">
                  <div class="box-body">
                    <div class="form-group">
                      <label for="exampleInputEmail1">用户名</label>
                      <input type="text" name="username" class="form-control" id="exampleInputEmail1" placeholder="用户名">
                    </div>
                    <div class="form-group">
                      <label for="exampleInputPassword1">密码</label>
                      <input type="password"  name="password" class="form-control" id="exampleInputPassword1" placeholder="密码">
                    </div>
                      <input type="hidden" name="_token"  value="{{csrf_token()}}">
                  </div><!-- /.box-body -->
                  <div class="box-footer">
                    <button type="submit" class="btn btn-primary">Submit</button>
                  </div>
                </form>
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
        urlArgs: "bust=" +  (new Date()).getTime(),
        paths : {
            'Vue' : '../../js/vue.min',
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
    require(['initialize'], function(EVue) {
        var $ = require('jQuery'),
            Utils = require('Utilsc');

    })
    </script>
  </body>
</html>
