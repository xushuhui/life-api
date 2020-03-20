<?php
/**
 * Created by PhpStorm.
 * User: Administrator
 * Date: 2020/3/19 0019
 * Time: 下午 3:44
 * Note: Spu.php
 */

namespace app\controller;


class Spu extends Base
{
    public function search()
    {
        $data = [
            'total' => 1,
            'count' => 10,
            'page' => 0,
            'total_page' => 1,
            'items' => [
                [
                    'id' => 8,
                    'title' => 'ins复古翠绿NoteBook',
                    'subtitle' => '林白默默的掏出小本本，将她说的话一次不漏的记了下来。',
                    'img' => '',
                    'for_theme_img' => '',
                    'price' => '29.99',
                    'discount_price' => '27.8',
                    'description' => NULL,
                    'tags' => '林白推荐',
                    'sketch_spec_id' => '1',
                    'max_purchase_quantity' => NULL,
                    'min_purchase_quantity' => NULL,
                ],
            ],
        ];
        return json($data);
    }

    public function saleExplain()
    {
        $data = [
            [
                'id' => 1,
                'fixed' => true,
                'text' => '发货地：上海',
                'spu_id' => NULL,
                'index' => 1,
                'replace_id' => NULL,
            ],
            [
                'id' => 2,
                'fixed' => true,
                'text' => '物流：顺丰',
                'spu_id' => NULL,
                'index' => 2,
                'replace_id' => NULL,
            ],
        ];
        return json($data);
    }

    public function searchByTag()
    {
        $data = [
            [
                'id' => 1,
                'title' => 'Sleeve',
                'highlight' => 1,
                'description' => NULL,
                'type' => 1,
            ],
            [
                'id' => 3,
                'title' => '林白',
                'highlight' => 0,
                'description' => NULL,
                'type' => 1,
            ],
        ];
        return json($data);

    }

    public function detail()
    {
       $data = [
           'id' => 2,
           'title' => '林间有风自营针织衫',
           'subtitle' => '瓜瓜设计，3件包邮',
           'category_id' => 12,
           'root_category_id' => 2,
           'price' => '77.00',
           'img' => '',
           'for_theme_img' => '',
           'description' => NULL,
           'discount_price' => '62.00',
           'tags' => '包邮$热门',
           'is_test' => true,
           'online' => true,
           'sku_list' => [
               [
                   'id' => 2,
                   'price' => 77.76,
                   'discount_price' => NULL,
                   'online' => true,
                   'img' => '',
                   'title' => '金属灰·七龙珠',
                   'spu_id' => 2,
                   'category_id' => 17,
                   'root_category_id' => 3,
                   'specs' => [
                       [
                           'key_id' => 1,
                           'key' => '颜色',
                           'value_id' => 45,
                           'value' => '金属灰',
                       ],
                       [
                           'key_id' => 3,
                           'key' => '图案',
                           'value_id' => 9,
                           'value' => '七龙珠',
                       ],
                       [
                           'key_id' => 4,
                           'key' => '尺码',
                           'value_id' => 14,
                           'value' => '小号 S',
                       ],
                   ],
                   'code' => '2$1-45#3-9#4-14',
                   'stock' => 5,
               ],
               [
                   'id' => 3,
                   'price' => 66,
                   'discount_price' => 59,
                   'online' => true,
                   'img' => '',
                   'title' => '青芒色·灌篮高手',
                   'spu_id' => 2,
                   'category_id' => 17,
                   'root_category_id' => 3,
                   'specs' => [
                       [
                           'key_id' => 1,
                           'key' => '颜色',
                           'value_id' => 42,
                           'value' => '青芒色',
                       ],
                       [
                           'key_id' => 3,
                           'key' => '图案',
                           'value_id' => 10,
                           'value' => '灌篮高手',
                       ],
                       [
                           'key_id' => 4,
                           'key' => '尺码',
                           'value_id' => 15,
                           'value' => '中号 M',
                       ],
                   ],
                   'code' => '2$1-42#3-10#4-15',
                   'stock' => 999,
               ],
               [
                   'id' => 4,
                   'price' => 88,
                   'discount_price' => NULL,
                   'online' => true,
                   'img' => '',
                   'title' => '青芒色·圣斗士',
                   'spu_id' => 2,
                   'category_id' => 17,
                   'root_category_id' => 3,
                   'specs' => [
                       [
                           'key_id' => 1,
                           'key' => '颜色',
                           'value_id' => 42,
                           'value' => '青芒色',
                       ],
                       [
                           'key_id' => 3,
                           'key' => '图案',
                           'value_id' => 11,
                           'value' => '圣斗士',
                       ],
                       [
                           'key_id' => 4,
                           'key' => '尺码',
                           'value_id' => 16,
                           'value' => '大号  L',
                       ],
                   ],
                   'code' => '2$1-42#3-11#4-16',
                   'stock' => 8,
               ],
               [
                   'id' => 5,
                   'price' => 77,
                   'discount_price' => 59,
                   'online' => true,
                   'img' => 'http://i1.sleeve.7yue.pro/assets/09f32ac8-1af4-4424-b221-44b10bd0986e.png',
                   'title' => '橘黄色·七龙珠',
                   'spu_id' => 2,
                   'category_id' => 17,
                   'root_category_id' => 3,
                   'specs' => [
                       [
                           'key_id' => 1,
                           'key' => '颜色',
                           'value_id' => 44,
                           'value' => '橘黄色',
                       ],
                       [
                           'key_id' => 3,
                           'key' => '图案',
                           'value_id' => 9,
                           'value' => '七龙珠',
                       ],
                       [
                           'key_id' => 4,
                           'key' => '尺码',
                           'value_id' => 14,
                           'value' => '小号 S',
                       ],
                   ],
                   'code' => '2$1-44#3-9#4-14',
                   'stock' => 7,
               ],
           ],
           'spu_img_list' => [
               [
                   'id' => 165,
                   'img' => 'http://i1.sleeve.7yue.pro/assets/5605cd6c-f869-46db-afe6-755b61a0122a.png',
                   'spu_id' => 2,
               ],
           ],
           'spu_detail_img_list' => [
               [
                   'id' => 24,
                   'img' => 'http://i2.sleeve.7yue.pro/n4.png',
                   'spu_id' => 2,
                   'index' => 1,
               ],
           ],
           'sketch_spec_id' => 1,
           'default_sku_id' => 2,
       ];
        return json($data);
    }

    public function latest()
    {
        $data = [
            'total' => 17,
            'count' => 10,
            'page' => 0,
            'total_page' => 2,
            'items' => [
                [
                    'id' => 27,
                    'title' => 'Mier双色靠椅',
                    'subtitle' => '安静的午后，一杯清茶，追忆似水年华。看清风浮动，看落日余晖',
                    'img' => 'http://i2.sleeve.7yue.pro/h1.png',
                    'for_theme_img' => 'http://i1.sleeve.7yue.pro/assets/f6c9fce8-626f-44c0-a709-3f6ef9f3fbef.png',
                    'price' => '1299',
                    'discount_price' => NULL,
                    'description' => NULL,
                    'tags' => '',
                    'sketch_spec_id' => '1',
                    'max_purchase_quantity' => NULL,
                    'min_purchase_quantity' => NULL,
                ],
                [
                    'id' => 27,
                    'title' => 'Mier双色靠椅',
                    'subtitle' => '安静的午后，一杯清茶，追忆似水年华。看清风浮动，看落日余晖',
                    'img' => 'http://i2.sleeve.7yue.pro/h1.png',
                    'for_theme_img' => 'http://i1.sleeve.7yue.pro/assets/f6c9fce8-626f-44c0-a709-3f6ef9f3fbef.png',
                    'price' => '1299',
                    'discount_price' => NULL,
                    'description' => NULL,
                    'tags' => '',
                    'sketch_spec_id' => '1',
                    'max_purchase_quantity' => NULL,
                    'min_purchase_quantity' => NULL,
                ],
                [
                    'id' => 27,
                    'title' => 'Mier双色靠椅',
                    'subtitle' => '安静的午后，一杯清茶，追忆似水年华。看清风浮动，看落日余晖',
                    'img' => 'http://i2.sleeve.7yue.pro/h1.png',
                    'for_theme_img' => 'http://i1.sleeve.7yue.pro/assets/f6c9fce8-626f-44c0-a709-3f6ef9f3fbef.png',
                    'price' => '1299',
                    'discount_price' => NULL,
                    'description' => NULL,
                    'tags' => '',
                    'sketch_spec_id' => '1',
                    'max_purchase_quantity' => NULL,
                    'min_purchase_quantity' => NULL,
                ],
                [
                    'id' => 27,
                    'title' => 'Mier双色靠椅',
                    'subtitle' => '安静的午后，一杯清茶，追忆似水年华。看清风浮动，看落日余晖',
                    'img' => 'http://i2.sleeve.7yue.pro/h1.png',
                    'for_theme_img' => 'http://i1.sleeve.7yue.pro/assets/f6c9fce8-626f-44c0-a709-3f6ef9f3fbef.png',
                    'price' => '1299',
                    'discount_price' => NULL,
                    'description' => NULL,
                    'tags' => '',
                    'sketch_spec_id' => '1',
                    'max_purchase_quantity' => NULL,
                    'min_purchase_quantity' => NULL,
                ],
            ],
        ];
        return json($data);

    }

    public function getSpuByCategory()
    {
        $data = [
            'total' => 5,
            'count' => 10,
            'page' => 0,
            'total_page' => 1,
            'items' => [
                [
                    'id' => 10,
                    'title' => '英雄碳素墨水',
                    'subtitle' => '虽然我们早已不再使用钢笔书写，但钢笔在纸上划过的笔触永远是键盘无法替代的。一只钢笔+一瓶墨水在一个安静的午后，写写内心的故事。',
                    'img' => 'http://i1.sleeve.7yue.pro/assets/0bb351c7-4dba-4403-8d4e-f98a2f440098.png',
                    'for_theme_img' => '',
                    'price' => '80.00',
                    'discount_price' => '69.00',
                    'description' => NULL,
                    'tags' => '',
                    'sketch_spec_id' => NULL,
                    'max_purchase_quantity' => NULL,
                    'min_purchase_quantity' => NULL,
                ],
            ],
        ];
        return json($data);

    }
}