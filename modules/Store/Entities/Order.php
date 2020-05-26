<?php

namespace Modules\Store\Entities;

class Order extends Common
{
    public function store()
    {
        return $this->hasOne(Store::class, 'id', 'store_id');
    }

    public function user()
    {
        return $this->hasOne(User::class, 'id', 'user_id');
    }
}
