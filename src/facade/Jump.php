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

namespace tp5er\think\facade;

use think\Response;

/**
 * Class Jump.
 *
 * @method static string getResponseType()
 * @method static Response redirect($url, $code = 302, $with = [])
 * @method static Response result($data, $code = 0, $msg = '', $type = '', array $header = [])
 * @method static Response success($msg = '', $url = null, $data = '', $wait = 3, array $header = [])
 * @method static Response error($msg = '', $url = null, $data = '', $wait = 3, array $header = [])
 */
class Jump extends \think\Facade
{
    /**
     * @return string
     */
    protected static function getFacadeClass()
    {
        return "tp5er.base.library.jump";
    }
}
