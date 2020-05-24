<?php

namespace Modules\Store\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Modules\Store\Entities\Order;
use Modules\Store\Entities\UserCoupon;
use Modules\Store\Http\Requests\WriteOffRequest;

/**
 * 扫码核销
 *
 * Class DeductionCodeController
 *
 * @package Modules\Store\Http\Controllers
 */
class DeductionCodeController extends Controller
{
    /**
     * @OA\Post(path="/store/code_check", summary="扫码检测优惠券码是否可用",
     *     tags={"store"},
     *     parameters={
     *     {
     *          "name" : "name",
     *          "in" : "string",
     *          "description" : "优惠码",
     *          "required" : true
     *      },
     *     },
     *     @OA\Response(response="200", description="{code:0（0.成功，1.失败）,message:'提示语'}"),
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
    public function check(WriteOffRequest $request)
    {
        $coupon = UserCoupon::where([
            'name' => $request->name,
            'store_id' => $this->store_id
        ])->first();
        if (empty($coupon)) return $this->fail(20501);

        switch ($coupon->status){
            case 3: // 使用结束
                return $this->fail(20502);
                break;
            case 4: // 已过期
                return $this->fail(20503);
                break;
        }

        switch ($coupon->coupon_type){
            case 1: // 普通券
                break;
            case 2: // 联盟券
                break;
            case 3: // 次卡券
                if ($coupon->surplus_nums <= 0) return $this->fail(20502);
                break;
            case 4: // 储值券
                if (empty($request->moneys)) return $this->fail(20504);
                if ($request->moneys > $coupon->surplus_nums) return $this->fail(20505);
                break;
        }
        $this->setMessage(20507);
        return $this->succeed();
    }

    /**
     * @OA\Post(path="/store/write_off", summary="扫码核销-确认核销",
     *     tags={"store"},
     *     parameters={
     *     {
     *          "name" : "name",
     *          "in" : "string",
     *          "description" : "优惠码",
     *          "required" : true
     *      },
     *     {
     *          "name" : "moneys",
     *          "in" : "number",
     *          "description" : "核销的金额",
     *          "required" : false
     *      },
     *     },
     *     @OA\Response(response="200", description="{code:0（0.成功，1.失败）,message:'提示语'}"),
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
    public function writeOff(WriteOffRequest $request)
    {
        $coupon = UserCoupon::where([
            'name' => $request->name,
            'store_id' => $this->store_id
        ])->first();
        if (empty($coupon)) return $this->fail(20501);

        switch ($coupon->status){
            case 3: // 使用结束
                return $this->fail(20502);
                break;
            case 4: // 已过期
                return $this->fail(20503);
                break;
        }

        switch ($coupon->coupon_type){
            case 1: // 普通券
                break;
            case 2: // 联盟券
                break;
            case 3: // 次卡券
                if ($coupon->surplus_nums <= 0) return $this->fail(20502);
                break;
            case 4: // 储值券
                if (empty($request->moneys)) return $this->fail(20504);
                if ($request->moneys > $coupon->surplus_nums) return $this->fail(20505);
                break;
        }

        // 核销流程
        DB::beginTransaction(); //开启事务
        try{
            // 1.user_coupons变动
            switch ($coupon->coupon_type){
                case 3: // 次卡券
                    $coupon->surplus_nums -= 1;
                    if ($coupon->surplus_nums > 0) $coupon->status = 2; // 使用中
                    else $coupon->status = 3; // 使用结束
                    break;
                case 4: // 储值券
                    $coupon->surplus_nums -= $request->moneys;
                    if ($coupon->surplus_nums > 0) $coupon->status = 2; // 使用中
                    else $coupon->status = 3; // 使用结束
                    break;
                default:
                    $coupon->use_at = date('Y-m-d H:i:s');
                    $coupon->status = 3; // 使用结束
                    break;
            }
            $coupon->save();
            // 2.order表录入
            Order::create([
                'user_id' => $coupon->user_id,
                'coupon_id' => $coupon->coupon_id,
                'store_id' => $coupon->store_id,
                'store_user_id' => $this->store_user->id, // 核销人
                'use_nums' => $coupon->coupon_type == 3 ? 1 : $request->moneys ?? 0, // 核销额度
                'surplus_nums' => $coupon->surplus_nums, // 剩余额度
            ]);

            DB::commit();  //提交
            $this->setMessage(20509);
            return $this->succeed();
        }catch (\Exception $e){
            DB::rollback();  //回滚
            return $this->fail(20508);
        }
    }
}
