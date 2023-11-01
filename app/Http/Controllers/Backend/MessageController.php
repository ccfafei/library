<?php

namespace App\Http\Controllers\Backend;

use App\Models\Message as ThisModel;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use EasyWeChat\Factory;
use Overtrue\EasySms\EasySms;
use Illuminate\Support\Facades\Log;

class MessageController extends BaseController
{
    /**
     * 消息列表
     * @param Request $request
     * @return \Illuminate\Contracts\View\Factory|\Illuminate\View\View
     */
    public function index(Request $request){
        $where =[];
        $search_arr = ['user_name','phone','wx_name','title','option_category','send_type'];
        $params = $request->all();
        foreach ($params as $key=>$val){
            if(in_array($key,$search_arr) && !empty($val)){
                $where[$key] = $val;
            }
        }

        $lists = ThisModel::where($where)
            ->with(['user','apply','admin'])
            ->orderBy('id','desc')
            ->paginate(config('params')['pageSize']);

        return view('backend.message.index', [
            'lists' => $lists,
            'list_title_name' => '消息列表',
            'request_params' => $request,
        ]);
    }

    /**
     * 发送信息
     * @param $send_type
     * @param $user_id
     * @param $title
     * @param $content
     * @param $option_category
     */
    public function sendMessage($send_type,$user_id,$title,$content,$option_category){

    }

    /**
     * 发送短信消息
     * @param $template_id
     * @param $phone
     * @param $data
     * @return bool
     * @throws \Overtrue\EasySms\Exceptions\InvalidArgumentException
     * @throws \Overtrue\EasySms\Exceptions\NoGatewayAvailableException
     */
    public function  sendPhone($template_id,$phone,$data){
        $config = config('params')['sms'];
        $easySms = new EasySms($config);
        $res = $easySms->send($phone, [
            'template' => $template_id,
            'data' => $data,
        ]);
        if ($res['yuntongxun']['status'] == 'success'){
            return true;
        }else{
            Log::error(json_encode($res));
            return false;
        }
    }

    /**
     * 发送微信消息
     * @param $template_id
     * @param $open_id
     * @param $data
     * @return bool
     */
    public function sendWeiXin($template_id,$open_id,$data){
        $app = app('wechat.official_account');
        $result = $app->template_message->send([
            'touser' => $open_id,
            'template_id' => $template_id,
            'data' => $data
        ]);
        if ($result['errcode'] == 0) {
            return true;
        }else{
            Log::error(json_encode($result));
            return false;
        }
    }

}
