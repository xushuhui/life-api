<?php


namespace App\Services;


use App\Models\User;
use Illuminate\Support\Facades\Auth;
use Overtrue\LaravelWeChat\Facade as LaravelWeChat;

class WeappService
{
    public function getOpenId($code)
    {
        $data = LaravelWeChat::miniProgram()->auth->session($code);

        return isset($data['errcode'])? false : $data;
    }
    public function grantToken($wxResult)
    {
        $user = User::query()->where('weapp_openid',$wxResult['openid'])->first();
        if (!$user) {
            $user = User::query()->create([
                'openid' => $wxResult['openid']
            ]);
        }
        return Auth::guard('api')->fromUser($user);
    }

}