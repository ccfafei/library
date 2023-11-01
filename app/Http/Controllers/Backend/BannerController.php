<?php

namespace App\Http\Controllers\Backend;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use App\Models\Banner as ThisModel;

class BannerController extends BaseController
{
    /**
     *  显示banner列表
     * @param Request $request
     * @return \Illuminate\Contracts\View\Factory|\Illuminate\View\View
     */
    public function index(Request $request){

        $where = [];
        $data = ThisModel::where($where)->orderBy('sort','asc')->paginate(config('params')['pageSize']);
        return view('backend.banner.index', [
            'lists' => $data,
            'list_title_name' => 'Banner',
            'request_params' => $request,
        ]);
    }

    /**
     *  添加banner
     * @param Request $request
     * @return array
     */
    public function save(Request $request){
        DB::beginTransaction();
        try{
            $id = $request->input('id');
            $upload_url = $request->input('upload_url');
            if (empty($upload_url)){
                DB::rollBack();
                return $this->err('请添加图片');
            }

            $request_all = $request->only(['upload_id','upload_url','title','sort','link_url']);
            $result = [];
            if($id){
                $res = ThisModel::find($id);
                if(!$res){
                    return $this->err('未查询到记录');
                }
                $request_all['updated_at'] = date('Y-m-d H:i:s');
                $result = ThisModel::where('id',$id)->update($request_all);
            }else{
                $result = ThisModel::insert ($request_all);
            }

            if(!$result){
                DB::rollBack();
                return $this->err('失败');
            }
            DB::commit();
            return $this->ok();
        }catch (\Exception $exception){
            return $this->err('系统异常，请与管理员联系');
        }

    }


    /**
     * 删除banner
     * @param Request $request
     * @return array
     */
    public function destory(Request $request){
        try{
            $id = $request->input('id');
            if(empty($id)){
                return $this->err('id不能为空');
            }
            $result = ThisModel::where('id',$id)->delete();
            if(!$result){
                return $this->err('删除失败');
            }else{
                return $this->ok('删除成功');
            }
        }catch (\Exception $exception){
            return $this->ok('删除成功');
        }
    }


}
