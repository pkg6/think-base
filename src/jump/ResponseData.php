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

namespace tp5er\think\jump;

use think\App;

interface ResponseData
{
    /**
     * @param App $app
     *
     * @return mixed
     */
    public function setApp(App $app);
    /**
     * @param int $code
     *
     * @return $this
     */
    public function setCode($code);

    /**
     * @param string $msg
     *
     * @return $this
     */
    public function setMsg($msg);

    /**
     * @param $data
     *
     * @return $this
     */
    public function setData($data);

    /**
     * @param string $url
     *
     * @return $this
     */
    public function setUrl($url);

    /**
     * @param int $wait
     *
     * @return $this
     */
    public function setWait($wait);

    /**
     * @return array
     */
    public function render();

    /**
     * @return array
     */
    public function result();
}
