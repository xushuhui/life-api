<?php

namespace Modules\Store\Http\Requests;

class LoginRequest extends Request
{
    /**
     * Get the validation rules that apply to the request.
     *
     * @return array
     */
    public function rules()
    {
        return [
            'shop_no' => 'required',
            'phone' => [
                'required',
                function($attribute, $value, $fail){
                    if (!check_mobile($value)){
                        $fail('手机号格式错误!');
                        return;
                    }
                }
            ],
            'password'   => 'required',
        ];
    }

    public function messages()
    {
        return [
            'phone.required' => '手机号为必填项！',
            'password.required'   => '密码为必填项！',
        ];
    }
}
