<?php

namespace Modules\Store\Http\Requests;

class AddressRequest extends Request
{
    /**
     * Get the validation rules that apply to the request.
     *
     * @return array
     */
    public function rules()
    {
        return [
            'store_address' => [
                'required',
                'max:100'
            ],
            'house_number'   => 'required|max:100',
        ];
    }

    public function messages()
    {
        return [
            'store_address.required' => '店铺地址为必填项！',
            'store_address.max' => '店铺地址不超过100字！',
            'house_number.required'   => '门牌号为必填项！',
            'house_number.max' => '门牌号不超过100字！',
        ];
    }
}
