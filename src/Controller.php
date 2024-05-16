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

namespace tp5er\think;

use think\App;
use think\exception\ValidateException;
use think\Validate;
use tp5er\think\traits\controller\Jump;

class Controller
{
    use Jump;

    /**
     * Request实例.
     *
     * @var \think\Request
     */
    protected $request;
    /**
     * 应用实例.
     *
     * @var \think\App
     */
    protected $app;
    /**
     * @var \think\View
     */
    protected $view;
    /**
     * @var \think\Session
     */
    protected $session;
    /**
     * @var \think\Cookie
     */
    protected $cookie;
    /**
     * @var \think\Config
     */
    protected $config;
    /**
     * @var \think\view\driver\Think
     */
    protected $engine;
    /**
     * @var bool 是否批量验证
     */
    protected $batchValidate = false;
    /**
     * @var bool 验证失败是否抛出异常
     */
    protected $failException = false;

    /**
     * @var array 前置操作方法列表
     */
    protected $beforeActionList = [];

    /**
     * 当前模块名称.
     *
     * @var string
     */
    protected $currentModule = "";

    /**
     * 当前控制器.
     *
     * @var string
     */
    protected $currentController = "";

    /**
     * 当前控制器方法.
     *
     * @var string
     */
    protected $currentAction = "";

    /**
     * 构造方法.
     *
     * @param App $app 应用对象
     */
    public function __construct(App $app)
    {
        $this->app = $app;
        $this->request = $this->app->request;

        $this->session = $this->app->session;
        $this->cookie = $this->app->cookie;
        $this->config = $this->app->config;

        $this->view = $this->app->get("view");
        $this->engine = $this->view->engine();

        $this->currentModule = $this->app->http->getName();
        $this->currentController = $this->request->controller();
        $this->currentAction = $this->request->action();

        // 控制器初始化
        $this->_initialize();

        // 前置操作方法
        if ($this->beforeActionList) {
            foreach ($this->beforeActionList as $method => $options) {
                is_numeric($method) ?
                    $this->beforeAction($options) :
                    $this->beforeAction($method, $options);
            }
        }
    }

    /**
     * 初始化操作.
     */
    protected function _initialize()
    {

    }

    /**
     * 加载模板输出.
     *
     * @param string $template 模板文件名
     * @param array $vars 模板输出变量
     *
     * @return mixed
     *
     * @throws \Exception
     */
    protected function fetch($template = '', $vars = [])
    {
        return $this->view->fetch($template, $vars);
    }

    /**
     * 渲染内容输出.
     *
     * @param string $content 模板内容
     * @param array $vars 模板输出变量
     *
     * @return mixed
     */
    protected function display($content = '', $vars = [])
    {
        return $this->view->display($content, $vars);
    }

    /**
     * 模板变量赋值
     *
     * @param mixed $name 要显示的模板变量
     * @param mixed $value 变量的值
     *
     * @return $this
     */
    protected function assign($name, $value = '')
    {
        $this->view->assign($name, $value);

        return $this;
    }

    /**
     * 前置操作.
     *
     * @param string $method 前置操作方法名
     * @param array $options 调用参数 ['only'=>[...]] 或者 ['except'=>[...]]
     *
     * @return void
     */
    protected function beforeAction($method, $options = [])
    {
        if (isset($options['only'])) {
            if (is_string($options['only'])) {
                $options['only'] = explode(',', $options['only']);
            }

            if ( ! in_array($this->request->action(), $options['only'])) {
                return;
            }
        } elseif (isset($options['except'])) {
            if (is_string($options['except'])) {
                $options['except'] = explode(',', $options['except']);
            }

            if (in_array($this->request->action(), $options['except'])) {
                return;
            }
        }
        call_user_func([$this, $method]);
    }

    /**
     * 验证数据.
     *
     * @param array $data 数据
     * @param string|array $validate 验证器名或者验证规则数组
     * @param array $message 提示信息
     * @param bool $batch 是否批量验证
     *
     * @return array|string|true
     */
    protected function validate(array $data, $validate, array $message = [], bool $batch = false)
    {
        if (is_array($validate)) {
            $v = new Validate();
            $v->rule($validate);
        } else {
            if (strpos($validate, '.')) {
                // 支持场景
                [$validate, $scene] = explode('.', $validate);
            }
            $class = false !== strpos($validate, '\\') ? $validate : $this->app->parseClass('validate', $validate);
            $v = new $class();
            if ( ! empty($scene)) {
                $v->scene($scene);
            }
        }
        $v->message($message);
        // 是否批量验证
        if ($batch || $this->batchValidate) {
            $v->batch(true);
        }
        if ( ! $v->check($data)) {
            if ($this->failException) {
                throw new ValidateException($v->getError());
            }

            return $v->getError();
        }

        return true;
    }
}
