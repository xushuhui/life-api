<?php

namespace Modules\Store\Entities;

class UserCoupon extends Common
{
    public function coupon()
    {
        return $this->hasOne(Coupon::class, 'id', 'coupon_id');
    }

    public function store()
    {
        return $this->hasOne(Store::class, 'id', 'store_id');
    }

    public function user()
    {
        return $this->hasOne(User::class, 'id', 'user_id');
    }

    /**
     * 指定的优惠券已发送多少量
     *
     * @param int $coupon_id
     *
     * @return mixed
     */
    public static function getCountForCoupon(int $coupon_id)
    {
        return self::where('coupon_id', $coupon_id)->count();
    }

    /**
     * 检测会员领取指定优惠券的数量
     *
     * @param int $coupon_id
     * @param int $user_id
     *
     * @return mixed
     */
    public static function getHasCountByUserForCoupon(int $coupon_id, int $user_id)
    {
        return self::where('coupon_id', $coupon_id)->where('user_id', $user_id)->count();
    }

    protected function storeRecharge($request)
    {
        $this->name        = $this->makeUniqueCouponCode();
        $this->user_id        = $request->user_id;
        $this->coupon_id        = $request->coupon_id;
        $this->store_id        = $request->store_id;
        $this->store_user        = $request->store_user;
        return $this->save();
    }

    /**
     * 生成唯一的邀请码
     *
     * @return string
     */
    private function makeUniqueCouponCode()
    {
        $name = make_blend_code(6);
        if (empty($this->where('name', $name)->count())) {
            return $name;
        } else {
            return $this->makeUniqueCouponCode();
        }
    }
}
