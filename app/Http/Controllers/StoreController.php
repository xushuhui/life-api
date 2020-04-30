<?php

namespace App\Http\Controllers;

use App\Models\Store;
use OpenApi\Annotations as OA;

class StoreController extends Controller
{
    /**
     * @OA\Get(
     *     path="/api/stores", summary="首页商家列表",
     *     @OA\Response(response="200", description="success")
     * )
     * @return \Illuminate\Http\JsonResponse
     */
    public function index()
    {
        return $this->setData();
    }

    /**
     * @OA\Get(
     *     path="/api/store/search", summary="搜索商家",
     *     @OA\Response(response="200", description="success")
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
     *     path="/api/store/filter", summary="商家筛选",
     *     @OA\Response(response="200", description="success")
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
     *     path="/api/stores/1", summary="商家详情",
     *     @OA\Response(response="200", description="success")
     * )
     * @return \Illuminate\Http\JsonResponse
     */
    public function show(int $id)
    {
        $data = Store::query()->find($id);
        return $this->setData($data);
    }
}
