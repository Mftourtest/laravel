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

    public function test(Request $request)
    {
        $prints = $request->all(); 
        print_r($prints);
        echo $prints['token'];
    }
    
}
