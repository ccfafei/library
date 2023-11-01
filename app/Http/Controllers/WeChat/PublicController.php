<?php

namespace App\Http\Controllers\WeChat;

use App\Models\Order;
use App\Models\User;
use App\Models\VipCardLog;
use Carbon\Carbon;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use EasyWeChat\Factory;
use Illuminate\Support\Facades\Log;

class PublicController extends Controller
{
    public function serve()
    {
        $app = app('wechat.official_account');
        $app->server->push(function($message){
            $msg = null;
            switch ($message['MsgType']) {
                case 'event':
                    $msg = $this->responseEvent($message);
                    break;
            }
            if ($msg){
                return $msg;
            }
        });
        return $app->server->serve();
    }

    protected function responseEvent($message){
        if (strtolower($message['Event']) == 'subscribe'){
            return '欢迎同悦儿童借阅系统';
        }
        return false;
    }

    public function vipCardNotify(){
        $app = Factory::payment(config('wechat.payment.default'));
        $response = $app->handlePaidNotify(function ($message, $fail) {
            Log::error(json_encode($message));
            $order_map = [
                'trade_no' => $message['out_trade_no'],
            ];
            $order = VipCardLog::where($order_map)->first();
            if ($order){
                if ($message['return_code'] === 'SUCCESS') { // return_code 表示通信状态，不代表支付状态
                    // 用户是否支付成功
                    if (array_get($message, 'result_code') === 'SUCCESS') {

                        //会员卡日志更新
                        $save_order = VipCardLog::where($order_map)->update([
                            'status' => 1,
                        ]);

                        //用户会员卡过期时间更新
                        $member_no = $this->encodeID($order['id']);
                        $update_data = [
                            'member_no'  => $member_no,
                            'vip_id'     => $order['vip_id'],
                            'vip'         => $order['vip_name'],
                            'vip_type'   => $order['vip_type'],
                            'vip_exp_at' => $order['vip_exp_at'],
                        ];

                        //是否体验过
                        if($order['vip_type'] ==0){
                            $update_data['is_old'] =1;
                        }
                        $save_user = User::where(['id' => $order['user_id']])->update($update_data);


                        if ($save_order && $save_user){
                            return true;
                        }else{
                            return $fail('通信失败，请稍后再通知我');
                        }
                    } else {
                        return $fail('支付失败，请稍后重试');
                    }
                } else {
                    return $fail('通信失败，请稍后再通知我');
                }
            }else{
                return $fail('通信失败，请稍后再通知我');
            }
        });
        return $response;
    }

    public function orderNotify(){
        $app = Factory::payment(config('wechat.payment.default'));
        $response = $app->handlePaidNotify(function ($message, $fail) {
            $order_map = [
                'order_no' => $message['out_trade_no'],
            ];
            $order = Order::where($order_map)->first();
            if ($order){
		if ($order->status >= 1){
                    return true;
                }
                if ($message['return_code'] === 'SUCCESS') { // return_code 表示通信状态，不代表支付状态
                    // 用户是否支付成功
                    if (array_get($message, 'result_code') === 'SUCCESS') {
                        $save_order = Order::where(['order_no' => $message['out_trade_no']])->update([
                            'status' => 1,
                            'pay_at' => Carbon::now(),
                        ]);
                        if ($save_order){
                            return true;
                        }else{
                            return $fail('通信失败，请稍后再通知我');
                        }
                    } else {
                        return $fail('支付失败，请稍后重试');
                    }
                } else {
                    return $fail('通信失败，请稍后再通知我');
                }
            }else{
                return $fail('通信失败，请稍后再通知我');
            }
        });
        return $response;
    }

    public function test(){

    }
}
