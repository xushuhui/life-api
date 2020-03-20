<?php

namespace app\controller;


class Theme extends Base
{
    public function getThemes()
    {
        $data =  [
            [
                'id' => 1,
                'title' => '清凉一夏，折扣季',
                'description' => '首页顶部入口',
                'name' => 't-1',
                'entrance_img' => '',
                'extend' => NULL,
                'internal_top_img' => NULL,
                'title_img' => NULL,
                'tpl_name' => NULL,
                'online' => true,
              ],
            [
                'id' => 4,
                'title' => '每周上新',
                'description' => '新品推荐',
                'name' => 't-2',
                'entrance_img' => NULL,
                'extend' => NULL,
                'internal_top_img' => NULL,
                'title_img' => '',
                'tpl_name' => NULL,
                'online' => true,
              ],
            ];

        return json($data);
    }

    public function getThemeByName()
    {
        $data = [
            'id' => 6,
            'title' => '时尚穿搭',
            'description' => '帅点才有女朋友',
            'name' => 't-4',
            'extend' => NULL,
            'entrance_img' => '',
            'internal_top_img' => '',
            'online' => true,
            'title_img' => NULL,
            'tpl_name' => 'irelia',
            'spu_list' => [
            [
                'id' => 2,
                'title' => 'Sleeve羊绒毛衣',
                'subtitle' => 'Sleeve风袖说当季经典款',
                'category_id' => 17,
                'root_category_id' => 3,
                'price' => '77.00',
                'img' => '',
                'for_theme_img' => '',
                'description' => NULL,
                'discount_price' => '62.00',
                'tags' => '包邮$热门',
                'is_test' => true,
                'online' => true,
              ],
            ],
        ];
          return json($data);
    }
}
