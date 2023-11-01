@extends('weChat.layout.app')
@section('title', $title_name)
<style>
    * {
        touch-action: none;
    }

    .mui-content {
        background-color: #FFFFFF !important;
    }

    .mui-slider-indicator a {
        color: #999793 !important;
        font-size: 15px !important;
    }
</style>
@section('body')
    <div class="mui-content">
        <div id="slider" class="mui-slider">
            <div class="mui-slider-indicator mui-segmented-control mui-segmented-control-inverted">
                <a class="mui-control-item mui-active" href="#item1">待配送</a>
                <a class="mui-control-item" href="#item2">待回收</a>
                <a class="mui-control-item" href="#item3">已完成</a>
                <a class="mui-control-item" href="#item4">已取消</a>
            </div>
            <div id="sliderProgressBar" class="mui-slider-progress-bar mui-col-xs-3"></div>
            <div class="mui-slider-group">
                <div id="item1" class="mui-slider-item mui-control-content mui-active">
                    <ul class="mui-table-view">

                    </ul>
                </div>

                <div id="item2" class="mui-slider-item mui-control-content">
                    <ul class="mui-table-view">

                    </ul>
                </div>
                <div id="item3" class="mui-slider-item mui-control-content">
                    <ul class="mui-table-view">

                    </ul>
                </div>
                <div id="item4" class="mui-slider-item mui-control-content">
                    <ul class="mui-table-view">

                    </ul>
                </div>
            </div>
        </div>
        <input id="itemStatus" value="{{$request_params['status']}}" type="hidden">
        <input id="itemNum" value="1" type="hidden">

        <!-- 换成js方式 -->
        <div id="sheet1" class="mui-popover mui-popover-bottom mui-popover-action ">
            <!-- 可选择菜单 -->
            <div class="mui-card">

            </div>
        {{--            <ul class="mui-table-view">--}}
        {{--                <li class="mui-table-view-cell">--}}
        {{--                    借阅儿童："{{$kids['name']}}"--}}
        {{--                </li>--}}
        {{--                <li class="mui-table-view-cell">--}}
        {{--                    书名：《<span id="current_book_name"></span>》--}}
        {{--                </li>--}}
        {{--                <li class="mui-table-view-cell">--}}
        {{--                    已借：<span id="current_borrow_day"></span>天--}}
        {{--                </li>--}}
        {{--                <li class="mui-table-view-cell">--}}
        {{--                    学校："{{$kids['school_name']}}"--}}
        {{--                </li>--}}
        {{--                <li class="mui-table-view-cell">--}}
        {{--                    班级："{{$kids['grade_name']}}"--}}
        {{--                </li>--}}
        {{--                <li class="mui-table-view-cell">--}}
        {{--                    归还日期--}}
        {{--                </li>--}}
        {{--            </ul>--}}
        <!-- 取消菜单 -->
            <ul class="mui-table-view">
                <li class="mui-table-view-cell">
                    <a href="#sheet1"><b>取消</b></a>
                </li>
            </ul>

        </div>

        <div class="footer">
            <div style="margin-bottom: 5rem;text-align: center;height: 3.125rem;width: 100%;color: #555555;font-size: 0.75rem;">
                <a href="{{url('/wechat/book/more')}}" class="mui-btn" style="color: #999793;font-size: 12px;">探索更多</a>
            </div>
            <nav class="mui-bar mui-bar-tab" style="height: 60px;">
                <a class="mui-tab-item" href="{{url('/wechat/index')}}">
                    <span class="mui-icon icon-home"
                          style="background: url('/images/icon_index_no.png') no-repeat;background-size: 100% 100%;width: 30px;height: 30px;"></span>
                    <span class="mui-tab-label">首页</span>
                </a>
                <a class="mui-tab-item" href="{{url('/wechat/search')}}">
                    <span class="mui-icon icon-search"
                          style="background: url('/images/icon_search_no.png') no-repeat;background-size: 100% 100%;width: 30px;height: 30px;"></span>
                    <span class="mui-tab-label">寻书</span>
                </a>
                <a class="mui-tab-item mui-active" href="{{url('/wechat/history/index')}}">
                    <span class="mui-icon icon-refresh"
                          style="background: url('/images/icon_history.png') no-repeat;background-size: 100% 100%;width: 30px;height: 30px;"></span>
                    <span class="mui-tab-label">历史</span>
                </a>
                <a class="mui-tab-item" href="{{url('/wechat/user')}}">
                    <span class="mui-icon icon-contact"
                          style="background: url('/images/icon_ucenter_no.png') no-repeat;background-size: 100% 100%;width: 30px;height: 30px;"></span>
                    <span class="mui-tab-label">我的</span>
                </a>
            </nav>
        </div>

    </div>

@endsection
@section('js')
    <script src="{{asset('/mui/js/jquery-3.3.1.min.js')}}"></script>
    <script src="{{asset('/mui/js/mui.min.js')}}"></script>
    <script type="text/javascript">
        mui.init();
        mui('.mui-bar-tab').on('tap', 'a', function () {
            var str = (this.href.indexOf("?") != -1) ? "&" : "?";
            document.location.href = this.href+str+"timestamp=" + new Date().getTime();

        });
        (function ($) {
            $.ready(function () {
                getList(1);
            });
        })(mui);


        //有分页 var page = page,无分页page=0;
        var lastPage;  //总共页数
        var currPage = 1; //当前页码
        var counter = 1; //计数器

        //最外选项卡的自适应
        var aaa = document.getElementById("item1").offsetHeight + 80;
        document.getElementById("slider").style.height = aaa + "px";

        var istatus = document.getElementById("itemStatus").value;
        if (istatus == 2) {
            console.log('status' + istatus)
            mui('.mui-slider').slider().gotoItem(1);
        }

        document.getElementById('slider').addEventListener('slide', function (e) {
            //自适应方法就是获得当前的slide数据如：e.detail.slideNumber ，表示当前，再修改id=item里的高度即可
            var side_num = e.detail.slideNumber + 1;
            document.getElementById("itemNum").value = side_num;
            console.log(side_num);
            var hhh = document.getElementById("item" + side_num).offsetHeight + 80;
            document.getElementById("slider").style.height = hhh + "px";
            getList(side_num);
        });

        //获取数据
        //0=>'待配送',1=>'取消借阅',2=>'完成配送',3=>'待回收',4=>'未完成',5=>'已完成'
        function getList(index) {
            var result = "";
            if (index == "") {
                index = 1;
            }
            var status = getStatus(index);
            var div = "#item" + index;
            var baseUrl = "{{url('wechat/history/list')}}";
            var storageurl = "{{asset('storage/')}}";
            $.getJSON(baseUrl, {status: status, page: counter}, function (res) {

                lastPage = res.last_page;
                currPage = res.current_page;
                if (res.data.length == 0) {
                    var datas = [];
                } else {
                    var datas = res.data.data;
                }

                if (datas.length > 0) {
                    for (var i = 0; i < datas.length; i++) {
                        var kids = datas[i]['kids'];
                        var book_img = storageurl + "/" + datas[i]['book']['upload_url'];
                        var borrow_ts = "";
                        var back_ts = "";
                        var b = $.isEmptyObject(datas[i]['borrow'])
                        console.log(datas[i]['borrow'])
                        if (!b) {
                            borrow_ts = datas[i]['borrow']['borrow_ts'];
                            borrow_ts = borrow_ts == null ? '' : borrow_ts;
                            back_ts = datas[i]['borrow']['back_ts'];
                            back_ts = back_ts == null ? '' : back_ts;
                        }
                        result += '<div class="mui-card">'

                            + '<div class="mui-card-header" style="margin-bottom: 0.825rem;">'
                            + '<span style="font-size: 15px;">' + kids['school_name'] + '-' + kids['grade_name'] + '-' + kids['name'] + '</span>'
                            + '<span class="mui-pull-right"><b style="color: #FF9900;font-size: 15px;">' + datas[i]['status_text'] + '</b></span>'
                            + '</div>'
                            + '<div class="mui-card-content">'
                            + '<a href="javascript:;">'
                            + '<img class="mui-pull-right" src="' + book_img + '" style="width: 4.25rem;margin-right: 1.25rem;">'

                            + '<div class="mui-media-body" style="padding-left: 20px;">'
                            + ' <b>《' + datas[i]['book_name'] + '》</b>';

                        result += '<p class="mui-ellipsis">借阅编号：' + datas[i]['book_sn'] + '</p>';

                        if (status == 0) {
                            result += '<p class="mui-ellipsis">申请时间：' + datas[i]['format_created_at'] + '</p>';
                            result += '<p class="mui-ellipsis">配送时间：' + datas[i]['apply_ts'] + ' (18点前)</p>';
                        }

                        if (status == 1) {
                            result += '<p class="mui-ellipsis">取消时间：' + datas[i]['cancel_ts'] + '</p>';
                        }

                        if (status == 2) {
                            result += '<p class="mui-ellipsis">申请时间：' + datas[i]['format_updated_at'] + '</p>';
                            result += '<p class="mui-ellipsis">回收时间：' + datas[i]['apply_ts'] + ' (18点前)</p>';
                        }

                        if (status == 5) {
                            result += '<p class="mui-ellipsis">借阅时间：' + borrow_ts + '</p>';
                            result += '<p class="mui-ellipsis">归还时间：' + back_ts + '</p>';
                        }

                        result += '</div></div>'
                            + '<div class="mui-card-footer">';

                        if (datas[i]['status'] == 2) {
                            var book_sn = datas[i]['book_sn'];
                            result += '<button type="button" class="mui-btn" style="color:#B8B6B1;font-size: 12px; " onclick="backBook(' + book_sn + ')">申请还书</button>';
                        }

                        if (status == 0) {
                            var apply_id = datas[i]['id'];
                            result += '<button type="button" class="mui-btn" style="color:#B8B6B1;font-size:12px; " onclick="cancleBorrow(' + apply_id + ')">取消借阅</button>';
                        }

                        result += '</div>'
                            + '</a>'
                            + '</div>';
                    }
                } else {
                    result = "<div style='text-align: center;margin-top: 100px;'><img src='/images/search_miss.png' style='width: 60%;'>" +
                        "<span style=\"font-size: 15px;margin-top: 15px;display: block;color: #D6D3CE\">还未借阅书籍喔</span>" +
                        "</div>";

                    //mui('#offCanvasContentScroll').pullRefresh().endPullupToRefresh(true);
                    //没有数据
                }
                $(div).find(".mui-table-view").html(result);

            });

        }

        //上拉刷新
        mui.init({
            pullRefresh: {
                container: '#offCanvasContentScroll', //待刷新区域标识，querySelector能定位的css选择器均可，比如：id、.class等
                up: {
                    height: 130, //可选.默认50.触发上拉加载拖动距离
                    auto: true, //可选,默认false.自动上拉加载一次
                    contentrefresh: "正在加载...", //可选，正在加载状态时，上拉加载控件上显示的标题内容
                    contentnomore: '没有更多数据了', //可选，请求完毕若没有更多数据时显示的提醒内容；
                    callback: function () {
                        //必选，刷新函数，根据具体业务来编写，比如通过ajax从服务器获取新数据；
                        setTimeout(function () {
                            var index = document.getElementById("itemNum").value;
                            getList(index);
                            mui('#offCanvasContentScroll').pullRefresh().endPullupToRefresh((++counter > lastPage));
                        }, 1000)
                    }
                }
            }
        });

        //弹出菜单
        mui('body').on('tap', '#openPopover', function () {
            mui('#sheet1').popover('toggle', document.getElementById('openPopover'));

        })

        //取消借书
        function cancleBorrow(apply_id) {
            mui.post("{{url('wechat/book/checkCancel')}}", {
                    id: apply_id,
                    _token: '{{csrf_token()}}',
                }, function (data) {
                    if (data.code == 200) {
                        mui.toast('取消成功');
                        location.reload();
                    } else {
                        mui.alert(data.msg);
                    }
                }, 'json'
            );
        }

        //申请还书
        function backBook(book_sn) {
            mui.post("{{url('wechat/book/backCheck')}}", {
                    book_sn: book_sn,
                    _token: '{{csrf_token()}}',
                }, function (data) {
                    if (data.code == 200) {
                        mui.alert(data.msg);
                        getList(2)
                    } else {
                        mui.alert(data.msg);
                    }
                }, 'json'
            );
        }


        //通过选项卡index获取相关状态
        function getStatus(index) {
            var status = 0;
            switch (index) {
                case 1:
                    status = 0;
                    break;
                case 2:
                    status = 2;
                    break;
                case 3:
                    status = 5;
                    break;
                case 4:
                    status = 1;
                    break;
                default:
                    status = 0;

            }

            return status;
        }


    </script>
@endsection