<?php

/*
 * This file is part of the tp5er/think-base
 *
 * (c) pkg6 <https://github.com/pkg6>
 *
 * (L) Licensed <https://opensource.org/license/MIT>
 *
 * (A) zhiqiang <https://www.zhiqiang.wang>
 *
 * This source file is subject to the MIT license that is bundled.
 */

namespace tp5er\think\traits\controller;

use think\exception\HttpResponseException;
use tp5er\think\facade\Jump as JumpFacade;

trait Jump
{
    /**
     * 操作成功跳转的快捷方法.
     *
     * @param mixed $msg 提示信息
     * @param string $url 跳转的URL地址
     * @param mixed $data 返回的数据
     * @param int $wait 跳转等待时间
     * @param array $header 发送的Header信息
     *
     * @return void
     */
    protected function success($msg = '', $url = null, $data = '', $wait = 3, array $header = [])
    {
        throw new HttpResponseException($this->successR($msg, $url, $data, $wait, $header));
    }

    /**
     * 操作错误跳转的快捷方法.
     *
     * @param mixed $msg 提示信息
     * @param mixed $data 返回的数据
     * @param string $url 跳转的URL地址
     * @param int $wait 跳转等待时间
     * @param array $header 发送的Header信息
     *
     * @return void
     */
    protected function error($msg = '', $url = null, $data = '', $wait = 3, array $header = [])
    {
        throw new HttpResponseException($this->errorR($msg, $url, $data, $wait, $header));
    }

    /**
     * 返回封装后的API数据到客户端.
     *
     * @param mixed $data 要返回的数据
     * @param int $code 返回的code
     * @param mixed $msg 提示信息
     * @param string $type 返回数据格式
     * @param array $header 发送的Header信息
     *
     * @return void
     */
    protected function result($data, $code = 0, $msg = '', $type = '', array $header = [])
    {
        throw new HttpResponseException($this->resultR($data, $code, $msg, $type, $header));
    }

    /**
     * URL重定向.
     *
     * @param string $url 跳转的URL表达式
     * @param int $code http code
     * @param array $with 隐式传参
     *
     * @return void
     */
    protected function redirect($url, $code = 302, $with = [])
    {
        throw new HttpResponseException(JumpFacade::redirect($url, $code, $with));
    }

    /**
     * 操作成功跳转的快捷方法.
     *
     * @param mixed $msg 提示信息
     * @param string $url 跳转的URL地址
     * @param mixed $data 返回的数据
     * @param int $wait 跳转等待时间
     * @param array $header 发送的Header信息
     *
     * @return \think\Response
     */
    protected function successR($msg = '', $url = null, $data = '', $wait = 3, array $header = [])
    {
        return JumpFacade::success($msg, $url, $data, $wait, $header);
    }

    /**
     * 操作错误跳转的快捷方法.
     *
     * @param mixed $msg 提示信息
     * @param mixed $data 返回的数据
     * @param string $url 跳转的URL地址
     * @param int $wait 跳转等待时间
     * @param array $header 发送的Header信息
     *
     * @return \think\Response
     */
    protected function errorR($msg = '', $url = null, $data = '', $wait = 3, array $header = [])
    {
        return JumpFacade::error($msg, $url, $data, $wait, $header);
    }

    /**
     * 返回封装后的API数据到客户端.
     *
     * @param mixed $data 要返回的数据
     * @param int $code 返回的code
     * @param mixed $msg 提示信息
     * @param string $type 返回数据格式
     * @param array $header 发送的Header信息
     *
     * @return \think\Response
     */
    protected function resultR($data, $code = 0, $msg = '', $type = '', array $header = [])
    {
        return JumpFacade::result($data, $code, $msg, $type, $header);
    }

    /**
     * 获取当前的response 输出类型.
     *
     * @return string
     */
    protected function getResponseType()
    {
        return JumpFacade::getResponseType();
    }
}
