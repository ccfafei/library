<?php

namespace App\Http\Controllers\Backend;

use App\Models\School as ThisModel;
use Illuminate\Http\Request;

class SchoolController extends BaseController
{
    /**
     * 园所列表
     * @param Request $request
     * @return \Illuminate\Contracts\View\Factory|\Illuminate\View\View
     */
    public function index(Request $request){
        $request_name = $request->get('name');
        $where = [];
        if($request_name){
            $where[] = ['name', 'like', '%' . $request_name . '%'];
        }
        $data = ThisModel::where($where)->orderBy('id','desc')->paginate(config('params')['pageSize']);

        return view('backend.school.index', [
            'lists' => $data,
            'list_title_name' => '园所列表',
            'request_params' => $request,
        ]);
    }

    /**
     * 添加或编辑园所
     * @param Request $request
     * @return array
     */
    public function save(Request $request){
        $id = $request->input('id');
        $request_name = $request->input('name');
        $request_all = $request->only(['id','name','address','leader','tel','status']);
        if (ThisModel::isExist(['name' => $request_name], $id)){
            return $this->err('该幼儿园已经存在');
        }
        if($id){
            $res = ThisModel::find($id)->update($request_all);
        }else{
            $res = ThisModel::create($request_all);
        }
        if($res){
            return $this->ok();
        }else{
            return $this->err('失败');
        }
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
