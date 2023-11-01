<?php

namespace App\Http\Controllers\Backend;

use App\Models\Book as ThisModel;
use App\Models\BookCategory;

use App\Models\BookReading;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class BookController extends BaseController
{
    public function index(Request $request){
        $request_ISBN= $request->get('ISBN');
        $request_book_sn = $request->get('book_sn');
        $request_name = $request->get('name');
        $where = [];
        if($request_ISBN){
            $where[] = ['ISBN', '=', $request_ISBN ];
        }
        if($request_book_sn){
            $where[] = ['book_sn', '=', $request_book_sn ];
        }
        if($request_name){
            $where[] = ['name', 'like', '%' . $request_name . '%'];
        }
        $data = ThisModel::where($where)->has('category')->orderBy('id','desc')->paginate(config('params')['pageSize']);
       // dd($data);die;

        $book_category = BookCategory::get();

        return view('backend.book.index', [
            'lists' => $data,
            'book_category' => $book_category,
            'list_title_name' => '库存统计',
            'request_params' => $request,
        ]);
    }

    public function save(Request $request){
        try{
            $id = $request->input('id');;
            $upload_url = $request->input('upload_url');
            $book_sns = $request->input('book_sn');
            $ISBN = $request->input('ISBN');
            if (empty($upload_url)){
                return $this->err('请添加图片');
            }
            if (empty($book_sns)){
                return $this->err('图书条码编号不能为空');
            }
            if (empty($ISBN)){
                return $this->err('ISBN不能为空');
            }
            $bookSnArr = explode("\r\n",$book_sns);
            $request_all = $request->only(['id','category_id','name','author','ISBN','publisher',
                'price','texture','remark','upload_id','upload_url','status']);
            $arr=[];
            $bookSnArr = array_unique($bookSnArr);
            foreach ($bookSnArr as $item){
                $item = trim($item);
                if(!empty($item)){
                    unset($request_all['id']);
                    $request_all['book_sn'] = $item;
                    $request_all['created_at'] = date('Y-m-d H:i:s');
                    $arr[] = $request_all;
                }
            }
            if($id){
                if(count($arr)>1){
                    return $this->err('修改图书时图书编号只能填写一个');
                }
                $res = ThisModel::find($id);
                //当ISBN和book_sn及status相同时，如果修改名称、分类、图片才会批量修改
                $requst_book_sn = trim($bookSnArr[0]);
                if($res->ISBN == $request_all['ISBN']&&$res->book_sn==$requst_book_sn){
                    $update_data =[];
                    $res->name != $request_all['name'] && $update_data['name'] = $request_all['name'];
                    $res->category_id != $request_all['category_id'] && $update_data['category_id'] = $request_all['category_id'];
                    $res->author != $request_all['author'] && $update_data['author'] = $request_all['author'];
                    $res->publisher != $request_all['publisher'] && $update_data['publisher'] = $request_all['publisher'];
                    $res->price != $request_all['price'] && $update_data['price'] = $request_all['price'];
                    $res->texture != $request_all['texture'] && $update_data['texture'] = $request_all['texture'];
                    $res->remark != $request_all['remark'] && $update_data['remark'] = $request_all['remark'];
                    $res->upload_id != $request_all['upload_id'] && $update_data['upload_id'] = $request_all['upload_id'];
                    $res->upload_url != $request_all['upload_url'] && $update_data['upload_url'] = $request_all['upload_url'];
                    if(!empty($update_data)){
                        ThisModel::where('ISBN', $request_all['ISBN'])->update($update_data);
                    }
                    if($res->status!=$request_all['status']){
                        $res->status = $request_all['status'];
                        $res->save();
                    }
                }else{
                    $request_all['book_sn'] = $requst_book_sn;
                    $res = ThisModel::find($id)->update($request_all);
                }
            }else{
                $res = ThisModel::insert($arr);
            }
            if(!$res){
                return $this->err('失败');
            }
            return $this->ok();
        }catch (\Exception $exception){
            return $this->err('系统异常，请与管理员联系');
        }

    }

    /**
     * 统计图书库存
     * @param Request $request
     * @return \Illuminate\Contracts\View\Factory|\Illuminate\View\View
     */
    public function bookTotal(Request $request){
        $request_book_sn = $request->get('book_sn');
        $request_name = $request->get('name');
        $where = [];
        if($request_book_sn){
            $where[] = ['book_sn', '=', $request_book_sn ];
        }
        if($request_name){
            $where[] = ['name', 'like', '%' . $request_name . '%'];
        }
        $data = thisModel::select(
            'name',
            'book_sn',
            'ISBN',
            DB::raw('SUM(CASE WHEN STATUS =0 THEN 1 ELSE 0 END) AS store_num'),
            DB::raw('SUM(CASE WHEN STATUS =1 THEN 1 ELSE 0 END) AS borrow_num'),
            DB::raw('SUM(CASE WHEN STATUS =2 THEN 1 ELSE 0 END) AS invalid_num')
        )
            ->where($where)
            ->groupBy('ISBN')
            ->paginate(config('params')['pageSize']);

        foreach ($data->items() as &$item){
            $ISBN = $item->ISBN;
            $read =  BookReading::where('ISBN',$ISBN)->first();
            $item->reading = empty($read['reading'])?0:$read['reading'];
            $item->is_hot = empty($read['is_hot'])?0:$read['is_hot'];
            $item->is_new = empty($read['is_new'])?0:$read['is_new'];
        }

        return view('backend.book.stocks', [
            'lists' => $data,
            'list_title_name' => '书籍',
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

    public function saveReading(Request $request){
        try{
             $isbn = $request->input('ISBN');
             $reading = $request->input('reading');
             $is_hot= $request->input('is_hot');
             $is_new = $request->input('is_new');
             $result =  BookReading::where('ISBN',$isbn)->first();
             $data = [
                 'ISBN'=>$isbn,
                 'reading' =>$reading,
                 'is_hot'=>$is_hot,
                 'is_new'=>$is_new,
             ];

             if(collect($result)->isEmpty()){
                 $rs = BookReading::insert($data);
             }else{
                 $rs = BookReading::where('ISBN',$isbn)->update($data);
             }
             if($rs=== false){
                 return $this->err('修改失败');
             }
             return $this->ok();

        }catch (\Exception $exception){

            return $this->err('系统异常');
        }
    }
}
