<?php

namespace Modules\Store\Entities;

use Illuminate\Database\Eloquent\SoftDeletes;

class Good extends Common
{
    use SoftDeletes;

    protected $dates = ['delete_at'];

    /**
     * 新增/更新 商品
     *
     * @param object $object
     *
     * @return bool
     */
    protected function createOrUpdate(object $object)
    {
        $good = $this;
        $id          = intval($object->id ?? 0);
        // 如果存在那么需要查询一次，对象赋值，要不然默认一直插入
        if ($id){
            $good = $this->find($id) ?? $this;
        }
        $good->store_id       = $object->store_id;
        $good->photo          = $object->photo;
        $good->name           = $object->name;
        $good->price          = $object->price;
        $good->discount_price = $object->discount_price;
        return $good->save();
    }

    /**
     * 检测商品名称是否存在
     *
     * @param     $name
     * @param int $id
     *
     * @return mixed
     */
    protected function checkName($name, $id = 0)
    {
        $where = [];
        if (!empty($id)) $where[] = ['id', '<>', $id];
        $where[] = ['name', '=', $phone];
        return self::where($where)->exists();
    }
}
