<?php

namespace App\Http\Controllers;


use App\Models\Coupons;
use App\Models\Store;
use App\Models\User;

class LikeController extends Controller
{
    /**
     * @OA\Get(
     *     path="/api/like/stores", summary="收藏商家列表",
     *     @OA\Response(response="200", description="success")
     * )
     * @return \Illuminate\Http\JsonResponse
     */
    public function stores()
    {
        $likes = request()->user()->likes()->with('likeable')->paginate(20);
        return $this->setData($likes);
    }
    /**
     * @OA\Post(
     *     path="/api/like/store/{id}", summary="收藏商家",
     *     @OA\Response(response="200", description="success")
     * )
     * @return \Illuminate\Http\JsonResponse
     */
    public function store(int $id)
    {
        $store = Store::query()->find($id);
        request()->user()->like($store);
        return $this->succeed();
    }
    /**
     * @OA\Post(
     *     path="/api/like/coupon/{id}", summary="收藏优惠券",
     *     @OA\Response(response="200", description="success")
     * )
     * @return \Illuminate\Http\JsonResponse
     */
    public function coupon(int $id)
    {
        $coupon = Coupons::query()->find($id);
        request()->user()->like($coupon);
        return $this->succeed();
    }
    /**
     * @OA\Get(
     *     path="/api/like/coupons", summary="收藏优惠券列表",
     *     @OA\Response(response="200", description="success")
     * )
     * @return \Illuminate\Http\JsonResponse
     */
    public function coupons()
    {
        $likes = request()->user()->likes()->with('likeable')->paginate(20);
        return $this->setData($likes);
    }
}
