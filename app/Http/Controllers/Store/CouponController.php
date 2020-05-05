<?php

namespace App\Http\Controllers\Store;

use App\Http\Requests\Store\CouponRequest;
use App\Models\Coupons;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;

class CouponController extends Controller
{
    /**
     * @OA\Put(path="/api/stores/coupon/publish", summary="商家生成优惠券",
     *     @OA\Response(response="200", description="{status:1（1.成功，0.失败）,msg:'提示语'}"),
     *     @OA\RequestBody(@OA\MediaType(mediaType="application/json",
     *             @OA\Schema(
     *                  @OA\Property(property="coupon_name", type="string", description="优惠券名称"),
     *                  @OA\Property(property="coupon_explain", type="string", description="优惠券说明"),
     *                  @OA\Property(property="coupon_type", type="number", description="优惠券类型：1.普通券；2.联盟券；3.次卡券；4.储值券"),
     *                  @OA\Property(property="end_time", type="date", description="结束时间：2020-05-05"),
     *                  @OA\Property(property="total_num", type="int", description="总数"),
     *                  @OA\Property(property="user_num", type="int", description="每个人可使用的次数"),
     *                  @OA\Property(property="is_rec", type="int", description="是否推荐：0.否；1.是"),
     *                  @OA\Property(property="use_notice", type="string", description="使用须知"),
     *                  @OA\Property(property="careful_matter", type="string", description="注意事项"),
     *                  @OA\Property(property="recharge_amount", type="float", description="充值金额"),
     *                  @OA\Property(property="give_amount", type="float", description="赠送金额"),
     *             ))
     *      )
     * )
     *
     * @param \Illuminate\Http\Request $request
     *
     * @return \Illuminate\Http\JsonResponse
     */
    public function publish(Request $request)
    {
        $request_data = $request->all();
        $request_data['store_id'] = $this->store_id;

        $coupon_request = new CouponRequest;
        $validator = Validator::make($request_data, $coupon_request->rules(), $coupon_request->messages());
        if ($validator->fails()) return self::ajaxReturn(['msg' => $validator->errors()->first()]);

        if (Coupons::publish($request_data)){
            return self::ajaxReturn(['status' => 1, 'msg' => trans('common.make-success')]);
        }else{
            return self::ajaxReturn(['status' => 0, 'msg' => trans('common.make-error')]);
        }
    }
}
