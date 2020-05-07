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
}
