<?php

namespace Modules\Store\Http\Controllers;

use Modules\Store\Entities\Store;
use Modules\Store\Http\Requests\AddressRequest;

class AddressController extends Controller
{
    /**
     * @OA\Get(path="/store/address", summary="地址列表（只有一项）",
     *     tags={"store"},
     *     },
     *     @OA\Response(response="200", description="{code:0,message:'一直都是成功的。store_address-店铺地址；house_number-门牌号；is_empty-是否存在数据'}"),
     * @OA\RequestBody(
     *          @OA\MediaType(mediaType="application/json",
     *              @OA\Schema(
     *                  @OA\Property(property="store-token", type="string", description="商家Token"),
     *             ))
     *      )
     * )
     *
     * @return \Illuminate\Http\JsonResponse
     */
    public function index()
    {
        $store = Store::select('store_address', 'house_number')->find($this->store_id);
        $store['is_empty'] = empty($store['store_address']) && empty($store['house_number']) ? 1 : 0;
        return $this->setData($store);
    }

    /**
     * @OA\Get(path="/store/address/{id}",
     *   tags={"store"},
     *   summary="地址详情（id为0即可）",
     *   description="",
     *   parameters={},
     *   @OA\Response(
     *     response=200,
     *     description="code:0（0.成功，1.失败）,message:'提示语'}"
     *   ),
     * @OA\RequestBody(
     *          @OA\MediaType(mediaType="application/json",
     *              @OA\Schema(
     *                  @OA\Property(property="store-token", type="string", description="商家Token"),
     *             ))
     *      )
     * )
     *
     * @param int $id
     *
     * @return \Illuminate\Http\JsonResponse
     */
    public function detail($id = 0)
    {
        $store = Store::select('store_address', 'house_number')->find($this->store_id);
        return $this->setData($store);
    }

    /**
     * @OA\Put(path="/store/address", summary="新增/更新 地址",
     *     tags={"store"},
     *     parameters={
     *      {
     *          "name" : "store_address",
     *          "in" : "string",
     *          "description" : "店铺地址",
     *          "required" : true
     *      },
     *      {
     *          "name" : "house_number",
     *          "in" : "string",
     *          "description" : "门牌号",
     *          "required" : true
     *      },
     *     },
     *     @OA\Response(response="200", description="{code:0（0.成功，1.失败）,message:'提示语'}"),
     *     @OA\RequestBody(
     *          @OA\MediaType(mediaType="application/json",
     *              @OA\Schema(
     *                  @OA\Property(property="store-token", type="string", description="商家Token"),
     *             )
     *          )
     *     )
     * )
     *
     * @return \Illuminate\Http\JsonResponse
     */
    public function update(AddressRequest $request)
    {
        Store::updateStoreAddress($this->store_id, $request);
        return $this->succeed();
    }

    /**
     * @OA\Delete(path="/store/address/{id}",
     *   tags={"store"},
     *   summary="地址删除（id为0）",
     *   description="",
     *   parameters={},
     *   @OA\Response(
     *     response=200,
     *     description="code:0（0.成功，1.失败）,message:'提示语'}"
     *   ),
     * @OA\RequestBody(
     *          @OA\MediaType(mediaType="application/json",
     *              @OA\Schema(
     *                  @OA\Property(property="store-token", type="string", description="商家Token"),
     *             ))
     *      )
     * )
     *
     * @param int $id
     *
     * @return \Illuminate\Http\JsonResponse
     */
    public function delete($id = 0)
    {
        Store::updateStoreAddress($this->store_id, []);
        return $this->succeed();
    }
}
