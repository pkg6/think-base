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

namespace tp5er\think\traits\think;

use Exception;

trait Instance
{
    /**
     * @var null|static 实例对象
     */
    protected static $instance = null;

    /**
     * 获取示例.
     *
     * @param array $options 实例配置
     *
     * @return static
     */
    public static function instance($options = [])
    {
        if (is_null(self::$instance)) {
            self::$instance = new self($options);
        }

        return self::$instance;
    }

    /**
     * 静态调用.
     *
     * @param string $method 调用方法
     * @param array  $params 调用参数
     *
     * @return mixed
     *
     * @throws Exception
     */
    public static function __callStatic($method, array $params)
    {
        if (is_null(self::$instance)) {
            self::$instance = new self();
        }

        $call = substr($method, 1);

        if (0 !== strpos($method, '_') || ! is_callable([self::$instance, $call])) {
            throw new Exception("method not exists:" . $method);
        }

        return call_user_func_array([self::$instance, $call], $params);
    }
}
