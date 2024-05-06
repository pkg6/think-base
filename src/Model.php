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

use think\Collection;
use think\db\exception\DataNotFoundException;
use think\db\exception\DbException as Exception;
use think\db\exception\ModelNotFoundException;
use think\db\Query;

class Model extends \think\Model
{

    /**
     * 查找单条记录.
     *
     * @param mixed        $data  主键值或者查询条件（闭包）
     * @param array|string $with  关联预查询
     * @param bool         $cache 是否缓存
     *
     * @return Collection|array|static[]
     *
     * @throws ModelNotFoundException|DataNotFoundException|Exception
     */
    public static function get($data, $with = [], $cache = false)
    {
        if (is_null($data)) {
            return;
        }

        if (true === $with || is_int($with)) {
            $cache = $with;
            $with = [];
        }
        $query = static::parseQuery($data, $with, $cache);

        return $query->find($data);
    }

    /**
     * 查找所有记录.
     *
     * @param mixed $data 主键列表或者查询条件（闭包）
     * @param array|string $with 关联预查询
     * @param bool $cache 是否缓存
     *
     * @return Collection|array|static[]
     *
     * @throws Exception
     * @throws ModelNotFoundException
     * @throws DataNotFoundException
     */
    public static function all($data = null, $with = [], $cache = false)
    {
        if (true === $with || is_int($with)) {
            $cache = $with;
            $with = [];
        }
        $query = static::parseQuery($data, $with, $cache);

        return $query->select($data);
    }

    /**
     * 分析查询表达式.
     *
     * @param mixed $data 主键列表或者查询条件（闭包）
     * @param string $with 关联预查询
     * @param bool $cache 是否缓存
     *
     * @return Query
     */
    protected static function parseQuery(&$data, $with, $cache)
    {
        $result = self::with($with)->cache($cache);
        if (is_array($data) && key($data) !== 0) {
            $result = $result->where($data);
            $data = null;
        } elseif ($data instanceof \Closure) {
            call_user_func_array($data, [& $result]);
            $data = null;
        } elseif ($data instanceof Query) {
            $result = $data->with($with)->cache($cache);
            $data = null;
        }

        return $result;
    }
}
