<?php

namespace App\Http\Controllers\Store;

use App\Http\Requests\Store\StoreRequest;
use App\Models\Store;

class StoreController extends Controller
{
    public function store_type()
    {

    }

    /**
     * @OA\Put(path="/api/stores/update", summary="修改商家资料",
     *     @OA\Response(response="200", description="{code:0}"),
     *     @OA\RequestBody(@OA\MediaType(mediaType="application/json",
     *             @OA\Schema(
     *                  @OA\Property(property="name", type="string", description="店铺名称"),
     *                  @OA\Property(property="logo", type="string", description="店铺Logo"),
     *                  @OA\Property(property="photo", type="string", description="店铺门头照"),
     *                  @OA\Property(property="intro", type="string", description="介绍"),
     *                  @OA\Property(property="type", type="int", description="店铺类型"),
     *             ))
     *      )
     * )
     * @param StoreRequest $request
     *
     * @return \Illuminate\Http\JsonResponse
     */
    public function update(StoreRequest $request)
    {
        $store        = $request->user($this->guard);
        Store::updateStore($store, $request);
        return self::ajaxReturn(['status' => 1, 'msg' => trans('common.update-success')]);
    }
}
