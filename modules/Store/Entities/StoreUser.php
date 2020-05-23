<?php

namespace Modules\Store\Entities;

use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Illuminate\Support\Facades\DB;
use Tymon\JWTAuth\Contracts\JWTSubject;

class StoreUser extends Authenticatable implements JWTSubject
{
    use Notifiable;

    use SoftDeletes;

    // 店铺类型
    const STORE_TYPE = [
        '1' => '美食',
    ];

    const ROLE_STORE = 0, // 商家
        ROLE_STAFF = 1; // 角色

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $guarded = ['id'];

    #把ORM查询的数据自动转换。例如把int转boolean，时间戳转时间，json转成数组等。
    protected $casts = [
        'created_at'   => 'date:Y-m-d H:i',
        'updated_at'   => 'date:Y-m-d H:i',
    ];

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
        DB::beginTransaction();
        try{
            // 商家登录表
            $this->phone       = $data['phone'];
            $this->name       = '';
            $this->password    = hash_make($data['password']);
            $this->invite_code = $this->makeUniqueInviteCode();
            $this->invite_id   = $this->getIdByInvitecode($data['invite_code']);

            // 商家信息表
            $store = Store::create([
                'name' => $this->phone,
                'expire_at' => date('Y-m-d H:i:s', strtotime('+1 month')),
            ]);

            // 关联商家信息表
            $this->store_id = $store->id;

            $this->save();

            DB::commit();

            return $this;

        }catch (\Exception $e){
            DB::rollBack();

            return false;
        }
    }

    public static function checkMobild(string $phone, $id = 0)
    {
        $where = [];
        if (!empty($id)) $where[] = ['id', '<>', $id];
        $where[] = ['phone', '=', $phone];
        return self::where($where)->exists();
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
     *
     * @return int|mixed
     */
    private function getIdByInvitecode($invite_code = '')
    {
        if (empty($invite_code)) return 0;
        return $this->where('invite_code', $invite_code)->value('id', 0);
    }

    /**
     * 新增/更新 员工
     *
     * @param object $object
     *
     * @return bool
     */
    protected function createOrUpdateStaff(object $object)
    {
        $user = $this;
        $id          = intval($object->id ?? 0);
        // 如果存在那么需要查询一次，对象赋值，要不然默认一直插入
        if ($id){
            $user = $this->find($id) ?? $this;
        }
        $user->store_id    = $object->store_id;
        $user->phone       = $object->phone;
        $user->name        = $object->name;
        $user->password    = hash_make($object->password);
        $user->role        = self::ROLE_STAFF;
        $user->invite_code = $this->makeUniqueInviteCode();
        return $user->save();
    }

    /**
     * 通过Id获取名称
     * 
     * @param $ids
     *
     * @return array
     */
    public static function getNameByIds($ids)
    {
        $list = self::whereIn('id', $ids)->select('id', 'name')->get()->toArray() ?? [];
        return array_column($list, 'name', 'id');
    }
}
