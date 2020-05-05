<?php

namespace App\Http\Controllers\Store;

use Illuminate\Routing\Controller as BaseController;

class Controller extends BaseController
{
    use Common;
    protected $guard = 'store';
    protected $method;
    protected $store_id;

    public function __construct()
    {
        $this->store_id = request()->user($this->guard);
        $this->method = strtoupper(request()->method());
    }

    protected function checkPost()
    {
        return true;
        return $this->checkMethod('POST');
    }

    private function checkMethod(string $method)
    {
        return strtoupper($method) == strtoupper($this->method) ? true : false;
    }
}
