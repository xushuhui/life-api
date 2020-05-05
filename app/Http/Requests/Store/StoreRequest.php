<?php

namespace App\Http\Requests\Store;

use Illuminate\Foundation\Http\FormRequest;

class StoreRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     *
     * @return bool
     */
    public function authorize()
    {
        return true;
    }

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
