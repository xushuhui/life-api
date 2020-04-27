<?php

namespace App\Http\Controllers;

use App\Models\User;
use App\Http\Requests\UserRequest;
use EasyWeChatComposer\EasyWeChat;
use Illuminate\Support\Facades\Cache;

/**
 * @OA\Info(title="My First API", version="0.1")
 */
class UserController extends Controller
{
    /**
     * @OA\Get(
     *     path="/api/resource.json",
     *     @OA\Response(response="200", description="An example resource")
     * )
     */
    public function weappStore(UserRequest $request)
    {
        // 缓存中是否存在对应的 key
        $verifyData = Cache::get($request->verification_key);
        if (!$verifyData) {
            return $this->response->error('验证码已失效', 422);
        }
        // 判断验证码是否相等，不相等反回 401 错误
        if (!hash_equals((string)$verifyData['code'], $request->verification_code)) {
            return $this->response->errorUnauthorized('验证码错误');
        }
        // 获取微信的 openid 和 session_key
        $miniProgram = EasyWeChat::miniProgram();
        $data        = $miniProgram->auth->session($request->code);
        if (isset($data['errcode'])) {
            return $this->response->errorUnauthorized('code 不正确');
        }
        // 如果 openid 对应的用户已存在，报错403
        $user = User::where('weapp_openid', $data['openid'])->first();
        if ($user) {
            return $this->response->errorForbidden('微信已绑定其他用户，请直接登录');
        }
        // 创建用户
        $user = User::query()->create([
            'nickname'     => $request->nickname,
            'phone'        => $verifyData['phone'],
            'weapp_openid' => $data['openid'],
        ]);

    }
}
