<?php

namespace App\Http\Controllers;

use Laravel\Lumen\Routing\Controller as BaseController;

class Controller extends BaseController
{
    protected $data = [];
    protected $code = 0;
    protected $message = 'OK';

    public function succeed()
    {
        return $this->setCode($this->code);
    }

    public function fail($code)
    {
        return $this->setCode($code);

    }

    public function setCode($code)
    {
        $this->code = $code;
        //TODO message
        //$this->message = CodeTable::$table[$code];
        return $this->toJson();
    }

    public function setData($data)
    {
        $this->data = $data;
        $this->toJson();
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
