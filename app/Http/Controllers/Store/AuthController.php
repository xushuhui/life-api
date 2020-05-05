<?php

namespace App\Http\Controllers\Store;

use App\Http\Requests\Store\LoginRequest;
use App\Http\Requests\Store\RegisterRequest;
use App\Models\Store;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Validator;

use OpenApi\Annotations as OA;

/**
 * @OA\Info(title="商家模块", version="0.2")
 */
class AuthController extends Controller
{
    /**
     * @OA\Post(path="/api/stores/getCode", summary="获取验证码",
     *     @OA\Response(response="200", description="{status:1（1.成功，0.失败）,msg:'提示语'}"),
     *     @OA\RequestBody(@OA\MediaType(mediaType="application/json",
     *             @OA\Schema(
     *                  @OA\Property(property="store_mobile", type="string", description="手机号"),
     *             ))
     *      )
     * )
     *
     * @param \Illuminate\Http\Request $request
     *
     * @return \Illuminate\Http\JsonResponse
     */
    public function getCode(Request $request)
    {
        $store_mobile = $request->input('store_mobile', '');
        if (!check_mobile($store_mobile)){
            return self::ajaxReturn(['status' => 0, 'msg' => trans('auth.mobile-format-error')]);
        }
        if (!Store::checkMobild($store_mobile)){
            return self::ajaxReturn(['status' => 0, 'msg' => trans('auth.mobile-unregistered')]);
        }
        $code = 123456;
        return self::ajaxReturn(['data' => $code, 'msg' => trans('auth.getcode-success')]);
    }

    /**
     * @OA\Post(path="/api/stores/login", summary="商家登录",
     *     @OA\Response(response="200", description="{status:1（1.成功，0.失败）,msg:提示语}"),
     *     @OA\RequestBody(@OA\MediaType(mediaType="application/json",
     *             @OA\Schema(
     *                  @OA\Property(property="shop_no", type="string", description="店铺号（SP+手机号）"),
     *                  @OA\Property(property="store_mobile", type="string", description="手机号"),
     *                  @OA\Property(property="password", type="string", description="登录密码"),
     *             ))
     *      )
     * )
     *
     * @param \Illuminate\Http\Request $request
     *
     * @return \Illuminate\Http\JsonResponse
     */
    public function login(Request $request)
    {
        if ($this->checkPost())
        {
            // shop_no 后期再完善
            $request_data = $request->only(['store_mobile', 'password']);
            $request_data['store_mobile'] = $request->input('store_mobile', '');
            $request_data['password'] = $request->input('password', '');

            $login_request = new LoginRequest;
            $validator = Validator::make($request_data, $login_request->rules(), $login_request->messages());
            if ($validator->fails()) return self::ajaxReturn(['msg' => $validator->errors()->first()]);

            if (!Store::checkMobild($request_data['store_mobile'])){
                return self::ajaxReturn(['status' => 0, 'msg' => trans('auth.mobile-unregistered')]);
            }

            if (!$token = Auth::guard($this->guard)->attempt($request_data)) {
                return self::ajaxReturn(['status' => 0, 'msg' => trans('auth.login-error')], 401);
            }

            $data = $this->respondWithToken($token)->{'original'};

            return self::ajaxReturn(['data' => $data, 'status' => 1, 'msg' => trans('auth.login-success')]);
        }
    }

    /**
     * @OA\Put(path="/api/stores/register", summary="商家注册",
     *     @OA\Response(response="200", description="{status:1（1.成功，0.失败）,msg:'提示语'}"),
     *     @OA\RequestBody(@OA\MediaType(mediaType="application/json",
     *             @OA\Schema(
     *                 @OA\Property(property="store_mobile", type="string", description="手机号"),
     *                 @OA\Property(property="password", type="string", description="登录密码"),
     *                 @OA\Property(property="password_confirmation", type="string", description="确认密码"),
     *                 @OA\Property(property="invite_code", type="string", description="邀请码"),
     *                 @OA\Property(property="sms_code", type="string", description="验证码"),
     *             ))
     *      )
     * )
     *
     * @param \Illuminate\Http\Request $request
     *
     * @return \Illuminate\Http\JsonResponse
     */
    public function register(Request $request)
    {
        if ($this->checkPost())
        {
            $request_data = $request->all();
            $request_data['store_mobile'] = $request->input('store_mobile', '');
            $request_data['password'] = $request->input('password', '');

            if (Store::checkMobild($request_data['store_mobile'])){
                return self::ajaxReturn(['status' => 0, 'msg' => trans('auth.mobile-registered')]);
            }

            $register_request = new RegisterRequest;
            $validator = Validator::make($request_data, $register_request->rules(), $register_request->messages());
            if ($validator->fails()) return self::ajaxReturn(['msg' => $validator->errors()->first()]);

            if (Store::register($request_data)){
                return self::ajaxReturn(['status' => 1, 'msg' => trans('auth.register-success')]);
            }else{
                return self::ajaxReturn(['status' => 0, 'msg' => trans('auth.register-error')]);
            }
        }
    }

    public function reset()
    {

    }

    public function logout()
    {

    }

    /**
     * Refresh a token.
     * 刷新token，如果开启黑名单，以前的token便会失效。
     * 值得注意的是用上面的getToken再获取一次Token并不算做刷新，两次获得的Token是并行的，即两个都可用。
     *
     * @return \Illuminate\Http\JsonResponse
     */
    public function refresh()
    {
        return $this->respondWithToken(auth($this->guard)->refresh());
    }

    /**
     * Get the token array structure.
     *
     * @param string $token
     *
     * @return \Illuminate\Http\JsonResponse
     */
    protected function respondWithToken($token)
    {
        return response()->json([
            'access_token' => $token,
            'token_type'   => 'admin',
            'expires_in'   => auth($this->guard)->factory()->getTTL() * 24 * 60 * 60
        ]);
    }
}
