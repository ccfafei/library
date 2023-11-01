<?php

namespace App\Http\Controllers\Backend;

use App\Models\Apply;
use App\Models\Grade;
use App\Models\Kids;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use App\Models\Borrow;
use App\Models\School;
use App\Models\User;
use App\Models\Admin;
use App\Models\Book;


class BorrowController extends BaseController
{
    //查询所有幼儿园
    public function getSoresList(){
        $responses =[];
        $result = School::select('id','name')->where('status',1)->get();
        if(collect($result)->isNotEmpty()){
            $responses = $result->toArray();
        }
        return $responses;
    }

    // 借阅信息
    public function index(Request $request){
        $result =[];
        $where =[];
        $request_stime = $request->get('stime');
        $request_etime = $request->get('etime');
        $request_search = $request->get('search');
        $request_type = $request->get('type');
        $request_status = $request->get('status');
        $type = $request->get('type');
        $borrows = new Borrow();
        $users = new User();

        if(!empty($request_stime)){
            $request_stime = date('Y-m-d',strtotime($request_stime));
            $borrows = $borrows->where('created_at','>=',$request_stime);
        }

        if(!empty($request_etime)){
            $request_etime = date('Y-m-d',strtotime($request_etime));
            $borrows = $borrows->where('created_at','<=',$request_etime.' 23:59:59');
        }

        if(!empty($request_search)){
            switch ($request_type){
                case 'name':
                    $kid_ids = Kids::where('name','like','%'.$request_search.'%')->pluck('id');
                    $borrows = $borrows->whereIn('kid_id',$kid_ids);
                    break;
                case 'phone':
                    $user_ids = User::where('phone','like','%'.$request_search.'%')->pluck('id');
                    $borrows = $borrows->whereIn('user_id',$user_ids);
                    break;
                case 'school':
                    $school_ids = School::where('name','like','%'.$request_search.'%')->pluck('id');
                    $kid_ids = Kids::whereIn('school_id',$school_ids)->pluck('id');
                    $borrows = $borrows->whereIn('kid_id',$kid_ids);
                    break;
                case 'grade':
                    $grade_ids = Grade::where('name','like','%'.$request_search.'%')->pluck('id');
                    $kid_ids = Kids::whereIn('grade_id',$grade_ids)->pluck('id');
                    $borrows = $borrows->whereIn('kid_id',$kid_ids);
                    break;
                case 'book_name':
                    $ISBNS = Book::where('name','like','%'.$request_search.'%')->pluck('ISBN');
                    $borrows = $borrows->whereIn('ISBN',$ISBNS);
                    break;
            }
        }

        if(isset($request_status)){
            $borrows = $borrows->where('status',$request_status);
        }
        if($type ==1){
            $data =  $borrows->with(['book', 'user','kid','admin'])->orderBy('id','desc')->get();
            $lists =  $this->getExcelData($data);
            return $this->ok($lists);
        }else{
            $data = $borrows->with(['book', 'user','kid','admin'])->orderBy('id','desc')->paginate(config('params')['pageSize']);
            return view('backend.borrow.index', [
                'lists' => $data,
                'list_title_name' => '借阅信息',
                'request_params' => $request,
            ]);
        }

    }

    public function getExcelData($datas){
        $rowData[] = ['图书名称', '图书编号', 'ISBN', '小朋友', '学校', '班级', '家长', '电话', '借书时间', '还书时间','状态'];
        $datas = collect($datas)->toArray();
        foreach ($datas as $k => $v) {
            $book_name = isset($v['book'])?$v['book']['name']:'';
            $user_name = isset($v['user'])?$v['user']['name']:'';
            $phone = isset($v['user'])?$v['user']['phone']:'';
            $kid_name = isset($v['kid'])?$v['kid']['name']:'';
            $info = getKidInfos($v['kid_id']);
            $school_name =  isset($info)?$info['school_name']:'';
            $grade_name =  isset($info)?$info['grade_name']:'';
            $status = $v['status']==1?'已还':'已借';
            $rowData[] = [
                $book_name,
                $v['book_sn'],
                $v['ISBN'],
                $kid_name,
                $school_name,
                $grade_name,
                $user_name,
                $phone,
                $v['borrow_ts'],
                $v['back_ts'],
                $status,
            ];
        }
        return $rowData;
    }

    //保存
    public function save(Request $request){
        try{
            $id = $request->input('id');
            $request_all = $request->all();
            unset($request_all['s']);
            $phone = $request_all['phone'];
            $search_phone = User::where('phone',$phone)->first();
            $user_id = $search_phone['id'];
            if(collect($search_phone)->isEmpty()){
                return $this->err('该电话不存在!');
            }

            $kid_id = Kids::where('p_id',$user_id)->value('id');
            if(empty($kid_id)){
                return $this->err('未查到该用户的小孩信息!');
            }

            $search_book = Book::where('book_sn',$request_all['book_sn'])->first();
            if(collect($search_book)->isEmpty()){
                return $this->err('该图书编号存在!');
            }


            $admin_id = Auth::guard('admin')->id();
            unset($request_all['phone']);
            unset($request_all['user_name']);
            unset($request_all['book_name']);

            $request_all['user_id'] = $user_id;
            $request_all['kid_id'] = $kid_id;
            $request_all['book_id'] = $search_book['id'];
            $request_all['ISBN'] = $search_book['ISBN'];
            $request_all['admin_id'] = $admin_id;
            // dd($request_all);
            if(!empty($id)){
                $res = Borrow::find($id)->update($request_all);
                $status = $this->updateApplyStatus($search_book['id'], $request_all['status']);
                if($status===false){
                    return $this->err('操作失败');
                }
            }else{
                $res = Borrow::create($request_all);
            }
            if($res){
                return $this->ok();
            }else{
                return $this->err('操作失败');
            }
        }catch (\Exception $exception){
            return $this->err('系统异常，请稍后再试');
        }

    }

    public function destory(Request $request){
        try{
            $id = $request->input('id');
            if(empty($id)){
                return $this->err('id不能为空');
            }
            $res = Borrow::where('id',$id)->delete();
            if(!$res){
                return $this->err('删除失败');
            }else{
                return $this->ok('删除成功');
            }
        }catch (\Exception $exception){
            return $this->ok('删除成功');
        }

    }

    /**
     * 更新状态
     * @param $book_id
     * @param $status
     * @return bool
     */
    public function updateApplyStatus($book_id,$status){
        try{
            $book = Book::findOrFail($book_id);
            if($status==1){
                $book->status = 0;
                $data =[
                    'status' => 5,
                    'updated_at' => date('Y-m-d H:i:s'),
                    'admin_id' => Auth::guard('admin')->id()
                ];
            }else{
                $book->status = 1;
                $data =[
                    'status' => 2,
                    'updated_at' => date('Y-m-d H:i:s'),
                    'admin_id' => Auth::guard('admin')->id()
                ];
            }
            $result = Apply::where('book_id',$book_id)->first();
            if(collect($result)->isEmpty()){
                return false;
            }
             Apply::where('book_id',$book_id)->update($data);
             $book->save();
             return true;
        }catch (\Exception $exception){
            return false;
        }
    }

}
