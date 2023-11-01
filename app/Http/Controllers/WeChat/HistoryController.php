<?php

namespace App\Http\Controllers\WeChat;

use App\Models\Borrow;
use App\Models\Kids;
use App\Models\User;
use App\Models\Apply;
use App\Models\Book;
use arbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Cache;
use EasyWeChat\Factory;

class HistoryController extends BaseController
{
    protected $official_config;
    protected $payment_config;

    public function __construct()
    {
        parent::__construct();
        $this->official_config = config('wechat.official_account.default');
        $this->payment_config = config('wechat.payment.default');
    }


    //借阅历史展示
    public function index(Request $request)
    {
        $user = $request->getUser;
        if ($user) {
            //查询用户信息
            $kid_info = [];
            $info = Kids::where('p_id',$user['id'])->first();
            $kid_info = getKidInfos($info['id']);
            return view('weChat.history.index', [
                'kids'=>$kid_info,
                'title_name' => '借阅历史',
                'request_params' => $request,
            ]);
        } else {
            abort(404);
        }
    }


    /**
     * 根据状态查询借阅情况
     * @param Request $request
     * @return array
     *
     */
    public function getListByStatus(Request $request){
        try{
            $user = $request->getUser;
            if ($user) {
                $status = $request->input('status');
                if(!isset($status)){
                    return $this->err('查询传参有误，请核查!');
                }
                $apply = new Apply();
                if($status == 2){
                    $apply = $apply->whereIn('status',[2,3]);
                }else{
                    $apply =  $apply->where('status',$status);
                }
                $result = $apply->where('user_id',$user['id'])->orderBy('created_at','desc')
                    ->paginate(config('params')['pageSize']);

                $status_arr = config('params.borrow_status');
                if(collect($result -> items())->isNotEmpty()){
                    foreach($result -> items()  as $k => $v){
                        $v['format_created_at'] = date("Y-m-d H:i:s",strtotime($v['created_at']));
                        $v['apply_ts'] = empty( $v['apply_ts'])?"": date("Y-m-d",strtotime($v['apply_ts']));
                        $v['format_updated_at'] = empty( $v['updated_at'])?"": date("Y-m-d",strtotime($v['updated_at']));
                        $v['status_text'] = $status_arr[$v['status']];
                        $v['kids'] = getKidInfos($v['kid_id']);
                        $v['book'] = Book::where('id',$v['book_id'])->first();
                        $v['borrow'] = Borrow::where('book_id',$v['book_id'])->first();
                        $arr[] = $v ;
                        $info = collect(['data' => $arr]) ;
                    }

                    $data = $info->merge($result);
                }else{
                    $data =[];
                }
                return $this->ok($data);

            } else {
                return $this->err('系统异常，请重新进入系统!');
            }
        }catch (\Exception $ex){
            return $this->err('系统异常，请稍后再试!'.$ex->getMessage());
        }

    }



}
