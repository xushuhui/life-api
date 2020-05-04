<?php

namespace App\Http\Controllers\Store;

use Illuminate\Support\Facades\Request;

trait Common
{

    /**
     * API接口返回格式统一
     *
     * @param     $data
     * @param int $status_code
     *
     * @return \Illuminate\Http\JsonResponse
     */
    public static function ajaxReturn($data, $status_code = 200)
    {
        return response()->json(self::checkAjaxReturn($data), $status_code);
    }

    public static function check_error(Request $request)
    {
        return $request->only(['status', 'data', 'msg', 'code']);
    }

    /**
     * 检测返回的数组，参数是否匹配，不匹配主动生成空
     *
     * @param array $data
     *
     * @return array
     */
    public static function checkAjaxReturn(array $data = [])
    {
        $data['data']   = (isset($data['data']) && is_object($data['data'])) ? $data['data']->toArray() : ($data['data'] ?? []);
        $data['status'] = intval($data['status'] ?? (empty($data['data']) ? 0 : 1));
        $data['msg']    = $data['msg'] ?? (empty($data['status']) ? '数据不存在！' : '');
        return $data;
    }
}
