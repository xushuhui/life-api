<?php

namespace Modules\Store\Http\Controllers;

use Modules\Store\Entities\Store;
use Modules\Store\Http\Requests\StoreRequest;

class StoreController extends Controller
{
    public function store_type()
    {

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
        $store        = $request->user($this->guard);
        Store::updateStore($store, $request);

        $this->setMessage(20008);
        return $this->succeed();
    }
}
