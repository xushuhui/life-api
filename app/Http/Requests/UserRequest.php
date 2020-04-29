<?php

namespace App\Http\Requests;


class UserRequest extends Request
{

    public function rules()
    {
        return [
            'nickname' => 'required|between:3,25',
            'phone'    => 'required|string',

        ];
    }


}
