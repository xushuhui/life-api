<?php

namespace Modules\Store\Entities;

use Overtrue\LaravelLike\Traits\Likeable;

class Store extends Common
{
    use Likeable;

    // 店主
    public function shopkeeper()
    {
        return $this->hasOne(StoreUser::class, 'store_id', 'id')->where('role', 0);
    }

    /**
     * 更新商家信息
     *
     * @param $store
     * @param $request
     *
     * @return mixed
     */
    protected function updateStore($store_id, $request)
    {
        $store                = self::find($store_id);
        $store->name          = $request->name;
        $store->logo          = remove_prefix_for_url($request->logo);
        $store->photo         = remove_prefix_for_url($request->photo);
        $store->intro         = $request->intro;
        $store->type          = $request->type;
        $store->store_address = $request->store_address;
        $store->save();
        return $store;
    }

    /**
     * 更新商家的地址信息
     *
     * @param $store_id
     * @param $request
     *
     * @return mixed
     */
    protected function updateStoreAddress($store_id, $request)
    {
        $store = self::find($store_id);
        $store->store_address = $request->store_address ?? '';
        $store->longitude = $request->longitude ?? '';
        $store->latitude = $request->latitude ?? '';
        $store->save();
        return $store;
    }
}
