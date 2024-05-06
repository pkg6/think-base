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

namespace tp5er\think\library;

use think\App;
use think\Response;

class Jump
{
    /**
     * @var App
     */
    protected $app;

    /**
     * @var string
     */
    protected $dispatch_success_tmpl;
    /**
     * @var string
     */
    protected $dispatch_error_tmpl;
    /**
     * @var string
     */
    protected $default_return_type = "html";
    /**
     * @var string
     */
    protected $default_ajax_return = "json";

    /**
     * msg 多语言启动.
     *
     * @var bool
     */
    protected $useLang = false;

    /**
     * @param App $app
     */
    public function __construct(App $app)
    {
        $this->app = $app;
        $this->_initialize();
    }

    /**
     * @return void
     */
    protected function _initialize()
    {
        $dispatch_success_tmpl = $this->app->getRootPath() . "vendor/tp5er/think-base/src/tpl/dispatch_jump.html";
        $dispatch_error_tmpl = $this->app->getRootPath() . "vendor/tp5er/think-base/src/tpl/dispatch_jump.html";
        $this->dispatch_success_tmpl = $this->app->config->get("app.dispatch_success_tmpl", $dispatch_success_tmpl);
        $this->dispatch_error_tmpl = $this->app->config->get("app.dispatch_error_tmpl", $dispatch_error_tmpl);
        $this->default_return_type = $this->app->config->get("app.default_return_type", 'html');
        $this->default_ajax_return = $this->app->config->get("app.default_ajax_return", 'json');
        $this->useLang = $this->app->config->get("app.jump_use_lang", false);
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
     * @return void
     */
    public function success($msg = '', $url = null, $data = '', $wait = 3, array $header = [])
    {

        $type = $this->getResponseType();
        if (is_null($url) && isset($_SERVER["HTTP_REFERER"])) {
            $url = $_SERVER["HTTP_REFERER"];
        } elseif ('' !== $url) {
            $url = (strpos($url, '://') || 0 === strpos($url, '/')) ? $url : (string) $this->app->route->buildUrl($url);
        }

        if ($this->useLang) {
            $msg = $this->app->lang->get($msg);
        }

        $result = [
            'code' => 0,
            'msg' => $msg,
            'data' => $data,
            'url' => $url,
            'wait' => $wait,
        ];
        if ($type == "html") {
            $response = Response::create($this->dispatch_success_tmpl, 'view')
                ->assign($result)
                ->header($header);
        } else {
            $response = Response::create($result, $type)
                ->header($header);
        }

        return $response;
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
     * @return Response
     */
    public function error($msg = '', $url = null, $data = '', $wait = 3, array $header = [])
    {
        $type = $this->getResponseType();
        if (is_null($url)) {
            $url = $this->app['request']->isAjax() ? '' : 'javascript:history.back(-1);';
        } elseif ('' !== $url) {
            $url = (strpos($url, '://') || 0 === strpos($url, '/')) ? $url : (string) $this->app->route->buildUrl($url);
        }

        if ($this->useLang) {
            $msg = $this->app->lang->get($msg);
        }

        $result = [
            'code' => 1,
            'msg' => $msg,
            'data' => $data,
            'url' => $url,
            'wait' => $wait,
        ];
        if ('html' == strtolower($type)) {
            $response = Response::create($this->dispatch_success_tmpl, 'view')
                ->assign($result)
                ->header($header);
        } else {
            $response = Response::create($result, $type)
                ->header($header);
        }

        return $response;
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
     * @return Response
     */
    public function result($data, $code = 0, $msg = '', $type = '', array $header = [])
    {
        if ($this->useLang) {
            $msg = $this->app->lang->get($msg);
        }
        $result = [
            'code' => $code,
            'msg' => $msg,
            'time' => $this->app->request->server('REQUEST_TIME'),
            'data' => $data,
        ];
        $type = $type ?: $this->getResponseType();

        return Response::create($result, $type)->header($header);
    }

    /**
     * @param string $url 跳转的URL表达式
     * @param int $code http code
     * @param array $with 隐式传参
     *
     * @return Response
     */
    public function redirect($url, $code = 302, $with = [])
    {
        $response = Response::create($url, 'redirect');

        return $response->code($code)->with($with);
    }

    /**
     * 获取当前的response 输出类型.
     *
     * @return string
     */
    public function getResponseType()
    {
        $isAjax = $this->app->request->isAjax();

        return $isAjax ? $this->default_ajax_return : $this->default_return_type;
    }
}
