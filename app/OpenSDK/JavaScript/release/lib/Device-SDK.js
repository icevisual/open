require.config({
	baseUrl : '../lib',
	paths : {
		'bytebuffer' : 'Third/bytebuffer.min',
		'long' : 'Third/long.min',
		'Paho' : 'Third/mqttws31',
		'protobuf' : 'Third/protobuf.min',
		'sjcl' : 'Third/sjcl',
		'cryptojs.core' : "CryptoJS/components/core",
		'cryptojs.pad-zeropadding' : 'CryptoJS/components/pad-zeropadding-min',
		'cryptojs.md5' : "CryptoJS/rollups/md5",
		'cryptojs.aes' : 'CryptoJS/rollups/aes',
		'Utils' : 'SDK/Utils',
		'logger' : 'SDK/Logger',
		'protoDemo' : 'SDK/ProtoDemo',
		'protocolStruct' : 'SDK/ProtocolStruct',
		'Core' : 'SDK/Core',
		'CmdApi' : 'SDK/CmdApi',
		'event' : 'SDK/Event',
		'device' : 'DeviceSDK/Device',
		'msgHandler' : 'DeviceSDK/DevMsgHandler',
	},
	shim : {
		'sjcl' : {
			exports : 'sjcl'
		},
		'Paho' : {
			exports : 'Paho'
		},
		'cryptojs.core' : {
			exports : "CryptoJS"
		},
		'cryptojs.md5' : {
			deps : [ 'cryptojs.core' ],
			exports : "CryptoJS" // You can also use "CryptoJS.MD5"
		},
		'cryptojs.aes' : {
			deps : [ 'cryptojs.core' ],
			exports : "CryptoJS" 
		},
		'cryptojs.pad-zeropadding' : {
			deps : [ 'cryptojs.aes' ],
			exports : "CryptoJS"
		}
	}
});

define(['Core','device'],function(Core,Device){
    Core['Device'] = Device;
	return Core;
});