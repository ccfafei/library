<?php

namespace App\Http\Controllers\Backend;

use App\Models\BookCategory as ThisModel;
use Illuminate\Http\Request;

class BookCategoryController extends BaseController
{
    public function index(Request $request){
        $request_name = $request->get('name');
        $where = [];
        if($request_name){
            $where[] = ['name', 'like', '%' . $request_name . '%'];
        }
        $data = ThisModel::where($where)->orderBy('id','asc')->paginate(config('params')['pageSize']);

        return view('backend.book_category.index', [
            'lists' => $data,
            'list_title_name' => '分类列表',
            'request_params' => $request,
        ]);
    }

    public function save(Request $request){
        $id = $request->input('id');
        $request_name = $request->input('name');
        $request_remark = $request->input('remark');
        $request_all = $request->only(['id','name','remark']);
        if (ThisModel::isExist(['name' => $request_name], $id)){
            return $this->err('该分类已存在');
        }
        if($id){
            $res = ThisModel::find($id)->update($request_all);
        }else{
            $res = ThisModel::create($request_all);
        }
        if($res){
            return $this->ok();
        }else{
            return $this->err('操作失败');
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
