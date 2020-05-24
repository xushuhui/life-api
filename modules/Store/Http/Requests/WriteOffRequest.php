<?php

namespace Modules\Store\Http\Requests;

class WriteOffRequest extends Request
{
    /**
     * Get the validation rules that apply to the request.
     *
     * @return array
     */
    public function rules()
    {
        return [
            'name' => 'required',
        ];
    }

    public function messages()
    {
        return [
            'name.required' => '优惠码为必填项！',
        ];
    }
}
