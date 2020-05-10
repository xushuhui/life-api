<?php


namespace App\Http\Controllers;


use App\Http\Requests\WeappAuthorizationRequest;
use App\Services\WeappService;
use Illuminate\Support\Facades\Auth;

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
     *                  @OA\Property(property="gender", type="int", description="性别）"),
     *                  @OA\Property(property="avatar_url", type="int", description="头像）"),

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
        $token = $this->weappService->grantToken($wxResult,$input);
        return $this->respondWithToken($token);
    }

    public function update()
    {
        $token = Auth::guard('api')->refresh();

        return $this->respondWithToken($token);
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
