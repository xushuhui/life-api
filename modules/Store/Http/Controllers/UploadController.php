<?php

namespace Modules\Store\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;

class UploadController extends Controller
{
    public $server_url;

    public function __construct()
    {
        parent::__construct();

        $this->server_url = get_server_url();
    }

    public function files(Request $request)
    {
        $file = $request->file('file');
        if (empty($file)) return $this->fail(30001);
        $path = $file->store('/public/uploads/' . date('Y-m-d'));
        $this->setMessage(30002);
        return $this->setData(['url' => $this->server_url . Storage::url($path)]);
    }
}
