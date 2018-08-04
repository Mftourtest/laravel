<?php
namespace App\Lib\Common;

use App;
use Illuminate\Support\Facades\Log;
use GuzzleHttp\Client;

use App\Models\OrderTeam;
use App\Models\FoodUserCoupon;

class CashierPrinter {

    const USER = 'jike_002@126.com';
    const UKEY = 'wzLPDEGT4ppVQ4s8';

    const IP = 'http://api.feieyun.cn';
    const PORT = 80;
    const PATH = '/Api/Open/';

    public function __construct() {

    }

    /**
     * 收银台订单详情打印
     * @param $order
     * @param $foodinfos
     * @param $printer  object
     */
    public static function orderPrint($order, $foodinfos,$printer)
    {
        list($lang, $count, $orderInfo) = self::headerPrint($order,$printer); //头部打印信息

        $footData = array(
            'timezone'       => $order['timezone'], //时区
            'discount_price' => $order['discount_price'], //折扣金额
            'cost'           => $order['coupon'], //代金券金额
            'tax_price'      => $order['tax_price'], //税费
            'srv_price'      => $order['srv_price'], //服务费
            'temp_order_no'  => $order['temp_order_no'], //订单编号
            'create_time'    => $order['create_time'], //下单时间
            'service'        => $order['service'],   //支付方式
            'remark'         => $order['remark'],    //订单备注
            'source'         => $order['source'],    //下单人账号
            'order_price'    => $order['order_price'],//订单金额
            'last_price'     => $order['last_price'], //应收金额
            'small_price'    => $order['small_price'], //抹零金额
            'coupon'         => 0                      //优惠券金额
        );
        $suffix = '_'.$lang;
        foreach ($foodinfos as $i=>$food) {
            $arr[$i] = array(
                'title'  => $food['title' . $suffix],
                'num'    => floor($food['number']),
                'prices' => $food['price']
            );
        }
       // if ($lang == 'vi') {
        list($info, $nums) = self::typeSetting3($arr, 23, 2, 5);
       // } else {
       //     list($info, $nums) = self::typeSetting($arr, 14, 7, 3, 6);
       // }   
        $orderInfo .= $info;
        $orderInfo .= self::footerPrint($footData);
        for ($i=0; $i<$count; $i++) {
            self::wpPrint($printer->pr_sn, $orderInfo, 1);
        }
        
    }


    /**
     * 后厨打印
     * @param $foodinfos  array 
     * @param $order      array 
     * @param $cate_prints array
     */
    public static function categoryPrint($order, $foodinfos,$cate_prints)
    {
        foreach($cate_prints as $cate_print){
            $lang = $cate_print['pr_lang'];
            $count = $cate_print['number'];
            App::setLocale($lang);
            if($order['type']=='order'){     //下单
                $type = __('foods.waiter_single_summary');
            }
            else if($order['type']=='cancel'){ //退菜
                $type = __('foods.waiter_collection_vegetables');
            }
            else if($order['type']=='add'){ //加菜
                $type = __('foods.cashier_add_food');
            }
            $orderInfo = '<C><BOLD>'.$order['title'].'</BOLD></C><BR>';
            $orderInfo .= '<C><B>'.__('foods.waiter_table_no').'：'.$order['desk_sn'].'</B></C><BR>';
            $orderInfo .= '<C><B>'.$type.'</B></C><BR>';
            $orderInfo .= '--------------------------------<BR>';
            $arr = [];
            $i = 0;
            $suffix = '_'.$lang;
            $cate_ids = explode(',', $cate_print['cate_id']);
            foreach ($cate_ids as $cate_id) {            
                foreach ($foodinfos as $food) {
                    if($cate_id==$food['cate_id']){  //如果菜品的类别id和打印的分类id一样就把菜信息填到打印数组
                        $arr[$i] = array(
                        'title'  => $food['title' . $suffix],
                        'num'    => floor($food['number']),
                        'prices' => $food['price']
                       );
                       $i = $i+1;
                    }
                }
            }
            list($info) = self::typeSetting1($arr, 28, 2);
            $orderInfo .= $info; // var_dump($info);
            $orderInfo .= self::kitchenFooterPrint($order);
            if(!empty($arr)){
                for ($i=0; $i<$count; $i++) {
                    self::wpPrint($cate_print['pr_sn'], $orderInfo, 1);
                }
            }
        }
    }


    /**
     * @param $partner
     * @param $order
     * @param $team
     * @return mixed|string
     */
    public static function teamPrint($partner, $order, $team)
    {
        list($lang, $count, $orderInfo) = self::headerPrint($partner);
        $orderInfo .= __('foods.food_order_no') . "：{$order['id']}<BR>";
        $orderInfo .= __('foods.x_biz_pay_time') . '：' . date('Y-m-d H:i:s', $order['pay_time']).'<BR>';
        $orderInfo .= '--------------------------------<BR>';
        $orderInfo .= '<CB>验券成功</CB><BR>';
        $orderInfo .= '--------------------------------<BR>';
        $orderInfo .= "{$team['title']}<BR>";
        $orderInfo .= __('foods.food_amount') . "：￥{$order['origin']}<BR>";
        $orderInfo .= '--------------------------------<BR>';

        for ($i=0; $i<$count; $i++) {
            self::wpPrint($partner['pr_sn'], $orderInfo, 1);
        }

        return $orderInfo;
    }


    /**
     * @param $partner
     * @return array
     */
    public static function headerPrint($order,$printer)
    {
        $lang   = $printer->pr_lang;
        $count  = $printer->number;
        
        App::setLocale($lang);
        if($order['type']=='order'){     //下单
            $type = __('foods.waiter_single_summary');
        }
        else if($order['type']=='cash'){  //结账
            $type = __('foods.waiter_checkout_summary');
        }
        else if($order['type']=='cancel'){ //退菜
            $type = __('foods.waiter_collection_vegetables');
        }
        else if($order['type']=='del'){ //撤单
            $type = __('foods.cashier_cancel_order');
        }
        else if($order['type']=='add'){ //加菜
            $type = __('foods.cashier_add_food');
        }
        $orderInfo = '<C><BOLD>'.$order['title'].'</BOLD></C><BR>';
        $orderInfo .= '<C><B>'.__('foods.waiter_table_no').'：'.$order['desk_sn'].'</B></C><BR>';
        $orderInfo .= '<C><B>'.$type.'</B></C><BR>';
        $orderInfo .= '--------------------------------<BR>';

        return array($lang, $count, $orderInfo);
    }


    /**
     * @param $data
     * @return string
     */
    public static function footerPrint($data)
    {
        $ordertime = date('Y-m-d H:i:s', $data['create_time']); //下单时间
        $nowtime = date('Y-m-d H:i:s', time()); //打印时间
        $moling_price = $data['last_price']-$data['small_price'];
        $orderInfo = '';
        $orderInfo .= '--------------------------------<BR>';
        $orderInfo .= __('foods.food_remark').'：'.$data['remark'].'<BR>';
        $orderInfo .= '--------------------------------<BR>';
        $orderInfo .= __('foods.biz_order_id')."：{$data['temp_order_no']}<BR>";
        $orderInfo .= __('foods.biz_order_create_time')."：{$ordertime}<BR>";
        $orderInfo .= __('foods.cashier_source')."：{$data['source']}<BR>";
        $orderInfo .= __('foods.food_pr_pay_type')."：{$data['service']}<BR>";
        $orderInfo .= __('foods.cashier_order_price')."：{$data['order_price']}<BR>"; //订单价格
        $orderInfo .= __('foods.food_tax')."：{$data['tax_price']}<BR>";
        $orderInfo .= __('foods.food_charge')."：{$data['srv_price']}<BR>";
        $orderInfo .= __('foods.food_discount')."：-{$data['discount_price']}<BR>";
        $orderInfo .= __('foods.food_coupon')."：-{$data['coupon']}<BR>"; //优惠券
        $orderInfo .= __('foods.waiter_cash_coupon')."：-{$data['cost']}<BR>";   //代金券
        $orderInfo .= __('foods.food_pr_amount')."：{$data['last_price']}<BR>";  //应收金额
        $orderInfo .= __('foods.cashier_moling_price')."：{$moling_price}<BR>";  //抹零后金额
        $orderInfo .= '--------------------------------<BR>';
        $orderInfo .= '<C>Thank you very much</C><BR>';
        return $orderInfo;
    }

    /**
     * @param $data
     * @return string
     */
    public static function kitchenFooterPrint($data)
    {
        $time = date('Y-m-d H:i:s', time());
        $orderInfo = '';
        $orderInfo .= '--------------------------------<BR>';
        $orderInfo .= __('foods.food_remark').'：'.$data['remark'].'<BR>';
        $orderInfo .= '--------------------------------<BR>';
        $orderInfo .= __('foods.biz_order_id')."：{$data['temp_order_no']}<BR>";
        $orderInfo .= __('foods.biz_order_create_time')."：{$time}<BR>";
        return $orderInfo;
    }

    /**
     * 交班打印
     * @param $data  array
     * @param $print object
     * @return string
     */
    public static function shiftPrint($data,$print)
    {
        $lang   = $print->pr_lang;
        $count  = $print->number;     
        App::setLocale($lang);
        $orderInfo = '<C><BOLD>'.$data['title'].'</BOLD></C><BR>';
        $orderInfo .= '<C><B>'.__('foods.cashier_shift_record').'</B></C><BR>'; 
        $orderInfo .= '--------------------------------<BR>';
        $orderInfo .= __('foods.cashier_start_time')."：{$data['start_time']}<BR>";
        $orderInfo .= __('foods.cashier_end_time')."：{$data['end_time']}<BR>";
        $orderInfo .= __('foods.cashier_cashier')."：{$data['cashier']}<BR>";
        $orderInfo .= '--------------------------------<BR>';
        $orderInfo .= '['.__('foods.cashier_pay_info').']<BR>'; //已结账信息汇总
        $orderInfo .= __('foods.cashier_pay_count')."：{$data['pay_count']}<BR>";
        $orderInfo .= __('foods.cashier_pay_ying')."：{$data['last_price']}<BR>";
        $orderInfo .= __('foods.cashier_pay_shi')."：{$data['last_price']}<BR>";
        $orderInfo .= '--------------------------------<BR>';
        $orderInfo .= '['.__('foods.cashier_unpay_info').']<BR>'; //未结账信息汇总
        $orderInfo .= __('foods.cashier_unpay_count')."：{$data['unpay_count']}<BR>";
        $orderInfo .= __('foods.cashier_unpay_price')."：{$data['unpay_order_price']}<BR>";
        $orderInfo .= __('foods.cashier_unpay_tax')."：{$data['unpay_tax']}<BR>";
        $orderInfo .= __('foods.cashier_unpay_srv')."：{$data['unpay_srv']}<BR>";
        $orderInfo .= __('foods.cashier_pay_ying')."：{$data['unpay_ying_price']}<BR>";
        $orderInfo .= '--------------------------------<BR>';
        $orderInfo .= '['.__('foods.cashier_cancel_info').']<BR>'; //已撤单信息汇总
        $orderInfo .= __('foods.cashier_cancel_count')."：{$data['cancel_count']}<BR>";
        $orderInfo .= __('foods.cashier_cancel_price')."：{$data['cancel_order_price']}<BR>";
        $orderInfo .= '--------------------------------<BR>';
        $orderInfo .= '['.__('foods.cashier_refund_info').']<BR>'; //退菜信息汇总
        $orderInfo .= __('foods.cashier_refund_count')."：{$data['refund_count']}<BR>";
        $orderInfo .= __('foods.cashier_refund_price')."：{$data['refund_price']}<BR>";
        $orderInfo .= '--------------------------------<BR>';
        $orderInfo .= '<B>'.__('foods.cashier_sign').':</B><BR>'; 
        for ($i=0; $i<$count; $i++) {
            self::wpPrint($print->pr_sn, $orderInfo, 1);
        }
    }


    /**
     * @param $arr
     * @param $A
     * @param $B
     * @param $C
     * @param $D
     * @return array
     */
    public static function typeSetting($arr, $A, $B, $C, $D)
    {
        $orderInfo = '';
        $nums = 0;

        foreach ($arr as $k5 => $v5) {
            $name = $v5['title'];
            $price = $v5['price'];
            $num = $v5['num'];
            $prices = $v5['prices'];
            $kw1 = '';
            $kw2 = '';
            $kw3 = '';
            $kw4 = '';
            $str = $name;
            $blankNum = $A;//名称控制为14个字节
            $lan = mb_strlen($str, 'utf-8');
            $m = 0;
            $j = 1;
            $blankNum ++;
            $result = array();

            $tail='';
            for ($i=0; $i<$lan; $i++){
                $new = mb_substr($str, $m, $j, 'utf-8');
                $j ++;
                if(mb_strwidth($new, 'utf-8') < $blankNum) {
                    if($m+$j>$lan) {
                        $m = $m+$j;
                        $tail = $new;
                        $lenght = iconv("UTF-8", "GBK//IGNORE", $new);
                        $k = $A - strlen($lenght);
                        for($q=0; $q<$k; $q++){
                            $kw3 .= ' ';
                        }
                        $tail .= $kw3;
                        break;
                    }else{
                        $next_new = mb_substr($str, $m, $j, 'utf-8');
                        if(mb_strwidth($next_new, 'utf-8') < $blankNum)
                            continue;
                        else{
                            $m = $i+1;
                            $result[] = $new.'<BR>';
                            $j = 1;
                        }
                    }
                }
            }
            $head = '';
            foreach ($result as $value) {
                $head .= $value;
            }
            if(strlen($price) < $B){
                $k1 = $B - strlen($price);
                for($q=0;$q<$k1;$q++){
                    $kw1 .= ' ';
                }
                $price = $price.$kw1;
            }
            if(strlen($num) < $C){
                $k2 = $C - strlen($num);
                for($q=0;$q<$k2;$q++){
                    $kw2 .= ' ';
                }
                $num = $num.$kw2;
            }
            if(strlen($prices) < $D){
                $k3 = $D - strlen($prices);
                for($q=0;$q<$k3;$q++){
                    $kw4 .= ' ';
                }
                $prices = $prices.$kw4;
            }
            $orderInfo .= $head.$tail.' '.$price.' '.$num.' '.$prices.'<BR>';
            @$nums += $prices;
        }
        return array($orderInfo, $nums);
    }

    /**
     * @param $arr
     * @param $A
     * @param $C
     * @param $D
     * @return array
     */
    public static function typeSetting1($arr, $A, $C) //, $B
    {
        $orderInfo = '';
        $nums = 0;

        foreach ($arr as $k5 => $v5) {
            $name = $v5['title'];
            $num = $v5['num'];
            $kw1 = '';
            $kw2 = '';
            $kw3 = '';
            $kw4 = '';
            $str = $name;
            $blankNum = $A;//名称控制为14个字节
            $lan = mb_strlen($str, 'utf-8');
            $m = 0;
            $j = 1;
            $blankNum ++;
            $result = array();

            $tail='';
            for ($i=0; $i<$lan; $i++){
                $new = mb_substr($str, $m, $j, 'utf-8');
                $j ++;
                if(mb_strwidth($new, 'utf-8') < $blankNum) {
                    if($m+$j>$lan) {
                        $m = $m+$j;
                        $tail = $new;
                        $lenght = iconv("UTF-8", "GBK//IGNORE", $new);
                        $k = $A - strlen($lenght);
                        for($q=0; $q<$k; $q++){
                            $kw3 .= ' ';
                        }
                        $tail .= $kw3;
                        break;
                    }else{
                        $next_new = mb_substr($str, $m, $j, 'utf-8');
                        if(mb_strwidth($next_new, 'utf-8') < $blankNum)
                            continue;
                        else{
                            $m = $i+1;
                            $result[] = $new.'<BR>';
                            $j = 1;
                        }
                    }
                }
            }
            $head = '';
            foreach ($result as $value) {
                $head .= $value;
            }
            if(strlen($num) < $C){
                $k2 = $C - strlen($num);
                for($q=0;$q<$k2;$q++){
                    $kw2 .= ' ';
                }
                $num = $num.$kw2;
            }
            $orderInfo .= $num.' '.$head.$tail.'<BR>';
        }
        return array($orderInfo);
    }
    /**
     * @param $arr
     * @param $A
     * @param $C
     * @param $D
     * @return array
     */
    public static function typeSetting3($arr, $A, $C, $D) //, $B
    {
        $orderInfo = '';
        $nums = 0;

        foreach ($arr as $k5 => $v5) {
            $name = $v5['title'];
            $num = $v5['num'];
            $prices = $v5['prices'];
            $kw1 = '';
            $kw2 = '';
            $kw3 = '';
            $kw4 = '';
            $str = $name;
            $blankNum = $A;//名称控制为14个字节
            $lan = mb_strlen($str, 'utf-8');
            $m = 0;
            $j = 1;
            $blankNum ++;
            $result = array();

            $tail='';
            for ($i=0; $i<$lan; $i++){
                $new = mb_substr($str, $m, $j, 'utf-8');
                $j ++;
                if(mb_strwidth($new, 'utf-8') < $blankNum) {
                    if($m+$j>$lan) {
                        $m = $m+$j;
                        $tail = $new;
                        $lenght = iconv("UTF-8", "GBK//IGNORE", $new);
                        $k = $A - strlen($lenght);
                        for($q=0; $q<$k; $q++){
                            $kw3 .= ' ';
                        }
                        $tail .= $kw3;
                        break;
                    }else{
                        $next_new = mb_substr($str, $m, $j, 'utf-8');
                        if(mb_strwidth($next_new, 'utf-8') < $blankNum)
                            continue;
                        else{
                            $m = $i+1;
                            $result[] = $new.'<BR>';
                            $j = 1;
                        }
                    }
                }
            }
            $head = '';
            foreach ($result as $value) {
                $head .= $value;
            }
            if(strlen($num) < $C){
                $k2 = $C - strlen($num);
                for($q=0;$q<$k2;$q++){
                    $kw2 .= ' ';
                }
                $num = $num.$kw2;
            }
            if(strlen($prices) < $D){
                $k3 = $D - strlen($prices);
                for($q=0;$q<$k3;$q++){
                    $kw4 .= ' ';
                }
                $prices = $prices.$kw4;
            }
            $orderInfo .= $num.' '.$head.$tail.' '.$prices.'<BR>';
            @$nums += $prices;
        }
        return array($orderInfo, $nums);
    }


    /**
     * @param $printerSn
     * @param $orderInfo
     * @param $times
     * @return string
     */
    public static function wpPrint($printerSn, $orderInfo, $times)
    {
        $sTime = time(); //公共参数，请求时间
        $sign = sha1(self::USER . self::UKEY . $sTime); //公共参数，请求公钥
        $http = new Client();

        $res = $http->request('POST', self::IP.self::PATH, [
            'form_params' => [
                'user'  => self::USER,
                'stime' => $sTime,
                'sig'   => $sign,
                'apiname'   =>'Open_printMsg',

                'sn'        => $printerSn,
                'content'   => $orderInfo,
                'times'     => $times, //打印次数
                'language'  => 'Thai', //CP874
            ]
        ]);

        $resJson = (string) $res->getBody();


        return empty($resJson) ? 'error' : $resJson;
    }
}

