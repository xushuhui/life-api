<?php

namespace Modules\Store\Entities;

use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Tymon\JWTAuth\Contracts\JWTSubject;

class Store extends Authenticatable implements JWTSubject
{
    use Notifiable;

    // 店铺类型
    const STORE_TYPE = [
        '1' => '美食',
    ];

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $guarded = ['id'];

    /**
     * The attributes that should be hidden for arrays.
     *
     * @var array
     */
    protected $hidden = [
        'password',
    ];

    public function getJWTIdentifier()
    {
        return $this->getKey();
    }

    public function getJWTCustomClaims()
    {
        return [];
    }

    /**
     * 商家注册
     *
     * @param $data
     *
     * @return $this
     */
    protected function register($data)
    {
        $this->name = $data['store_mobile'];
        $this->store_mobile = $data['store_mobile'];
        $this->password = hash_make($data['password']);
        $this->expire_at = date('Y-m-d H:i:s', strtotime('+1 month'));
        $this->invite_code = $this->makeUniqueInviteCode();
        $this->invite_id = $this->getIdByInvitecode($data['invite_code']);
        $this->save();
        return $this;
    }

    /**
     * 更新商家信息
     *
     * @param $store
     * @param $request
     *
     * @return mixed
     */
    protected function updateStore($store, $request)
    {
        $store->name  = $request->name;
        $store->logo  = $request->logo;
        $store->photo = $request->photo;
        $store->intro = $request->intro;
        $store->type  = $request->type;
        $store->store_address  = $request->store_address;
        $store->save();
        return $store;
    }

    public static function checkMobild(string $store_mobile)
    {
        return self::where('store_mobile', $store_mobile)->first();
    }


    /**
     * 生成唯一的邀请码
     *
     * @return string
     */
    private function makeUniqueInviteCode()
    {
        $invite_code = make_blend_code(8);
        if (empty($this->where('invite_code', $invite_code)->count())) return $invite_code;
        else $this->makeUniqueInviteCode();
    }

    /**
     * 通过邀请码获取商家Id
     *
     * @param string $invite_code
     * @return int|mixed
     */
    private function getIdByInvitecode($invite_code = ''){
        if (empty($invite_code)) return 0;
        return $this->where('invite_code', $invite_code)->value('id', 0);
    }
}
