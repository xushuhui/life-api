<?php

namespace Modules\Store\Entities;

use Carbon\Carbon;
use Illuminate\Database\Eloquent\Model;

class Common extends Model
{
    protected $guarded = ['id'];

    /**
     * 该模型是否被自动维护时间戳
     *
     * @var bool
     */
    public $timestamps = true;

    #把ORM查询的数据自动转换。例如把int转boolean，时间戳转时间，json转成数组等。
    protected $casts = [
        'created_at'   => 'date:Y-m-d H:i',
        'updated_at'   => 'date:Y-m-d H:i',
    ];
}
