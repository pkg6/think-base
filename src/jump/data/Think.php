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

namespace tp5er\think\jump\data;

use think\App;
use tp5er\think\jump\ResponseData;

class Think implements ResponseData
{
    /**
     * @var int
     */
    protected $code;
    /**
     * @var string
     */
    protected $msg;
    /**
     * @var mixed
     */
    protected $data;
    /**
     * @var string
     */
    protected $url;
    /**
     * @var int
     */
    protected $wait;
    /**
     * @var App
     */
    protected $app;

    /**
     * @inheritDoc
     */
    public function setApp(App $app)
    {
        $this->app = $app;

        return $this;
    }

    /**
     * @inheritDoc
     */
    public function setCode($code)
    {
        $this->code = $code;

        return $this;
    }

    /**
     * @inheritDoc
     */
    public function setMsg($msg)
    {
        if ($this->app->config->get("app.jump_use_lang", false)) {
            $msg = $this->app->lang->get($msg);
        }
        $this->msg = $msg;

        return $this;
    }

    /**
     * @inheritDoc
     */
    public function setData($data)
    {
        $this->data = $data;

        return $this;
    }

    /**
     * @inheritDoc
     */
    public function setUrl($url)
    {
        $this->url = $url;

        return $this;
    }

    /**
     * @inheritDoc
     */
    public function setWait($wait)
    {
        $this->wait = $wait;

        return $this;
    }

    /**
     * @inheritDoc
     */
    public function render()
    {
        return [
            'code' => $this->code,
            'msg' => $this->msg,
            'data' => $this->data,
            'url' => $this->url,
            'wait' => $this->wait,
        ];
    }
    /**
     * @inheritDoc
     */
    public function result()
    {
        return [
             'code' => $this->code,
             'msg' => $this->msg,
             'time' => $this->app->request->server('REQUEST_TIME'),
             'data' => $this->data,
         ];
    }

}
