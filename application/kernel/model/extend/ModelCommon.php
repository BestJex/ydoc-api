<?php

/*
 * Model扩展静态方法
 *
 * @Created: 2020-06-19 08:15:12
 * @Author: yesc (yes.ccx@gmail.com)
 */

namespace app\kernel\model\extend;

use app\extend\common\AppQuery;
use think\db\Query;
use think\Model;

trait ModelCommon {

    /**
     * 使用Query(AppQuery)
     *
     * @param Query|array $query
     * @return Model
     */
    public static function useQuery($query) {
        /** @var Model */
        $instance = new static;

        if (!($query instanceof Query)) {
            $query = AppQuery::make($query);
        }

        return $instance->setOption('where', $query->getOptions('where'))
            ->setOption('field', $query->getOptions('field'))
            ->setOption('order', $query->getOptions('order'))
            ->setOption('group', $query->getOptions('group'))
            ->setOption('limit', $query->getOptions('limit'));
    }

    /**
     * 单个查询
     *
     * @param Query|array $query
     * @return Model|null
     */
    public static function findOne($query) {
        return static::useQuery($query)->find();
    }

    /**
     * 查询数量
     *
     * @param Query|array $query
     * @return Model|null
     */
    public static function findCount($query = []) {
        return static::useQuery($query)->count();
    }

    /**
     * 判断是否存在
     *
     * @param Query|array $query
     * @return Model|null
     */
    public static function existsOne($query = []) {
        return static::useQuery($query)->count() > 0;
    }

}
