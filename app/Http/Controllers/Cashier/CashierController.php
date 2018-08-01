<?php

namespace App\Http\Controllers\Cashier;

use App;
use DB;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Input;
use App\Models\PartnerAdmin;
use App\Models\Partner;
use App\Models\FoodArea;
use App\Models\FoodAreaDesk;
use App\Lib\Common\Helper;
use App\Lib\Common\NewPrinter;
use App\Models\Order;

class CashierController extends Controller
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

    //修改菜品库存
    public function edit_stock(Request $request) {
        $number = $request->input("number"); //要修改的库存数量
        $food_id = $request->input("food_id"); //菜品id
        $result = DB::table('food')->where('id',$food_id)->update(['stock'=>$number]);
        if($result){
            return $this->json_encode(1,"修改成功","");
        }else{
            return $this->json_encode(0,"修改失败","");
        }
    }
    
    //添加前台打印机
    public function add_print(Request $request){
        $prints = $request->all(); //要添加的打印机数据
        $result = DB::table('printer')
            ->insert(['pr_sn' => $prints['pr_sn'],
                      'pr_lang' => $prints['pr_lang'],
                      'number' => $prints['number'],
                      'partner_id' => $prints['partner_id'],
                      'cate_id' => $prints['cate_id'],
                      'type' => $prints['type'],
                      ]);
        if($result){
            return $this->json_encode(1,"添加成功","");
        }
        else{
            return $this->json_encode(0,"添加失败","");
        }
    }

    //获取商户所有打印机信息
    public function all_printer(Request $request)
    {
        $inputs = $request->all(); 
        $allprints = DB::table('printer')->where("partner_id",$inputs['partner_id'])->get()->toArray();
        if($allprints){
            return $this->json_encode(1,"查询成功",$allprints);
        }
        else{
            return $this->json_encode(0,"查询失败","");
        }
    }

    //收银台点餐下单接口
    public function place_order(Request $request)
    {
        $inputs = $request->all(); 
        //避免重复下单
        //return json_encode($inputs);exit;
        $order_temp = DB::table('order_temp')->where('partner_id',$inputs['partner_id'])->where('desk_sn',$inputs['desk_sn'])->where('state',0)->orderBy('id','DESC')->first();
        if(!empty($order_temp->temp_order_no)){
            return $this->json_encode_nodata(0,"不能重复下单");
        }
        else{
        //为了获取team_id
        $areainfo = DB::table('food_area')->where('partner_id',$inputs['partner_id'])->first();
        $arr = $inputs['foods'];
        $foods = json_decode($arr,true);
        //dd($foods);exit;
        //创建下单订单号
        $temp_order_id = 1000 + $inputs['partner_id'];
        $desk_sn = $inputs['desk_sn'];
        $time = time();
        if($desk_sn<10){
            $desksn = '0'.$desk_sn;
        }
        else{
            $desksn = $desk_sn;
        }
        $temp_order_id .= $desksn;
        $temp_order_id .= $time;
        foreach($foods as $i=>$food){
            //判断food_id里有没有下滑线
           if(strpos($food['foodid'],'_')){
               $arr = explode('_',$food['foodid']); //下滑线两端字符取值
               $food_id = $arr[0];
               $pack_id = $arr[1];
           }
           else{
               $food_id = $food['foodid'];
               $pack_id = 0;
           }
           $result = DB::table('order_temp')->insert(['partner_id' => $inputs['partner_id'],
                     'team_id' => $areainfo->team_id,
                     'desk_sn' => $desk_sn,
                     'food_id' => $food_id,
                     'package_id' => $pack_id,
                     'number' => $food['foodnum'],
                     'price' => $food['foodprice'],
                     'create_time' => $time,
                     'remark' => $inputs['remark'],
                     'temp_order_no' => $temp_order_id
                     ]);
           //$foodinfo = DB::table('food')->where('id',$food_id)->first();
           //$foodcate = DB::table('food_cate')->where('id',$foodinfo->cate_id)->first();
        }
        if($result){
            //将桌位信息变成未结账
            DB::table('food_area_desk')->where('partner_id',$inputs['partner_id'])->where('desk_sn',$desk_sn)->update(['desk_state'=>3]);
            $data['temp_order_no'] = $temp_order_id;
            return $this->json_encode(1,"下单成功",$data);
        }
        else{
            return $this->json_encode_nodata(0,"下单失败");
        }
      }      
    }

    //收银台取消订单接口
    public function cancel_order(Request $request)
    {
        $temp_order_no = $request->input('temp_order_no');
        $order_temp = DB::table('order_temp')->where('temp_order_no',$temp_order_no)->first();
        //根据下单id更新临时单状态为已撤单
        $result = DB::table('order_temp')->where('temp_order_no',$temp_order_no)->update(['state'=>2]);
        if($result){
            DB::table('food_area_desk')->where('partner_id',$order_temp->partner_id)->where('desk_sn',$order_temp->desk_sn)->update(['desk_state'=>1]);
            $order_temp = DB::table('order_temp')->where('temp_order_no',$temp_order_no)->get()->map(function ($value) {
                return (array)$value;
            })->toArray();  
            return $this->json_encode(1,"撤单成功",$order_temp);
        }
        else{
            return $this->json_encode_nodata(0,"撤单失败");
        }
    }

    //加菜
    public function add_food(Request $request){
        $input = $request->all();
        $temp_order_id = $input['temp_order_no'];
        $order_temp = DB::table('order_temp')->where('temp_order_no',$temp_order_id)->first();
        $arr = $input['foods'];
        $foods = json_decode($arr,true);//json转数组
        //dd($foods);exit;
        foreach($foods as $i=>$food){
            //判断food_id里有没有下滑线
           if(strpos($food['foodid'],'_')){
               $arr = explode('_',$food['foodid']); //下滑线两端字符取值
               $food_id = $arr[0];
               $pack_id = $arr[1];
           }
           else{
               $food_id = $food['foodid'];
               $pack_id = 0;
           }
           $ordertemp_id = DB::table('order_temp')->insertGetId([
                     'partner_id' => $order_temp->partner_id,
                     'team_id' => $order_temp->team_id,
                     'desk_sn' => $order_temp->desk_sn,
                     'food_id' => $food_id,
                     'package_id' => $pack_id,
                     'number' => $food['foodnum'],
                     'price' => $food['foodprice'],
                     'create_time' => time(),
                     'remark' => $order_temp->remark,
                     'temp_order_no' => $temp_order_id
                     ]);
           //$foodinfo = DB::table('food')->where('id',$food_id)->first();
           //$foodcate = DB::table('food_cate')->where('id',$foodinfo->cate_id)->first();
        }
        if($ordertemp_id){
            return $this->json_encode(1,"加菜成功",$ordertemp_id);
        }
        else{
            return $this->json_encode_nodata(0,"加菜失败");
        }
    }

    //退菜
    public function refund(Request $request)
    {
        $id = $request->input('id');
        $result = DB::table('order_temp')->where('id',$id)->update(['is_refund'=>1]);
        if($result){
            return $this->json_encode(1,"退菜成功",$id);
        }
        else{
            return $this->json_encode_nodata(0,"退菜失败");
        }
    }

    //结账
    public function payment(Request $request)
    {
        $input = $request->all();
        $ordertemps = DB::table('order_temp')->where('temp_order_no',$input['temp_order_no'])->get();
        
    }

    public function test(Request $request)
    {
        $partner_id = 668;
        $desk_sn = 2;
        $order_temp = DB::table('order_temp')->where(['partner_id'=>$partner_id,'desk_sn'=>$desk_sn,'order_id'=>0,'state'=>0])->first();
        if($order_temp->state==0){
            DB::table('order_temp')->where(['partner_id'=>668,'desk_sn'=>2,'order_id'=>0])->update(['state'=>2]);
        }
        $info['code'] = 1;
        $info['msg'] = "查询成功";
        $info['data'] = $order_temp;
        $js = json_encode($info,JSON_UNESCAPED_UNICODE);
        //echo $js;
        $partnerid = 1000+$partner_id;
        if($desk_sn<10){
            $desk_sn = '0'.$desk_sn;
        }
        $string = time();
        echo $partnerid.$desk_sn.$string;
    }
    
}
