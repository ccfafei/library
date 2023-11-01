<?php

namespace App\Http\Controllers\WeChat;


use Illuminate\Http\Request;

class PromptController extends BaseController
{
    protected $official_config;
    protected $payment_config;

    public function __construct()
    {
        parent::__construct();
        $this->official_config = config('wechat.official_account.default');
        $this->payment_config = config('wechat.payment.default');
    }

   public function index(Request $request){
       $data =   [
           'msg'=> session('msg'),
           'confirm_url' =>session('confirm_url'),
           'cancel_url' =>session('cancel_url'),
       ];

       return view('weChat.prompt.index',['data' => $data]);

   }

}
