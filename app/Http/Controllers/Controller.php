<?php

namespace App\Http\Controllers;

use Illuminate\Foundation\Bus\DispatchesJobs;
use Illuminate\Http\Request;
use Illuminate\Routing\Controller as BaseController;
use Illuminate\Foundation\Validation\ValidatesRequests;
use Illuminate\Foundation\Auth\Access\AuthorizesRequests;
use Illuminate\Support\Facades\Cache;
use Overtrue\EasySms\EasySms;


class Controller extends BaseController
{
    use AuthorizesRequests, DispatchesJobs, ValidatesRequests;

    private $dic = array(
        0=>'0',    1=>'1', 2=>'2', 3=>'3', 4=>'4', 5=>'5', 6=>'6', 7=>'7', 8=>'8',
        9=>'9', 10=>'A',  11=>'B', 12=>'C', 13=>'D', 14=>'E', 15=>'F',  16=>'G',  17=>'H',
        18=>'I',19=>'J',  20=>'K', 21=>'L',  22=>'M',  23=>'N', 24=>'O', 25=>'P', 26=>'Q',
        27=>'R',28=>'S',  29=>'T',  30=>'U', 31=>'V',  32=>'W',  33=>'X', 34=>'Y', 35=>'Z'
    );


    // 成功返回状态
    public static function ok($data = null, $msg = 'success')
    {
        return ['code' => 200, 'msg' => $msg, 'data' => $data];
    }

    // 失败返回状态
    public static function err($msg = 'error',$err_code = '')
    {
        return ['code' => 0, 'msg' => $msg, 'err_code' => $err_code];
    }


    public static function check_phone($phone)
    {
        return preg_match('/^1[3456789]\d{9}$/', $phone);
    }


    protected static function code($len = 4)
    {
        $str = '1234567890';
        $res = '';
        for ($i = 0; $i < $len; $i++){
            $j = mt_rand(0, strlen($str)-1);
            $res .= substr($str, $j, 1);
        }
        return $res;
    }

    // 检验短信验证码
    protected function check_vCode($phone,$vcode)
    {
        if ($phone && $vcode){
            $value = Cache::get('vcode_'.$phone);
            if ($value == $vcode){
                return true;
            }
        }
        return false;
    }


    protected function sendSmsVCode($phone,$vcode){
        $config = config('params')['sms'];
        $easySms = new EasySms($config);
        $res = $easySms->send($phone, [
            'template' => $config['vcode_template_id'],
            'data' => [
                $vcode, $config['cache_vcode_exp'],
            ],
        ]);
        if ($res['yuntongxun']['status'] == 'success'){
            return true;
        }else{
            return false;
        }
    }

    //生成卡号
    public function encodeID($int, $format=8) {
        $dics = $this->dic;
        $dnum = 36; //进制数
        $arr = array ();
        $loop = true;
        while ($loop) {
            $arr[] = $dics[bcmod($int, $dnum)];
            $int = bcdiv($int, $dnum, 0);
            if ($int == '0') {
                $loop = false;
            }
        }
        if (count($arr) < $format)
            $arr = array_pad($arr, $format, $dics[0]);

        return implode('', array_reverse($arr));
    }

    public function decodeID($ids) {
        $dics = $this->dic;
        $dnum = 36; //进制数
        //键值交换
        $dedic = array_flip($dics);
        //去零
        $id = ltrim($ids, $dics[0]);
        //反转
        $id = strrev($id);
        $v = 0;
        for ($i = 0, $j = strlen($id); $i < $j; $i++) {
            $v = bcadd(bcmul($dedic[$id {
            $i }
            ], bcpow($dnum, $i, 0), 0), $v, 0);
        }
        return $v;
    }

}
