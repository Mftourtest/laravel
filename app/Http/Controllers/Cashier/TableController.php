<?php

namespace App\Http\Controllers\Cashier;

use App;
use Illuminate\Http\Request;
use Illuminate\Http\Response;
use Illuminate\Support\Facades\Cookie;
use Illuminate\Cookie\CookieJar;
use App\Http\Controllers\Controller;
use DB;
use Validator;
use Illuminate\Support\Facades\Input;
use App\Models\PartnerAdmin;
use App\Models\Partner;
use App\Models\FoodArea;
use App\Models\FoodAreaDesk;
use App\Lib\Common\Helper;
use App\Lib\Common\Printer;
use App\Lib\Common\NewPrinter;
use App\Models\Order;
use App\Models\Coupon;
class TableController extends Controller
{
    private $lang;
    private $suffix;
    public function __construct(){
        $this->lang = $_GET['lang'];
        App::setLocale($this->lang);
        if($this->lang=="zh_cn"){
            $this->suffix = "";
        }
        elseif($this->lang=="en_us"){
            $this->suffix = "_en";
        }
        else{
            $this->suffix = "_vi";
        }
        date_default_timezone_set("Asia/Bangkok"); //系统时间设成泰国时区时间
    }
    //返回餐厅的房间信息
    public function room(Request $request) {
        $partner_id = $request->input("partner_id");
       //完全转成数组
        $rooms = DB::table('food_area')->where("partner_id",$partner_id)->get()->map(function ($value) {
                    return (array)$value;
                })->toArray();
        //返回桌面信息
        $deskinfos = DB::table('food_area_desk')->join('food_desk_state', function($join)  
            {  
                $join->on('food_area_desk.desk_state', '=', 'food_desk_state.id');  
            })->select('*')  
              ->where('partner_id',$partner_id)                 
              ->orderBy('food_area_desk.desk_sn', 'asc')  
              ->get()->map(function ($value) {
                return (array)$value;
            })->toArray();
      
        //返回桌面信息
        $info = [];
        $info['room'] = $rooms;
        $info['desk'] = $deskinfos;

        // $arr = [];
        if($info) {
            return $this->json_encode(1,"查询成功",$info);

         }else{
            return  $this->json_encode_nodate(0,"查询失败");
         }
         // return json_encode($arr,JSON_UNESCAPED_UNICODE);
    }

    //根据房间id返回房间桌位信息
    public function table(Request $request) {
        $p_id = $request->input("partner_id");
        $room_id = $request->input("room_id");
        $deskinfos = DB::table('food_area_desk')->join('food_desk_state', function($join)  
            {  
                $join->on('food_area_desk.desk_state', '=', 'food_desk_state.id');  
            })->select('*')  
            ->where('partner_id',$p_id)->where('area_id',$room_id)                
              ->orderBy('food_area_desk.desk_sn', 'asc')  
              ->get()->map(function ($value) {
                return (array)$value;
            })->toArray();   
        if($deskinfos){
            return $this->json_encode(1,"查询成功",$deskinfos); 
        }else{
            return  $this->json_encode_nodate(0,"查询失败");
        }           
    }

    //桌台-未结账返回结账信息页
    public function paymoney_info(Request $request) {
        //获取partner_id
        $p_id = $request->input("partner_id");
         // 获取桌位号
        $desk_sn = $request->input("desk_sn");
        $status= DB::table('food_area_desk')->where("partner_id",$p_id)->where("desk_sn",$desk_sn)->first()->desk_state;
        // echo $status;
        if($status!=3) return $this->json_encode_nodata(0,"当前餐桌状态不是未结账状态");
        //查询临时订单表
          $order_temp = DB::table('order_temp')->leftJoin("food",'food.id', '=', 'order_temp.food_id')->where('order_temp.partner_id',$p_id)->where('order_temp.desk_sn',$desk_sn)->where('order_temp.order_id',0)
          ->where('order_temp.state',0)->select('order_temp.*', 'food.title', 'food.title_en','food.title_vi','food.pack')->get();
        // dump($order_temp);die;
        //拼接菜单明细数组
        $menu_list = [];
        foreach ($order_temp as $k => $v) {
           if($v->pack==1) {
                $package = DB::table('food_packages')->where('id',$v->package_id)->first();
                $menu_list[$k]["title"] = $v->title.'('.$package->name.')';
                $menu_list[$k]["title_en"] = $v->title_en.'('.$package->name_en.')';
                $menu_list[$k]["title_vi"] = $v->title_vi.'('.$package->name_vi.')';
                $menu_list[$k]["package_id"] = $v->package_id;
           }else{
                 $menu_list[$k]["title"] = $v->title;
                $menu_list[$k]["title_en"] = $v->title_en;
                $menu_list[$k]["title_vi"] = $v->title_vi;
           }
           $menu_list[$k]["id"] = $v->id;  //下单一道菜的id
           $menu_list[$k]["temp_order_no"] = $v->temp_order_no; //下单总编号，对应下单的所有的菜
           $menu_list[$k]["food_id"] = $v->food_id;
           $menu_list[$k]["number"] = $v->number;
           $menu_list[$k]["price"] = $v->price;
           $menu_list[$k]["remark"] = $v->remark;
           $menu_list[$k]["state"] = $v->state;         //订单状态
           $menu_list[$k]["is_refund"] = $v->is_refund; //1是已退菜
           $menu_list[$k]["is_print"] = $v->is_print;         
        }
        if(!empty($menu_list)){
            $partner = DB::table('partner')->where('id',$p_id)->select('fee_tax','fee_srv','discount')->first();
            $arr = get_object_vars($partner);//first转数组
            $brr['code'] = 1;
            $brr['msg'] = "查询成功";
            $brr['partner'] = $arr;
            $brr['data'] = $menu_list;
            return json_encode($brr,JSON_UNESCAPED_UNICODE);
        }
        else{
            return $this->json_encode_nodata(0,"查询失败");
        }
    }

    //点击桌号返回订单信息
     public function order(Request $request) {
          //通过token查询出来用户id
        $token = $request->input("token");
        $p_id= DB::table('partner_admin')->where("token",$token)->first()->partner_id;
        // $desk_sn = $_GET['desk_sn'];
        // 获取桌位号
        $desk_sn = $request->input("desk_sn");
        $enomination = 0;
        // $enomination = $_GET['price']; //代金券面额
        // 获取代金券
        $enomination = $request->input("price"); //代金券面额
        $deskinfo = DB::table('food_area_desk')->where('partner_id',$p_id)->where('desk_sn',$desk_sn)->first();
        $order_temp = DB::table('order_temp')->where('partner_id',$p_id)
                                             ->where('desk_sn',$desk_sn)
                                             ->where('order_id',0)->get();
        $pinfo = Partner::find($p_id);
        $total_price = 0;  //原总价
        foreach($order_temp as $v){
            $total_price =  $v->price * $v->number + $total_price;
        }
        $srv_price = round($total_price * $pinfo->fee_srv); //服务费
        $tax_price = round($total_price * $pinfo->fee_tax); //税费
        $discount_price = round($total_price * (1 - $pinfo->discount));    //打折要减去的价格
        if($total_price-$discount_price+$srv_price+$tax_price>=$enomination){ //如果打折后价格大于代金券的价格
            $last_price = round($total_price - $discount_price + $srv_price + $tax_price - $enomination);    //最终应付价格
        }
        else{
            $last_price = 0;
        }
        //检查未结账订单是否已手动打印
        $orderNewTemp = DB::table('order_temp')->where('partner_id',$p_id)
                       ->where('desk_sn',$desk_sn)->where('is_print',0)
                       ->where('order_id',0)->get();
        if(!empty($orderNewTemp[0])){
            $is_print = 1; //需要手动打印
        }
        else{
            $is_print = 0; //不需要手动打印
        }
        $data = [];
        $data['enomination']=$enomination;
        $data['desk_sn']=$desk_sn;
        $data['total_price']=$total_price;
        $data['discount_price']=$discount_price;
        $data['last_price']=$last_price;
        $data['srv_price']=$srv_price;
        $data['tax_price']=$tax_price;
        $data['is_print']=$is_print;
         return  $this->json_encode(1,$data);
        // return view('waiter/reckoning')->with("lang",$this->lang)->with("enomination",$enomination)
        //     ->with('desk_sn',$desk_sn)->with(['total_price'=>$total_price,'discount_price'=>$discount_price,'last_price'=>$last_price])
        //     ->with(['srv_price'=>$srv_price,'tax_price'=>$tax_price])->with("is_print",$is_print);
     }

     //订单-今天统计返回今天订单数
     public function today_orders(Request $request) {
        //根据传入的参数判断获取日还是月还是周
        $mark = $request->input("mark");
        if($mark=="day") {
         //获取当天凌晨0:00的时间戳
            // $today = strtotime(date('Y-m', time()));
            
            $today = strtotime(date('Y-m-d', time()));
        }elseif ($mark=="week") {
            $today = time()-(60*60*24*7);
        }elseif($mark=="month"){

            $today = strtotime(date('Y-m', time()));
        }else{
            return $this->json_encode_nodata(2,"参数传入错误");
        }
            // echo date("Y-m-d H:i:s",$today);die;
            // echo $today;
         //获取partner_id
        $p_id = $request->input("partner_id");
        $pinfo = Partner::find($p_id);
         //获取order_temp下面的已结账订单和已取消订单
        $order_temps = DB::table('order_temp')->where('partner_id',$p_id)->where('create_time','>=',$today)
        ->where('state','>',0)->where('state','<',3)->get();

        //dd($order_temps);exit;
        if($order_temps->isEmpty()) return $this->json_encode_nodata(0,"没有订单");
          //遍历所有的订单相同的订单号放到一起
        $orders = [];
        foreach ($order_temps as $k => $v) {
              $orders[$v->temp_order_no][] = $v;
        }
         //返回数据到前台
         $dorders_info = [];

        foreach ($orders as $k => $v) {
                    //计算每一个订单的价格
                    $total_price= 0;
                    foreach($v as $kk=>$vv) {
                    $total_price =  $vv->price * $vv->number + $total_price;
                    }
                    $srv_price = $total_price * $pinfo->fee_srv; //服务费
                    $tax_price = $total_price * $pinfo->fee_tax; //税费
                    $discount_price = $total_price * (1 - $pinfo->discount);    //打折要减去的价格
                    // if($total_price-$discount_price+$srv_price+$tax_price>=$enomination){ //如果打折后价格大于代金券的价格
                    //     $last_price = round($total_price - $discount_price + $srv_price + $tax_price - $enomination);    //最终应付价格
                    // }
                    // else{
                    //     $last_price = 0;
                    // }
                     $last_price = $total_price - $discount_price + $srv_price + $tax_price;    //最终应付价格
                     $dorders_info[$k]['yingshou_price'] = $last_price;
                     $dorders_info[$k]['order_price'] = $total_price;
                     $dorders_info[$k]['time'] = $v[0]->create_time;
                     $dorders_info[$k]['temp_order_no'] = $k;
                     $dorders_info[$k]['moling'] = round($last_price-round($last_price),2);
                     $dorders_info[$k]['state'] =$v[0]->state;
         }
         //进行合计
        $heji = [];
        $heji['num']= count($dorders_info);
        $order_price = 0;
        $yingshou_price = 0;
        $arr = [];
        $i = 0;
        foreach ($dorders_info as $k => $v) {
             $arr[$i]['temp_order_no'] = $v['temp_order_no'];
             $arr[$i]['time'] = $v['time'];
             $arr[$i]['order_price'] = $v['order_price'];
             $arr[$i]['yingshou_price'] = $v['yingshou_price'];
             $arr[$i]['moling'] = $v['moling'];
             $arr[$i]['state'] = $v['state'];
             $order_price += $arr[$i]['order_price'];
             $yingshou_price += $arr[$i]['yingshou_price'];
             $i = $i+1;
         }
        $heji['order_price'] = $order_price;
        $heji['yingshou_price'] = $yingshou_price;
        //总结数据
        $data = [];
        $data['code'] = 1;
        $data['msg'] = "查询成功";
        $data['heji'] = $heji;
        $data['data'] = $arr;
        return json_encode($data,JSON_UNESCAPED_UNICODE);

           
      }
    //接口注册，暂时不写
    public function register(Request $request) {
         // $userID = 'admin3';
        // $userPwd = '123456';
        // $userTel = '15939956756';
        // $userID = isset($_POST['name']) ? $_POST['name'] : '';
        // $userPwd = isset($_POST['password']) ? md5($_POST['password']) : '';
        // $userTel = isset($_POST['tel']) ? $_POST['tel']:'';
        // if(!empty($userID)&&!empty($userPwd)&&!empty($userTel)){
        //     $sql = "select count(id) as num from userInfo where userTel='{$userTel}' or userID='{$userID}'";
        //     $num = $this->db->fetchRow($sql);
        //     //根据不同的返回结果，对其进行相应的响应
        //     if ($num['num']==0) {
        //         $sql = "insert into userInfo (userID,userPwd,userTel) values ('{$userID}','{$userPwd}','{$userTel}')";
        //         // var_dump($sql);die;
        //         $data = $this->db->query($sql);
        //         if(!empty($data)){
        //             Response::json(200,"注册成功",$data);
        //         }else if(empty($data)){
        //             Response::json(404,"记录不存在");
        //         }else if($data==false){
        //             Response::json(406,"读取数据失败");
        //         }else{
        //             Response::json(500,"服务器发生错误");
        //         }
        //     }else{
        //         Response::json(400,"用户名或手机号重复",$num);
        //     }
        // }else{
        //     Response::json(406,"读取数据失败");
        // }
    }
    //接口登陆
    public function login(Request $request) {
         // $_POST['userID']='admin1';
        // $_POST['userPwd']='123456';
        $username = $request->input("userName");
        $userpwd = $request->input("userPwd");
        // echo $_POST['userID'];die;
        if(empty($username) || empty($userpwd)){
            return $this->json_encode(0,"请输入账号和密码");
        }
        // $userID=$_POST['userID'];
        // $userPwd=$_POST['userPwd'];
        // $userpwd=md5($userpwd);
        $token = $this->setToken();
        // echo $token;die;
        // $time_out = strtotime("+7 days");
        //连接数据库进行查询
     // $data=$this->db->fetchRow("select userID,userName,userTel,url from userinfo where userID='{$userID}' and userPwd='{$userPwd}'");
        $data = DB::table('partner_admin')->where(['account'=>$username,"password"=>$userpwd])->get()->toArray();
        // dump($data[0]->id);die;
        if(empty($data)){
            // Response::show(404,'账号或密码输入错误');
            // exit;
            return $this->json_encode_nodata(1,"账号或密码输入错误");
        }else{
            //更新token到数据库
            $data['token']=$token;
             //$num = DB::table('partner_admin')->where('id', $data[0]->id)->update(['token' => $token]);
             $num = 1;
             if($num==1) {
                    session(['username'=>$data[0]->account]);
                    session(['id'=>$data[0]->id]);
                    return $this->json_encode(2,"登陆成功",$data[0]);
             }else{
                $data = object();
                    return $this->json_encode(3,"未知错误",$data);

             }

        }

      // $rst2=$this->db->query("update userinfo set token='{$token}',time_out='{$time_out}' where userID='{$userID}'");

        // $cnt = $rst2->rowCount();
         // var_dump($data);die;
        //根据不同的返回结果，对其进行相应的响应
        // if ($cnt >= 1) {
        //     Response::json(201,"登录成功",$data);
        // }else if ($rst2 == false) {
        //     Response::json(407,"登录失败");
        // } else {
        //     Response::json(500,"未知错误");
        // }
    }

    //点餐-获取所有商户菜单分类和菜和规格
    public function food_info(Request $request)
    {
        $p_id = $request->input("partner_id");
        // 获取桌位号
        //$desk_sn = $request->input("desk_sn");
        //if(empty($desk_sn)) return $this->json_encode(0,"缺少桌号","");
        //获取当前用户下所有的菜品种类信息
        $arr = [];
        //按partner_id分店获取不到菜分类
        $cateinfo = DB::table('food_cate')->select('id','team_id','partner_id','name','name_en','name_vi','display_order')
                    ->where('partner_id',$p_id)->orderBy('display_order','desc')->get()->toArray();
        $arr = $this->objectToArray($cateinfo); //将对象转为数组
        // dump($arr);die;
        //根据当前的菜品种类获取所有的菜信息
        $foods = [];
        $food =  DB::table('food')->select('id', 'team_id','cate_id','food_no','title','title_en','title_vi','price','display_order','pack','status')->where('team_id',$arr[0]['team_id'])->orderBy('display_order','desc')->get()->map(function ($value) {
            return (array)$value;
        })->toArray();
        //每次遍历所有的食物获取套餐
        foreach ($food as $k => $v) {
            $foods[$k] = $v;
            if($v['pack']==1){                 
                $prr = DB::table('food_packages')->where('food_id',$v['id'])->get()->map(function ($value) {
                    return (array)$value;
                })->toArray();
                $foods[$k]['packages'] = $prr;
            }
        }
        // dump($foods);die;
        if(empty($arr)) return $this->json_encode_nodata(1,"未查到信息");
        $data = [];
        $data['food_cate'] = $arr;
        $data['food'] = $foods;
        return $this->json_encode(2,"查询成功",$data);
    }

    // 桌台-修改商家优惠
    public function edit_coupon(Request $request) {
        // echo 1234;die;
        //获取token值
          // $token = $request->input("token");
        //根据token获取用户的id
          // $p_id= DB::table('partner_admin')->where("token",$token)->first()->partner_id;
          $p_id = $request->input("partner_id");
         // 获取折扣值
          $discount = $request->input("discount");
            if(!isset($discount)) return $this->json_encode(0,"缺少折扣值","");
         //判断获取到的折扣值是否符合0-1之间两位小数的规则
            if($discount<0 || $discount>1) return $this->json_encode(1,"折扣值不符合规则","");
            $temp = explode ( '.',$discount);
            if(sizeof ( $temp ) > 1) {
                $decimal = end ( $temp );
                $count = strlen ( $decimal );
                if($count !=2 ) return $this->json_encode(1,"折扣值不符合规则","");
            }
           $res = DB::table('partner')->where('id', $p_id)->update(['discount' => $discount]);
           if ($res) {
               return $this->json_encode(3,"修改成功","");
           }else{
               return $this->json_encode(2,"修改失败","");

           }
            
    }
    //服务员将购物车内的菜品显示在下单页
    public function placeorder_info()
    {
        $p_id = session('partner_id');
        if(empty($p_id)){
            return redirect('waiter/index?lang='.$this->lang);
        }
        $input = Input::all();
        $desk_sn = $input['desk_sn'];
        $goods = $input['goods'];
        $arr = [];
        $i = 0;
        $allnum = 0;
        $allprice = 0;
        $food_num = 0; //几道菜
        foreach($input['goods'] as $good){
            if(!empty($good)){
                $arr[$i]['id'] = $good['id'];
                $arr[$i]['num'] = $good['num'];
                $arr[$i]['title'] = $good['title'];
                $arr[$i]['price'] = $good['price'];
                $allnum = $good['num'] + $allnum;
                $allprice = $good['price']*$good['num'] + $allprice;
                $food_num += 1;
                $i = $i+1;
            }
            else{
                continue; 
            }
        } 
        return view('waiter/placeorder')->with('lang',$this->lang)
        ->with('desk_sn',$desk_sn)
        ->with('goods',$arr)
        ->with(['allnum'=>$allnum,'allprice'=>$allprice,'food_num'=>$food_num]);
        //->with('allprice',$allprice);
    }

    //服务员确认下单
    public function place_order()
    {
        $p_id = session('partner_id');
        if(empty($p_id)){
            return redirect('waiter/index?lang='.$this->lang);
        }
        $input = Input::all();
        if(empty($input['desk_sn'])){
            return redirect('waiter/table?lang='.$this->lang);
        }
        $desk_sn = $input['desk_sn'];
        $deskinfo = DB::table('food_area_desk')->where('partner_id',$p_id)->where('desk_sn',$desk_sn)->first();
        $areainfo = DB::table('food_area')->where('partner_id',$p_id)->first();
        $time = time();
        $goods = $input['goods'];
        $partner_object = DB::table('partner')->where('id',$p_id)->first();
        $partner = $this->objectToArray($partner_object);
        foreach($goods as $i=>$good){
             //判断food_id里有没有下滑线
            if(strpos($good['id'],'_')){
                $arr = explode('_',$good['id']); //下滑线两端字符取值
                $good_id = $arr[0];
                $pack_id = $arr[1];
            }
            else{
                $good_id = $good['id'];
                $pack_id = 0;
            }
            DB::table('order_temp')
            ->insert(['partner_id' => $p_id,
                      'team_id' => $areainfo->team_id,
                      'desk_sn' => $desk_sn,
                      'food_id' => $good_id,
                      'package_id' => $pack_id,
                      'number' => $good['num'],
                      'price' => $good['price'],
                      'create_time' => $time,
                      'remark' => $input['remark']
                      ]);
            $foodinfo = DB::table('food')->where('id',$good_id)->first();
            $foodcate = DB::table('food_cate')->where('id',$foodinfo->cate_id)->first();
            //判断菜分类里的打印机编号是不是数字
            if(intval($foodcate->pr_sn)==0){
                $pr_arr['p'.$p_id] = $foodcate->pr_sn;
            }
            else{
                $pr_arr = unserialize($foodcate->pr_sn);
            }
            //判断套餐
            if($foodinfo->pack==1){
                $package = DB::table('food_packages')->where('id',$pack_id)->first();
                $orderTeam[$i] = [
                'price' => $package->price,
                'quantity' => $good['num'],
                'package' => serialize([
                    'desk_sn' => $desk_sn,
                    'title_zh_cn' => $foodinfo->food_no.$foodinfo->title.'('.$package->name.')',
                    'title_en_us' => $foodinfo->food_no.$foodinfo->title_en.'('.$package->name_en.')',
                    'title_vi' => $foodinfo->food_no.$foodinfo->title_vi.'('.$package->name_vi.')',
                    'pr_sn' => $pr_arr['p'.$p_id]
                ])
                ];
            }
            else{
                $orderTeam[$i] = [
                    'price' => $good['price'],
                    'quantity' => $good['num'],
                    'package' => serialize([
                        'desk_sn' => $desk_sn,
                        'title_zh_cn' => $foodinfo->food_no.$foodinfo->title,
                        'title_en_us' => $foodinfo->food_no.$foodinfo->title_en,
                        'title_vi' => $foodinfo->food_no.$foodinfo->title_vi,
                        'pr_sn' => $pr_arr['p'.$p_id]
                    ])
                    ];
            }
        }
         //打印菜单
         $order['service'] = "";
         $order['desk_sn'] = $desk_sn;
         $order['type'] = 'order';
         $order['remark'] = $input['remark'];
         $partner['feeTax'] = $partner['fee_tax'];
         $partner['feeSrv'] = $partner['fee_srv'];
         $old_lang = $this->lang;
         NewPrinter::singlePrint($partner, $order, $orderTeam);
         NewPrinter::categoryPrint($partner, $order, $orderTeam); //厨房分类打印
         DB::table('food_area_desk')->where('id',$deskinfo->id)->update(['desk_state'=>3]);
         //return redirect('waiter/over?lang='.$this->lang)->with('lang',$this->lang);
         return view('waiter/orderover')->with('lang',$old_lang);


    }

    //将桌位状态变为空闲
    public function empty_desk()
    {
        $p_id = session('partner_id');
        $desk_sn = $_GET['desk_sn'];
        $update_row = DB::table('food_area_desk')->where('partner_id',$p_id)->where('desk_sn',$desk_sn)->update(['desk_state'=>1]);
        if($update_row){
            return redirect('waiter/table?lang='.$this->lang);
        }
        else{
            echo "更新数据失败";
        }
    }

    //未结账页需要获取的数据
    // public function non_checkout()
    // {
    //     $p_id = session('partner_id');
    //     if(empty($p_id)){
    //         return redirect('waiter/index?lang='.$this->lang);
    //     }
    //     $desk_sn = $_GET['desk_sn'];
    //     $enomination = 0;
    //     $enomination = $_GET['price']; //代金券面额
    //     $deskinfo = DB::table('food_area_desk')->where('partner_id',$p_id)->where('desk_sn',$desk_sn)->first();
    //     $order_temp = DB::table('order_temp')->where('partner_id',$p_id)
    //                                          ->where('desk_sn',$desk_sn)
    //                                          ->where('order_id',0)->get();
    //     $pinfo = Partner::find($p_id);
    //     $total_price = 0;  //原总价
    //     foreach($order_temp as $v){
    //         $total_price =  $v->price * $v->number + $total_price;
    //     }
    //     $srv_price = round($total_price * $pinfo->fee_srv); //服务费
    //     $tax_price = round($total_price * $pinfo->fee_tax); //税费
    //     $discount_price = round($total_price * (1 - $pinfo->discount));    //打折要减去的价格
    //     if($total_price-$discount_price+$srv_price+$tax_price>=$enomination){ //如果打折后价格大于代金券的价格
    //         $last_price = round($total_price - $discount_price + $srv_price + $tax_price - $enomination);    //最终应付价格
    //     }
    //     else{
    //         $last_price = 0;
    //     }
    //     //检查未结账订单是否已手动打印
    //     $orderNewTemp = DB::table('order_temp')->where('partner_id',$p_id)
    //                    ->where('desk_sn',$desk_sn)->where('is_print',0)
    //                    ->where('order_id',0)->get();
    //     if(!empty($orderNewTemp[0])){
    //         $is_print = 1; //需要手动打印
    //     }
    //     else{
    //         $is_print = 0; //不需要手动打印
    //     }
    //     return view('waiter/reckoning')->with("lang",$this->lang)->with("enomination",$enomination)
    //         ->with('desk_sn',$desk_sn)->with(['total_price'=>$total_price,'discount_price'=>$discount_price,'last_price'=>$last_price])
    //         ->with(['srv_price'=>$srv_price,'tax_price'=>$tax_price])->with("is_print",$is_print);
    // }

    //现金支付
    public function cash()
    {
        $desk_sn = $_GET['desk_sn'];
        $last_price = $_GET['last_price'];
        $enomination = $_GET['price'];
        return view('waiter/cash')->with('lang',$this->lang)->with("enomination",$enomination)
            ->with('desk_sn',$desk_sn)
            ->with('last_price',$last_price);
    }

    //确认支付
    public function confirm_payment()
    {
        $p_id = session('partner_id');
        if(empty($p_id)){
            return redirect('waiter/index?lang='.$this->lang);
        }
        $desk_sn = $_GET['desk_sn'];
        $last_price = $_GET['last_price'];
        if($last_price==0){ //如果取消订单或已结账页面没刷新跳回桌面
            return redirect('waiter/table?lang='.$this->lang);
        }
        $coupon = $_GET['price'];
        $partner_object = DB::table('partner')->where('id',$p_id)->first();
        $partner = $this->objectToArray($partner_object);
        //结算打印小票
        $ordertemps = DB::table('order_temp')
                        ->where('partner_id',$p_id)
                        ->where('desk_sn',$desk_sn)
                        ->where('order_id',0)->get();
        if(!empty($ordertemps)){
            $order_id = DB::table('order')->insertGetId([
                'team_id' => $ordertemps[0]->team_id,
                'partner_id' => $p_id,
                'desk_sn' => $desk_sn,
                'bu_type' => 4,
                'source' => 'waiter',
                'service' => 'cash',
                'state' => 'pay',
                'cost' => $coupon,
                'money' => $last_price,
                'create_time' => time(),
                'pay_time' => time()
            ]); 
        }
        else{
            return redirect('waiter/table?lang='.$this->lang);
        }
        $orderTeam = [];
        foreach ($ordertemps as $i => $ordertemp) {
            $food = DB::table('food')->where('id',$ordertemp->food_id)->first();
            if(!empty($food)){
                if($food->pack==1){
                    $package = DB::table('food_packages')->where('id',$ordertemp->package_id)->first();
                    $orderTeam[$i] = [
                    'price' => $ordertemp->price,
                    'quantity' => $ordertemp->number,
                    'package' => serialize([
                        'desk_sn' => $desk_sn,
                        'title_zh_cn' => $food->title.'('.$package->name.')',
                        'title_en_us' => $food->title_en.'('.$package->name_en.')',
                        'title_vi' => $food->title_vi.'('.$package->name_vi.')',
                        'pr_sn' => $partner['pr_sn']
                    ])
                    ];
                }
                else{
                  $orderTeam[$i] = [
                    'price' => $ordertemp->price,
                    'quantity' => $ordertemp->number,
                    'package' => serialize([
                        'desk_sn' => $desk_sn,
                        'title_zh_cn' => $food->title,
                        'title_en_us' => $food->title_en,
                        'title_vi' => $food->title_vi,
                        'pr_sn' => $partner['pr_sn']
                    ])
                    ];
                }
                //每道菜存入orderteam表内对应一条数据
                DB::table('orderteam')->insert([
                    'partner_id' => $p_id,
                    'userid' => 0,
                    'orderid' => $order_id,
                    'productid' => $food->id,
                    'quantity' => $ordertemp->number,
                    'price' => $ordertemp->price,
                    'create_time' => time(),
                    'package' => $orderTeam[$i]['package']
                ]); 
            }
            else{
                continue;
            }
        }
        $order['cost'] = $coupon;  //传给打印代金券面值
        $order['service'] = "cash";
        $order['remark'] = "";
        $order['desk_sn'] = $desk_sn;
        $order['type'] = 'cash';
        $partner['feeTax'] = $partner['fee_tax'];
        $partner['feeSrv'] = $partner['fee_srv'];
        if(!empty($orderTeam)){
            NewPrinter::singlePrint($partner, $order, $orderTeam);
        }
        else{
            return redirect('waiter/table?lang='.$this->lang);
        }
        //将临时单order_id转为生成订单id
        if(!empty($order_id)){
            DB::table('order_temp')->where('partner_id',$p_id)->where('desk_sn',$desk_sn)->update(['order_id'=>$order_id]); 
        }    
        //测试将临时单order_id =1
       // DB::table('order_temp')->where('partner_id',$p_id)->where('desk_sn',$desk_sn)->update(['order_id'=>1]);
        $update_row = DB::table('food_area_desk')->where('partner_id',$p_id)->where('desk_sn',$desk_sn)->update(['desk_state'=>1]);
        if($update_row){
            return redirect('waiter/table?lang='.$this->lang);
        }
        else{
            echo "更新数据失败";
        }
    }

    //选择代金卷
    public function coupon()
    {
        $p_id = session('partner_id');
        if(empty($p_id)){
            return redirect('waiter/index?lang='.$this->lang);
        }
        $desk_sn = $_GET['desk_sn'];
        $coupon_id = Input::get('coupon_id');
        $enomination = $_GET['price'];
        $arr = DB::table('coupon')->where('id',$coupon_id)->first();
        $coupon_info = $this->objectToArray($arr);
        $now = time();
        if(!empty($coupon_id)){
        if(!empty($arr)){
          if($coupon_info['consume']=='N' && $now<=$coupon_info['expire_time']){
             $msg =  "Vouchers null and void"; //信息不全
             $expire_time = date("Y-m-d H:i:s",$coupon_info['expire_time']);
             $sql = "SELECT c.id,o.id as order_id, pp.packagename,pp.packagenameen, t.title,t.titleen, 
                            ot.quantity,c.expire_time,pp.price
                    FROM `order` o,`orderteam` ot,`product_price` pp,`team` t,`coupon` c 
                    WHERE c.order_id=ot.id and c.team_id=t.id and ot.orderid=o.id and 
                    pp.proid=c.team_id and ot.packageid=pp.id and o.partner_id=".$p_id." and c.id=".$coupon_id;
             $order = DB::select($sql);   
             $orders = $this->objectToArray($order);
             if(!empty($orders[0]['packagename'])){
                 $packname = $orders[0]['packagename'];
                 preg_match_all('/\d+/',$packname,$djq); //取代金券套餐名称里的数字作为代金券面额
                 $enomination = $djq[0][0];
                 //$enomination = round($orders[0]['price'],-2)*5;  //根据价格算出代金券面额
                 //使用完作废代金券
                 DB::table('coupon')->where('id',$coupon_id)->update(['consume'=>'Y','consume_time'=>time()]);
            }   
             return view('waiter/coupon')->with('lang',$this->lang)->with('orders',$orders)
            ->with('desk_sn',$desk_sn)->with('msg',$msg)->with('enomination',$enomination)
            ->with('expire_time',$expire_time);
          }
          elseif(!empty($coupon_info['id']) && $coupon_info['consume']=='Y'){
             $msg =  "The voucher has been used";
          }
          elseif(!empty($coupon_info['id']) && $now>$coupon_info['expire_time']){
             $msg = "The voucher has expired";
          }
          else{
             $msg =  "Find no result";
          }
        }
        else{
             $msg = "No result";
        }
        }else{$msg = "";}
         
        return view('waiter/coupon')->with('lang',$this->lang)->with('desk_sn',$desk_sn)
                                    ->with('msg',$msg)->with('enomination',$enomination);
        //dd($coupon_info);
    }

    //微信支付
    public function wechat()
    {
        $desk_sn = $_GET['desk_sn'];
        $enomination = $_GET['price'];
        return view('waiter/wechat')->with("lang",$this->lang)->with("enomination",$enomination)
            ->with('desk_sn',$desk_sn);
    }

    //团购-验证团购套餐，成功返回套餐名
    public function voucher(Request $request)
    {
       //获取partner_id
         $p_id = $request->input("partner_id");
         $id = $request->input("voucher_id");
         if(!isset($id)) return $this->json_encode(0,"验证号码不存在","");
       //进行验证团购套餐
        $coupon = Coupon::where(['id' => $id,'partner_id'=>$p_id])->first();
        if(empty($coupon)) return $this->json_encode(6,"团购号不正确","");
        $coupon =$coupon->toArray(); 
        if($coupon['consume'] == 'Y') return $this->json_encode(1,"团购券已消费","");
        if ( $coupon['expire_time'] < time()) return $this->json_encode(2,"团购券已过期","");
        $order_id = $coupon['order_id'];
        //去orderteam表里面查询packgeid
        $packege_id = DB::table('orderteam')->where('id',$order_id)->first()->packageid;
        if(empty($packege_id)) return $this->json_encode(3,"没有套餐","");
        //t通过packageid 去product_price表里面查询
            // 修改数据库信息  
                //获取客户端ip
                $ip = $request->getClientIp();
                //获取消费时间
                $time = time();
     DB::table('coupon')->where('id',$id)->update(['ip' => $ip,'consume_time'=>$time,'consume'=>"Y"]);
    $data = $packege_id = DB::table('product_price')->where('id',$packege_id)->first();
        if($data) {
            return $this->json_encode(4,"查询成功",$data);
            
         
        }else{
            return $this->json_encode(5,"查询失败","");
           

        }
        // dump($data);die;
        // echo $packege_id;die;
            // $op = 'foods_team'; //团购验券固定数据
            // $res = Helper::voucher($id, $op, $request->getClientIp());
            // if($res['msg']=="验证成功"){
            //     $update_row = DB::table('food_area_desk')
            //                   ->where('partner_id',$p_id)
            //                   ->where('desk_sn',$desk_sn)
            //                   ->update(['desk_state'=>2]);                
            // }
        //     return view('waiter/voucher')->with("lang",$this->lang)->with("res",$res)
        //     ->with('desk_sn',$desk_sn);
        // }
       
    }

    //整体打折
    public function discount()
    {
        $desk_sn = $_GET['desk_sn'];
        return view('waiter/discount')->with("lang",$this->lang)
            ->with('desk_sn',$desk_sn);
    }

     /**
     * 对象转换数组
     *
     * @param $e StdClass对象实例
     * @return array|void
     */
     public function objectToArray($e)
     {
         $e = (array)$e;
         foreach ($e as $k => $v) {
             if (gettype($v) == 'resource') return;
             if (gettype($v) == 'object' || gettype($v) == 'array')
                 $e[$k] = (array)$this->objectToArray($v);
         }
         return $e;
     }

    //已点的菜单详情列表
    public function menu()
    {
        $p_id = session('partner_id');
        if(empty($p_id)){
            return redirect('waiter/index?lang='.$this->lang);
        }
        $desk_sn = $_GET['desk_sn'];
        $partner_object = DB::table('partner')->where('id',$p_id)->first();
        $partner = $this->objectToArray($partner_object);
        //退菜
        if(!empty(Input::get('ordertemp_id'))){
            $ordertemp_ids = Input::get('ordertemp_id');
            $orderTeam = [];
            foreach ($ordertemp_ids as $i => $ordertemp_id) {
                //删除临时单数据前打印该小票
                $tempinfo = DB::table('order_temp')->where('id',$ordertemp_id)->first();
                $food = DB::table('food')->where('id',$tempinfo->food_id)->first();
                $foodcate = DB::table('food_cate')->where('id',$food->cate_id)->first();
                //判断菜分类里的打印机编号是不是数字
                if(intval($foodcate->pr_sn)==0){
                    $pr_arr['p'.$p_id] = $foodcate->pr_sn;
                }
                else{
                    $pr_arr = unserialize($foodcate->pr_sn);
                }
                if($food->pack==1){
                    $package = DB::table('food_packages')->where('id',$tempinfo->package_id)->first();
                    $orderTeam[$i] = [
                    'price' => $tempinfo->price,
                    'quantity' => $tempinfo->number,
                    'package' => serialize([
                        'desk_sn' => $desk_sn,
                        'title_zh_cn' => $food->food_no.$food->title.'('.$package->name.')',
                        'title_en_us' => $food->food_no.$food->title_en.'('.$package->name_en.')',
                        'title_vi' => $food->food_no.$food->title_vi.'('.$package->name_vi.')',
                        'pr_sn' => $pr_arr['p'.$p_id]
                    ])
                    ];
                }
                else{
                $orderTeam[$i] = [
                    'price' => $tempinfo->price,
                    'quantity' => $tempinfo->number,
                    'package' => serialize([
                        'desk_sn' => $desk_sn,
                        'title_zh_cn' => $food->food_no.$food->title,
                        'title_en_us' => $food->food_no.$food->title_en,
                        'title_vi' => $food->food_no.$food->title_vi,
                        'pr_sn' => $pr_arr['p'.$p_id]
                    ])
                    ];
                }
                DB::table('order_temp')->where('id',$ordertemp_id)->delete();
            }
            $order['service'] = "";
            $order['remark'] = "";
            $order['desk_sn'] = $desk_sn;
            $order['type'] = 'cancel';
            $partner['feeTax'] = $partner['fee_tax'];
            $partner['feeSrv'] = $partner['fee_srv'];
            NewPrinter::singlePrint($partner, $order, $orderTeam);  //前台打印
            NewPrinter::categoryPrint($partner, $order, $orderTeam); //厨房分类打印
        }
        $ordertemps = DB::table('order_temp')
                        ->where('partner_id',$p_id)
                        ->where('desk_sn',$desk_sn)
                        ->where('order_id',0)->get();
        $arr = [];
        $allnum = 0;
        $allprice = 0;
        foreach ($ordertemps as $key => $ordertemp) {
            $food = DB::table('food')->where('id',$ordertemp->food_id)->first();
            if(!empty($food)){
                if($food->pack==1){
                    $package = DB::table('food_packages')->where('id',$ordertemp->package_id)->first();         
                    $arr[$key]['title'] = $food->food_no.$food->title.'('.$package->name.')';
                    $arr[$key]['title_en'] = $food->food_no.$food->title_en.'('.$package->name_en.')';
                    $arr[$key]['title_vi'] = $food->food_no.$food->title_vi.'('.$package->name_vi.')';
                }
                else{
                    $arr[$key]['title'] = $food->food_no.$food->title;
                    $arr[$key]['title_en'] = $food->food_no.$food->title_en;
                    $arr[$key]['title_vi'] = $food->food_no.$food->title_vi;
                }
                $arr[$key]['id'] = $ordertemp->id;
                $arr[$key]['number'] = $ordertemp->number;
                $arr[$key]['price'] = $ordertemp->price;
                $arr[$key]['remark'] = $ordertemp->remark;
                $allnum += $ordertemp->number;
                $allprice += $ordertemp->price*$ordertemp->number;
            }
            else{
                continue;
            }
        }
        return view('waiter/menu')->with("lang",$this->lang)->with('desk_sn',$desk_sn)->with('ordertemps',$arr)
                               ->with('suffix',$this->suffix)->with('allnum',$allnum)->with('allprice',$allprice);
    } 
    
    //下单成功页
    public function orderover()
    {     
        if(empty(session('partner_id'))){
            return redirect('waiter/index?lang='.$this->lang);
        }
        return view('waiter/orderover')->with("lang",$this->lang);
    }

    //取消下单
    public function cancel_order()
    {
        $p_id = session('partner_id');
        $desk_sn = $_GET['desk_sn'];
        $ordertemps = DB::table('order_temp')->where('partner_id',$p_id)->where('desk_sn',$desk_sn)->where('order_id',0)->get();
        if(!empty($ordertemps[0])){
            foreach($ordertemps as $ordertemp){
                DB::table('order_temp')->where('id',$ordertemp->id)->delete();
            }
        }
        return redirect('waiter/table?lang='.$this->lang);
    }

    //手动打印
    public function manual_print()
    {
        $p_id = session('partner_id');
        $desk_sn = $_GET['desk_sn'];
        $partner_object = DB::table('partner')->where('id',$p_id)->first();
        $partner = $this->objectToArray($partner_object);
        $ordertemps = DB::table('order_temp')->where('partner_id',$p_id)->where('desk_sn',$desk_sn)
                     ->where('order_id',0)->where('is_print',0)->get();
        foreach($ordertemps as $i=>$ordertemp){
            $food = DB::table('food')->where('id',$ordertemp->food_id)->first();
            $foodcate = DB::table('food_cate')->where('id',$food->cate_id)->first();
            //判断菜分类里的打印机编号是不是数字
            if(intval($foodcate->pr_sn)==0){
                $pr_arr['p'.$p_id] = $foodcate->pr_sn;
            }
            else{
                $pr_arr = unserialize($foodcate->pr_sn);
            }
            $remark = $ordertemp->remark;
            //判断是否有套餐
            if($food->pack==1){
                $package = DB::table('food_packages')->where('id',$ordertemp->package_id)->first();
                $orderTeam[$i] = [
                'price' => $ordertemp->price,
                'quantity' => $ordertemp->number,
                'package' => serialize([
                    'desk_sn' => $desk_sn,
                    'title_zh_cn' => $food->food_no.$food->title.'('.$package->name.')',
                    'title_en_us' => $food->food_no.$food->title_en.'('.$package->name_en.')',
                    'title_vi' => $food->food_no.$food->title_vi.'('.$package->name_vi.')',
                    'pr_sn' => $pr_arr['p'.$p_id]
                ])
                ];
            }
            else{
            $orderTeam[$i] = [
                'price' => $ordertemp->price,
                'quantity' => $ordertemp->number,
                'package' => serialize([
                    'desk_sn' => $desk_sn,
                    'title_zh_cn' => $food->food_no.$food->title,
                    'title_en_us' => $food->food_no.$food->title_en,
                    'title_vi' => $food->food_no.$food->title_vi,
                    'pr_sn' => $pr_arr['p'.$p_id]
                ])
                ];
            }
            //标记为已打印
            DB::table('order_temp')->where('id',$ordertemp->id)->update(['is_print'=>1]);
        }
        //打印菜单
        $order['service'] = "";
        $order['desk_sn'] = $desk_sn;
        $order['type'] = 'order';
        $order['remark'] = $remark;
        $partner['feeTax'] = $partner['fee_tax'];
        $partner['feeSrv'] = $partner['fee_srv'];
        if(!empty($orderTeam)){
            NewPrinter::singlePrint($partner, $order, $orderTeam);
            NewPrinter::categoryPrint($partner, $order, $orderTeam); //厨房分类打印
            return redirect('waiter/orderover?lang='.$this->lang); 
        }
        else{
            return redirect('waiter/table?lang='.$this->lang); 
        }
    }

    public function test(Request $request)
    {
        // echo  123456;die;
        $p_id = session('partner_id');
        // echo $p_id;
        $foodname = 'B00';
         $foodcate = DB::table('food_cate')->where('partner_id',$p_id)->first();
        $team_id = $foodcate->team_id;
        // $search_foods = DB::table('food')->where('team_id',$team_id)->get()->map(function ($value) {return (array)$value;})->toArray();   
      $search_foods = DB::table('food')->where('team_id',$team_id)->first();
        //return  json_encode($search_foods);
         echo $team_id;
    }
    
}
