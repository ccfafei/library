<?php

namespace App\Http\Controllers\WeChat;

use App\Models\BookReading;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Cache;
use EasyWeChat\Factory;
use App\Models\User;
use App\Models\Book;

class SearchController extends BaseController
{
    protected $official_config;
    protected $payment_config;

    public function __construct()
    {
        parent::__construct();
        $this->official_config = config('wechat.official_account.default');
        $this->payment_config = config('wechat.payment.default');
    }

    /**
     * 搜索
     * @param Request $request
     * @return \Illuminate\Contracts\View\Factory|\Illuminate\View\View
     */
    public function Search(Request $request)
    {
        $user = $request->getUser;
        $isworker = false;
        if ($user) {
            $res = User::find($user['id']);
            $model = Book::leftJoin('book_reading','books.ISBN','book_reading.ISBN')
                ->select('books.*','book_reading.reading','book_reading.is_hot','books.remark')
                ->where('books.status',0);
            if($request->has('book_name')){
                $book_name = $request->input("book_name");
                $result =  $model->where('name','like','%'.$book_name.'%')
                    ->orderBy('id','desc')
                    ->groupBy('ISBN')
                    ->get();
            }else{
                $result =  $model->where('book_reading.is_hot',1)
                    ->orderBy('book_reading.reading','desc')
                    ->groupBy('ISBN')
                    ->limit(3)
                    ->get();
            }

            $total  = collect($result)->count();
            return view('weChat.search.index', [
                'users'=>$res,
                'lists' => $result,
                'total'=>$total,
                'title_name' => '图书搜索',
                'request_params' => $request,
            ]);

        } else {
            abort(404);
        }
    }


}
