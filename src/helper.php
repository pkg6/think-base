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

if ( ! function_exists('recurse_copy')) {
    /**
     * 目录资源从一个目录复制到另外一个目录.
     *
     * @param $src
     * @param $des
     *
     * @return void
     */
    function recurse_copy($src, $des)
    {
        $dir = opendir($src);
        @mkdir($des);
        while (false !== ($file = readdir($dir))) {
            if (($file != '.') && ($file != '..')) {
                if (is_dir($src . DIRECTORY_SEPARATOR . $file)) {
                    recurse_copy(
                        $src . DIRECTORY_SEPARATOR . $file,
                        $des . DIRECTORY_SEPARATOR . $file
                    );
                } else {
                    if ( ! is_dir(dirname($des . DIRECTORY_SEPARATOR . $file))) {
                        mkdir(
                            dirname($des . DIRECTORY_SEPARATOR . $file),
                            0777,
                            true
                        );
                    }
                    copy(
                        $src . DIRECTORY_SEPARATOR . $file,
                        $des . DIRECTORY_SEPARATOR . $file
                    );
                }
            }
        }
        closedir($dir);
    }
}

if ( ! function_exists('loader_parse_name')) {
    /**
     * 字符串命名风格转换
     * type 0 将 Java 风格转换为 C 的风格 1 将 C 风格转换为 Java 的风格
     *
     * @param  string  $name    字符串
     * @param  int $type    转换类型
     * @param  bool    $ucfirst 首字母是否大写（驼峰规则）
     *
     * @return string
     */
    function loader_parse_name($name, $type = 0, $ucfirst = true)
    {
        if ($type) {
            $name = preg_replace_callback('/_([a-zA-Z])/', function ($match) {
                return strtoupper($match[1]);
            }, $name ?? '');

            return $ucfirst ? ucfirst($name) : lcfirst($name);
        }

        return strtolower(trim(preg_replace("/[A-Z]/", "_\\0", $name), "_"));
    }
}
