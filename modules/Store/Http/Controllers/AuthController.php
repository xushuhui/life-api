<?php

namespace Modules\Store\Http\Controllers;

use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;

use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Cache;
use Modules\Store\Entities\StoreUser;
use Modules\Store\Http\Requests\LoginRequest;
use Modules\Store\Http\Requests\RegisterRequest;
use Modules\Store\Http\Requests\ResetRequest;

class AuthController extends Controller
{
    /**
     * Create a new AuthController instance.
     * 要求附带email和password（数据来源users表）
     *
     * @return void
     */
    public function __construct()
    {
        parent::__construct();
        $this->middleware('auth:api', ['except' => ['login', 'register', 'getCode', 'reset']]);
    }

    /**
     * @OA\Post(path="/store/getCode", summary="获取验证码",
     *     tags={"store"},
     *     parameters={
     *      {
     *          "name" : "phone",
     *          "in" : "string",
     *          "description" : "手机号",
     *          "required" : true
     *      },
     *     },
     *     @OA\Response(response="200", description="{code:0（0.成功，1.失败）,message:'ok'}"),
     *     @OA\RequestBody(@OA\MediaType(mediaType="application/json"))
     * )
     *
     * @param \Illuminate\Http\Request $request
     *
     * @return \Illuminate\Http\JsonResponse
     */
    public function getCode(Request $request)
    {
        $phone = $request->input('phone', '');
        if (!check_mobile($phone)) {
            return $this->fail(20001);
        }
        // if (!StoreUser::checkMobild($phone)) {
        //     return $this->fail(20002);
        // }
        $sms_code = 123456;

        Cache::remember('code_' . $phone, 60, function () use ($sms_code, $phone){
            return $sms_code;
        });

        $this->setMessage(20007);
        $this->setData($sms_code);
        return $this->succeed();
    }

    /**
     * @OA\Post(path="/store/login", summary="商家登录",
     *     tags={"store"},
     *     parameters={
     *      {
     *          "name" : "shop_no",
     *          "in" : "string",
     *          "description" : "店铺号（SP+手机号）",
     *          "required" : true
     *      },
     *     {
     *          "name" : "phone",
     *          "in" : "string",
     *          "description" : "手机号",
     *          "required" : true
     *      },
     *     {
     *          "name" : "password",
     *          "in" : "string",
     *          "description" : "登录密码",
     *          "required" : true
     *      },
     *     },
     *     @OA\Response(response="200", description="{code:0（0.成功，1.失败）,message:提示语}"),
     *     @OA\RequestBody(@OA\MediaType(mediaType="application/json")
     *      )
     * )
     *
     * @param \Modules\Store\Http\Requests\LoginRequest $request
     *
     * @return \Illuminate\Http\JsonResponse
     */
    public function login(LoginRequest $request)
    {
        if ($this->checkPost()) {
            // shop_no 后期再完善
            $request_data             = $request->only(['phone', 'password']);
            $shop_no = $request->shop_no;
            $store_id = StoreUser::checkSeller($shop_no);
            if (empty($store_id)) return $this->fail(20009);

            if (!StoreUser::checkMobild($request->phone)) {
                return $this->fail(20002);
            }

            // 店员登录必须是传参的规定所属的店铺
            if (!StoreUser::checkStaffForSeller($store_id, $request->phone)){
                return $this->fail(20010);
            }

            if (!$token = Auth::guard($this->guard)->attempt($request_data)) {
                return $this->fail(20003);
            }

            $data = $this->respondWithToken($token)->{'original'};

            return $data;
        }
    }

    /**
     * @OA\Put(path="/store/register", summary="商家注册",
     *     tags={"store"},
     *     parameters={
     *     {
     *          "name" : "phone",
     *          "in" : "string",
     *          "description" : "手机号",
     *          "required" : true
     *      },
     *     {
     *          "name" : "password",
     *          "in" : "string",
     *          "description" : "登录密码",
     *          "required" : true
     *      },
     *     {
     *          "name" : "password_confirmation",
     *          "in" : "string",
     *          "description" : "确认密码",
     *          "required" : true
     *      },
     *     {
     *          "name" : "invite_code",
     *          "in" : "string",
     *          "description" : "邀请码",
     *          "required" : true
     *      },
     *     {
     *          "name" : "sms_code",
     *          "in" : "string",
     *          "description" : "验证码",
     *          "required" : true
     *      },
     *     },
     *     @OA\Response(response="200", description="{code:0（0.成功，1.失败）,message:'提示语'}"),
     *     @OA\RequestBody(@OA\MediaType(mediaType="application/json")
     *      )
     * )
     *
     * @param \Modules\Store\Http\Requests\RegisterRequest $request
     *
     * @return \Illuminate\Http\JsonResponse
     */
    public function register(RegisterRequest $request)
    {
        if ($this->checkPost()) {
            if (StoreUser::checkMobild($request->phone)) {
                return $this->fail(20004);
            }

            $key = 'code_' . $request->phone;
            $sms_code = Cache::get($key);
            // 验证码是否存在
            if (empty($sms_code)) return $this->fail(20013);
            // 验证码是否可用
            if ($sms_code != $request->sms_code) return $this->fail(20014);

            if (StoreUser::register($request->all())) {
                Cache::forget($key); // 验证码验证之后失效

                $this->setMessage(20006);
                return $this->succeed();
            } else {
                return $this->fail(20005);
            }
        }
    }

    /**
     * @OA\Post(path="/store/reset", summary="商家找回密码",
     *     tags={"store"},
     *     parameters={
     *     {
     *          "name" : "phone",
     *          "in" : "string",
     *          "description" : "手机号",
     *          "required" : true
     *      },
     *     {
     *          "name" : "password",
     *          "in" : "string",
     *          "description" : "登录密码",
     *          "required" : true
     *      },
     *     {
     *          "name" : "password_confirmation",
     *          "in" : "string",
     *          "description" : "确认密码",
     *          "required" : true
     *      },
     *     {
     *          "name" : "sms_code",
     *          "in" : "string",
     *          "description" : "验证码",
     *          "required" : true
     *      },
     *     },
     *     @OA\Response(response="200", description="{code:0（0.成功，1.失败）,message:'提示语'}"),
     *     @OA\RequestBody(@OA\MediaType(mediaType="application/json")
     *      )
     * )
     *
     * @param \Modules\Store\Http\Requests\ResetRequest $request
     *
     * @return \Illuminate\Http\JsonResponse
     */
    public function reset(ResetRequest $request)
    {
        if ($this->checkPost()) {
            if (!StoreUser::checkMobild($request->phone)) {
                return $this->fail(20002);
            }

            $key = 'code_' . $request->phone;
            $sms_code = Cache::get($key);
            // 验证码是否存在
            if (empty($sms_code)) return $this->fail(20013);
            // 验证码是否可用
            if ($sms_code != $request->sms_code) return $this->fail(20014);

            if (StoreUser::setPass($request->phone, $request->password)) {
                Cache::forget($key); // 验证码验证之后失效

                $this->setMessage(20012);
                return $this->succeed();
            } else {
                return $this->fail(20011);
            }
        }
    }

    /**
     * Get the authenticated User.
     *
     * @return JsonResponse
     */
    public function me()
    {
        return $this->setData(auth($this->guard)->user());
    }

    public function logout()
    {
        auth($this->guard)->logout();

        return $this->succeed();
    }

    /**
     * Refresh a token.
     * 刷新token，如果开启黑名单，以前的token便会失效。
     * 值得注意的是用上面的getToken再获取一次Token并不算做刷新，两次获得的Token是并行的，即两个都可用。
     *
     * @return JsonResponse
     */
    public function refresh()
    {
        return $this->setData(auth($this->guard)->refresh());
    }

    /**
     * Get the token array structure.
     *
     * @param  string $token
     *
     * @return JsonResponse
     */
    protected function respondWithToken($token)
    {
        return $this->setData([
            'access_token' => $token,
            'token_type'   => 'store-token',
            'expires_in'   => auth($this->guard)->factory()->getTTL() * 60
        ]);
    }

}
