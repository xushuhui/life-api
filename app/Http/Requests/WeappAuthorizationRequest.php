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
            'code' => 'required|string'
        ];
    }
}
