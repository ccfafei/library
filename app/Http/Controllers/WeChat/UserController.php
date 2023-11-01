<?php

namespace App\Http\Controllers\WeChat;

use App\Models\Evaluate;
use App\Models\Grade;
use App\Models\Kids;
use App\Models\Order;
use App\Models\School;
use App\Models\User;
use App\Models\VipCard;
use App\Models\VipCardLog;
use App\Models\WeChat;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Cache;
use EasyWeChat\Factory;
use Illuminate\Support\Facades\Log;


class UserController extends BaseController
{
    protected $official_config;
    protected $payment_config;

    public function __construct()
    {
        parent::__construct();
        $this->official_config = config('wechat.official_account.default');
        $this->payment_config = config('wechat.payment.default');
    }

    // 用户中心
    public function index(Request $request)
    {
        $user = $request->getUser;
        $isworker = false;
        if ($user) {
            $res = User::find($user['id']);
            $kid_info = [];
            $info = Kids::where('p_id',$user['id'])->first();
            if(!empty($info)){
                $kid_info = getKidInfos($info['id']);
            }else{
                $kid_info =[];
            }
            $vip_arr = config('params.vip_type');
            return view('weChat.ucenter.center', [
                'user' => $res,
                'kids' =>$kid_info,
                'vip_arr'=>$vip_arr,
                'title_name' => '我的',
                'isworker' => $isworker
            ]);


        } else {
            abort(404);
        }
    }

    //更改手机号
    public function edit_phone(Request $request)
    {
        $user = $request->getUser;
        if ($user) {
            $res = User::find($user['id']);
            return view('weChat.ucenter.bindphone', [
                'user' => $res,
                'title_name' => '更改手机号',
            ]);
        } else {
            abort(404);
        }
    }



    //查看会员资料
    public function memberinfo(Request $request)
    {
        $user = $request->getUser;
        if ($user) {
            $res = User::find($user['id']);
            $info = Kids::where('p_id',$user['id'])->first();

            $kid_info =[];
            if(!empty($info)){
                $kid_info = getKidInfos($info['id']);
            }
            return view('weChat.ucenter.info', [
                'list' => $res,
                'kids'=>$kid_info,
                'title_name' => '会员资料',
            ]);
        } else {
            abort(404);
        }
    }


    //修改会员资料
    public function editinfo(Request $request)
    {
        $user = $request->getUser;
        if ($user) {
            $res = User::with('kids')->find($user['id']);
            $info = Kids::where('p_id',$user['id'])->first();
            $kid_info =[];
            if(!empty($info)){
                $kid_info = getKidInfos($info['id']);
            }
            $school_options = School::get();
            return view('weChat.ucenter.editinfo', [
                'list' => $res,
                'school_options'=>$school_options,
                'kids'=>$kid_info,
                'title_name' => '更改会员资料',
            ]);
        } else {
            abort(404);
        }
    }

    //获取班级信息
    public function getGrade(Request $request){
        try {
            $user = $request->getUser;
            if ($user) {
              $school_id = $request->input('school_id');
              if(empty($school_id)){
                  return self::err('系统错误，传参失败');
              }
              $grade_info = Grade::where('school_id',$school_id)->get();
              if(collect($grade_info)->isEmpty()){
                  return self::err('获取班级信息失败');
              }
              return self::ok($grade_info);
            } else {
                return self::err('获取用户信息失败');
            }
        } catch (\Exception $ex) {
            return self::err('系统异常，请稍后再试');
        }
    }

    //保存会员资料
    public function saveMemberinfo(Request $request)
    {
        $user = $request->getUser;
        $data = [];
        if ($user) {
            $requst_parms = $request->all();

            try {
                $users = User::where('id', $user['id'])->update(['name' => $requst_parms['name']]);
                $kids   =   new Kids();
                $result = Kids::where('p_id',$user['id'])->first();
                $kid_info = [
                    'name' => $requst_parms['kid_name'],
                    'school_id' => $requst_parms['school_id'],
                    'grade_id' => $requst_parms['grade_id'],
                    'p_id'     => $user['id'],
                ];
                if(collect($result)->isEmpty($result)){
                    $kid_info['created_at'] = date('Y-m-d H:i:s');
                    Kids::insert($kid_info);
                }else{
                    $kid_info['updated_at'] = date('Y-m-d H:i:s');
                    Kids::where('id',$result['id'])->update($kid_info);
                }

            } catch (\Exception $ex) {

                return redirect('wechat/prompt')->with(
                    ['msg'=>'系统异常！',
                        'confirm_url' =>url('/wechat/user/editinfo'),
                        'cancel_url' =>url('/wechat/user/editinfo'),
                    ]);
            }
            return redirect('wechat/prompt')->with(
                ['msg'=>'保存成功！',
                    'confirm_url' =>url('/wechat/user/vipcard'),
                    'cancel_url' =>url('/wechat/user/editinfo'),
                ]);

        } else {
            return redirect('wechat/prompt')->with(
                ['msg'=>'系统异常！',
                    'confirm_url' =>url('/wechat/user'),
                    'cancel_url' =>url('/wechat/user/editinfo'),
                ]);
        }
        //dd($request->all());
    }

    /**
     * 手机号注册
     * @param Request $request
     * @return array
     */

    public function update_phone(Request $request)
    {
        $phone = $request->input('phone');
        $vcode = $request->input('vcode');
        $user = $request->getUser;
        if ($user) {
            if (!self::check_phone($phone)) {
                return self::err('手机号格式错误');
            }
            // 检测验证码
            if (!self::check_vCode($phone, $vcode)) {
                return self::err('验证码错误');
            }
            if (User::isExist(['phone' => $phone], $user['id'])) {
                return self::err('手机号已存在');
            }
            $res = User::find($user['id'])->update(['phone' => $phone]);
            if ($res) {
                return self::ok();
            } else {
                return self::err('操作失败');
            }
        } else {
            return self::err('操作失败');
        }
    }

    /**
     * 获取验证码
     * @param Request $request
     * @return array
     */
    public function getVCode(Request $request)
    {
        try{
            $phone = $request->input('phone');
            $user = $request->getUser;
            if ($user) {
                if (!self::check_phone($phone)) {
                    return self::err('手机号格式错误');
                }
                if (User::isExist(['phone' => $phone], $user['id'])) {
                    return self::err('手机号已存在');
                }
                $code = self::code();
                $expiresAt = Carbon::now()->addMinutes(config('params')['sms']['cache_vcode_exp']);
                Cache::put('vcode_' . $phone, $code, $expiresAt);
                $cache = Cache::get('vcode_' . $phone);
                if ($cache) {
                    // 发短信
                    $sms = $this->sendSmsVCode($phone, $code);
                    if ($sms) {
                        return self::ok();
                    } else {
                        return self::err('获取验证码失败');
                    }
                } else {
                    return self::err('获取验证码失败');
                }
            } else {
                return self::err('操作失败');
            }
        }catch (\Exception $ex){
            Log::err($ex->getExceptions());
            return self::err('网络异常');
        }
    }

    /**
     * 会员支付
     * @param Request $request
     * @return \Illuminate\Contracts\View\Factory|\Illuminate\View\View
     */
    public function vipCard(Request $request){
        $user = $request->getUser;
        $is_buy_vip = false;
        $app = Factory::payment($this->payment_config);
        $app_official = Factory::officialAccount($this->official_config);
        if ($user) {

            $vip_arr = config('params.vip_type');
            $get_user = User::find($user['id']);
            $info = Kids::where('p_id',$user['id'])->first();
            $kid_info =[];
            if(!empty($info)){
                $kid_info = getKidInfos($info['id']);
            }

            $get_user = User::find($user['id']);
            if (empty($get_user->vip) || $get_user->vip_exp_at < date('Y-m-d')) {
                $is_buy_vip = true;
            }

            $vip_options =[];
            $today = date('Y-m-d');
            $d = new \DateTime($today);
            $d->modify('+1 months');

            $month = $d->format('Y-m-d');
            $halfMonth = date('Y-m-d',strtotime('+15 day'));
            $vip_list = [];
            if(!empty($info['school_id'])){
                $vip_options =  VipCard::where('school_id',$info['school_id'])->where('status',1)->get();
                foreach ($vip_options as $key=>$item){

                    if($item->type==0 ){
                        //是否领取过体验卡
                        if($get_user->is_old ==1){
                            continue;
                        }
                        $item->start_ts = $today;
                        $item->end_ts = $halfMonth;
                    }elseif($item->type==1){
                        $item->start_ts = $today;
                        $item->end_ts = $month;
                    }else{
                        $item->start_ts = date('Y-m-d',strtotime($item->start_ts));
                        $item->end_ts = date('Y-m-d',strtotime($item->end_ts));
                    }
                    $vip_list[] = $item;
                }
            }



            return view('weChat.ucenter.vipcard', [
                'user' => $get_user,
                'app'=>$app_official,
                'is_buy_vip' =>$is_buy_vip,
                'kids'=>$kid_info,
                'vip_options'=>$vip_list,
                'title_name' => '购买会员卡',
                'vip_arr' =>$vip_arr,
            ]);
        }else{
            abort(500);
        }
    }



    /**
     * 生成交易
     * @param Request $request
     * @return array
     */
    public function createTrade(Request $request)
    {
        try{
            $vip_id = $request->input('vip_id');
            $vip_end_ts = $request->input('vip_end_ts');
            if(!isset($vip_id)){
                return self::err('会员信息不能为空');
            }
            //$vip_info = json_decode($vip_info,true);
            $user = $request->getUser;
            if ($user) {
                //获取费用
                $total_fee = 0;
                $vip = VipCard::find($vip_id);
                $get_user = User::find($user['id']);
                $total_fee = $vip->price*100;
                $recommend_uid = null;
                $app = Factory::payment($this->payment_config);
                $app_official = Factory::officialAccount($this->official_config);
                $trade_no = WeChat::generate18NumOrderNo($user['id']);

                $order = $app->order->unify([
                    'body' => '年卡充值',
                    'out_trade_no' => $trade_no,
                    'total_fee' => $total_fee,
                    'trade_type' => 'JSAPI',
                    'openid' => $get_user->openid,
                    'notify_url' => url('wechat/payment/vipCardNotify')
                ]);

                Log::info(json_encode($order));
                if ($order['return_code'] == 'SUCCESS' && $order['result_code'] == 'SUCCESS') {
                    //记录会员卡日志
                    $vip_exp_at = $vip_end_ts;
                    $data = [
                        'user_id' => $user['id'],
                        'recommend_uid' => $recommend_uid,
                        'trade_no' => $trade_no,
                        'vip_id' => $vip->id,
                        'vip_name' => $vip->name,
                        'vip_type'=> $vip->type,
                        'vip_exp_at' =>$vip_exp_at,
                        'price' =>  $vip->price,
                        'status' => 0,
                    ];
                    VipCardLog::create($data);
                    $prepayId = $order['prepay_id'];

                    $jssdk = $app->jssdk;
                    $sdk = $jssdk->sdkConfig($prepayId);
                    $pay_data = [
                        'sdk' => $sdk,
                        'trade_no' => $trade_no,
                        'price'=>  $vip->price,
                    ];
                    Log::info(json_encode($pay_data));
                    return self::ok($pay_data);
                }else{
                    return self::err('生成支付订单出错');
                }

            } else {
                return self::err('生成订单失败');
            }
        }catch (\Exception $ex){
            return self::err('生成订单异常，请稍后再试');
        }
    }



}
