<?php

namespace App\Http\Controllers;

use App\Models\Store;
use App\Models\UserCoupons;

class CouponController extends Controller
{
    /**
     * @OA\Get(
     *     path="/api/coupon/status/{status}", summary="用户优惠券列表",
     *     @OA\Response(response="200", description="{code:0,message:'ok'}"),
     * )
     * @return \Illuminate\Http\JsonResponse
     */
    public function index(int $status)
    {
        $userId = request()->user()->id;
        $list   = UserCoupons::query()->where('user_id', $userId)->where('status', $status)->get();
        return $this->setData($list);
    }
    
    /**
     * @OA\Get(
     *     path="/api/coupon/store/{store_id}", summary="商家详情",
     *     @OA\Response(response="200", description="{code:0,message:'ok'}"),
     * )
     * @return \Illuminate\Http\JsonResponse
     */
    public function store(int $store_id)
    {
        $store = Store::query()->where('id', $store_id)->first();
        return $this->setData($store);
    }
    
    /**
     * @OA\Get(
     *     path="/api/coupon/stores", summary="商家列表",
     *     @OA\Response(response="200", description="{code:0,message:'ok'}"),
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
     *     @OA\Response(response="200", description="{code:0,message:'ok'}"),
     * )
     * @param int $coupon_id
     * @return \Illuminate\Http\JsonResponse
     */
    public function used(int $coupon_id)
    {
        $coupon = UserCoupons::query()->where('coupon_id', $coupon_id)->first();
        return $this->setData($coupon);
    }
}
