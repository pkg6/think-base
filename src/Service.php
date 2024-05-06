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

use tp5er\think\library\Jump;

class Service extends \think\Service
{
    public function register(): void
    {
        $this->app->bind("tp5er.base.library.jump", function () {
            $jump = new Jump($this->app);

            return $jump;
        });
    }
}
