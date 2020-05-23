<?php

namespace Modules\Store\Http\Controllers;

use Modules\Store\Entities\Store;
use Modules\Store\Entities\StoreUser;
use Modules\Store\Http\Requests\StoreRequest;

class StoreController extends Controller
{
    public function store_type()
    {

    }

    /**
     * @OA\Get(path="/store/detail", summary="获取商家资料",
     *     tags={"store"},
     *     @OA\Response(response="200", description="{code:0（0.成功，1.失败）}"),
     *     @OA\RequestBody(@OA\MediaType(mediaType="application/json",
     *             @OA\Schema(
     *                  @OA\Property(property="store-token", type="string", description="商家Token"),
     *             ))
     *      )
     * )
     *
     * @param \Modules\Store\Http\Requests\StoreRequest $request
     *
     * @return \Illuminate\Http\JsonResponse
     */
    public function detail()
    {
        $store = Store::find($this->store_id);
        $store['logo'] = set_url_prefix($store['logo']);
        $store['photo'] = set_url_prefix($store['photo']);
        return $this->setData($store);
    }

    /**
     * @OA\Put(path="/store/update", summary="修改商家资料",
     *     tags={"store"},
     *     @OA\Response(response="200", description="{code:0（0.成功，1.失败）}"),
     *     @OA\RequestBody(@OA\MediaType(mediaType="application/json",
     *             @OA\Schema(
     *                  @OA\Property(property="store-token", type="string", description="商家Token"),
     *                  @OA\Property(property="name", type="string", description="店铺名称"),
     *                  @OA\Property(property="logo", type="string", description="店铺Logo"),
     *                  @OA\Property(property="photo", type="string", description="店铺门头照"),
     *                  @OA\Property(property="intro", type="string", description="介绍"),
     *                  @OA\Property(property="type", type="int", description="店铺类型"),
     *             ))
     *      )
     * )
     *
     * @param \Modules\Store\Http\Requests\StoreRequest $request
     *
     * @return \Illuminate\Http\JsonResponse
     */
    public function update(StoreRequest $request)
    {
        Store::updateStore($this->store_id, $request);

        $this->setMessage(20008);
        return $this->succeed();
    }

    /**
     * @OA\Get(path="/store/share", summary="商家分享",
     *     tags={"store"},
     *     @OA\Response(response="200", description="{code:0（0.成功，1.失败）}"),
     *     @OA\RequestBody(@OA\MediaType(mediaType="application/json",
     *             @OA\Schema(
     *                  @OA\Property(property="store-token", type="string", description="商家Token"),
     *             ))
     *      )
     * )
     *
     * @return \Illuminate\Http\JsonResponse
     */
    public function share()
    {
        $store_user = $this->store_user;
        if ($store_user->role == 0){ // 如果登录的会员就是店主
            $store = Store::where('id', $this->store_id)->select('id', 'name')->first();
            $store->phone = $store_user->phone;
        }else{
            $store = Store::where('id', $this->store_id)->with('shopkeeper')->select('id', 'name')->first();
            $store->phone = $store->shopkeeper->phone;
        }
        $data = [
            'shop_no' => 'SP' . $store->phone, //店铺号
            'shop_name' => $store->name, //店铺名称
            'share_cover' => '分享的封面图-待定', //分享图
            'share_name' => $store_user->name, //分享人昵称
            'share_phone' => $store_user->phone, //分享人手机号
        ];
        return $this->setData($data);
    }
}
