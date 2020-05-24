<?php

namespace Modules\Store\Http\Controllers;

use Illuminate\Http\Request;
use Modules\Store\Entities\Order;
use Modules\Store\Entities\StoreUser;
use Modules\Store\Entities\User;

class UserController extends Controller
{
    /**
     * @OA\Get(path="/store/users", summary="我的-用户管理",
     *     tags={"store"},
     *     parameters={
     *     {
     *          "name" : "search",
     *          "in" : "string",
     *          "description" : "手机号或昵称",
     *          "required" : false
     *      },
     *      {
     *          "name" : "start_date",
     *          "in" : "string",
     *          "description" : "开始日期",
     *          "required" : false
     *      },
     *      {
     *          "name" : "end_date",
     *          "in" : "string",
     *          "description" : "结束日期",
     *          "required" : false
     *      },
     *     },
     *     @OA\Response(response="200", description="{code:0,message:'一直都是成功的。current_page-当前页码；per_page-每页数量；total-总数量；share_name-分享人名称；share_phone-分享人手机号'}"),
     * @OA\RequestBody(
     *          @OA\MediaType(mediaType="application/json",
     *              @OA\Schema(
     *                  @OA\Property(property="store-token", type="string", description="商家Token"),
     *             ))
     *      )
     * )
     *
     * @param \Illuminate\Http\Request $request
     *
     * @return \Illuminate\Http\JsonResponse
     */
    public function index(Request $request)
    {
        $search     = $request->input('search', '');
        $start_date = $request->input('start_date', '');
        $end_date   = $request->input('end_date', '');

        $query = User::query()
            ->where([
            'store_id' => $this->store_id,
        ]);
        if (!empty($search)) {
            $query = $query->where(function ($query) use ($search)
            {
                $query->where('phone', 'LIKE', '%' . $search)
                    ->orWhere('nickname', 'LIKE', '%' . $search);
            });
        }
        if (!empty($start_date)) $query = $query->where('created_at', '>=', $start_date);
        if (!empty($end_date)) $query = $query->where('created_at', '<=', $end_date);
        $order_model = new Order;
        $list = $query->select('nickname', 'phone', 'source', 'created_at', 'parent_id', 'share_role')->paginate(20)->each(function ($item) use ($order_model)
            {
                $item->order_nums = $order_model->where('user_id', $item->user_id)->where('store_id', $this->store_id)->count();
            });

        if ($data = $list->toArray()){
            // 获取所有的分享人信息
            $share_stores = StoreUser::getUserByIds(array_column($data, 'parent_id'));
            $share_users = User::getUserByIds(array_column($data, 'parent_id'));
            // 分享人的 名称与手机号
            $list->each(function ($item) use ($share_stores, $share_users){
                $item->share_name = $item->parent_id == 0 ? '' : ($item->share_role == 0 ? $share_users[$item->parent_id]['nickname'] : $share_stores[$item->parent_id]['name']);
                $item->share_phone = $item->parent_id == 0 ? '' : ($item->share_role == 0 ? $share_users[$item->parent_id]['phone'] : $share_stores[$item->parent_id]['phone']);
            });
        }
        return $this->setData($list);
    }
}
