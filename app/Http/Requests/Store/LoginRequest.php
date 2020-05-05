<?php

namespace App\Http\Requests\Store;

use Illuminate\Foundation\Http\FormRequest;

class LoginRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     *
     * @return bool
     */
    public function authorize()
    {
        return true;
    }

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
            'password'   => 'required',
        ];
    }

    public function messages()
    {
        return [
            'store_mobile.required' => '手机号为必填项！',
            'password.required'   => '密码为必填项！',
        ];
    }
}
