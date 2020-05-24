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
            'longitude'   => 'required',
            'latitude'   => 'required',
        ];
    }

    public function messages()
    {
        return [
            'store_address.required' => '店铺地址为必填项！',
            'store_address.max' => '店铺地址不超过100字！',
            'longitude.required'   => '经度为必传项！',
            'latitude.required' => '纬度为必传项！',
        ];
    }
}
