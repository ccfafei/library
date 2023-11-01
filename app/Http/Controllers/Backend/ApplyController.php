<?php

namespace App\Http\Controllers\Backend;

use App\Models\Apply;
use App\Models\User;
use App\Models\Kids;
use App\Models\Book;
use App\Models\Admin;
use App\Models\Borrow;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\App;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Maatwebsite\Excel\Facades\Excel;
use App\Exports\ApplyExport;

class ApplyController extends BaseController
{

    //借阅历史展示
    public function index(Request $request)
    {
            //查询用户信息
            return view('backend.apply.index', [
                'title_name' => '配送、回收',
                'request_params' => $request,
            ]);

    }


    /**
     * 根据状态查询借阅情况
     * @param Request $request
     * @return array
     *
     */
    public function getListByStatus(Request $request){
        try{
            $apply = $this->getSearch($request);
            $result = $apply->orderBy('created_at','desc')
                ->paginate(config('params')['pageSize']);
            $status_arr = config('params.borrow_status');
            if(collect($result -> items())->isNotEmpty()){
                foreach($result -> items()  as $k => $v){
                    if($v['status'] ==0){ $v['cancel_ts']="";} //待配送没有取消时间
                    if($v['status'] ==1){ $v['apply_ts']="";} //取消没有配送时间
                    if($v['status'] >1){ $v['cancel_ts']="";} //1以后没有取消时间
                    $v['cancel_ts'] = empty( $v['cancel_ts'])?"": $v['cancel_ts'];
                    $v['status_text'] = $status_arr[$v['status']];
                    $v['users'] = User::where('id',$v['user_id'])->first();
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

        }catch (\Exception $ex){
            return $this->err('系统异常，请稍后再试!'.$ex->getMessage());
        }

    }


    /**
     * 批量处理审核
     * @param Request $request
     * @return array
     */
    public function checkOption(Request $request){
        try{
            if(!$request->has('ids')){
                return $this->err('ids参数不能为空');
            }
            if(!$request->has('status')){
                return $this->err('status参数不能为空');
            }
            $ids = $request->input('ids');
            $status = $request->input('status');
            switch($status){
                case 0:
                    return $this->recoverDeliver($ids,$status);
                    break;
                case 1:
                    return $this-> cancelDeliver($ids,$status);
                    break;
                case 2:
                    return $this->completeDeliver($ids,$status);
                    break;
                case 4:
                    return $this->unRecovered($ids,$status);
                    break;
                case 5:
                    return $this->completeAll($ids,$status);
                    break;
            }
        }catch (\Exception $ex){
            //echo $ex->getMessage();
            return $this->err('系统异常，请稍后再试');
        }

    }

    /**
     * 导出excel
     * @param Request $request
     */
    public function exportExcel(Request $request)
    {
        try {
            ini_set('memory_limit','500M');
            set_time_limit(0);//设置超时限制为0分钟
            $apply = $this->getSearch($request);
            $rowData[] = ['图书名称', '图书编号', 'ISBN', '借阅人', '手机号', '申请时间', '借阅时间', '取消时间', '归还时间', '状态'];
            $status_arr = config('params.borrow_status');
            $datas =  $apply->orderBy('created_at','desc')->get();
            foreach ($datas as $k => $v) {
                $kids = getKidInfos($v['kid_id']);
                $users = User::where('id', $v['user_id'])->first();
                $borrow = Borrow::where('book_id', $v['book_id'])->first();
                $all_school = '';
                if (!empty($kids)) {
                    $all_school = $kids['school_name'] . '-' . $kids['grade_name'] . '-' . $kids['name'];
                }
                $borrow_time = empty($borrow) ? '' : $borrow['borrow_ts'];
                $back_time = empty($borrow) ? '' : $borrow['back_ts'];
                $rowData[] = [
                    $v['book_name'],
                    $v['book_sn'],
                    $v['ISBN'],
                    $all_school,
                    $users['phone'],
                    $v['apply_ts'],
                    $borrow_time,
                    $v['cancel_ts'],
                    $back_time,
                    $status_arr[$v['status']]
                ];
            }

            // chrome chrome 浏览器下载有问题，换成前端下载
            //$result =  Excel::download(new ApplyExport, 'apply.xlsx');
            return $this->ok($rowData);
        }catch (Exception $e) {
            return $this->err('系统异常，请稍后再试');
        }
    }


    protected function getSearch(Request $request){
        $apply = new Apply();
        $status  = $request->input('status');
        if($status !=-1){
            $apply =  $apply->where('status',$status);
        }
        $start_ts = $request->input('start_ts');
        if(!empty($start_ts)){
            $apply =  $apply->where('apply_ts','>=',$start_ts);
        }
        $end_ts = $request->input('end_ts');
        if(!empty($end_ts)){
            $apply =  $apply->where('apply_ts','<=',$end_ts);
        }
        $book_name = $request->input('book_name');
        if(!empty($book_name)){
            $apply =  $apply->where('book_name','like','%'.$book_name.'%');
        }
        $book_sn = $request->input('book_sn');
        if(!empty($book_sn)){
            $apply =  $apply->where('book_sn','like','%'.$book_sn.'%');
        }
        $ISBN = $request->input('ISBN');
        if(!empty($ISBN)){
            $apply =  $apply->where('ISBN','like','%'.$ISBN.'%');
        }
        $phone = $request->input('phone');
        if(!empty($phone)){
            $user_ids = User::where('phone','like','%'.$phone.'%')->pluck('id');
            $apply =  $apply->whereIn('user_id',$user_ids);
        }
        return $apply;
    }

    /**
     * 恢复待配送
     * @param $ids
     * @param $status
     * @return array
     */
    protected  function recoverDeliver($ids,$status){
        DB::beginTransaction();
        try{
            // 1.查出所有记录
            $result = Apply::whereIn('id',$ids)->get();
            if(collect($result)->isEmpty()){
                DB::rollBack();
                return $this->err('未查到相关记录');
            }
            $all_num = count($result);
            // 2.刷选出满足要求的记录:是已经被取消配送的记录
            $data =[];
            foreach ($result as $key=>$item){
                if($item['status'] ==1){
                    $data[$key] = $item;
                }
            }
            if(collect($data)->isEmpty()){
                DB::rollBack();
                return $this->err('没有需要恢复的记录');
            }
            $ok_num = count($data);
             $data = array_values($data);

            // 3.更新申请状态
            $ok_ids  = collect($data)->pluck('id')->all();
            $new_time = date('Y-m-d H:i:s');
            $rs = Apply::whereIn('id',$ok_ids)->update(
                [
                    'status' => $status,
                    'updated_at' => $new_time,
                    'apply_ts' => $new_time,
                    'cancel_ts' => '000-00-00 00:00:00'
                ]
            );
            if($rs === false){
                DB::rollBack();
                return $this->err('更新申请状态失败，请稍后再试');
            }

            // 4.更新图书状态为不可借
            $book_ids = collect($data)->pluck('book_id')->all();
            $responses = Book::whereIn('id',[$book_ids])->update(['status' => 1]);
            if($responses === false){
                DB::rollBack();
                return $this->err('更新图书借阅状态失败，请稍后再试');
            }
            DB::commit();
            return  $this->ok('找到记录共  '.$all_num.' 条，修改成功 '.$ok_num.' 条.');

        }catch (\Exception $ex){
            DB::rollBack();
            return $this->err('系统异常，请稍后再试');
        }

    }


    /**
     * 取消配送
     * @param $ids
     * @param $status
     * @return array
     */
    protected  function cancelDeliver($ids,$status){
        DB::beginTransaction();
        try{
            // 1.查出所有记录
            $result = Apply::whereIn('id',$ids)->get();
            if(collect($result)->isEmpty()){
                DB::rollBack();
                return $this->err('未查到相关记录');
            }
            $all_num = count($result);
            // 2.刷选出满足要求的记录:是已经被取消配送的记录
            $data =[];
            foreach ($result as $key=>$item){
                if($item['status'] ==0){
                    $data[$key] = $item;
                }
            }
            if(collect($data)->isEmpty()){
                DB::rollBack();
                return $this->err('没有需要取消的记录');
            }
            $ok_num = count($data);
            $data = array_values($data);

            // 3.更新申请状态
            $ok_ids  = collect($data)->pluck('id')->all();
            $new_time = date('Y-m-d H:i:s');
            $rs = Apply::whereIn('id',$ok_ids)->update(
                [
                    'status' => $status,
                    'updated_at' => $new_time,
                    'cancel_ts' => $new_time
                ]
            );
            if($rs === false){
                DB::rollBack();
                return $this->err('取消配送失败，请稍后再试');
            }

            // 4.更新图书状态为可借
            $book_ids = collect($data)->pluck('book_id')->all();
            $responses = Book::whereIn('id',$book_ids)->update(['status' => 0]);
            if($responses === false){
                DB::rollBack();
                return $this->err('更新图书借阅状态失败，请稍后再试');
            }
            DB::commit();
            return  $this->ok('找到记录共  '.$all_num.' 条，修改成功 '.$ok_num.' 条.');

        }catch (\Exception $ex){
            DB::rollBack();
            return $this->err('系统异常，请稍后再试');
        }

    }


    /**
     * 完成配送
     * @param $ids
     * @param $status
     * @return array
     */
    protected  function completeDeliver($ids,$status){
        DB::beginTransaction();
        try{

            // 1.查出所有记录
            $result = Apply::whereIn('id',$ids)->get();
            if(collect($result)->isEmpty()){
                DB::rollBack();
                return $this->err('未查到相关记录');
            }
            $all_num = collect($result)->count();
            // 2.刷选出满足要求的记录:是已经被取消配送的记录
            $data =[];
            foreach ($result as $key=>$item){
                if($item['status'] ==0){
                    $data[$key] = $item;
                }
            }
            if(collect($data)->isEmpty()){
                DB::rollBack();
                return $this->err('没有需要取消的记录');
            }
            $ok_num = collect($data)->count();
            $data = array_values($data);

            // 3.更新配送状态
            $ok_ids  = collect($data)->pluck('id')->all();
            $new_time = date('Y-m-d H:i:s');
            $rs = Apply::whereIn('id',$ok_ids)->update(
                [
                    'status' => $status,
                    'updated_at' => $new_time,
                ]
            );
            if($rs === false){
                DB::rollBack();
                return $this->err('确认完成配送失败，请稍后再试');
            }


            // 4.给用户添加借书成功记录
            $borrow_data =[];
            foreach ($data as $key=>$val){
                $borrow_data[]  = [
                    'book_id'       => $val['book_id'],
                    'book_sn'       => $val['book_sn'],
                    'ISBN'          => $val['ISBN'],
                    'user_id'       => $val['user_id'],
                    'kid_id'        => $val['kid_id'],
                    'borrow_ts'     => $new_time,
                    'created_at'    => $new_time,
                    'status'        => 0,
                ];
            }

            $borrow_rs = Borrow::insert($borrow_data);
            if($borrow_rs === false){
                DB::rollBack();
                return $this->err('添加用户借书记录失败');
            }

            DB::commit();
            return  $this->ok('找到记录共  '.$all_num.' 条，修改成功 '.$ok_num.' 条.');

        }catch (\Exception $ex){
            echo $ex->getMessage();
            DB::rollBack();
            return $this->err('系统异常，请稍后再试');
        }

    }



    /**
     * 未完成回收
     * @param $ids
     * @param $status
     * @return array
     */
    protected  function unRecovered($ids,$status){
        DB::beginTransaction();
        try{

            // 1.查出所有记录
            $result = Apply::whereIn('id',$ids)->get();
            if(collect($result)->isEmpty()){
                DB::rollBack();
                return $this->err('未查到相关记录');
            }
            $all_num = collect($result)->count();
            // 2.刷选出满足要求的记录:待回收
            $data =[];
            foreach ($result as $key=>$item){
                if($item['status'] ==3){
                    $data[$key] = $item;
                }
            }
            if(collect($data)->isEmpty()){
                DB::rollBack();
                return $this->err('没有需要取消的记录');
            }
            $ok_num = collect($data)->count();
            $data = array_values($data);

            // 3.更新回收状态
            $ok_ids  = collect($data)->pluck('id')->all();
            $new_time = date('Y-m-d H:i:s');
            $rs = Apply::whereIn('id',$ok_ids)->update(
                [
                    'status' => $status,
                    'updated_at' => $new_time,
                ]
            );
            if($rs === false){
                DB::rollBack();
                return $this->err('未回收确认失败，请稍后再试');
            }


            DB::commit();
            return  $this->ok('找到记录共  '.$all_num.' 条，修改成功 '.$ok_num.' 条.');

        }catch (\Exception $ex){
            echo $ex->getMessage();
            DB::rollBack();
            return $this->err('系统异常，请稍后再试');
        }

    }

    /**
     * 回收完成，所有就绪
     * @param $ids
     * @param $status
     * @return array
     */
    protected  function completeAll($ids,$status){
        DB::beginTransaction();
        try{

            // 1.查出所有记录
            $result = Apply::whereIn('id',$ids)->get();
            if(collect($result)->isEmpty()){
                DB::rollBack();
                return $this->err('未查到相关记录');
            }
            $all_num = collect($result)->count();
            // 2.刷选出满足要求的记录:待回收或未完成
            $data =[];
            foreach ($result as $key=>$item){
                if($item['status'] ==3 ||$item['status'] ==4){
                    $data[$key] = $item;
                }
            }
            if(collect($data)->isEmpty()){
                DB::rollBack();
                return $this->err('没有需要回收的记录');
            }
            $ok_num = collect($data)->count();
            $data = array_values($data);

            // 3.更新回收状态
            $ok_ids  = collect($data)->pluck('id')->all();
            $new_time = date('Y-m-d H:i:s');
            $rs = Apply::whereIn('id',$ok_ids)->update(
                [
                    'status' => $status,
                    'updated_at' => $new_time,
                ]
            );
            if($rs === false){
                DB::rollBack();
                return $this->err('回收确认失败，请稍后再试');
            }

            // 4.更新还书
            $book_ids = collect($data)->pluck('book_id')->all();
            $borrow_rs = Borrow::whereIn('book_id',$book_ids)->update(
                [
                    'status' => 1, //0在馆，1借出
                    'back_ts' => $new_time,
                ]
            );
            if($borrow_rs === false){
                DB::rollBack();
                return $this->err('更新用户还书失败');
            }

            DB::commit();
            return  $this->ok('找到记录共  '.$all_num.' 条，修改成功 '.$ok_num.' 条.');

        }catch (\Exception $ex){
            echo $ex->getMessage();
            DB::rollBack();
            return $this->err('系统异常，请稍后再试');
        }

    }

    //保存
    public function save(Request $request){
        $id = $request->input('id');
        $request_all = $request->all();
        $phone = $request_all['phone'];
        $search_phone = User::where('phone',$phone)->first();

        if(collect($search_phone)->isEmpty()){
            return $this->err('该电话不存在，请在用户管理中绑定!');
        }
        $search_phone = $search_phone->toArray();
        $user_id = $search_phone['id'];
        $admin_id = Auth::guard('admin')->id();
        unset($request_all['phone']);
        $request_all['user_id'] = $user_id;
        $request_all['admin_id'] = $admin_id;
        // dd($request_all);
        if($id){
            $res = Apply::find($id)->update($request_all);
        }else{
            $res = Apply::create($request_all);
        }
            if($res){
                return $this->ok();
            }else{
            return $this->err('失败');
        }
    }

}
