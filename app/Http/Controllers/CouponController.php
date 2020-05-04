<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class CouponController extends Controller
{
    /**
     * @OA\Get(
     *     path="/api/coupon/status/{status}", summary="用户优惠券列表",
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
     *     path="/api/coupon/store/{store_id}", summary="商家详情",
     *     @OA\Response(response="200", description="success")
     * )
     * @return \Illuminate\Http\JsonResponse
     */
    public function store(int $store_id)
    {
        return $this->setData();
    }
    /**
     * @OA\Get(
     *     path="/api/coupon/stores", summary="商家列表",
     *     @OA\Response(response="200", description="success")
     * )
     * @return \Illuminate\Http\JsonResponse
     */
    public function stores()
    {
        return $this->setData();
    }
    /**
     * @OA\Get(
     *     path="/api/coupon/used/{coupon_id}", summary="已使用优惠券性情",
     *     @OA\Response(response="200", description="success")
     * )
     * @return \Illuminate\Http\JsonResponse
     */
    public function used(int $coupon_id)
    {
        return $this->setData();
    }
}
