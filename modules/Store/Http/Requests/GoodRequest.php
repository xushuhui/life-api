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

        ];
    }
}
