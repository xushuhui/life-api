<?php


namespace App\Http\Controllers;


use App\Http\Requests\WeappAuthorizationRequest;
use App\Models\User;
use App\Services\WeappService;
use EasyWeChatComposer\EasyWeChat;
use Illuminate\Support\Facades\Auth;
use Overtrue\LaravelWeChat\Facade as LaravelWeChat;

class AuthorizationsController extends Controller
{
    private $weappService;

    public function __construct(WeappService $weappService)
    {
        $this->weappService = $weappService;
    }

    public function store(WeappAuthorizationRequest $request)
    {
        $wxResult = $this->weappService->getOpenId($request->code);
        if ($wxResult) {
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
            'expires_in'   => Auth::guard('api')->factory()->getTTL() * 60
        ]);
    }

}