<?php
/**
 * Created by PhpStorm.
 * User: Administrator
 * Date: 2020/3/19 0019
 * Time: 下午 3:40
 * Note: Category.php
 */

namespace app\controller;


class Category extends Base
{
    public function all()
    {
        $data = [
            'roots' => [
                [
                    'id'        => 37,
                    'name'      => '测试数据',
                    'is_root'   => true,
                    'img'       => null,
                    'parent_id' => null,
                    'index'     => null,
                ],
                [
                    'id'        => 3,
                    'name'      => '包包',
                    'is_root'   => true,
                    'img'       => null,
                    'parent_id' => null,
                    'index'     => 1,
                ],
            ],
            'subs'  => [
                [
                    'id'        => 6,
                    'name'      => '平底鞋',
                    'is_root'   => false,
                    'img'       => '',
                    'parent_id' => 1,
                    'index'     => null,
                ],
                [
                    'id'        => 7,
                    'name'      => '凉鞋',
                    'is_root'   => false,
                    'img'       => null,
                    'parent_id' => 1,
                    'index'     => null,
                ],
            ],
        ];
        return json($data);

    }

    public function grid()
    {
        $data = [
            [
                'id' => 1,
                'title' => '服装',
                'img' => 'https://talelin.coding.net/p/sleeve/git/raw/master/grid/clothing.png',
                'name' => NULL,
                'category_id' => NULL,
                'root_category_id' => 2,
            ],
            [
                'id' => 2,
                'title' => '包包',
                'img' => 'https://talelin.coding.net/p/sleeve/git/raw/master/grid/bag.png',
                'name' => NULL,
                'category_id' => NULL,
                'root_category_id' => 3,
            ],
        ];
        return json($data);
    }
}