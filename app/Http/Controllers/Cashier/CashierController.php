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

    //按时间范围查询订单信息
    public function select_orders(Request $request)
    {
        $p_id = $request->input("partner_id");
        $start_time = $request->input("start_time");
        $end_time = $request->input("end_time");
        $pinfo = Partner::find($p_id);
        //获取order_temp下面的已结账订单和已取消订单
        $order_temps = DB::table('order_temp')->where('partner_id',$p_id)->whereBetween('create_time',[$start_time,$end_time])
        ->whereIn('state',[1,2])->where('temp_order_no','!=',0)->get();
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
                     if($v[0]->state==1){
                        $dorders_info[$k]['state_name'] = "已结账";
                     }
                     else{
                        $dorders_info[$k]['state_name'] = "已撤单";
                     }
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
              $arr[$i]['state_name'] = $v['state_name'];
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

    //查看订单详情
    public function order_detail(Request $request)
    {
        $temp_order_no = $request->input('temp_order_no');
        $order_temps = DB::table('order_temp')->where('temp_order_no',$temp_order_no)->get();
        $foodinfos = []; //菜单
        $orderprice = 0; //订单金额
        //获取菜单信息
        foreach($order_temps as $i=>$v){
            $food = DB::table('food')->where('id',$v->food_id)->first();
            if($v->package_id!=0){
                $package = DB::table('food_packages')->where('id',$v->package_id)->first();
                $foodinfos[$i]['title'] = $food->title.'('.$package->name.')';
                $foodinfos[$i]['title_en'] = $food->title_en.'('.$package->name_en.')';
                $foodinfos[$i]['title_vi'] = $food->title_vi.'('.$package->name_vi.')';
            }
            else{
                $foodinfos[$i]['title'] = $food->title;
                $foodinfos[$i]['title_en'] = $food->title_en;
                $foodinfos[$i]['title_vi'] = $food->title_vi;
            }
            $foodinfos[$i]['number'] = $v->number;
            $foodinfos[$i]['price'] = $v->price*$v->number;
            $orderprice += $foodinfos[$i]['price'];
        }
        $orderinfo = []; //订单信息
        $partner = DB::table('partner')->where('id',$order_temps[0]->partner_id)->first();
        $coupon = DB::table('order')->where('id',$order_temps[0]->order_id)->select('cost')->first();
        $orderinfo['temp_order_no'] = $temp_order_no;
        $orderinfo['create_time'] = $order_temps[0]->create_time;
        $orderinfo['desk_sn'] = $order_temps[0]->desk_sn;
        $orderinfo['source'] = $order_temps[0]->source;
        $orderinfo['type'] = "点餐";
        $orderinfo['order_price'] = $orderprice;
        $orderinfo['discount_price'] = round($orderprice*(1-$partner->discount),2); //折扣价格
        $orderinfo['tax_price'] = round($orderprice*$partner->fee_tax,2); //税费
        $orderinfo['srv_price'] = round($orderprice*$partner->fee_srv,2); //服务费
        $orderinfo['coupon'] = $coupon->cost; //代金券金额
        $orderinfo['last_price'] = round($orderprice-$orderinfo['discount_price']+$orderinfo['tax_price']+$orderinfo['srv_price']-$orderinfo['coupon'],2); //应收金额
        $orderinfo['small_price'] = round($orderinfo['last_price']-floor($orderinfo['last_price']),2);
        //dd($orderinfo);exit;
        $arr = [];
        if(!$order_temps->isEmpty()){
            $arr['code'] = 1;
            $arr['msg'] = "查询成功";
            $arr['orderinfo'] =  $orderinfo;
            $arr['foodinfos'] = $foodinfos;
            return json_encode($arr,JSON_UNESCAPED_UNICODE);
        }
        else{
            return $this->json_encode_nodata(1,"查询失败");
        }
    }

    //订单详情打印
    public function order_detail_printer(Request $request)
    {
        $temp_order_no = $request->input('temp_order_no');
        $order_temps = DB::table('order_temp')->where('temp_order_no',$temp_order_no)->get();
        $foodinfos = []; //菜单
        $orderprice = 0; //订单金额
        //获取菜单信息
        foreach($order_temps as $i=>$v){
            $food = DB::table('food')->where('id',$v->food_id)->first();
            if($v->package_id!=0){
                $package = DB::table('food_packages')->where('id',$v->package_id)->first();
                $foodinfos[$i]['title'] = $food->title.'('.$package->name.')';
                $foodinfos[$i]['title_en'] = $food->title_en.'('.$package->name_en.')';
                $foodinfos[$i]['title_vi'] = $food->title_vi.'('.$package->name_vi.')';
            }
            else{
                $foodinfos[$i]['title'] = $food->title;
                $foodinfos[$i]['title_en'] = $food->title_en;
                $foodinfos[$i]['title_vi'] = $food->title_vi;
            }
            $foodinfos[$i]['number'] = $v->number;
            $foodinfos[$i]['price'] = $v->price*$v->number;
            $orderprice += $foodinfos[$i]['price'];
        }
        $orderinfo = []; //订单信息
        $partner = DB::table('partner')->where('id',$order_temps[0]->partner_id)->first();
        $coupon = DB::table('order')->where('id',$order_temps[0]->order_id)->select('cost')->first();
        $orderinfo['partner_name'] = $partner->title;
        $orderinfo['temp_order_no'] = $temp_order_no;
        $orderinfo['create_time'] = $order_temps[0]->create_time;
        $orderinfo['desk_sn'] = $order_temps[0]->desk_sn;
        $orderinfo['source'] = $order_temps[0]->source;
        $orderinfo['state'] = $order_temps[0]->state;
        $orderinfo['order_price'] = $orderprice;
        $orderinfo['discount_price'] = round($orderprice*(1-$partner->discount),2); //折扣价格
        $orderinfo['tax_price'] = round($orderprice*$partner->fee_tax,2); //税费
        $orderinfo['srv_price'] = round($orderprice*$partner->fee_srv,2); //服务费
        $orderinfo['coupon'] = $coupon->cost; //代金券金额
        $orderinfo['last_price'] = round($orderprice-$orderinfo['discount_price']+$orderinfo['tax_price']+$orderinfo['srv_price']-$orderinfo['coupon'],2); //应收金额
        $orderinfo['small_price'] = round($orderinfo['last_price']-floor($orderinfo['last_price']),2);
        //dd($orderinfo);exit;
        $printer = DB::table('printer')->where('partner_id',$order_temps[0]->partner_id)->where('type',1)->first();
        $arr = [];
        if(!$order_temps->isEmpty()){
            $arr['code'] = 1;
            $arr['msg'] = "打印成功";
            $arr['orderinfo'] =  $orderinfo;
            $arr['foodinfos'] = $foodinfos;
            return json_encode($arr,JSON_UNESCAPED_UNICODE);
        }
        else{
            return $this->json_encode_nodata(1,"查询失败");
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
