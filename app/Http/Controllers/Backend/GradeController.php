<?php

namespace App\Http\Controllers\Backend;

use App\Models\School;
use App\Models\Grade as ThisModel;
use Illuminate\Http\Request;

class GradeController extends BaseController
{
    /**
     * 班级列表
     * @param Request $request
     * @return \Illuminate\Contracts\View\Factory|\Illuminate\View\View
     */
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

        $data = ThisModel::where($where)->with('school')->orderBy('id','desc')->paginate(config('params')['pageSize']);

        return view('backend.grade.index', [
            'lists' => $data,
            'school_id'=>$school_id,
            'list_title_name' => '班级列表',
            'request_params' => $request,
        ]);
    }

    /**
     * 添加或编辑班级
     * @param Request $request
     * @return array
     */
    public function save(Request $request){
        $id = $request->input('id');
        $request_name = $request->input('name');
        $request_all = $request->only(['id','school_id','name','leader','tel']);

        if (ThisModel::where('school_id',$id)->where('name',$request_name)->first()){
            return $this->err('该班级已经存在');
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
