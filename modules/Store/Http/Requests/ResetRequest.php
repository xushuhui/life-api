<?php

namespace Modules\Store\Http\Requests;

class ResetRequest extends Request
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
            'password'   => 'required|confirmed',
            'sms_code'   => 'required',
        ];
    }

    public function messages()
    {
        return [
            'phone.required' => '手机号为必填项！',
            'password.required'   => '密码为必填项！',
            'password.confirmed'   => '两次密码不一致！',
            'sms_code.required'   => '验证码为必填项！',
        ];
    }
}
