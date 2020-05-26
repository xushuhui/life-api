<?php

namespace Modules\Store\Http\Controllers;

use Illuminate\Http\Request;
use Modules\Store\Entities\Coupon;
use Modules\Store\Entities\User;
use Modules\Store\Entities\UserCoupon;

/**
 * 拥有 次卡或者储值券的会员展示
 *
 * Class CardUserController
 *
 * @package Modules\Store\Http\Controllers
 */
class CardUserController extends Controller
{
    /**
     * @OA\Get(path="/store/cardusers", summary="我的-会员管理",
     *     tags={"store"},
     *     parameters={
     *     {
     *          "name" : "search",
     *          "in" : "string",
     *          "description" : "手机号或昵称",
     *          "required" : false
     *      },
     *      {
     *          "name" : "start_date",
     *          "in" : "string",
     *          "description" : "开始日期",
     *          "required" : false
     *      },
     *      {
     *          "name" : "end_date",
     *          "in" : "string",
     *          "description" : "结束日期",
     *          "required" : false
     *      },
     *     },
     *     @OA\Response(response="200", description="{code:0（0.成功，1.失败）,message:'提示语，一直都是成功的。current_page-当前页码；per_page-每页数量；total-总数量；avatar_url-头像；nickname-昵称；phone：手机号；created_at：关注时间'}"),
     * @OA\RequestBody(
     *          @OA\MediaType(mediaType="application/json",
     *              @OA\Schema(
     *                  @OA\Property(property="store-token", type="string", description="商家Token"),
     *             ))
     *      )
     * )
     *
     * @param \Illuminate\Http\Request $request
     *
     * @return \Illuminate\Http\JsonResponse
     */
    public function index(Request $request)
    {
        $search     = $request->input('search', '');
        $start_date = $request->input('start_date', '');
        $end_date   = $request->input('end_date', '');

        $query = User::query()
            ->from('users as u')
            ->where([
            'u.store_id' => $this->store_id,
        ])->join('user_coupons as uc', 'uc.user_id', 'u.id')
        ->whereIn('uc.coupon_type', [Coupon::ONCECARD_TYPE, Coupon::STOREDVALUE_TYPE]);
        if (!empty($search)) {
            $query = $query->where(function ($query) use ($search)
            {
                $query->where('u.phone', 'LIKE', '%' . $search)
                    ->orWhere('u.nickname', 'LIKE', '%' . $search);
            });
        }
        if (!empty($start_date)) $query = $query->where('u.created_at', '>=', $start_date);
        if (!empty($end_date)) $query = $query->where('u.created_at', '<=', $end_date);
        $list = $query->select('u.*')->groupBy('u.id')->orderBy('id', 'DESC')->paginate(10);
        return $this->setData($list);
    }

    /**
     * @OA\Get(path="/store/oncecard/用户Id", summary="我的-会员管理-次卡券",
     *     tags={"store"},
     *     parameters={
     *     },
     *     @OA\Response(response="200", description="{code:0（0.成功，1.失败）,message:'提示语，一直都是成功的。current_page-当前页码；per_page-每页数量；total-总数量；name-优惠码（通过它生成二维码）；surplus_nums：剩余次数；total_nums：总次数；status：状态（1已领取,2使用中，3使用结束，4已过期）；coupon.coupon_name-优惠券的名称；store.store_address-店铺地址；coupon.end_time-过期时间；coupon.created_at：开始时间'}"),
     * @OA\RequestBody(
     *          @OA\MediaType(mediaType="application/json",
     *              @OA\Schema(
     *                  @OA\Property(property="store-token", type="string", description="商家Token"),
     *             ))
     *      )
     * )
     *
     * @param \Illuminate\Http\Request $request
     *
     * @return \Illuminate\Http\JsonResponse
     */
    public function oncecard(int $user_id)
    {
        $list = UserCoupon::query()
            ->with([
                'coupon' => function($query){
                    $query->select('id', 'coupon_name', 'end_time', 'created_at');
                },
                'store' => function($query){
                    $query->select('id', 'store_address');
                },
            ])
            ->where('user_id', $user_id)
            ->where('store_id', $this->store_id)
            ->where('coupon_type', Coupon::ONCECARD_TYPE)
            ->orderBy('id', 'DESC')
            ->paginate(10);
        return $this->setData($list);
    }

    /**
     * @OA\Get(path="/store/storedvalue/用户Id", summary="我的-会员管理-储值券",
     *     tags={"store"},
     *     parameters={
     *     },
     *     @OA\Response(response="200", description="{code:0（0.成功，1.失败）,message:'提示语，一直都是成功的。current_page-当前页码；per_page-每页数量；total-总数量；name-优惠码（通过它生成二维码）；surplus_nums：剩余金额；total_nums：总金额；status：状态（1已领取,2使用中，3使用结束，4已过期）；coupon.coupon_name-优惠券的名称；store.store_address-店铺地址；coupon.end_time-过期时间；coupon.created_at：开始时间'}"),
     * @OA\RequestBody(
     *          @OA\MediaType(mediaType="application/json",
     *              @OA\Schema(
     *                  @OA\Property(property="store-token", type="string", description="商家Token"),
     *             ))
     *      )
     * )
     *
     * @param \Illuminate\Http\Request $request
     *
     * @return \Illuminate\Http\JsonResponse
     */
    public function storedvalue(int $user_id)
    {
        $list = UserCoupon::query()
            ->with([
                'coupon' => function($query){
                    $query->select('id', 'coupon_name', 'end_time', 'created_at');
                },
                'store' => function($query){
                    $query->select('id', 'store_address');
                },
            ])
            ->where('user_id', $user_id)
            ->where('store_id', $this->store_id)
            ->where('coupon_type', Coupon::STOREDVALUE_TYPE)
            ->orderBy('id', 'DESC')
            ->paginate(10);
        return $this->setData($list);
    }

    /**
     * @OA\Get(path="/store/getCouponDetail/优惠券Id", summary="我的-会员管理-优惠券详情",
     *     tags={"store"},
     *     parameters={
     *     },
     *     @OA\Response(response="200", description="{code:0（0.成功，1.失败）,message:'提示语。name-优惠码（通过它生成二维码）'}"),
     * @OA\RequestBody(
     *          @OA\MediaType(mediaType="application/json",
     *              @OA\Schema(
     *                  @OA\Property(property="store-token", type="string", description="商家Token"),
     *             ))
     *      )
     * )
     *
     * @param \Illuminate\Http\Request $request
     *
     * @return \Illuminate\Http\JsonResponse
     */
    public function getCouponDetail(int $id)
    {
        $data = UserCoupon::query()->find($id);
        if (empty($data)) return $this->fail(20402);
        return $this->setData($data);
    }
}
