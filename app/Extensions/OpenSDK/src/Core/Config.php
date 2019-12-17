<?php

namespace SmellOpen\Core;

use Proto2\Scentrealm\Simple;
use Proto2\Scentrealm\Simple\SrCmdId;

class Config {
    
    /**
     * Console.color
     *
     * @var unknown
     */
    
    const COLOR_WHITE = 29;
    
    const COLOR_BLACK = 30;
    
    const COLOR_RED = 31;
    
    const COLOR_GREEN = 32;
    
    const COLOR_YELLOW = 33;
    
    const COLOR_BLUE = 34;
    
    const COLOR_LIGHT_PURPLE = 35;
    
    const COLOR_LIGHT_BLUE = 36;
    
    /**
     * Header.const
     *
     * @var unknown
     */
    const PROTO_HEADER_LENGRH = 10;
    
    const PROTO_HEADER_MAGIC_NUMBER = 0xfe;
    
    const PROTO_HEADER_VERSION = 0x01;
    
    public static $headerStruct = [
        'MAGIC_NUMBER' => 1,
        'VERSION' => 1,
        'BODY_LENGTH' => 2,
        'COMMAND_ID' => 2,
        'SEQUENCE_NUMBER' => 4,
    ];
    
    /**
     * CmdID & Class Map
     *
     * @var unknown
    */
    public static $CmdIDMap = [
        SrCmdId::SCI_req_sleep => '',
        SrCmdId::SCI_resp_sleep => Simple\BaseResponse::class,
        SrCmdId::SCI_req_wakeup => '',
        SrCmdId::SCI_resp_wakeup => Simple\BaseResponse::class,
        SrCmdId::SCI_req_usedSeconds => '',
        SrCmdId::SCI_resp_usedSeconds => Simple\UsedTimeResponse::class,
        SrCmdId::SCI_req_playSmell => Simple\PlaySmell::class,
        SrCmdId::SCI_resp_playSmell => Simple\BaseResponse::class,
        SrCmdId::SCI_req_getDevAttr => Simple\GetDevAttrsRequest::class,
        SrCmdId::SCI_resp_getDevAttr => Simple\DevAttrs::class,
        SrCmdId::SCI_req_setDevAttr => Simple\DevAttrs::class,
        SrCmdId::SCI_resp_setDevAttr => Simple\BaseResponse::class,
        SrCmdId::SCI_req_featureReport => '',
        SrCmdId::SCI_resp_featureReport => Simple\FeatureReportResponse::class,
    ];
    
}