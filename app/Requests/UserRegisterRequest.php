<?php

namespace App\Requests;

use App\Core\Request;

class UserRegisterRequest extends Request
{
    public $name;

    public function rules()
    {
        return [
            'name' => 'required'
        ];
    }


}