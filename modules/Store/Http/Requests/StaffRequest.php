<?php

namespace Modules\Store\Http\Requests;

class StaffRequest extends Request
{
    /**
     * Get the validation rules that apply to the request.
     *
     * @return array
     */
    public function rules()
    {
        return [
            'phone'          => [
                'required',
                'max:11',
                function($attribute, $value, $fail){
                    if (!check_mobile($value)){
                        $fail('手机号格式错误!');
                        return;
                    }
                }
            ],
            'name'           => 'required|max:5',
            'password'   => [
                'required',
                'confirmed',
                'min:8',
                'max:16',
                function($attribute, $value, $fail){
                    if (!check_number_and_str($value)){
                        $fail('请输入数字字母组合密码!');
                        return;
                    }
                }
            ],
        ];
    }

    public function messages()
    {
        return [

        ];
    }
}
