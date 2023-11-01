<?php

namespace App\Http\Controllers\Backend;

use App\Models\Recharge;
use App\Models\School;
use App\Models\User;
use App\Models\Admin;
use App\Models\ShareRewards;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;

class RechargesController extends BaseController
{

    // 充值信息
    public function index(Request $request)
    {

        $data =[];
        $request_name = $request->get('b_name');
        $request_phone = $request->get('b_tel');
        $users = new User();
        if(!empty($request_name)){
            $users = $users->where('name', 'like', '%' . $request_name . '%');
        }

        if(!empty($request_phone)){
            $users = $users->where('phone', 'like', '%' . $request_phone . '%');
        }

        $users= $users->get();
        if(collect($users)->isNotEmpty()){
            $ids = collect($users)->pluck('id')->all();
        }

        if(!empty($ids)){
            $data = Recharge::whereIn('user_id',$ids)->with('user', 'admin')
                ->orderBy('id', 'desc')
                ->paginate(config('params')['pageSize']);
            $stores = School::get();
        }





        return view('backend.recharges.index', [
            'lists' => $data,
            'list_title_name' => '充值信息',
            'request_params' => $request,
        ]);

    }


    //保存
    public function save(Request $request)
    {
        $id = null;
        try {
            //dd($id);
            DB::beginTransaction();
            $request_all = $request->all();
            $admin_id  = Auth::guard('admin')->id();
            if (empty($admin_id)) {
                DB::rollback();
                return self::err('未查到管理员账号信息');
            }

            if (empty($request_all['amount'])) {
                DB::rollback();
                return self::err('充值金额不能为空');
            }

            $data = [
                'user_id' => $request_all['user_id'],
                'last_balance' => $request_all['last_balance']??0,
                'amount' => $request_all['amount'],
                'admin_id' => $admin_id,
                'recharge_ts' => date('Y-m-d H:i:s'),
            ];



            //在此可以插入充值数据了
            $recharges_rs = $this->rechargesAdd($data);
            if($recharges_rs == false){
                DB::rollback();
                return self::err('充值失败');
            }
            DB::commit();
            return self::ok();

        }catch (\Exception $ex){
            echo $ex->getMessage();die;
            DB::rollback();
            return self::err('系统异常，请稍后再试');
        }

    }


    //充值操作
    protected function  rechargesAdd($data){

        try {

            if (Recharge::create($data)) {
                $user = User::find($data['user_id']);
                //先加再重新赋值
                $blance = bcadd ($user->balance,$data['amount']);
                $user->balance = $blance;
                if(!$user->update()){
                    return false;
                }
                return true;
            }
        } catch (\Exception $ex) {
            return false;
        }
    }




}
