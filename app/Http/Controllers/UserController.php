<?php

namespace App\Http\Controllers;

use App\Http\Requests\UserRequest;
use App\Models\User;
use OpenApi\Annotations as OA;

/**
 * @OA\Info(title="用户模块", version="0.1")
 */
class UserController extends Controller
{
    /**
     * @OA\Put(path="/api/user", summary="修改用户资料",
     *     @OA\Response(response="200", description="{code:0,message:'ok'}"),
     *     @OA\RequestBody(@OA\MediaType(mediaType="application/json",
     *             @OA\Schema(
     *                 @OA\Property(property="nickname",type="string", description="昵称"),
     *                 @OA\Property(property="phone",type="string", description="手机号"),
     *                 example={"nickname": "Jessica Smith", "phone": "13012341234"}
     *             ))
     *      )
     * )
     * @param UserRequest $request
     * @return \Illuminate\Http\JsonResponse
     */
    public function update(UserRequest $request)
    {
        $user           = $request->user();
        $user->nickname = $request->nickname;
        $user->phone    = $request->phone;
        $user->save();
        return $this->succeed();
    }

    /**
     * @OA\Get(path="/api/user", summary="查询用户资料",
     *     @OA\Response(response="200", description="{code:0,message:'ok'}"),
     *     )
     * @return \Illuminate\Http\JsonResponse
     */
    public function show()
    {
        return $this->setData(request()->user());
    }

    /**
     * @OA\Get(
     *     path="/api/user/invites", summary="用户邀请列表",
     *     @OA\Response(response="200", description="{code:0,message:'ok'}"),
     * )
     * @return \Illuminate\Http\JsonResponse
     */
    public function invites()
    {
        $data = User::query()->where("parent_id", request()->user()->id)->select("phone,nickname")->get();
        return $this->setData($data);
    }
}
