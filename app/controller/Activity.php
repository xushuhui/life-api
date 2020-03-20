<?php
/**
 * Created by PhpStorm.
 * User: Administrator
 * Date: 2020/3/19 0019
 * Time: 上午 9:55
 * Note: Activity.php
 */

namespace app\controller;


class Activity extends Base
{
    public function getActivityByName()
    {
        $data = [
            'id' => 1,
            'title' => '全场无门槛立减券',
            'entrance_img' => '',
            'online' => true,
            'remark' => '全场无门槛立减',
            'start_time' => NULL,
            'end_time' => NULL,
        ];
        return json($data);

    }

    public function getActivityByNameWithSpu()
    {
        $data = [
            'id' => 2,
            'title' => '夏日好礼送不停',
            'entrance_img' => '',
            'online' => true,
            'remark' => '限服装、鞋、文具等商品',
            'start_time' => NULL,
            'end_time' => NULL,
            'coupons' => [
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
            ],
        ];
        return json($data);

    }
}