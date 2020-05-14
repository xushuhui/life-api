<?php


namespace App\Http\Controllers;


use App\Http\Requests\WeappAuthorizationRequest;
use App\Services\WeappService;
use Illuminate\Support\Facades\Auth;

/**
 * @OA\Get(
 *     path="/", summary="分页说明",
 *     @OA\Response(response="200", description="{
code: 0,
message: OK,
data: {
data: [
],
current_page: 1,
first_page_url: /recommend?page=1,
from: 1,
last_page: 1,
last_page_url: /recommend?page=1,
next_page_url: null,
path: /recommend,
per_page: 10,
prev_page_url: null,
to: 1,
total: 1
}
}"),
 *     @OA\RequestBody(@OA\MediaType(mediaType="application/json",
 *             @OA\Schema(
 *      @OA\Property(property="data", type="arr", description="分页数据"),
 *      @OA\Property(property="per_page", type="integer", description="每页显示数量"),
 *      @OA\Property(property="current_page", type="integer", description="当前页码"),
 *      @OA\Property(property="last_page", type="integer", description="最后页码"),
 *      @OA\Property(property="first_page_url", type="string", description="第一页地址"),
 *      @OA\Property(property="last_page_url", type="string", description="最后一页地址"),
 *      @OA\Property(property="next_page_url", type="string", description="下一页"),
 *      @OA\Property(property="prev_page_url", type="string", description="上一页"),
 *      @OA\Property(property="from", type="integer", description="开始"),
 *      @OA\Property(property="to", type="integer", description="结束"),
 *             ))
 *      )
 * )
)*
 */
class AuthorizationsController extends Controller
{
    private $weappService;

    public function __construct(WeappService $weappService)
    {
        $this->weappService = $weappService;
    }

    /**
     * @OA\Post(
     *     path="/api/authorizations", summary="小程序授权登录",
     *     @OA\Response(response="200", description="{'code':0,'message':'OK','data':{'access_token':'eyEA','token_type':'Bearer','expires_in':51840}}"),
     *     @OA\RequestBody(@OA\MediaType(mediaType="application/json",
     *             @OA\Schema(
     *                  @OA\Property(property="code", type="string", description="小程序登录code）"),
     *                  @OA\Property(property="nickname", type="string", description="昵称）"),
     *                  @OA\Property(property="province", type="string", description="省）"),
     *                  @OA\Property(property="city", type="string", description="城市）"),
     *                  @OA\Property(property="gender", type="integer", description="性别）"),
     *                  @OA\Property(property="avatar_url", type="integer", description="头像）"),
     *
     *             ))
     *      )
     * )
     * @param WeappAuthorizationRequest $request
     * @return \Illuminate\Http\JsonResponse
     */
    public function store(WeappAuthorizationRequest $request)
    {
        $input     = $request->input();
        $wxResult = $this->weappService->getOpenId($input['code']);
        if (!$wxResult) {
            return $this->fail(10001);
        }
        $token = $this->weappService->grantToken($wxResult, $input);
        return $this->respondWithToken($token);
    }
    
    public function update()
    {
        $token = Auth::guard('api')->refresh();
        return $this->respondWithToken($token);
    }
    
    /**
     * @OA\Get(
     *     path="/api/authorizations", summary="小程序授权登录",
     *     @OA\Response(response="200", description="{'code':0,'message':'OK','data':{'is_valid':'true'}}"),
     *      )
     * )
     * @param WeappAuthorizationRequest $request
     * @return \Illuminate\Http\JsonResponse
     */
    public function check()
    {
        $isValid = Auth::guard('api')->check();
        return $this->setData(['is_valid' => $isValid]);
    }
    
    public function destroy()
    {
        Auth::guard('api')->logout();
        return $this->succeed();
    }
    
    protected function respondWithToken($token)
    {
        return $this->setData([
            'access_token' => $token,
            'token_type'   => 'Bearer',
            'expires_in' => Auth::guard('api')->factory()->getTTL() * 3600 * 24 * 100
        ]);
    }

}
