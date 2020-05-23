<?php

namespace Modules\Store\Http\Requests;

class RechargeRequest extends Request
{
    /**
     * Get the validation rules that apply to the request.
     *
     * @return array
     */
    public function rules()
    {
        return [
            'phone' => [
                'required',
                function($attribute, $value, $fail){
                    if (!check_mobile($value)){
                        $fail('手机号格式错误!');
                        return;
                    }
                }
            ],
            'coupon_id'   => 'required|numeric',
        ];
    }

    public function messages()
    {
        return [
            'phone.required' => '充值号码为必填项！',
            'coupon_id.required'   => '请选择卡券！',
            'coupon_id.numeric'   => '请选择卡券！',
        ];
    }
}
