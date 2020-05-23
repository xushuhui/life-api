<?php

namespace Modules\Store\Entities;

use Illuminate\Database\Eloquent\SoftDeletes;

class Coupon extends Common
{
    const ONCECARD_TYPE = 3, // 次卡
        STOREDVALUE_TYPE = 4; // 储值

    use SoftDeletes;
    protected $dates = ['delete_at'];

    public function store()
    {
        return $this->hasOne(Store::class, 'id', 'store_id');
    }

    protected function publish($data)
    {
        $this->store_id        = $data['store_id'];
        $this->coupon_name     = $data['coupon_name'];
        $this->coupon_code     = $this->makeUniqueCouponCode();
        $this->coupon_type     = $data['coupon_type'];
        $this->end_time        = $data['end_time'];
        $this->total_num       = $data['total_num'];
        $this->user_num        = $data['user_num'] ?? 0;
        $this->is_rec          = $data['is_rec'] ?? 0;
        $this->coupon_explain  = $data['coupon_explain'];
        $this->use_notice      = $data['use_notice'];
        $this->careful_matter  = $data['careful_matter'];
        $this->recharge_amount = $data['recharge_amount'] ?? 0;
        $this->give_amount     = $data['give_amount'] ?? 0;
        $this->save();

        // 是否推荐，点击了推荐，数据将被推送到小程序遇圈页；默认为1次推荐，最新推荐将覆盖最老的推荐。
        // 同类型的其他的数据取消推荐
        if ($this->is_rec == 1) {
            $this->withTrashed()->where('coupon_type', $this->coupon_type)->where('id', '<>', $this->coupon_id)->update(['is_rec' => 0]);
        }

        return $this;
    }

    /**
     * 生成唯一的邀请码
     *
     * @return string
     */
    private function makeUniqueCouponCode()
    {
        $coupon_code = make_blend_code(12);
        if (empty($this->where('coupon_code', $coupon_code)->count())) {
            return $coupon_code;
        } else {
            return $this->makeUniqueCouponCode();
        }
    }
}
