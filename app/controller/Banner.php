<?php

namespace app\controller;


class Banner extends Base
{

    public function getBannerById()
    {
        $data = [
            'id'          => 1,
            'name'        => 'b-1',
            'description' => '首页顶部主banner',
            'img'         => null,
            'title'       => null,
            'items'       => [
                [
                    'id'        => 1,
                    'img'       => '',
                    'keyword'   => '12',
                    'type'      => 1,
                    'name'      => null,
                    'banner_id' => 1,
                ],
                [
                    'id'        => 2,
                    'img'       => '',
                    'keyword'   => '13',
                    'type'      => 1,
                    'name'      => null,
                    'banner_id' => 1,
                ],
            ],
        ];
        return json($data);

    }

    public function getBannerByName()
    {
        $data = [
            'id'          => 1,
            'name'        => 'b-1',
            'description' => '首页顶部主banner',
            'img'         => null,
            'title'       => null,
            'items'       => [
                [
                    'id'        => 1,
                    'img'       => '',
                    'keyword'   => '12',
                    'type'      => 1,
                    'name'      => null,
                    'banner_id' => 1,
                ],
                [
                    'id'        => 2,
                    'img'       => '',
                    'keyword'   => '13',
                    'type'      => 1,
                    'name'      => null,
                    'banner_id' => 1,
                ],
            ],
        ];
        return json($data);

    }
}