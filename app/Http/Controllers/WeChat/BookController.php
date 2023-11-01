<?php

namespace App\Http\Controllers\WeChat;

use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use Illuminate\Http\Request;
use App\Models\Apply,
    App\Models\BookReading,
    App\Models\Borrow,
    App\Models\User,
    App\Models\Book,
    App\Models\Kids,
    App\Models\BookCategory,
    App\Models\Banner;



class BookController extends BaseController
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
     * 图书列表
     * @param Request $request
     * @return \Illuminate\Contracts\View\Factory|\Illuminate\View\View
     */
    public function getList(Request $request)
    {
        $user = $request->getUser;
        $isworker = false;
        if ($user) {
            $book_read = new BookReading();
            $res = User::find($user['id']);
            $book_data = Book::leftJoin('book_reading', 'books.ISBN', 'book_reading.ISBN')
                ->select('books.*', 'book_reading.reading', 'book_reading.is_hot', 'books.remark')
                ->where('books.status', 0)
                ->where('book_reading.is_hot', 1)
                ->orderBy('book_reading.reading', 'desc')
                ->limit(9)
                ->get();

            $banner_list = Banner::where('id','>',0)->orderBy('sort','asc')->limit(5)->get();

            return view('weChat.home.index', [
                'users' => $res,
                'lists' => $book_data,
                'banner_list' => $banner_list,
                'title_name' => '童悦借书',
                'request_params' => $request,
            ]);

        } else {
            abort(404);
        }

    }


    /**
     * 图书详情
     * @param Request $request
     * @return \Illuminate\Contracts\View\Factory|\Illuminate\View\View
     */
    public function getDetail(Request $request)
    {
        $user = $request->getUser;
        $isworker = false;
        $data = [];
        if ($user) {
            $users = User::find($user['id']);
            $ISBN = $request->input('ISBN');
            if (!empty($ISBN)) {
                $data = Book::leftJoin('book_reading', 'books.ISBN','=', 'book_reading.ISBN')
                    ->select('books.*', 'book_reading.reading', 'book_reading.is_hot', 'books.remark')
                    ->where('books.ISBN', $ISBN)
                    ->has('category')
                    ->orderBy('books.id', 'desc')
                    ->first();
            }else{
                abort(500);
            }

            //查询用户信息
            $kid_info = [];
            $info = Kids::where('p_id', $user['id'])->first();
            if (!empty($info)) {
                $kid_info = getKidInfos($info['id']);
            }
            //dd($data);die;
            return view('weChat.book.details', [

                'list' => $data,
                'users' => $users,
                'kid_info' => $kid_info,
                'title_name' => '图书详情',
                'request_params' => $request,
            ]);

        } else {
            abort(404);
        }
    }


    /**
     * 更多图书
     * @param Request $request
     * @return \Illuminate\Contracts\View\Factory|\Illuminate\View\View
     */
    public function more(Request $request)
    {
        $user = $request->getUser;
        $isworker = false;
        $data = [];
        if ($user) {
            $where = [];
            if ($request->has('category_id')) {
                $where = ['category_id' => $request->input('category_id')];
            }
            $book_read = new BookReading();
            $res = User::find($user['id']);
            $book_data = Book::where($where)->has('category')->where('status', 0)->orderBy('id', 'desc')->groupBy('ISBN')
                ->get();
            if (collect($book_data)->isNotEmpty()) {
                foreach ($book_data as $item) {
                    $item->reading = $book_read->where('ISBN', $item->ISBN)->value('reading');
                }
            }
            $category = BookCategory::all();
            return view('weChat.book.more', [
                'category' => $category,
                'lists' => $book_data,
                'title_name' => '图书库',
                'request_params' => $request,
            ]);

        } else {
            abort(404);
        }
    }

    /**
     * 申请借书
     * @param Request $request
     * @return array
     */
    public function borrowCheck(Request $request)
    {

        DB::beginTransaction();
        try {
            $borrow_info = [];
            $user = $request->getUser;
            $ISBN = $request->input('ISBN');
            if (empty($user)) {
                DB::rollBack();
                return $this->err('无法获取用户信息，请稍后再试!');
            }
            if (empty($ISBN)) {
                DB::rollBack();
                return $this->err('书籍信息有误，请稍后再试!');
            }

            //处理申请时间
            $apply_ts = $request->has('appley_ts')?$request->input('appley_ts'):null;
            if(empty($apply_ts)) {
                DB::rollBack();
                return $this->err('请选择预约时间!');
            }

            if(!strtotime($apply_ts)){
                DB::rollBack();
                return $this->err('预约时间格式不正确，请核对!');
            }

            // 申请的时间不能在当日以前
            if($apply_ts<date('Y-m-d')){
                DB::rollBack();
                return $this->err('预约时间不能在当日时间以前!');
            }

            //周六、周日无法配送
            $week = date('w', strtotime($apply_ts));
            if ($week == 0 || $week == 6) {
                DB::rollBack();
                return $this->err("周末无法配送，请预约其它时间!");
            }

            //当日20点以后延迟一个工作日配送
            $hour = date('H');
            $afer_day = date('Y-m-d',strtotime('+1 day'));
            if($hour>=20){
                if($apply_ts<$afer_day){
                    DB::rollBack();
                    return $this->err('20点以后借书，延后一个工作日配送,请预约其它时间!');
                }
            }


            //核实身份
            $user_info = User::where('status', 1)->find($user['id']);
            if (empty($user_info)) {
                DB::rollBack();
                return $this->err('账号被停用或无此账号!');
            }

           //$vip_arr = Config('params.vip_type');

            if (!isset($user_info->vip_type) || empty($user_info->vip)) {
                DB::rollBack();
                return $this->err('对不起，您不是会员，无法借书!', $err_code = '101');
            }

            if (strtotime($user_info->vip_exp < date('Y-m-d'))) {
                DB::rollBack();
                return $this->err('对不起，您的会员卡已过期', $err_code = '101');
            }


            //查询库存
            $book_info = Book::where(['ISBN' => $ISBN, 'status' => 0])->first();
            if (empty($book_info)) {
                DB::rollBack();
                return $this->err('很抱歉，该书籍库存不足，请联系客服!');
            }

            //查询用户在借数量
            $borrow_ids = Borrow::where(['user_id' => $user['id'], 'status' => 0])->pluck('book_id');
            $num = count($borrow_ids);

            //只要有待配送的不能借书
            $applys_info = Apply::where(['user_id' => $user['id']])->where('status', 0)->first();
            if (!empty($applys_info)) {
                DB::rollBack();
                $msg = '你有1本图书待配送，暂不能借书!';
                return $this->err($msg);
            }

            //如果借阅数是1,申请还书也就是待回收（状态=3）才可借阅
            if ($num == 1) {
                $applys_info = Apply::where(['user_id' => $user['id']])
                    ->whereIn('book_id', $borrow_ids)
                    ->where('status', 3)
                    ->first();
                if (empty($applys_info)) {
                    DB::rollBack();
                    $msg = '您已借了一本图书，请先申请还书再借书';
                    return $this->err($msg);
                }
            }
            //如果是借阅数是2,只有待还完一本书后还可借阅(还书成功其实手上只有一本书)
            if ($num >= 2) {
                DB::rollBack();
                $msg = '很抱歉，每人最多借两本书,还书成功后可借阅';
                return $this->err($msg);
            }

            $kid_info = Kids::where('p_id', $user['id'])->first();
            $kid_id = isset($kid_info) ? $kid_info['id'] : null;

            $data = [
                'book_id' => $book_info['id'],
                'book_name' => $book_info['name'],
                'book_sn' => $book_info['book_sn'],
                'ISBN' => $book_info['ISBN'],
                'user_id' => $user['id'],
                'kid_id' => $kid_id,
                'type' => 1,
                'apply_ts' => $apply_ts,
                'status' => 0
            ];
            $result = Apply::where($data)->first();
            if (empty($result)) {
                Apply::create($data);
            } else {
                Apply::where('id', $result['id'])->update($data);
            }
            //更新图书在馆状态
            $rs = Book::where("id", $book_info['id'])->update(['status' => 1]);
            if ($rs == false) {
                DB::rollBack();
                return $this->err('更新图书信息失败');
            }
            DB::commit();
            return $this->ok();
        } catch (\Exception $ex) {
            Log::error($ex->getMessage());
            DB::rollBack();
            return $this->err('系统异常，请稍后再试');
        }
    }

    /**
     * 申请还书
     * @param Request $request
     * @return array|string
     */
    public function backCheck(Request $request)
    {

        DB::beginTransaction();
        try {
            $borrow_info = [];
            $user = $request->getUser;
            $book_sn = $request->input('book_sn');
            if (empty($user)) {
                DB::rollBack();
                return $this->err('无法获取用户信息，请稍后再试');
            }
            if (empty($book_sn)) {
                DB::rollBack();
                return $this->err('书籍信息有误，请稍后再试');
            }
            $book_info = Book::where('book_sn', $book_sn)->first();
            $apply_ts = date('Y-m-d H:i:s');
            if ($request->has('appley_ts')) {
                $apply_ts = $request->input('appley_ts');
            }


            $kid_info = Kids::where('p_id', $user['id'])->first();
            $kid_id = isset($kid_info) ? $kid_info['id'] : null;

            $week = date('w', strtotime($apply_ts));
            if ($week == 0) {
                $msg = '申请成功！当前是周日，工作人员会在下周进行回收!';
                $apply_ts = date("Y-m-d H:i:s",strtotime("+1 day"));
            }elseif($week == 6){
                $msg = '申请成功！当前是周六，工作人员会在下周进行回收!';
                $apply_ts = date("Y-m-d H:i:s",strtotime("+2 day"));
            } else{
                $msg = '恭喜您，申请成功!';
            }
            $data = [
                'updated_at'=> date('Y-m-d H:i:s'),
                'apply_ts' => $apply_ts,
                'status' => 3
            ];
            $result = Apply::where('book_sn', $book_info['book_sn'])->update($data);
            if ($result === false) {
                DB::rollBack();
                return $this->err('申请还书失败');
            }
            DB::commit();

            return $this->ok(null,$msg);
        } catch (\Exception $ex) {
            Log::error($ex->getMessage());
            //echo $ex->getMessage();
            DB::rollBack();
            return $this->err('系统异常，请稍后再试');
        }
    }

    /**
     * 取消借阅
     * @param Request $request
     * @return array|string
     */
    public function checkCancel(Request $request)
    {
        DB::beginTransaction();
        try {
            $borrow_info = [];
            $user = $request->getUser;
            $apply_id = $request->input('id');
            if (empty($user)) {
                DB::rollBack();
                return $this->err('无法获取用户信息，请稍后再试');
            }
            if (empty($apply_id)) {
                DB::rollBack();
                return $this->err('参数ID不能为空');
            }
            $apply = Apply::find($apply_id);
            if (collect($apply)->isEmpty()) {
                DB::rollBack();
                return $this->err('未查到申请记录');
            }
            $now_cancel_ts = time();
            $last_allow_ts = strtotime($apply->created_at) ;
            if ( $now_cancel_ts - $last_allow_ts > 30 * 60) {
                DB::rollBack();
                return $this->err('申请时间已经超过半小时，不能取消');
            }
            $apply->cancel_ts = date("Y-m-d H:i:s");
            $apply->status = 1;
            $apply->save();
            $book = Book::find($apply->book_id);
            if (collect($apply)->isEmpty()) {
                DB::rollBack();
                return $this->err('未查到图书信息');
            }
            $book->status = 0;
            $book->save();
            DB::commit();
            return $this->ok();
        } catch (\Exception $ex) {

            DB::rollBack();
            return $this->err('系统异常，请稍后再试');
        }
    }


}
