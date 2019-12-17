require.config({
	paths : {
//		'SDK' : '../build/OpenSDK.min',
		
		'SDK' : '../lib/Controller-SDK',
	},
});
var dev;
require([ 'SDK'], function(SDK) {
	SDK.connect({
		'accessKey' : 'BwFSfU0uMxodVBcAAGYs',
		'accessSecret' : 'fMakdIbruxuLOOwFvidR',
		'logLevel' : 'debug',
		'mqtt' : {
	        'basepath' : 'http://test.open.qiweiwangguo.com/',
	        'hostname' : '121.41.33.141', // 测试
	        'port' : '8083',
        },
	});
//	'mqtt' : {
//        'basepath' : 'http://open.smell.com/',
//        'hostname' : '192.168.5.61', // 测试
//        'port' : '8083',
//    },
//	'test' : {
//        'basepath' : 'http://test.open.qiweiwangguo.com/',
//        'hostname' : '121.41.33.141', // 测试
//        'port' : '8083',
//    },
	
	dev = SDK.usingDevice('aaaaaaaaaa','aaaaaaaaaa');
	dev.deviceModelReport(function(e){
	    console.log(e);
	});
//	dev.setControlAttr([{
//        'identity' : 2, // 控件 ID
//        'attr' : 'value', // 控件属性名称
//        'value' : '12' // 设置的值
//    },{
//        'identity' : 3, // 控件 ID
//        'attr' : 'angle', // 控件属性名称
//        'value' : '2332' // 设置的值
//    },{
//        'identity' : 4, // 控件 ID
//        'attr' : 'status', // 控件属性名称
//        'value' : '-1' // 设置的值
//    },{
//        'identity' : 3, // 控件 ID
//        'attr' : 'value', // 控件属性名称
//        'value' : '123123' // 设置的值
//    },{
//        'identity' : 4, // 控件 ID
//        'attr' : 'name', // 控件属性名称
//        'value' : 'adsfdfss' // 设置的值
//    }],function(){
//		console.log(arguments);
//	},function(){
//		console.log(arguments);
//	});
	return SDK;
})
