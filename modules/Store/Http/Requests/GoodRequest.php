<?php

namespace Modules\Store\Http\Requests;

class GoodRequest extends Request
{
    /**
     * Get the validation rules that apply to the request.
     *
     * @return array
     */
    public function rules()
    {
        return [
            'name'           => 'required|max:100',
            'photo'          => 'required',
            'price'          => 'required',
            'discount_price' => 'required',
        ];
    }

    public function messages()
    {
        return [
            'name.required' => '商品名称为必填项！',
            'name.max' => '商品名称不超过100字！',
            'photo.required'   => '商品封面为必填项！',
            'price.required'   => '商品价格为必填项！',
            'discount_price.required'   => '商品市场价为必填项！',
        ];
    }
}
