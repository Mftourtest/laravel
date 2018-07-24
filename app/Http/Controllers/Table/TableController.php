<?php

namespace App\Http\Controllers\Table;

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
        // $p_id = session('id');
        //通过token查询出来用户id
        $token = $request->input("token");
        $partner_id= DB::table('partner_admin')->where("token",$token)->first()->partner_id;
        // var_dump($partner_id);
        $rooms = DB::table('food_area')->where("partner_id",$partner_id)->get()->toArray();
        // echo "<pre>";
        // var_dump($rooms);die;
        //返回桌面信息
            $deskinfos = DB::table('food_area_desk')->join('food_desk_state', function($join)  
            {  
                $join->on('food_area_desk.desk_state', '=', 'food_desk_state.id');  
            })->select('*')  
              ->where('partner_id',$partner_id)                 
              ->orderBy('food_area_desk.desk_sn', 'asc')  
              ->get()->toArray();   
       
     
            $deskarr = [];
            foreach ($deskinfos as $key => $deskinfo) {
                $ordertemp = DB::table('order_temp')->where('partner_id',$partner_id)->where('desk_sn',$deskinfo->desk_sn)
                             ->where('order_id',0)->first();
                if(!empty($ordertemp)){
                    if($deskinfo->desk_state!=3){
                       DB::table('food_area_desk')->where('partner_id',$partner_id)->where('desk_sn',$deskinfo->desk_sn)->update(['desk_state'=>3]);
                    }
                }
                else{
                    if($deskinfo->desk_state==3){
                        DB::table('food_area_desk')->where('partner_id',$partner_id)->where('desk_sn',$deskinfo->desk_sn)->update(['desk_state'=>1]);
                    }
                }
                $deskarr[$key]['desk_sn'] = $deskinfo->desk_sn;
                $deskarr[$key]['state_name'] = $deskinfo->state_name;
                $deskarr[$key]['state_name_en'] = $deskinfo->state_name_en;
                $deskarr[$key]['state_name_vi'] = $deskinfo->state_name_vi;
                $deskarr[$key]['desk_state'] = $deskinfo->desk_state;
                $deskarr[$key]['area_id'] = $deskinfo->area_id;
            }
          //   $
          // return  $this->json_encode(1,$deskarr);
        
        //返回桌面信息
        $info = [];
        $info['room'] = $rooms;
        $info['desk'] = $deskarr;

        // $arr = [];
        if($info) {
            return  $this->json_encode(1,"查询成功",$info);
                // $arr['code']=1;
                // $arr['msg']="查询成功";
                // $arr['data'] = $rooms;

         }else{
                // $arr['code']=0;
                // $arr['msg']="查询失败";
                // $arr['data'] = "";
                return  $this->json_encode(0,"查询失败","");
         }
         // return json_encode($arr,JSON_UNESCAPED_UNICODE);
    }
    //返回房间桌位信息
    public function table(Request $request) {
         // echo 123;die;
        // $p_id = session('partner_id');
        //通过token查询出来用户id
        $token = $request->input("token");
        $p_id= DB::table('partner_admin')->where("token",$token)->first()->partner_id;
            $deskinfos = DB::table('food_area_desk')->join('food_desk_state', function($join)  
            {  
                $join->on('food_area_desk.desk_state', '=', 'food_desk_state.id');  
            })->select('*')  
              ->where('partner_id',$p_id)                 
              ->orderBy('food_area_desk.desk_sn', 'asc')  
              ->get()->toArray();   
       
     
            $deskarr = [];
            foreach ($deskinfos as $key => $deskinfo) {
                $ordertemp = DB::table('order_temp')->where('partner_id',$p_id)->where('desk_sn',$deskinfo->desk_sn)
                             ->where('order_id',0)->first();
                if(!empty($ordertemp)){
                    if($deskinfo->desk_state!=3){
                       DB::table('food_area_desk')->where('partner_id',$p_id)->where('desk_sn',$deskinfo->desk_sn)->update(['desk_state'=>3]);
                    }
                }
                else{
                    if($deskinfo->desk_state==3){
                        DB::table('food_area_desk')->where('partner_id',$p_id)->where('desk_sn',$deskinfo->desk_sn)->update(['desk_state'=>1]);
                    }
                }
                $deskarr[$key]['desk_sn'] = $deskinfo->desk_sn;
                $deskarr[$key]['state_name'] = $deskinfo->state_name;
                $deskarr[$key]['state_name_en'] = $deskinfo->state_name_en;
                $deskarr[$key]['state_name_vi'] = $deskinfo->state_name_vi;
                $deskarr[$key]['desk_state'] = $deskinfo->desk_state;
                $deskarr[$key]['area_id'] = $deskinfo->area_id;
            }
          return  $this->json_encode(1,$deskarr);
        // dump($deskarr);
        // return view('waiter/table')->with('areainfos',$areainfos)->with('suffix',$this->suffix)
        // ->with('deskinfos',$deskarr)->with('lang',$this->lang);
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
            return $this->json_encode(1,"账号或密码输入错误","");
        }else{
            //更新token到数据库
            $data['token']=$token;
             $num = DB::table('partner_admin')->where('id', $data[0]->id)->update(['token' => $token]);
             if($num==1) {
                    session(['username'=>$data[0]->account]);
                    session(['id'=>$data[0]->id]);
                    return $this->json_encode(2,"登陆成功",$data[0]);
             }else{
                    return $this->json_encode(3,"未知错误","");

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
    //首页-登录页
    // public function index(Request $request)
    // {
    //     $cookies = $request->cookie();
    //     return view('waiter/index')->with("lang",$this->lang)->with('cookies',$cookies);
    // }

    //带验证登陆
    public function login_validator()
    {
        //$lang = $_GET['lang'];
        if($input = Input::all()){
            //验证提交的数据  
            $rules = [  
                'account'=>'required|between:1,20',  
                'password'=>'required|between:1,50'
            ];  
            $message = [  
                'account.required'=>'account not null！',  
                'account.between'=>'账号必须在1-20位之间！',  
                'password.required'=>'password not null！',  
                'password.between'=>'密码必须在1-50位之间！'
            ];  
            $validator = Validator::make($input,$rules,$message);  
            //表单验证  
            if($validator->passes()){  
                //用户验证  
                $admininfo = PartnerAdmin::where('account',$input['account'])->first(); //查询数据库用户名是否存在
                if(!$admininfo){  
                    return back()->with('msg','用户不存在！');  
                }else{  
                    $psw = $admininfo->password;
                    if($psw!=$input['password']){  
                        return back()->with('msg','密码错误！');  
                    } 
                    else{
                        $pinfo = Partner::find($admininfo->partner_id);
                        session(['partner_id'=>$admininfo->partner_id,'partner_name'=>$pinfo->title,'user_id'=>$admininfo->id]); 
                        //如果登录成功就把账号密码存入cookie
                        Cookie::queue('account', $input['account'], 10080); //视图自动响应
                        Cookie::queue('password', $input['password'], 10080); //cookie保存一周
                        return redirect('waiter/table?lang='.$this->lang); 
                    } 
               }  
            }else{  
                return back()->withErrors($validator);  
            }  
        }
        else{
            return view('waiter/index')->with("lang",$lang);;  
        }
    }

    //获取餐厅区域桌位信息
    // public function desk_info()
    // {
    //     // echo 123;die;
    //     $p_id = session('partner_id');
    //     //如果session过期就跳回登录页
    //     if(empty($p_id)){
    //         return redirect('waiter/index?lang='.$this->lang);
    //     }
    //     if($p_id){
    //         $areainfos = FoodArea::where('partner_id',$p_id)->get()->toArray();
    //         $deskinfos = DB::table('food_area_desk')->join('food_desk_state', function($join)  
    //         {  
    //             $join->on('food_area_desk.desk_state', '=', 'food_desk_state.id');  
    //         })->select('*')  
    //           ->where('partner_id',$p_id)                 
    //           ->orderBy('food_area_desk.desk_sn', 'asc')  
    //           ->get()->toArray();   
    //     }
    //     $deskarr = [];
    //     foreach ($deskinfos as $key => $deskinfo) {
    //         $ordertemp = DB::table('order_temp')->where('partner_id',$p_id)->where('desk_sn',$deskinfo->desk_sn)
    //                      ->where('order_id',0)->first();
    //         if(!empty($ordertemp)){
    //             if($deskinfo->desk_state!=3){
    //                DB::table('food_area_desk')->where('partner_id',$p_id)->where('desk_sn',$deskinfo->desk_sn)->update(['desk_state'=>3]);
    //             }
    //         }
    //         else{
    //             if($deskinfo->desk_state==3){
    //                 DB::table('food_area_desk')->where('partner_id',$p_id)->where('desk_sn',$deskinfo->desk_sn)->update(['desk_state'=>1]);
    //             }
    //         }
    //         $deskarr[$key]['desk_sn'] = $deskinfo->desk_sn;
    //         $deskarr[$key]['state_name'] = $deskinfo->state_name;
    //         $deskarr[$key]['state_name_en'] = $deskinfo->state_name_en;
    //         $deskarr[$key]['state_name_vi'] = $deskinfo->state_name_vi;
    //         $deskarr[$key]['desk_state'] = $deskinfo->desk_state;
    //         $deskarr[$key]['area_id'] = $deskinfo->area_id;
    //     }
    //     return view('waiter/table')->with('areainfos',$areainfos)->with('suffix',$this->suffix)
    //     ->with('deskinfos',$deskarr)->with('lang',$this->lang);
                                    
    // }           

    //查询该餐厅所有的菜品分类和菜品
    public function food_info()
    {
        $p_id = session('partner_id');
        if(empty($p_id)){
            return redirect('waiter/index?lang='.$this->lang);
        }
        $desk_sn = $_GET['desk_sn'];
        $arr = [];
        $cateinfo = DB::table('food_cate')->where('partner_id',$p_id)->orderBy('display_order','desc')->get()->toArray();
        $arr = $this->objectToArray($cateinfo); //将对象转为数组
        $categorys = [];
        $data = array();
        $brr = [];
        $prr = [];
        foreach($cateinfo as $i=>$cate){
            $categorys[$i] =  DB::table('food')->where('cate_id',$cate->id)->orderBy('display_order','desc')->get()->toArray();
            $brr = $this->objectToArray($categorys[$i]);
                foreach ( $brr as $j => $food) {
                    $data[$i][$j]['id'] = $food['id'];
                    $data[$i][$j]['food_no'] = $food['food_no'];
                    $data[$i][$j]['title'.$this->suffix] = $food['food_no'].$food['title'.$this->suffix];
                    $data[$i][$j]['price'] = round($food['price']);
                    $data[$i][$j]['display_order'] = $food['display_order'];
                    $data[$i][$j]['pack'] = $food['pack'];
                    if($food['pack']==1){                 
                        $prr = DB::table('food_packages')->where('food_id',$food['id'])->get()->toArray();
                        $data[$i][$j]['packages'] = $this->objectToArray($prr);
                    }
                }
        }
        $deskinfo = DB::table('food_area_desk')->where('partner_id',$p_id)->where('desk_sn',$desk_sn)->first();
        if($deskinfo->desk_state!=3){
            DB::table('food_area_desk')->where('partner_id',$p_id)->where('desk_sn',$desk_sn)->update(['desk_state'=>2]); //点餐中
        }
        $fooddata = json_encode($data,JSON_UNESCAPED_UNICODE); //为了js能获取菜单
        $jssuffix = json_encode($this->suffix,JSON_UNESCAPED_UNICODE);
        //echo $this->suffix;exit;
        return view('waiter/order')->with('cateinfo',$arr)->with('data',$data)->with('fooddata',$fooddata)
                                   ->with('suffix',$this->suffix)->with('jssuffix',$jssuffix)
                                   ->with('desk_sn',$desk_sn) ->with('lang',$this->lang);
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

    //验证团购券
    public function voucher(Request $request)
    {
        $p_id = session('partner_id');
        if(empty($p_id)){
            return redirect('waiter/index?lang='.$this->lang);
        }
        $desk_sn = $_GET['desk_sn'];
        $id = Input::get('voucher_id');
        if(!empty($id)){
            $op = 'foods_team'; //团购验券固定数据
            $res = Helper::voucher($id, $op, $request->getClientIp());
            if($res['msg']=="验证成功"){
                $update_row = DB::table('food_area_desk')
                              ->where('partner_id',$p_id)
                              ->where('desk_sn',$desk_sn)
                              ->update(['desk_state'=>2]);                
            }
            return view('waiter/voucher')->with("lang",$this->lang)->with("res",$res)
            ->with('desk_sn',$desk_sn);
        }
        else{
            return view('waiter/voucher')->with("lang",$this->lang)->with("voucher_id",$id)
            ->with('desk_sn',$desk_sn);
        }
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
