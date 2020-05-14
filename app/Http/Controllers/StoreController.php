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
