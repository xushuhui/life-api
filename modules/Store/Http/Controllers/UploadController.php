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

    /**
     * @OA\Put(path="/store/upload/file", summary="图片上传",
     *     tags={"store"},
     *     @OA\Response(response="200", description="{code:0（0.成功，1.失败）}"),
     *     @OA\RequestBody(@OA\MediaType(mediaType="application/json",
     *             @OA\Schema(
     *                  @OA\Property(property="store-token", type="string", description="商家Token"),
     *             ))
     *      )
     * )
     *
     * @param \Illuminate\Http\Request $request
     *
     * @return \Illuminate\Http\JsonResponse
     */
    public function files(Request $request)
    {
        $file = $request->file('file');
        if (empty($file)) return $this->fail(30001);
        $path = $file->store('/public/uploads/' . date('Y-m-d'));
        $this->setMessage(30002);
        return $this->setData(['url' => $this->server_url . Storage::url($path)]);
    }
}
