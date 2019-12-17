require.config({
    paths : {
        'SDK' : '../lib/Device-SDK',
        'jQuery' : '../../js/jquery-1.9.1.min', 
        'Vue' : '../../js/vue.min',
    },
    shim : {
        'jQuery' : {
            exports : '$'
        },
    }
});
var vm;
var SMSDK ,dev,ret;
require([ 'jQuery','SDK','Vue' ], function($,SDK,Vue) {
    var Device = require('device');
    dev = Device;
    Device['Log'] = [];
    var formValidate = function(formSelector){
        var _form = $(formSelector);
        var fields = {
            'accessKey' : {
                'type' : 'input',
            },
            'accessSecret' : {
                'type' : 'input',
            },
            'logLevel' : {
                'type' : 'select',
            },
            'env' : {
                'type' : 'select',
            },
        };
        var ret = {};
        for(var key in fields){
            var _valField = _form.find(fields[key].type + '[name='+key+']');
            if(!_valField.val()){
                _valField.focus();
                return false;
            }
            ret[key] = _valField.val();
        }
        return ret;
    }
    var isServerConnected = false;
    var connectBox = $('.dev-init');
    var reconnectbtn = $('.dev-init-redo');
    var dashboard = $('.dev-container');
    
    var show = function(selector){
        if(typeof selector == 'string'){
            $(selector).removeClass('hide');
        }else if(selector['removeClass']){
            selector.removeClass('hide');
        }
    }
    var hide = function(selector){
        if(typeof selector == 'string'){
            $(selector).addClass('hide');
        }else if(selector['removeClass']){
            selector.addClass('hide');
        }
    }

    var initDashboard = function(){
        vm = new Vue({
            'el' : '#app',
            'data' : Device,
            'filters' : {
                switchStatus : function (value) {
                    return value == 1 ? 'ON' : 'OFF'
                }
            },
        });
        dashboard = $('.dev-container');
    }
    
    reconnectbtn.find('.reconnect-btn').click(function(){
        show(connectBox);
        hide(reconnectbtn);
        hide(dashboard);
    });
    
    reconnectbtn.find('.disconnect-btn').click(function(){
        
        if(isServerConnected){
            SDK.disconnect();
            isServerConnected = false;
        }
        
        show(connectBox);
        hide(reconnectbtn);
        hide(dashboard);
    });
    
    
    $('.dev-cnt-btn').click(function(){
        var ret = formValidate('.dev-cnt-tb');
        
        if(false === ret){
            return ;
        }
        var cnnConfig = {
            'local' : {
                'basepath' : 'http://open.smell.com/',
                'hostname' : '192.168.5.61', // 测试
                'port' : '8083',
            },
            'test' : {
                'basepath' : 'http://test.open.qiweiwangguo.com/',
                'hostname' : '121.41.33.141', // 测试
                'port' : '8083',
            },
            'production' : {
                'basepath' : 'http://open.qiweiwangguo.com/',
                'hostname' : '120.26.109.169', // 正式
                'port' : '8083',
            },
            'local-win' : {
                'basepath' : 'http://open.smell.com/',
                'hostname' : '127.0.0.1', // 正式
                'port' : '8083',
            }
        };
        SDK.connect({
            'accessKey' : ret.accessKey,
            'accessSecret' : ret.accessSecret,
            'logLevel' : ret.logLevel,
            'mqtt' : cnnConfig[ret.env],
            'clientIDWithRandom': false,
            'event' : {
                'onSuccess' : function(){
                    isServerConnected = true;
                    
                    show(reconnectbtn);
                    hide(connectBox);
                    
                    show(dashboard);
                    initDashboard();
                    
//                    alert('连接成功');
                },
                'onFailure' :function(){
                    alert('连接失败');
                }
            },
            'registerEvent' : {
                'DEV-SCI_REQ_PLAYSMELL' : function(header,decode){
                    vm.Log.unshift({
                        'date' : SDK.utils.now(),
                        'msg' : 'PLAYSMELL ' + decode.actions[0].bottle + ' ' + decode.actions[0].duration
                    });
                },
                'DEV-SCI_REQ_STOP_PLAY' : function(header,decode){
                    vm.Log.unshift({
                        'date' : SDK.utils.now(),
                        'msg' : 'STOPPLAY ' + decode.bottles[0]
                    });
                }
            }
        });
        SMSDK = SDK;
    });
})
