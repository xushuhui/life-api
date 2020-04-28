<?php

namespace App\Http\Controllers;

use App\Models\User;
use App\Http\Requests\UserRequest;
use EasyWeChatComposer\EasyWeChat;
use Illuminate\Support\Facades\Cache;
use L5Swagger\Http\Controllers\SwaggerAssetController;
use L5Swagger\Http\Controllers\SwaggerController;
use OpenApi\Annotations as OA;

/**
 * @OA\Info(title="用户模块", version="0.1")
 */
class UserController extends Controller
{
    /**
     * @OA\Put(path="/api/user", summary="修改用户资料",
     *     @OA\Parameter(name="Accept",in="header",content="application/json"),
     *     @OA\Response(response="200", description=""),
     *     @OA\RequestBody(
     *
     *          @OA\MediaType(mediaType="application/json",
     *             @OA\Schema(
     *                 @OA\Property(property="id",type="int"),
     *                 @OA\Property(property="name",type="string"),
     *                 example={"id": 10, "name": "Jessica Smith"}
     *             ))
     *      )
     * )
     * @return \Illuminate\Http\JsonResponse
     */
    public function update(UserRequest $request)
    {

        return $this->succeed();
    }

    /**
     * @OA\Get(path="/api/user", summary="查询用户资料",
     * @OA\Parameter(name="id",in="path",@OA\Schema(type="int")),
     * @OA\Response(response="200", description="success"))
     * @param int $id
     * @return \Illuminate\Http\JsonResponse
     */
    public function show(int $id)
    {
        return $this->setData();
    }

    /**
     * @OA\Get(
     *     path="/api/user/invites", summary="用户邀请列表",
     *     @OA\Response(response="200", description="success")
     * )
     * @return \Illuminate\Http\JsonResponse
     */
    public function invites()
    {
        return $this->setData();
    }
}
