<?php

namespace App\Http\Requests;



class WeappAuthorizationRequest extends Request
{
    public $code;
    /**
     * Get the validation rules that apply to the request.
     *
     * @return array
     */
    public function rules()
    {
        return [
            'code' => 'required|string',
            'nickname' => 'required|string',
            'province' => 'required|string',
            'city' => 'required|string',
            'gender' => 'required|int',
            'avatar_url' => 'required|string'

        ];
    }
}
