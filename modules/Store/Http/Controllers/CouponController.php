<?php

namespace Modules\Store\Http\Controllers;

use Modules\Store\Entities\Coupon;
use Modules\Store\Http\Requests\CouponRequest;

class CouponController extends Controller
{
    /**
     * @OA\Put(path="/store/coupon/publish", summary="我的-商家生成优惠券",
     *     tags={"store"},
     *     parameters={
     *      {
     *          "name" : "coupon_name",
     *          "in" : "string",
     *          "description" : "优惠券名称",
     *          "required" : true
     *      },
     *     {
     *          "name" : "coupon_explain",
     *          "in" : "string",
     *          "description" : "优惠券说明",
     *          "required" : true
     *      },
     *     {
     *          "name" : "coupon_type",
     *          "in" : "number",
     *          "description" : "优惠券类型：1.普通券；2.联盟券；3.次卡券；4.储值券",
     *          "required" : true
     *      },
     *     {
     *          "name" : "end_time",
     *          "in" : "date|string",
     *          "description" : "结束时间：2020-05-05",
     *          "required" : true
     *      },
     *     {
     *          "name" : "total_num",
     *          "in" : "number",
     *          "description" : "总数",
     *          "required" : true
     *      },
     *     {
     *          "name" : "user_num",
     *          "in" : "number",
     *          "description" : "每个人可使用的次数",
     *          "required" : true
     *      },
     *     {
     *          "name" : "is_rec",
     *          "in" : "number",
     *          "description" : "是否推荐：0.否；1.是",
     *          "required" : true
     *      },
     *     {
     *          "name" : "use_notice",
     *          "in" : "string",
     *          "description" : "使用须知",
     *          "required" : true
     *      },
     *     {
     *          "name" : "careful_matter",
     *          "in" : "string",
     *          "description" : "注意事项",
     *          "required" : true
     *      },
     *     {
     *          "name" : "recharge_amount",
     *          "in" : "number",
     *          "description" : "充值金额",
     *          "required" : true
     *      },
     *     {
     *          "name" : "give_amount",
     *          "in" : "number",
     *          "description" : "赠送金额",
     *          "required" : true
     *      }
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
     * @param \Modules\Store\Http\Requests\CouponRequest $request
     *
     * @return \Illuminate\Http\JsonResponse
     */
    public function publish(CouponRequest $request)
    {
        $request_data             = $request->all();
        $request_data['store_id'] = $this->store_id;

        if (Coupon::publish($request_data)) {
            $this->setMessage(20101);
            return $this->succeed();
        } else {
            return $this->fail(20101);
        }
    }

    public function delete($id)
    {
        return $this->fail(10002);
        if ($coupon = Coupon::withTrashed()->where(['store_id' => $this->store_id])->find($id)) {
            $coupon->delete();
            if ($coupon->trashed()) {
                $this->setMessage(20203);
                return $this->succeed();
            } else {
                return $this->fail(20204);
            }
        } else {
            return $this->fail(20205);
        }
    }

    /**
     * @OA\Get(path="/coupon/{id}",
     *   tags={"store"},
     *   summary="我的-优惠券分享",
     *   description="优惠券分享",
     *   parameters={},
     *   @OA\Response(
     *     response=200,
     *     description="code:0（0.成功，1.失败）,message:'提示语'}",
     *   ),
     *     @OA\RequestBody(
     *          @OA\MediaType(mediaType="application/json",
     *              @OA\Schema(
     *                  @OA\Property(property="store-token", type="string", description="商家Token"),
     *             ))
     *      )
     * )
     */
    public function share(int $id)
    {
        $data = Coupon::withTrashed()
            ->where('store_id', $this->store_id)
            ->with([
                'store' => function ($query)
                {
                    $query->select('id', 'name', 'logo', 'store_address');
                }
            ])
            ->select('id', 'store_id', 'coupon_name', 'coupon_code', 'created_at', 'end_time', 'use_notice')
            ->find($id);
        return $this->setData($data);
    }

    /**
     * @OA\Get(path="/store/coupon_oncecard",
     *   tags={"store"},
     *   summary="我的-次卡充值-次卡券列表（次卡充值的下拉）",
     *   description="",
     *   parameters={},
     *   @OA\Response(
     *     response=200,
     *     description="code:0（0.成功，1.失败）,message:'提示语'}",
     *   ),
     *     @OA\RequestBody(
     *          @OA\MediaType(mediaType="application/json",
     *              @OA\Schema(
     *                  @OA\Property(property="store-token", type="string", description="商家Token"),
     *             ))
     *      )
     * )
     *
     * @return \Illuminate\Http\JsonResponse
     */
    public function getOnceCardList()
    {
        $list = Coupon::withTrashed()
            ->where('store_id', $this->store_id)
            ->where('coupon_type', Coupon::ONCECARD_TYPE)
            ->where('end_time', '>=', date('Y-m-d H:i:s'))
            ->select('id', 'coupon_name', 'total_num', 'user_num')
            ->get();
        // 需要检测优惠券是否已发送完毕
        return $this->setData($list);
    }

    /**
     * @OA\Get(path="/store/coupon_storedvalue",
     *   tags={"store"},
     *   summary="我的-储值充值-储值券列表（储值充值的下拉）",
     *   description="",
     *   parameters={},
     *   @OA\Response(
     *     response=200,
     *     description="code:0（0.成功，1.失败）,message:'提示语'}",
     *   ),
     *     @OA\RequestBody(
     *          @OA\MediaType(mediaType="application/json",
     *              @OA\Schema(
     *                  @OA\Property(property="store-token", type="string", description="商家Token"),
     *             ))
     *      )
     * )
     *
     * @return \Illuminate\Http\JsonResponse
     */
    public function getStoredValueList()
    {
        $list = Coupon::withTrashed()
            ->where('store_id', $this->store_id)
            ->where('coupon_type', Coupon::STOREDVALUE_TYPE)
            ->where('end_time', '>=', date('Y-m-d H:i:s'))
            ->select('id', 'coupon_name', 'total_num', 'user_num')
            ->get();
        // 需要检测优惠券是否已发送完毕
        return $this->setData($list);
    }
}
