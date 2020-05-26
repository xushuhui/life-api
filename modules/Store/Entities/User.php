<?php

namespace Modules\Store\Entities;

use Overtrue\LaravelLike\Traits\Liker;

class User extends Common
{
    use Liker;

    public static function getUserByIds($ids, $field = ['id', 'nickname', 'phone'])
    {
        $list = self::whereIn('id', $ids)->select($field)->get()->toArray() ?? [];
        return array_column($list, null, 'id');
    }
}
