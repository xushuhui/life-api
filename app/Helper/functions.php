<?php

//是否为手机号码
if (!function_exists('check_mobile')) {
    function check_mobile(string $text)
    {
        $search = '/^0?1[3|4|5|6|7|8][0-9]\d{8}$/';
        if (preg_match($search, $text)) return true;
        else return false;
    }
}

if (!function_exists('hash_make')) {
    /**
     * hash加密
     *
     * @param string $password
     *
     * @return string
     */
    function hash_make(string $password): string
    {
        return password_hash($password, PASSWORD_DEFAULT);
    }
}

if (!function_exists('hash_verify')) {
    /**
     * [hash_verify]
     *
     * @param string $pass      [description]
     * @param string $hash_pass [description]
     *
     * @return bool
     * @author             :cnpscy <[2278757482@qq.com]>
     * @chineseAnnotation  :hash解密
     * @englishAnnotation  :
     *
     * @version            :1.0
     */
    function hash_verify(string $pass, string $hash_pass): bool
    {
        return password_verify($pass, $hash_pass);
    }
}

/**
 * 生成混合code
 *
 * @param integer $length [description]
 *
 * @return             [type]          [description]
 * @author             :cnpscy <[2278757482@qq.com]>
 * @chineseAnnotation  :登录token值
 * @englishAnnotation  :
 */
function make_blend_code($length = 20): string
{
    $chars = ['a', 'b', 'c', 'd', 'e', 'f', 'g', 'h',
        'i', 'j', 'k', 'l', 'm', 'n', 'o', 'p', 'q', 'r', 's',
        't', 'u', 'v', 'w', 'x', 'y', 'z', 'A', 'B', 'C', 'D',
        'E', 'F', 'G', 'H', 'I', 'J', 'K', 'L', 'M', 'N', 'O',
        'P', 'Q', 'R', 'S', 'T', 'U', 'V', 'W', 'X', 'Y', 'Z',
        '0', '1', '2', '3', '4', '5', '6', '7', '8', '9'];
    $array = array_rand($chars, $length);
    $rand  = '';
    for ($i = 0; $i < $length; $i++) {
        $rand .= $chars[$array[$i]];
    }
    return $rand;
}

/**
 * 字符串是否是数字与字母组合
 *
 * @param $pwd
 *
 * @return bool
 */
function check_number_and_str($pwd)
{
    // 数字
    if (preg_match("/^\d*$/", $pwd)) return false;
    // 字母
    if(preg_match("/^[a-z]*$/i",$pwd)) return false;
    // 组合
    if(preg_match("/^[a-z\d]*$/i",$pwd)) return true;

    return false;
}

if (!function_exists('get_days_range')) {
    /**
     * 指定日期范围之内的所有天
     *
     * @param string $start_date 开始日期
     * @param string $end_date   结束日期
     * @param string $format     返回格式
     *
     * @return array
     */
    function get_days_range(string $start_date, string $end_date, string $format = 'Y-m-d')
    {
        $end   = date($format, strtotime($end_date)); // 转换为月
        $range = [];
        $i     = 0;
        do {
            $day   = date($format, strtotime($start_date . ' + ' . $i . ' day'));
            $range[] = $day;
            $i++;
        } while ($day < $end);
        return $range;
    }
}