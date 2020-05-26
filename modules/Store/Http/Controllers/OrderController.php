<?php

namespace Modules\Store\Http\Controllers;

use Illuminate\Http\Request;
use Modules\Store\Entities\Coupon;
use Modules\Store\Entities\Order;
use Modules\Store\Entities\Store;
use Modules\Store\Entities\StoreUser;
use Modules\Store\Entities\User;

/**
 * 订单
 *
 * Class CardUserController
 *
 * @package Modules\Store\Http\Controllers
 */
class OrderController extends Controller
{
    /**
     * @OA\Get(path="/store/order/statistics", summary="订单-订单图表",
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
    public function statistics(Request $request)
    {
        /**
         * 订单7日统计图
         */
        $time       = time();
        $start_time = date('Y-m-d', strtotime('-7 day')) . ' 00:00:00';
        $end_time   = date('Y-m-d', $time) . ' 23:59:59';
        $day_list   = get_days_range($start_time, $end_time);
        $list       = Order::query()
                ->where('store_id', $this->store_id)
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
     * @OA\Get(path="/store/order/statisticCoupons", summary="订单-优惠券分析",
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
    public function statisticCoupons(Request $request)
    {
        /**
         * 订单7日优惠券统计图
         */
        $time       = time();
        $start_time = date('Y-m-d', strtotime('-7 day')) . ' 00:00:00';
        $end_time   = date('Y-m-d', $time) . ' 23:59:59';
        $day_list   = get_days_range($start_time, $end_time);
        $list       = Order::query()
                ->where('coupon_id', '<>', 0)
                ->where('store_id', $this->store_id)
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
     * @OA\Get(path="/store/order/lists", summary="订单-列表",
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
     *     @OA\Response(response="200", description="{code:0,message:'一直都是成功的。current_page-当前页码；per_page-每页数量；total-总数量；total_nums：总核销订单数；today_total：今日核销单数；nickname：昵称；phone：手机号；avatar_url：头像；store_address：商家地址'}"),
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

        // 店铺总订单数、当日订单数（可筛选）
        $total_nums  = Order::query()->where('store_id', $this->store_id)->count();
        $today_start = $date . ' 00:00:00';
        $today_end   = $date . ' 23:59:59';
        $today_total = Order::query()->where('store_id', $this->store_id)
            ->where('created_at', '>=', $today_start)
            ->where('created_at', '<=', $today_end)
            ->count();

        // 订单列表
        $list = Order::query()->where('store_id', $this->store_id)
                ->where('created_at', '>=', $today_start)
                ->where('created_at', '<=', $today_end)
                ->orderBy('id', 'DESC')
                ->paginate(10)
                ->toArray() ?? [];

        $list['total_nums']  = $total_nums;
        $list['today_total'] = $today_total;

        if ($list['data']){
            $users = User::getUserByIds(array_column($list['data'], 'user_id'), ['id', 'nickname', 'phone', 'avatar_url']);
            $stores = Store::getStoreByIds(array_column($list['data'], 'store_id'), ['id', 'store_address']);
            foreach ($list['data'] as &$v){
                $v['nickname'] = $users[$v['user_id']]['nickname'] ?? '';
                $v['phone'] = $users[$v['user_id']]['phone'] ?? '';
                $v['avatar_url'] = $users[$v['user_id']]['avatar_url'] ?? '';
                $v['store_address'] = $stores[$v['store_id']]['store_address'] ?? '';
            }
        }
        return $this->setData($list);
    }


    /**
     * @OA\Get(path="/store/order/userLists", summary="订单-订单统计",
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
     *     {
     *          "name" : "coupon_type",
     *          "in" : "number",
     *          "description" : "优惠券类型：1.普通券；2.联盟券；3.次卡券；4.储值券",
     *          "required" : false
     *      },
     *     },
     *     @OA\Response(response="200", description="{code:0（0.成功，1.失败）,message:'提示语，一直都是成功的。current_page-当前页码；per_page-每页数量；total-总数量；nickname-昵称；phone：手机号；created_at：消费时间；coupon_name：优惠券名称'}"),
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
    public function orderLists(Request $request)
    {
        $search     = $request->input('search', '');
        $start_date = $request->input('start_date', '');
        $end_date   = $request->input('end_date', '');
        $coupon_type   = $request->input('coupon_type', 1);

        $query = Order::query()
            ->from('orders as o')
            ->join('users as u', 'u.id', 'o.user_id')
            ->join('user_coupons as uc', 'uc.id', 'o.coupon_id')
            ->where('uc.coupon_type', $coupon_type);
        if (!empty($search)) {
            $query = $query->where(function ($query) use ($search)
            {
                $query->where('u.phone', 'LIKE', '%' . $search)
                    ->orWhere('u.nickname', 'LIKE', '%' . $search);
            });
        }
        if (!empty($start_date)) $query = $query->where('o.created_at', '>=', $start_date);
        if (!empty($end_date)) $query = $query->where('o.created_at', '<=', $end_date);
        $list = $query->orderBy('id', 'DESC')
                ->select('o.*', 'u.nickname', 'u.phone', 'uc.coupon_id')
            ->paginate(10)
            ->toArray() ?? [];
        if ($list['data']){
            $coupons = Coupon::getCouponByIds(array_column($list['data'], 'user_id'), ['id', 'coupon_name']);
            foreach ($list['data'] as &$v){
                $v['coupon_name'] = $coupons[$v['coupon_id']]['coupon_name'] ?? '';
            }
        }
        return $this->setData($list);
    }

    /**
     * @OA\Get(path="/store/order/coupons", summary="订单页面的优惠券分析列表",
     *     tags={"store"},
     *     parameters={
     *     {
     *          "name" : "coupon_type",
     *          "in" : "number",
     *          "description" : "优惠券类型：1.普通券；2.联盟券；3.次卡券；4.储值券",
     *          "required" : false
     *      },
     *      {
     *          "name" : "page",
     *          "in" : "int",
     *          "description" : "页码（默认为1）",
     *          "required" : false
     *      },
     *     },
     *     @OA\Response(response="200", description="{code:0,message:'一直都是成功的。current_page-当前页码；per_page-每页数量；total-总数量；coupon_name：名称；end_time：结束时间；created_at：开始时间'}"),
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
    public function coupons(Request $request)
    {
        $coupon_type   = $request->input('coupon_type', 1);

        $list = Coupon::query()
            ->where('store_id', $this->store_id)
            ->where('coupon_type', $coupon_type)
            ->orderBy('id', 'DESC')
            ->paginate(10);

        return $this->setData($list);
    }

    /**
     * @OA\Get(path="/store/order/couponLists/优惠券Id", summary="订单-优惠券分析列表",
     *     tags={"store"},
     *     parameters={
     *     {
     *          "name" : "coupon_type",
     *          "in" : "number",
     *          "description" : "优惠券类型：1.普通券；2.联盟券；3.次卡券；4.储值券",
     *          "required" : false
     *      },
     *      {
     *          "name" : "page",
     *          "in" : "int",
     *          "description" : "页码（默认为1）",
     *          "required" : false
     *      },
     *     },
     *     @OA\Response(response="200", description="{code:0,message:'一直都是成功的。current_page-当前页码；per_page-每页数量；total-总数量；nickname：昵称；phone：手机号；store_address：商家地址；surplus_nums：剩余次数/剩余金额；created_at：消费时间；store_user_name：核销人'}"),
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
    public function couponLists(int $coupon_id)
    {
        $list = Order::query()
            ->from('orders as o')
            ->join('user_coupons as uc', 'uc.id', 'o.coupon_id')
            ->where('o.store_id', $this->store_id)
            ->where('uc.coupon_id', $coupon_id)
            ->select('o.*', 'uc.surplus_nums')
            ->orderBy('o.id', 'DESC')
            ->paginate(10)
            ->toArray() ?? [];
        if ($list['data']){
            $users = User::getUserByIds(array_column($list['data'], 'user_id'), ['id', 'nickname', 'phone']);
            $stores = Store::getStoreByIds(array_column($list['data'], 'store_id'), ['id', 'store_address']);
            $store_users = StoreUser::getUserByIds(array_column($list['data'], 'store_id'), ['id', 'name']);
            foreach ($list['data'] as &$v){
                $v['nickname'] = $users[$v['user_id']]['nickname'] ?? '';
                $v['phone'] = $users[$v['user_id']]['phone'] ?? '';
                $v['store_address'] = $stores[$v['store_id']]['store_address'] ?? '';
                $v['store_user_name'] = $store_users[$v['store_user_id']]['name'] ?? '';
            }
        }
        return $this->setData($list);
    }

    /**
     * @OA\Get(path="/store/order/couponListExport/优惠券Id", summary="订单-优惠券分析-导出（直接跳转页面,就走形参，header的token传递到参数即可）",
     *     tags={"store"},
     *     parameters={
     *     {
     *          "name" : "store-token",
     *          "in" : "string",
     *          "description" : "商家Token",
     *          "required" : false
     *      },
     *     },
     *     @OA\Response(response="200", description="{code:0,message:'一直都是成功的。current_page-当前页码；per_page-每页数量；total-总数量；nickname：昵称；phone：手机号；store_address：商家地址；surplus_nums：剩余次数/剩余金额；created_at：消费时间；store_user_name：核销人'}"),
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
    public function couponListExport(int $coupon_id)
    {
        //输出的文件类型为excel
        header("Content-type:application/vnd.ms-excel");
        //提示下载
        header("Content-Disposition:attachement;filename=优惠券分析_".date("Ymd-his").".xls");
        $list = Order::query()
                ->from('orders as o')
                ->join('user_coupons as uc', 'uc.id', 'o.coupon_id')
                ->where('o.store_id', $this->store_id)
                ->where('uc.coupon_id', $coupon_id)
                ->select('o.*', 'uc.surplus_nums')
                ->orderBy('o.id', 'DESC')
                ->get()
                ->toArray() ?? [];
        $ReportArr = [
            ['用户昵称', '用户号码', '剩余次数/额度', '核销金额', '消费地址', '消费时间', '核销人']
        ];
        if ($list){
            $users = User::getUserByIds(array_column($list, 'user_id'), ['id', 'nickname', 'phone']);
            $stores = Store::getStoreByIds(array_column($list, 'store_id'), ['id', 'store_address']);
            $store_users = StoreUser::getUserByIds(array_column($list, 'store_id'), ['id', 'name']);
            foreach ($list as &$v){
                $v['nickname'] = $users[$v['user_id']]['nickname'] ?? '';
                $v['phone'] = $users[$v['user_id']]['phone'] ?? '';
                $v['store_address'] = $stores[$v['store_id']]['store_address'] ?? '';
                $v['store_user_name'] = $store_users[$v['store_user_id']]['name'] ?? '';
                $ReportArr[] = [$v['nickname'], $v['phone'], $v['surplus_nums'], $v['use_nums'], $v['store_address'], $v['created_at'], $v['store_user_name']];
            }
        }
        $ReportContent = '';
        $num1 = count($ReportArr);
        for($i=0;$i<$num1;$i++){
            $num2 = count($ReportArr[$i]);
            for($j=0;$j<$num2;$j++){
                //ecxel都是一格一格的，用\t将每一行的数据连接起来
                $ReportContent .= '"'.$ReportArr[$i][$j].'"'."\t";
            }
            //最后连接\n 表示换行
            $ReportContent .= "\n";
        }
        //用的utf-8 最后转换一个编码为gb
        $ReportContent = mb_convert_encoding($ReportContent,"gb2312","utf-8");
        //输出即提示下载
        echo $ReportContent;
        exit;
    }
}
