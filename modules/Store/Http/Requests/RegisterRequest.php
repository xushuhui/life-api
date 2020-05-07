<?php

namespace Modules\Store\Http\Requests;

class RegisterRequest extends Request
{
    /**
     * Get the validation rules that apply to the request.
     *
     * @return array
     */
    public function rules()
    {
        return [
            'store_mobile' => [
                'required',
                function($attribute, $value, $fail){
                    if (!check_mobile($value)){
                        $fail('手机号格式错误!');
                        return;
                    }
                }
            ],
            'password'   => 'required|confirmed',
            'invite_code'   => 'required|exists:App\Models\Store,invite_code',
        ];
    }

    public function messages()
    {
        return [
            'store_mobile.required' => '手机号为必填项！',
            'password.required'   => '密码为必填项！',
            'password.confirmed'   => '两次密码不一致！',
            'invite_code.required'   => '邀请码为必填项！',
            'invite_code.exists'   => '邀请码错误！',
        ];
    }
}
