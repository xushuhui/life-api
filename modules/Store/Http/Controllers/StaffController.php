<?php

namespace Modules\Store\Http\Controllers;

use Modules\Store\Entities\StoreUser;
use Modules\Store\Http\Requests\StaffRequest;

class StaffController extends Controller
{
    protected $comm_where = [];

    public function __construct()
    {
        parent::__construct();

        $this->comm_where = ['store_id' => $this->store_id, 'role' => StoreUser::ROLE_STAFF];
    }

    /**
     * @OA\Get(path="/store/staff", summary="我的-员工管理-员工列表",
     *     tags={"store"},
     *     parameters={
     *      {
     *          "name" : "page",
     *          "in" : "int",
     *          "description" : "页码（默认为1）",
     *          "required" : true
     *      },
     *     },
     *     @OA\Response(response="200", description="{code:0,message:'一直都是成功的。current_page-当前页码；per_page-每页数量；total-总数量'}"),
     * @OA\RequestBody(
     *          @OA\MediaType(mediaType="application/json",
     *              @OA\Schema(
     *                  @OA\Property(property="store-token", type="string", description="商家Token"),
     *             ))
     *      )
     * )
     *
     * @return \Illuminate\Http\JsonResponse
     */
    public function index()
    {
        $list   = StoreUser::where($this->comm_where)->paginate(10);

        return $this->setData($list);
    }

    /**
     * @OA\Get(path="/store/staff/{id}",
     *   tags={"store"},
     *   summary="我的-员工管理-员工详情",
     *   description="",
     *   parameters={},
     *   @OA\Response(
     *     response=200,
     *     description="code:0（0.成功，1.失败）,message:'提示语'}"
     *   ),
     * @OA\RequestBody(
     *          @OA\MediaType(mediaType="application/json",
     *              @OA\Schema(
     *                  @OA\Property(property="store-token", type="string", description="商家Token"),
     *             ))
     *      )
     * )
     */
    public function detail(int $id)
    {
        $data = StoreUser::where($this->comm_where)->find($id);
        if ($data) {
            return $this->setData($data);
        } else {
            return $this->fail(20301);
        }
    }

    /**
     * @OA\Put(path="/store/staff", summary="我的-员工管理-新增/更新 员工",
     *     tags={"store"},
     *     parameters={
     *      {
     *          "name" : "id",
     *          "in" : "int",
     *          "description" : "Id（新增时为0即可，或者不传）",
     *          "required" : false
     *      },
     *     {
     *          "name" : "name",
     *          "in" : "string",
     *          "description" : "名称",
     *          "required" : true
     *      },
     *     {
     *          "name" : "photo",
     *          "in" : "string",
     *          "description" : "手机号",
     *          "required" : true
     *      },
     *     {
     *          "name" : "password",
     *          "in" : "string",
     *          "description" : "登录密码（8-15位，数字与字母）",
     *          "required" : true
     *      },
     *     {
     *          "name" : "password_confirmation",
     *          "in" : "string",
     *          "description" : "确认密码",
     *          "required" : true
     *      },
     *     },
     *     @OA\Response(response="200", description="{code:0（0.成功，1.失败）,message:'提示语'}"),
     *     @OA\RequestBody(
     *          @OA\MediaType(mediaType="application/json",
     *              @OA\Schema(
     *                  @OA\Property(property="store-token", type="string", description="商家Token"),
     *             )
     *          )
     *     )
     * )
     *
     * @return \Illuminate\Http\JsonResponse
     */
    public function update(StaffRequest $request)
    {
        $request->store_id = $this->store_id;
        $request->id = intval($request->id ?? 0);
        if (StoreUser::checkMobild($request->phone, $request->id)) {
            return $this->fail(20302);
        }
        StoreUser::createOrUpdateStaff($request);
        $this->setMessage(20303);
        return $this->succeed();
    }

    /**
     * @OA\Delete(path="/store/staff/{id}",
     *   tags={"store"},
     *   summary="我的-员工管理-员工删除",
     *   description="",
     *   parameters={},
     *   @OA\Response(
     *     response=200,
     *     description="code:0（0.成功，1.失败）,message:'提示语'}"
     *   ),
     * @OA\RequestBody(
     *          @OA\MediaType(mediaType="application/json",
     *              @OA\Schema(
     *                  @OA\Property(property="store-token", type="string", description="商家Token"),
     *             ))
     *      )
     * )
     *
     * @param int $id
     *
     * @return \Illuminate\Http\JsonResponse
     */
    public function delete(int $id)
    {
        if ($user = StoreUser::where($this->comm_where)->find($id)) {
            $user->delete();
            if ($user->trashed()) {
                $this->setMessage(20304);
                return $this->succeed();
            } else {
                return $this->fail(20305);
            }
        } else {
            return $this->fail(20301);
        }
    }
}
