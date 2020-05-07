<?php

namespace Modules\Store\Http\Controllers;

use Illuminate\Foundation\Auth\Access\AuthorizesRequests;
use Illuminate\Foundation\Bus\DispatchesJobs;
use Illuminate\Foundation\Validation\ValidatesRequests;
use Illuminate\Routing\Controller as BaseController;
use Modules\Store\Code;

use OpenApi\Annotations as OA;

class Controller extends BaseController
{
    use AuthorizesRequests, DispatchesJobs, ValidatesRequests;
    protected $data = [];
    protected $code = 0;
    protected $message = 'OK';

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

    public function succeed()
    {
        return $this->setCode($this->code);
    }

    public function fail($code)
    {
        return $this->setCode($code);

    }

    public function setMessage($code)
    {
        $this->message = Code::$table[$code];
    }

    public function setCode($code)
    {
        $this->code = $code;
        //TODO message
        $this->message = Code::$table[$code];
        return $this->toJson();
    }

    public function setData($data)
    {
        $this->data = $data;
        return $this->toJson();
    }

    public function getData()
    {
        return $this->data;
    }

    public function setResult($code, $message)
    {
        $this->code    = $code;
        $this->message = $message;
    }

    public function toJson()
    {
        return response()->json(['code' => $this->code, 'message' => $this->message, 'data' => $this->data]);
    }
}
