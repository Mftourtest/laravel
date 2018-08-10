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
use App\Lib\Common\CashierPrinter;
use App\Models\Order;

class PaymentController extends Controller
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
