<?php
namespace app\controller;


class Theme extends Base
{
    public function getThemes()
    {
        return '[{
            "id": 1,
            "title": "清凉一夏，折扣季",
            "description": "首页顶部入口",
            "name": "t-1",
            "entrance_img": "",
            "extend": null,
            "internal_top_img": null,
            "title_img": null,
            "tpl_name": null,
            "online": true
        }, {
            "id": 4,
            "title": "每周上新",
            "description": "新品推荐",
            "name": "t-2",
            "entrance_img": null,
            "extend": null,
            "internal_top_img": null,
            "title_img": "",
            "tpl_name": null,
            "online": true
        }]';
    }

    public function getThemeByName()
    {
        return '{"id":6,
            "title":"时尚穿搭",
            "description":"帅点才有女朋友",
            "name":"t-4",
            "extend":null,
            "entrance_img":"",
            "internal_top_img":"",
            "online":true,
            "title_img":null,
            "tpl_name":"irelia",
            "spu_list":[
                {
                    "id":2,
                    "title":"Sleeve羊绒毛衣",
                    "subtitle":"Sleeve风袖说当季经典款",
                    "category_id":17,
                    "root_category_id":3,
                    "price":"77.00",
                    "img":"",
                    "for_theme_img":"",
                    "description":null,
                    "discount_price":"62.00",
                    "tags":"包邮$热门",
                    "is_test":true,
                    "online":true,
                }
            ]
          }';
    }
}