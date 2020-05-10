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
        $this->id = $object->id ?? 0;
        $this->store_id = $object->store_id;
        $this->photo = $object->photo;
        $this->name = $object->name;
        $this->price = $object->price;
        $this->discount_price = $object->discount_price;
        return $this->save();
    }

    /**
     * 检测商品名称是否存在
     * @param     $name
     * @param int $id
     *
     * @return mixed
     */
    protected function checkName($name, $id = 0)
    {
        if (!empty($id)) $where['id'] = $id;
        $where['name'] = $name;
        return $this->where($where)->exists();
    }
}
