<?php

namespace App\Http\Controllers;

use Illuminate\Foundation\Bus\DispatchesJobs;
use Illuminate\Routing\Controller as BaseController;
use Illuminate\Foundation\Validation\ValidatesRequests;
use Illuminate\Foundation\Auth\Access\AuthorizesRequests;

class Controller extends BaseController
{
    use AuthorizesRequests, DispatchesJobs, ValidatesRequests;
      /**
     * 返回json数据
     * @param int $status  返回状态
     * @param  $msg (array  string int) 返回信息
     * @param  $data (array ) 返回数组
     * @return string
     */
    public function json_encode($status, $msg, $data)
    {
        $arr['code']  = $status;
        $arr['msg'] = $msg;
        $arr['data'] = $data;
        $json = json_encode($arr,JSON_UNESCAPED_UNICODE);
        return $json;
    }

      /**
     * 返回json数据
     * @param int $status  返回状态
     * @param  $msg (array  string int) 返回信息
     * @return string
     */
    public function json_encode_nodata($status, $msg)
    {
        $arr['code']  = $status;
        $arr['msg'] = $msg;
        $json = json_encode($arr,JSON_UNESCAPED_UNICODE);
        return $json;
    }

    //生成token方法代码
    public static function setToken()
    {
        $str = md5(uniqid(md5(microtime(true)),true));  //生成一个不会重复的字符串
        $str = sha1($str);  //SHA1加密
        return $str;
    }
}
