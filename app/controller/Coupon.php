<?php
/**
 * Created by PhpStorm.
 * User: Administrator
 * Date: 2020/3/19 0019
 * Time: 上午 10:37
 * Note: Coupon.php
 */

namespace app\controller;


class Coupon extends Base
{
    public function getCouponByCategory()
    {
        $data = [
            [
                'id' => 3,
                'title' => '无门槛减0.1券',
                'start_time' => 1564956702000,
                'end_time' => 1943647908000,
                'description' => NULL,
                'full_money' => NULL,
                'minus' => 0.1,
                'rate' => NULL,
                'type' => 3,
                'remark' => '全场无门槛立减',
                'whole_store' => true,
            ],
        ];
        return json($data);
    }

    public function wholeStore()
    {
        $data = [
            [
                'id' => 3,
                'title' => '无门槛减0.1券',
                'start_time' => 1564956702000,
                'end_time' => 1943647908000,
                'description' => NULL,
                'full_money' => NULL,
                'minus' => 0.1,
                'rate' => NULL,
                'type' => 3,
                'remark' => '全场无门槛立减',
                'whole_store' => true,
            ],
        ];
        return json($data);
    }

    public function myself()
    {
        $data = [
            [
                'id' => 4,
                'title' => '满500减100券',
                'start_time' => 1564956702000,
                'end_time' => 1564956708000,
                'description' => NULL,
                'full_money' => 500,
                'minus' => 101,
                'rate' => NULL,
                'type' => 1,
                'remark' => '限服装、鞋、文具等商品',
                'whole_store' => false,
            ],
        ];
        return json($data);
    }

    public function myselfWithCategory()
    {
        $data = [
            [
                'id' => 4,
                'title' => '满500减100券',
                'start_time' => 1564956702000,
                'end_time' => 1564956708000,
                'description' => NULL,
                'full_money' => 500,
                'minus' => 101,
                'rate' => NULL,
                'type' => 1,
                'remark' => '限服装、鞋、文具等商品',
                'whole_store' => false,
            ],
        ];
        return json($data);
    }

    public function collect()
    {
        $data = [
            'code' =>0,
            'message'=>'ok',
        ];
        return json($data);
    }
}