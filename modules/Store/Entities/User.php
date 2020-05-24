<?php

namespace Modules\Store\Entities;

use Overtrue\LaravelLike\Traits\Liker;

class User extends Common
{
    use Liker;

    public static function getUserByIds($ids)
    {
        $list = self::whereIn('id', $ids)->select('id', 'nickname', 'phone')->get()->toArray() ?? [];
        return array_column($list, null, 'id');
    }
}
