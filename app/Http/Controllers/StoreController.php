<?php

namespace App\Http\Controllers;

use App\Models\Store;
use OpenApi\Annotations as OA;

class StoreController extends Controller
{

    /**
     * @OA\Get(
     *     path="/api/store/search/{name}", summary="搜索商家",
     *     @OA\Response(response="200", description="{code:0,message:'ok'}"),
     *     @OA\RequestBody(@OA\MediaType(mediaType="application/json",
     *             @OA\Schema(
     *                  @OA\Property(property="name", type="string", description="商家名称"),
     *             ))
     *      )
     * )
     * @param string $name
     * @return \Illuminate\Http\JsonResponse
     */
    public function search(string $name)
    {
        $stores = Store::query()->where('name','like',"%$name%")->paginate(10);
        return $this->setData($stores);
    }

    /**
     * @OA\Get(
     *     path="/api/store/filter/{type}", summary="商家筛选",
     *     @OA\Response(response="200", description="{code:0,message:'ok'}"),
     *     @OA\RequestBody(@OA\MediaType(mediaType="application/json",
     *             @OA\Schema(
     *                  @OA\Property(property="type", type="int", description="商家类型，1餐馆"),
     *             ))
     *      )
     * )
     * @param int $type
     * @return \Illuminate\Http\JsonResponse
     */
    public function filter(int $type)
    {
        $stores = Store::query()->where('type',$type)->paginate(10);
        return $this->setData($stores);
    }
    /**
     * @OA\Get(
     *     path="/api/stores/{id}", summary="商家详情",
     *     @OA\Response(response="200", description="{code:0,message:'ok'}"),
     * )
     * @return \Illuminate\Http\JsonResponse
     */
    public function show(int $id)
    {
        $data = Store::query()->find($id);
        return $this->setData($data);
    }
}
