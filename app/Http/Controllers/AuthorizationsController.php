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
     *     @OA\Response(response="200", description="{code:0,message:'ok'}"),
     *     @OA\RequestBody(@OA\MediaType(mediaType="application/json",
     *             @OA\Schema(
     *                  @OA\Property(property="code", type="string", description="小程序登录code）"),
     *             ))
     *      )
     * )
     * @param WeappAuthorizationRequest $request
     * @return \Illuminate\Http\JsonResponse
     */
    public function store(WeappAuthorizationRequest $request)
    {
        $code     = $request->input('code');
        $wxResult = $this->weappService->getOpenId($code);
        if (!$wxResult) {
            return $this->fail(10001);
        }
        $token = $this->weappService->grantToken($wxResult);
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