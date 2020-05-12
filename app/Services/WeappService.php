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
        return isset($data['errcode']) ? false : $data;
    }

    public function grantToken($wxResult, $input)
    {
        $user = User::query()->where('weapp_openid', $wxResult['openid'])->first();
        if (!$user) {
            $user = User::query()->create([
                'weapp_openid' => $wxResult['openid'],
//                'nickname' => $input['nickname'],
//                'province' => $input['province'],
//                'city' => $input['city'],
//                'gender' => $input['gender'],
//                'avatar_url' => $input['avatar_url']
            ]);
        }
        return Auth::guard('api')->fromUser($user);
    }

}
