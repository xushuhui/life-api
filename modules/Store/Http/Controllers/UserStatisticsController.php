<?php

namespace Modules\Store\Http\Controllers;

use App\Models\Store;
use Illuminate\Http\Request;
use Modules\Store\Entities\Order;

class UserStatisticsController extends Controller
{
    protected $store;

    public function __construct()
    {
        parent::__construct();
        $this->store = Store::find($this->store_id);
    }

    /**
     * @OA\Get(path="/store/user/statistics", summary="用户-7日店铺关注人数的统计图",
     *     tags={"store"},
     *     parameters={
     *     },
     *     @OA\Response(response="200", description="{code:0,message:'一直都是成功的。'}"),
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
    public function statistics()
    {
        /**
         * 统计图
         */
        $time       = time();
        $start_time = date('Y-m-d', strtotime('-7 day')) . ' 00:00:00';
        $end_time   = date('Y-m-d', $time) . ' 23:59:59';
        $day_list   = get_days_range($start_time, $end_time);
        $list       = $this->store->likes()
                ->where('created_at', '>=', $start_time)
                ->where('created_at', '<=', $end_time)
                ->get() ?? [];
        $statistics = [];
        foreach ($day_list as $day) {
            $statistics[$day] = 0;
            foreach ($list as $like) {
                if ($day == $like->created_at->toDateString()) ++$statistics[$day];
            }
        }
        return $this->setData($statistics);
    }

    /**
     * @OA\Get(path="/store/user/lists", summary="用户-会员列表",
     *     tags={"store"},
     *     parameters={
     *     {
     *          "name" : "date",
     *          "in" : "string",
     *          "description" : "日期（格式：年-月-日）",
     *          "required" : false
     *      },
     *      {
     *          "name" : "page",
     *          "in" : "int",
     *          "description" : "页码（默认为1）",
     *          "required" : false
     *      },
     *     },
     *     @OA\Response(response="200", description="{code:0,message:'一直都是成功的。current_page-当前页码；per_page-每页数量；total-总数量；total_nums：总关注人数；today_total：筛选日期的关注人数'}"),
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
    public function lists(Request $request)
    {
        $date = $request->input('date', date('Y-m-d'));
        $date = empty($date) ? date('Y-m-d') : $date;


        // 店铺总关注人数、当日关注人数（可筛选）
        $total_nums  = $this->store->likes()->count();
        $today_start = $date . ' 00:00:00';
        $today_end   = $date . ' 23:59:59';
        $today_total = $this->store->likes()
            ->where('created_at', '>=', $today_start)
            ->where('created_at', '<=', $today_end)
            ->count();

        // 会员列表
        $list = $this->store
                ->likes()
                ->with([
                    'user' => function ($query)
                    {
                        $query->select('id', 'phone', 'avatar_url', 'nickname');
                    }
                ])
                ->paginate(20)
                ->toArray() ?? [];

        $list['total_nums']  = $total_nums;
        $list['today_total'] = $today_total;
        return $this->setData($list);
    }

    /**
     * @OA\Get(path="/store/user/userLists", summary="用户-会员统计-会员列表",
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
     *     @OA\Response(response="200", description="{code:0,message:'一直都是成功的。current_page-当前页码；per_page-每页数量；total-总数量；total_nums：总关注人数；order_nums ：消费次数；source：来源'}"),
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
    public function userLists(Request $request)
    {
        $search     = $request->input('search', '');
        $start_date = $request->input('start_date', '');
        $end_date   = $request->input('end_date', '');

        $query = $this->store
            ->likes()
            ->join('users', 'users.id', 'likes.user_id');
        if (!empty($search)) {
            $query = $query->where(function ($query) use ($search)
            {
                $query->where('users.phone', 'LIKE', '%' . $search)
                    ->orWhere('users.nickname', 'LIKE', '%' . $search);
            });
        }
        if (!empty($start_date)) $query = $query->where('likes.created_at', '>=', $start_date);
        if (!empty($end_date)) $query = $query->where('likes.created_at', '<=', $end_date);
        $order_model = new Order;
        $list = $query
                ->select('users.nickname', 'users.phone', 'users.source', 'users.created_at')
                ->paginate(20)->each(function ($item) use ($order_model)
            {
                $item->order_nums = $order_model->where('user_id', $item->user_id)->where('store_id', $this->store_id)->count();
            })->toArray() ?? [];

        // 总关注人数
        $total_nums         = $this->store->likes()->count();
        $list['total_nums'] = $total_nums;
        return $this->setData($list);
    }
}
