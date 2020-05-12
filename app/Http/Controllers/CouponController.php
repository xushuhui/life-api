<?php

namespace App\Http\Controllers;

use App\Models\Coupons;
use App\Models\Store;
use App\Models\UserCoupons;

class CouponController extends Controller
{
    
    /**
     * @OA\Get(
     *     path="/api/coupon/recommend", summary="首页推荐优惠券",
     *     @OA\Response(response="200", description="{
    data: [
    {
    end_time: 2020-05-22 09:46:13,
    created_at: 2020-05-12T01:45:48.000000Z,
    coupon_name: test,
    store_id: 1,
    store: {
    id: 1,
    name: store1,
    store_address: 岳麓区
    }
    }
    ],
    }"),
     *  @OA\RequestBody(@OA\MediaType(mediaType="application/json",
     *    @OA\Schema(
     *      @OA\Property(property="coupon_name", type="string", description="优惠券名称"),
     *      @OA\Property(property="created_at", type="string", description="开始时间"),
     *      @OA\Property(property="end_time", type="string", description="结束时间"),
     *      @OA\Property(property="store_id", type="integer", description="商家id"),
     *      @OA\Property(property="store.name", type="string", description="商家名称"),
     *      @OA\Property(property="store.store_address", type="string", description="商家地址"),
     *             ))
     *      )
     * )
     * )
     * @return \Illuminate\Http\JsonResponse
     */
    public function recommend()
    {
        $coupons = Coupons::query()->with(['store' => function ($query) {
            $query->select(["id", "name", "store_address"]);
        }])->where('is_rec', 1)->where('coupon_type', 1)->select(['coupon_name', 'store_id', 'end_time', 'created_at'])->paginate(10);
        return $this->setData($coupons);
    }
    
    /**
     *
     * @OA\Get(
     *     path="/api/coupon/latest", summary="首页最新优惠券",
     *     @OA\Response(response="200", description="{
    data: [
    {
    end_time: 2020-05-22 09:46:13,
    created_at: 2020-05-12T01:45:48.000000Z,
    coupon_name: test,
    store_id: 1,
    store: {
    id: 1,
    name: store1,
    store_address: 岳麓区
    }
    }
    ],
    }"),
     *  @OA\RequestBody(@OA\MediaType(mediaType="application/json",
     *    @OA\Schema(
     *      @OA\Property(property="coupon_name", type="string", description="优惠券名称"),
     *      @OA\Property(property="created_at", type="string", description="开始时间"),
     *      @OA\Property(property="end_time", type="string", description="结束时间"),
     *      @OA\Property(property="store_id", type="integer", description="商家id"),
     *      @OA\Property(property="store.name", type="string", description="商家名称"),
     *      @OA\Property(property="store.store_address", type="string", description="商家地址"),
     *             ))
     *      )
     * )
     * )
     * @return \Illuminate\Http\JsonResponse
     */
    public function latest()
    {
        $coupons = Coupons::query()->with(['store' => function ($query) {
            $query->select(["id", "name", "store_address"]);
        }])->where('coupon_type', 1)->select(['coupon_name', 'store_id', 'end_time', 'created_at'])->paginate(10);
        return $this->setData($coupons);
    }
    
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
