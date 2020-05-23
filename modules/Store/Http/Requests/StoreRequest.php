<?php

namespace Modules\Store\Http\Requests;

class StoreRequest extends Request
{
    /**
     * Get the validation rules that apply to the request.
     *
     * @return array
     */
    public function rules()
    {
        return [
            'name' => 'required|between:4,25',
            'logo'    => 'required|string',
            'photo'    => 'required|string',
            'intro'    => 'required|between:10,200',
            'type'    => 'required|numeric',
        ];
    }

    public function messages()
    {
        return [
            'name.required' => '手机号为必填项！',
            'logo.required' => 'LOGO为必传项！',
            'photo.required' => '门头照为必传项！',
            'intro.required'   => '店铺介绍为必填项！',
            'type.required'   => '店铺类型为必选项！',
        ];
    }
}
