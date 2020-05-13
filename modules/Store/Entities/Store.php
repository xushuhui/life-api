<?php

namespace Modules\Store\Entities;

class Store extends Common
{
    /**
     * 更新商家信息
     *
     * @param $store
     * @param $request
     *
     * @return mixed
     */
    protected function updateStore($store_user, $request)
    {
        $store                = self::find($store_user->id);
        $store->name          = $request->name;
        $store->logo          = $request->logo;
        $store->photo         = $request->photo;
        $store->intro         = $request->intro;
        $store->type          = $request->type;
        $store->store_address = $request->store_address;
        $store->save();
        return $store;
    }
}
