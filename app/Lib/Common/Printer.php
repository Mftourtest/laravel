<?php
/**
 * Created by PhpStorm.
 * User: zhaoyao
 * Date: 2018/5/6
 * Time: 10:45
 */
namespace App\Lib\Common;

use App;
use Illuminate\Support\Facades\Log;
use GuzzleHttp\Client;

use App\Models\OrderTeam;
use App\Models\FoodUserCoupon;
use App\Lib\Common\NewPrinter;

class Printer {

    const USER = 'jike_002@126.com';
    const UKEY = 'wzLPDEGT4ppVQ4s8';

    const IP = 'http://api.feieyun.cn';
    const PORT = 80;
    const PATH = '/Api/Open/';

    public function __construct() {

    }

    /**
     * @param $partner
     * @param $order
     * @param $orderTeam
     */
    public static function singlePrint($partner, $order, $orderTeam)
    {
        NewPrinter::singlePrint($partner, $order, $orderTeam);
    }


    /**
     * @param $partner
     * @param $order
     * @param $orderTeam
     */
    public static function categoryPrint($partner, $order, $orderTeam)
    {
        $prSnCate = explode('#', $partner['pr_sn_cate']);
        foreach ($prSnCate as $prSn) {
            list($lang, $count, $orderInfo) = self::headerPrint($partner);
            $footData = array(
                'timezone'  => $partner['timezone'],
                'discount'  => '',
                'coupon'    => 0,
                'feeTax'   => 0,
                'feeSrv'   => 0,
                'amount'    => 0.00,
                'deskSn'   => '',
                'service'   => $order['service'],
                'remark'    => $order['remark'],
            );
            $suffix = '_'.$lang;
            $arr = [];
            foreach ($orderTeam as $i=>$ot) {
                $package = unserialize($ot['package']);
                $footData['deskSn'] = $package['desk_sn'];

                if ($prSn == $package['pr_sn']) {
                    $arr[$i] = array(
                        'title'  => $package['title' . $suffix],
                        'price'  => $ot['price'],
                        'num'    => $ot['quantity'],
                        'prices' => $ot['price'] * $ot['quantity'],
                    );
                }
            }

            list($info, $nums) = self::typeSetting($arr, 14, 7, 3, 6);
            $footData['feeTax'] += round($partner['fee_tax'] * $nums, 2);
            $footData['feeSrv'] += round($partner['fee_srv'] * $nums, 2);
            $footData['amount']  += $nums + $footData['feeTax'] + $footData['feeSrv'];

            $orderInfo .= $info; // var_dump($info);

            $orderInfo .= self::footerPrint($footData);

            //for ($i=0; $i<$count; $i++) {
            if(count($arr) > 0) {
                self::wpPrint($prSn, $orderInfo, 1);
            }
            //}
        }
    }


    /**
     * @param $partner
     * @param $ids
     * @return string
     *
     */
    public static function confirmPrint($partner, $ids) {

        list($lang, $count, $orderInfo) = self::headerPrint($partner);
        $footData = array(
            'timezone'  => $partner['timezone'],
            'discount'  => $partner['discount'],
            'coupon'    => 0,
            'feeTax'   => 0,
            'feeSrv'   => 0,
            'amount'    => 0.00,
            'deskSn'   => '',
            'service'   => '',
            'remark'    => '',
        );

        $ids = explode(',', $ids); //return $ids;
        foreach ($ids as $oid) {

            $orderTeam = OrderTeam::select('price', 'quantity', 'package')
                ->where(['revoke'=>'0', 'orderid'=>$oid])->get();

            $suffix = '_'.$lang; //$lang == 'cn'?'':
            $arr = array();
            if (!empty($orderTeam)) {
                foreach ($orderTeam as $i=>$ot) {
                    $package = unserialize($ot['package']); //var_dump($package);exit;
                    $footData['deskSn']  = $package['desk_sn'];
                    $arr[$i] = array(
                        'title'  => $package['title' . $suffix],
                        'price'  => $ot['price'],
                        'num'    => $ot['quantity'],
                        'prices' => $ot['price'] * $ot['quantity']
                    );
                }
            }

            list($info, $nums) = self::typeSetting($arr, 14, 7, 3, 6);
            $footData['feeTax'] += round($partner['fee_tax'] * $nums, 2);
            $footData['feeSrv'] += round($partner['fee_srv'] * $nums, 2);
            $footData['amount']  += $nums + $footData['feeTax'] + $footData['feeSrv'];

            $userCoupon = FoodUserCoupon::with('coupon')->where(['order_id'=>$oid])->first();
            if (!empty($coupon)) {
                $footData['coupon'] += $userCoupon->coupon->price;
            }

            $orderInfo .= $info;
        }
        $footData['amount'] = $footData['amount'] * $footData['discount'];
        $footData['amount'] -= $footData['coupon'];

        $footData['service'] = 'cash';
        $orderInfo .= self::footerPrint($footData);

        if ($footData['amount'] > 0) {
            return self::wpPrint($partner['pr_sn'], $orderInfo, 1);
        } else {
            return 0;
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
    public static function headerPrint($partner)
    {
        $prLang = explode('@@@@', $partner['pr_lang']);
        $lang   = $prLang[0] ? $prLang[0] : 'en_us';
        $count  = $prLang[0] ? $prLang[1] : 2;

        App::setLocale($lang);
        $orderInfo = '<CB>'.mb_substr($partner['title'], 0, 8).'</CB><BR>';
        $orderInfo .= '--------------------------------<BR>';

        return array($lang, $count, $orderInfo);
    }


    /**
     * @param $data
     * @return string
     */
    public static function footerPrint($data)
    {
        $orderInfo = '';
        $orderInfo .= '--------------------------------<BR>';
        $orderInfo .= __('foods.food_remark').'：'.$data['remark'].'<BR>';
        $orderInfo .= '--------------------------------<BR>';

        $time = date('Y-m-d H:i:s', time());
        $orderInfo .= "$time<BR>";
        $orderInfo .= "Table：#{$data['deskSn']}<BR>";
        $orderInfo .= __('foods.food_pr_pay_type')."：{$data['service']}<BR>";
        $orderInfo .= __('foods.food_tax')."：{$data['feeTax']}<BR>";
        $orderInfo .= __('foods.food_charge')."：{$data['feeSrv']}<BR>";
        $orderInfo .= __('foods.food_discount')."：{$data['discount']}<BR>";
        $orderInfo .= __('foods.food_coupon')."：{$data['coupon']}<BR>";
        $orderInfo .= __('foods.food_pr_amount')."：{$data['amount']}<BR>";
        $orderInfo .= '--------------------------------<BR>';
        $orderInfo .= '<BR>';
        return $orderInfo;
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
    public static function typeSetting3($arr, $A, $C, $D) //, $B
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
            $orderInfo .= $num.' '.$prices.' '.$head.$tail.'<BR>';
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

