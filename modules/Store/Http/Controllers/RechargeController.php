<?php

namespace Modules\Store\Http\Controllers;

use Illuminate\Http\Request;
use Modules\Store\Entities\Coupon;
use Modules\Store\Entities\StoreUser;
use Modules\Store\Entities\User;
use Modules\Store\Entities\UserCoupon;
use Modules\Store\Http\Requests\RechargeRequest;

class RechargeController extends Controller
{
    /**
     * @OA\Get(path="/store/recharges", summary="我的-次卡、储值充值-记录",
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
     *      {
     *          "name" : "coupon_type",
     *          "in" : "int",
     *          "description" : "卡券类型：次卡-3；储值-4",
     *          "required" : true
     *      },
     *     },
     *     @OA\Response(response="200", description="{code:0,message:'一直都是成功的。current_page-当前页码；per_page-每页数量；total-总数量'}"),
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
        $coupon_type   = $request->input('coupon_type', Coupon::ONCECARD_TYPE);

        $query = UserCoupon::query()
            ->from('user_coupons as uc')
            ->where([
            'uc.store_id' => $this->store_id,
            'uc.coupon_type' => $coupon_type, // 券的类型必须做好限制
        ])->join('users as u', 'u.id', 'uc.user_id');
        if (!empty($search)) {
            $query = $query->where(function ($query) use ($search)
            {
                $query->where('u.phone', 'LIKE', '%' . $search)
                    ->orWhere('u.nickname', 'LIKE', '%' . $search);
            });
        }
        if (!empty($start_date)) $query = $query->where('uc.created_at', '>=', $start_date);
        if (!empty($end_date)) $query = $query->where('uc.created_at', '<=', $end_date);
        $list = $query->select('u.nickname', 'u.phone', 'uc.name', 'uc.created_at', 'uc.store_user')->paginate(20);

        // 获取所有的操作人名称
        $store_users = StoreUser::getNameByIds(array_column($list->toArray()['data'], 'store_user'));

        $list->each(function ($item) use ($store_users){
            $item->store_user_name = $store_users[$item->store_user] ?? '';
        });
        return $this->setData($list);
    }

    /**
     * @OA\Put(path="/store/coupon/oncecard_recharge", summary="我的-次卡充值",
     *     tags={"store"},
     *     parameters={
     *      {
     *          "name" : "phone",
     *          "in" : "string",
     *          "description" : "充值号码",
     *          "required" : true
     *      },
     *     {
     *          "name" : "coupon_id",
     *          "in" : "int",
     *          "description" : "下拉选择的次卡券",
     *          "required" : true
     *      },
     *     },
     *     @OA\Response(response="200", description="{code:0（0.成功，1.失败）,message:'提示语'}"),
     *     @OA\RequestBody(
     *          @OA\MediaType(mediaType="application/json",
     *              @OA\Schema(
     *                  @OA\Property(property="store-token", type="string", description="商家Token"),
     *             ))
     *      )
     * )
     *
     * @param \Modules\Store\Http\Requests\RechargeRequest $request
     *
     * @return \Illuminate\Http\JsonResponse
     */
    /**
     * @OA\Put(path="/store/coupon/storedvalue_recharge", summary="我的-储值充值",
     *     tags={"store"},
     *     parameters={
     *      {
     *          "name" : "phone",
     *          "in" : "string",
     *          "description" : "充值号码",
     *          "required" : true
     *      },
     *     {
     *          "name" : "coupon_id",
     *          "in" : "int",
     *          "description" : "下拉选择的储值券",
     *          "required" : true
     *      },
     *     },
     *     @OA\Response(response="200", description="{code:0（0.成功，1.失败）,message:'提示语'}"),
     *     @OA\RequestBody(
     *          @OA\MediaType(mediaType="application/json",
     *              @OA\Schema(
     *                  @OA\Property(property="store-token", type="string", description="商家Token"),
     *             ))
     *      )
     * )
     *
     * @param \Modules\Store\Http\Requests\RechargeRequest $request
     *
     * @return \Illuminate\Http\JsonResponse
     */
    public function create(RechargeRequest $request)
    {
        if (!$user = User::where('phone', $request->phone)->first()){
            return $this->fail(20401);
        }
        if (!$coupon = Coupon::withTrashed()->where('store_id', $this->store_id)->find($request->coupon_id)){
            return $this->fail(20402);
        }
        // 优惠券必须是 次卡券或者储值券
        if (!in_array($coupon->coupon_type, [Coupon::ONCECARD_TYPE, Coupon::STOREDVALUE_TYPE])){
            return $this->fail(20408);
        }
        // 检测优惠券是否已结束
        if ($coupon->end_time < date('Y-m-d H:i:s')) return $this->fail(20403);
        // 检测会员是否有资格拥有优惠券（每张优惠券针对于会员存在拥有的次数）
        if ($coupon->user_num <= UserCoupon::getHasCountByUserForCoupon($coupon->id, $user->id)) return $this->fail(20404);
        // 检测优惠券是否已发送完毕
        if ($coupon->total_num <= UserCoupon::getCountForCoupon($coupon->id)) return $this->fail(20405);

        // 登录的商家/员工的Id，即为操作人
        $request->user_id = $user->id;
        $request->name = $coupon->coupon_code;
        $request->store_id = $this->store_id;
        $request->store_user = $this->store_user->id;
        if (UserCoupon::storeRecharge($request)){
            $this->setMessage(20406);
            return $this->succeed();
        }else{
            return $this->fail(20407);
        }
    }
}
