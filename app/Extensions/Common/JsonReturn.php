<?php
namespace App\Extensions\Common;

use Illuminate\Database\Eloquent\Collection;

class JsonReturn
{

    /**
     * 返回成功
     * @var unknown
     */
    const STATUS_OK = 1;

    /**
     * 将json数据解析成数字、字符串、数组三种
     * @param unknown $segments
     * @return multitype:string multitype: unknown
     */
    public static function parseData($segments = [])
    {
        $data = [
            'integer' => \ErrorCode::STATUS_OK,
            'string' => 'OK',
            'array' => []
        ];
        foreach ($segments as $seg) {
            if (is_array($seg) || $seg instanceof  Collection) {
                $data['array'] = $seg;
            } else if (preg_match('/^\d+$/', $seg)) {
                $data['integer'] = $seg;
            } else {
                $data['string'] = $seg;
            }
        }
        return $data;
    }

    /**
     * JSON返回,记录日志
     *
     * @param
     *            1-3 code msg data
     * @param
     *            4 http status
     *            
     */
    public static function jsonp()
    {
        $callback = \Input::get('callback');
        $ret = self::jsonProcessor(func_get_args());
        if ($callback) {
            return response()->jsonp($callback, $ret[0], $ret[1]);
        }
        return response()->json($ret[0], $ret[1]);
    }

    /**
     * json返回的数据封装
     * @param unknown $inputData
     * @return multitype:number multitype:unknown Ambigous <string, string, multitype:, \Illuminate\Database\Eloquent\Collection, Ambigous <\Illuminate\Database\Eloquent\Collection, unknown>>
     */
    public static function jsonProcessor($inputData)
    {
        $segments = array_chunk($inputData, 3);
        $data = self::parseData((array) array_get($segments, 0));
        $httpStatus = isset($segments[1][0]) ? intval($segments[1][0]) : 200;
        $return = [
            [
                'code' => $data['integer'],
                'msg' => $data['string'],
                'data' => $data['array']
            ],
            $httpStatus
        ];
        if(!array_key_exists('array',$data)){
            unset($return[0]['data']);
        }
        return $return;
    }

    /**
     * JSON返回,记录日志
     *
     * @param
     *            1-3 code msg data
     * @param
     *            4 http status
     *            
     */
    public static function json()
    {
        $ret = self::jsonProcessor(func_get_args());
        return response()->json($ret[0], $ret[1]);
    }
}