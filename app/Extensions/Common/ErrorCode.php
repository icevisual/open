<?php
namespace App\Extensions\Common;

class ErrorCode
{
    use ConstTrait {
        detectConstName as  detectError;
    }

    /**
     * 返回成功
     *
     * @var unknown
     */
    const STATUS_OK = 1;

    /**
     * 系统错误
     *
     * @var unknown
     */
    const SYSTEM_ERROR = 9000;

    /**
     * 参数验证失败
     *
     * @var unknown
     */
    const VALIDATION_FAILED = 9003;

    /**
     * 逻辑流程错误
     *
     * @var unknown
     */
    const LOGIC_ERROR = 9005;

    /**
     * 未登录
     * 
     * @var unknown
     */
    const UNAUTHORIZED = 9030;

    /**
     * 可能的无信息
     *
     * @var unknown
     */
    const NOTHING_FOUND = 9007;

    /**
     * 没找到关键信息
     *
     * @var unknown
     */
    const VITAL_NOT_FOUND = 9009;

    /**
     * 登录、授权失败
     *
     * @var unknown
     */
    const AUTH_FAILED = 9011;

    /**
     * 账号无权限
     *
     * @var unknown
     */
    const ACCOUNT_UNAUTHORIZED = 9013;

    /**
     * 请求过于频繁
     *
     * @var unknown
     */
    const REQUEST_TOO_OFFEN = 9015;

    /**
     * 禁止请求
     *
     * @var unknown
     */
    const REQUEST_FORBIDDEN = 9017;

    /**
     * 超时
     *
     * @var unknown
     */
    const TIME_OUT = 9019;

    /**
     * 不在预期之中
     *
     * @var unknown
     */
    const UNEXPECTED = 9021;

    /**
     * 加密信息的解析失败
     *
     * @var unknown
     */
    const ANALYZE_FAILED = 9023;

    /**
     * 加密信息的解析后的信息缺失
     *
     * @var unknown
     */
    const ANALYZE_DATA_MISSING = 9025;

    /**
     * 加密信息的解析后的信息错误
     *
     * @var unknown
     */
    const ANALYZE_DATA_ERROR = 9027;

    public static function detectError($code)
    {
        $ReflectionClass = new \ReflectionClass(__CLASS__);
        $ConstantsArray = $ReflectionClass->getConstants();
        $ConstantsArray = array_flip($ConstantsArray);
        return isset($ConstantsArray[$code]) ? $ConstantsArray[$code] : false;
    }
}