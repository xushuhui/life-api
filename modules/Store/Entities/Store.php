<?php

namespace Modules\Store\Entities;

use Overtrue\LaravelLike\Traits\Likeable;

class Store extends Common
{
    use Likeable;

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
        $store->house_number = $request->house_number ?? '';
        $store->save();
        return $store;
    }
}
