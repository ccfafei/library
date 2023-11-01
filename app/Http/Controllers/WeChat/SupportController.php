<?php

namespace App\Http\Controllers\WeChat;

use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Cache;
use EasyWeChat\Factory;
use App\Models\User;
class SupportController extends BaseController
{
    protected $official_config;
    protected $payment_config;

    public function __construct()
    {
        parent::__construct();
        $this->official_config = config('wechat.official_account.default');
        $this->payment_config = config('wechat.payment.default');
    }


    /**
     * 新手上路
     * @param Request $request
     * @return \Illuminate\Contracts\View\Factory|\Illuminate\View\View
     */
    public function novice(Request $request)
    {
        $user = $request->getUser;
        $isworker = false;
        if ($user) {
            $res = User::find($user['id']);
            return view('weChat.support.novice', [
                'list' => $res,
                'title_name' => '新手上路',
            ]);

        } else {
            abort(404);
        }
    }

    /**
     * 常见问题
     * @param Request $request
     * @return \Illuminate\Contracts\View\Factory|\Illuminate\View\View
     */
    public function problem(Request $request)
    {
        $user = $request->getUser;
        $isworker = false;
        if ($user) {
            $res = User::find($user['id']);
            return view('weChat.support.problem', [
                'list' => $res,
                'title_name' => '常见问题',
            ]);

        } else {
            abort(404);
        }
    }


}
