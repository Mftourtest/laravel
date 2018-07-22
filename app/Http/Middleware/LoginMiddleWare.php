<?php

namespace App\Http\Middleware;

use Closure;
use DB;
class LoginMiddleWare
{
    /**
     * Handle an incoming request.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \Closure  $next
     * @return mixed
     */
    public function handle($request, Closure $next)
    {
        // header("Access-Control-Allow-Origin:*");      //设置ajax跨域
      // session(['id'=>668]);
      //   if(session('id'))
      //   {
      //       $this->checkTokensFct(1);
      //       return $next($request);
      //   }else{
      //       echo  "未登录";die;
      //       return redirect('/admin/login/login');
      //   }
           // $_POST['token'] = '1a2b7e9d7ed0a61f339f3ad1a63560c3c48a19f8';
           $token = $request->input("token");
           // echo  $token;die;
            if(empty($token)){
                // echo 123;die;
                  // Response::show(400,'请求出错');
                $arrb = [];
                $arrb['code']=400;
                $arrb['msg'] = "请求出错";
                 // response.setContentType("text/html;charset=utf-8");
                return response()->json($arrb);

                // return response('<h1>Service Unavailable</h1>', 503)->header('Content-Type', 'text/html');
                // var_dump($arrb);die;
                // return json_encode($arrb);
                // exit;
            }
            // $token = $_GET['token'];
            // $token ='6f329eac42ffbaaee01cc110ddf98bbe27a5b26e';
            $tokencheck = $this->checkTokensFct($token);
            // echo $tokencheck;die;
            if ($tokencheck !== 90001){
                // return 1;
                $arr = [];
                $arr['code']=401;
                $arr['msg'] = "用户没有权限";
               return response()->json($arr);
            }
            return $next($request);
            // else{
            //     // return 0;
            //     Response::json(401,"用户没有权限");
            // }

    }
     //token验证方法
    public function checkTokensFct($token)
    {
        //  $p_id = session('id');
        // $rooms = DB::table('food_area')->where("partner_id",$p_id)->get()->toArray();
        // echo "<pre>";
        // var_dump($rooms);die;
        // echo 123;die;
        // $res = $this->db->fetchRow("select time_out from userinfo where token='{$token}'");
        // if (!empty($res)){
        //     if (time() - $res['time_out'] > 0) {
        //         return 90003;  //token长时间未使用而过期，需重新登陆
        //     }
        $res= DB::table('partner_admin')->where("token",$token)->get()->toArray();
        // var_dump($res);die;
            // $new_time_out = time() + 604800;//604800是七天
            if ($res){
                return 90001;  //token验证成功，time_out刷新成功，可以获取接口信息
            }else{
                  return 90002;  //token错误验证失败
        }
    }
}
