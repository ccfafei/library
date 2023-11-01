<?php

namespace App\Http\Controllers\Backend;

use Illuminate\Http\Request;
use App\Models\VipCard as ThisModel;
use App\Models\VipCardLog;
use App\Models\User;

class VipCardController extends BaseController
{
    public function index(Request $request){
        $request_name = $request->get('name');
        $school_id = $request->get('school_id');

        $where = [];
        if($request_name){
            $where[] = ['name', 'like', '%' . $request_name . '%'];
        }

        if($school_id){
            $where[] = ['school_id','=',$school_id];
        }

        $data = ThisModel::where($where)->has('school')->orderBy('id','desc')->paginate(config('params')['pageSize']);

        return view('backend.vipcard.index', [
            'lists' => $data,
            'school_id'=>$school_id,
            'list_title_name' => '会员卡',
            'request_params' => $request,
        ]);
    }

    public function save(Request $request){
        $id = $request->input('id');
        $request_name = $request->input('name');
        $request_all = $request->except('s');
        $school_id = $request->input('school_id');

        if($id){
            $res = ThisModel::find($id)->update($request_all);
        }else{
            if (ThisModel::where('school_id',$school_id)->where('name',$request_name)->first()){
                return $this->err('该会员卡已经存在');
            }
            $res = ThisModel::create($request_all);
        }
        if($res){
            return $this->ok();
        }else{
            return $this->err('失败');
        }
    }

    /**
     * 购买记录
     * @param Request $request
     * @return \Illuminate\Contracts\View\Factory|\Illuminate\View\View
     */
    public function buy(Request $request){
        $request_stime = $request->get('stime');
        $request_etime = $request->get('etime');
        $request_name = $request->get('name');
        $request_phone = $request->get('phone');
        $vipcard =  VipCardLog::where('status',1);
        if(!empty($request_stime)){
            $request_stime = date('Y-m-d',strtotime($request_stime));
            $vipcard = $vipcard->where('created_at','>=',$request_stime);
        }

        if(!empty($request_etime)){
            $request_etime = date('Y-m-d',strtotime($request_etime));
            $vipcard = $vipcard->where('created_at','<=',$request_etime.' 23:59:59');
        }
        if($request_name){

            $user_id = User::where('name','like', '%' . $request_name . '%')->pluck('id')->all();
            $vipcard = $vipcard->whereIn('user_id',$user_id);
        }
        if($request_phone){
            $user_id = User::where('phone','like', '%' . $request_phone . '%')->pluck('id')->all();
            $vipcard = $vipcard->whereIn('user_id',$user_id);
        }
        $prices = $vipcard->sum('price');
        $counts =  $vipcard->count();
        $data =$vipcard->has('user')
            ->orderBy('id','desc')
            ->paginate(config('params')['pageSize']);

        return view('backend.vipcard.buy', [
            'lists' => $data,
            'prices' => $prices,
            'counts' => $counts,
            'list_title_name' => '购买',
            'request_params' => $request,
        ]);
    }

    public function destory(Request $request){
        try{
            $id = $request->input('id');
            if(empty($id)){
                return $this->err('id不能为空');
            }
            $res = ThisModel::where('id',$id)->delete();
            if(!$res){
                return $this->err('删除失败');
            }else{
                return $this->ok('删除成功');
            }
        }catch (\Exception $exception){
            return $this->ok('删除成功');
        }
    }

}
