<?php

namespace Modules\Store\Http\Controllers;

use Modules\Store\Entities\Good;
use Modules\Store\Http\Requests\GoodRequest;

class GoodController extends Controller
{
    /**
     * @OA\Get(path="/store/good", summary="商品列表",
     *     tags={"store"},
     *     parameters={
     *      {
     *          "name" : "page",
     *          "in" : "int",
     *          "description" : "页码（默认为1）",
     *          "required" : true
     *      },
     *     {
     *          "name" : "search",
     *          "in" : "string",
     *          "description" : "名称搜索",
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
     * @param \Modules\Store\Http\Requests\CouponRequest $request
     *
     * @return \Illuminate\Http\JsonResponse
     */
    public function index()
    {
        $search = request()->input('search', '');
        $good = Good::where(['store_id' => $this->store_id]);
        if ($search){
            $good = $good->where('name', 'LIKE', $search . '%');
        }
        $data = $good->paginate(10);
        return $this->setData($data);
    }

    /**
     * @OA\Get(path="/store/good/{id}",
     *   tags={"store"},
     *   summary="商品详情",
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
        $data = Good::find($id);
        if ($data){
            return $this->setData($data);
        }else{
            return $this->fail(20205);
        }
    }

    /**
     * @OA\Put(path="/store/good", summary="新增/更新商品",
     *     tags={"store"},
     *     parameters={
     *      {
     *          "name" : "id",
     *          "in" : "int",
     *          "description" : "商品Id（新增时为0即可，或者不传）",
     *          "required" : false
     *      },
     *     {
     *          "name" : "name",
     *          "in" : "string",
     *          "description" : "商品名称",
     *          "required" : true
     *      },
     *     {
     *          "name" : "photo",
     *          "in" : "string",
     *          "description" : "封面",
     *          "required" : true
     *      },
     *     {
     *          "name" : "price",
     *          "in" : "number",
     *          "description" : "原价",
     *          "required" : true
     *      },
     *     {
     *          "name" : "discount_price",
     *          "in" : "number",
     *          "description" : "折扣价",
     *          "required" : true
     *      }
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
    public function update(GoodRequest $request)
    {
        $request->store_id = $this->store_id;
        if (Good::checkName($request->name, $request->id)){
            return $this->fail(20206);
        }
        Good::createOrUpdate($request);
        $this->setMessage(20201);
        return $this->succeed();
    }

    /**
     * @OA\Delete(path="/store/good/{id}",
     *   tags={"store"},
     *   summary="商品删除",
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
        if ($good = Good::where(['store_id' => $this->store_id])->find($id)){
            $good->delete();
            if($good->trashed()){
                $this->setMessage(20203);
                return $this->succeed();
            }else{
                return $this->fail(20204);
            }
        }else{
            return $this->fail(20205);
        }
    }
}
