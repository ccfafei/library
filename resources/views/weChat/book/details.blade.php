@extends('weChat.layout.app')
@section('title', $title_name)
@section('css')
    <link rel="stylesheet" href="{{asset('mui/css/mui.picker.min.css')}}"/>
@endsection

@section('body')
    <div class="mui-content" style="text-align: center;">
        <img src="{{asset('storage/'.$list->upload_url)}}" style="width:70%">
        <ul class="mui-table-view mui-table-view-striped mui-table-view-condensed">
            <li class="mui-table-view-cell">
                <div class="mui-table">
                    <div class="mui-table-cell mui-col-xs-12">
                        <h4 style="margin-bottom: 1rem;color: #B8B6B1;">{{$list->author}}</h4>
                        <b class="mui-ellipsis" style="margin-bottom: 1rem;">{{$list->name}}</b>
                        <h5 style="margin-bottom: 1rem;"><b style="color: #FC852B;">{{$list->reading??0}}</b> 位小朋友已借阅</h5>
                        <h5 style="text-align: left;text-indent: 2rem;line-height: 30px;color: #999793;font-size: 15px;">{{$list->remark??'暂无内容'}}</h5>
                    </div>

                </div>
            </li>
        </ul>
        <div class="mui-card" style="text-align: left;">
            <div class="mui-card-content" style="padding: 0.625rem;">
                <h5>ISBN：{{$list->ISBN}}</h5>
                <h5>唯一码：{{$list->book_sn}}</h5>
                <h5>出版社：{{$list->publisher}}</h5>
                <h5 style="display: none">价格：¥{{$list->price}}</h5>
                <h5 style="display: none">材质：{{$list->texture}}</h5>
            </div>
        </div>

        <div class="mui-content-padded">
            <div id="openPopover" class="mui-btn mui-btn-block" style="border-radius: 30px;background-color:#FFD233;height: 48px;line-height: 15px; "><b>马 上 借 阅</b></div>
            {{--			<a href="#sheet1" id="openPopover" class="mui-btn mui-btn-primary mui-btn-block">我 要 借 书</a>--}}
        </div>
        <div id="sheet1" class="mui-popover mui-popover-bottom mui-popover-action ">
            <!-- 可选择菜单 -->
            <ul class="mui-table-view">
                @if(empty($kid_info) || empty($users))
                    <input id="info_null" type="hidden" value="0">
                @else
                    <input id="info_null" type="hidden" value="1">
                    <li class="mui-table-view-cell">
                        借阅儿童：{{$kid_info->name}}
                    </li>
                    <li class="mui-table-view-cell">
                        学校：{{$kid_info->school_name}}
                    </li>
                    <li class="mui-table-view-cell">
                        班级：{{$kid_info->grade_name}}
                    </li>
                    <li class="mui-table-view-cell">
                        <div id="pickDate" class="btime" value="">*请选择借阅日期*</div>
                    </li>
                @endif
            </ul>

            <!-- 取消菜单 -->
            <ul class="mui-table-view">
                <li class="mui-table-view-cell">
                    <button onclick="borrowBook('{{$list->ISBN}}');" style="width: 85%;"><b>确 认 提 交</b></button>
                </li>
            </ul>
        </div>
        {{--@include('weChat.layout.tabbar',['on' => 'index'])--}}
    </div>
@endsection
@section('js')
    <script type="text/javascript">
        mui.init();
        mui('.mui-bar-tab').on('tap', 'a', function () {
            var str = (this.href.indexOf("?") != -1) ? "&" : "?";
            document.location.href = this.href+str+"timestamp=" + new Date().getTime();

        });
        mui('body').on('tap', '#openPopover', function () {
            var info_null = document.getElementById("info_null").value;
            if (info_null == 0) {
                mui.confirm('请完善个人及小孩信息', '错误', function (e) {
                    if (e.index == 1) {
                        window.location.href = "{{url('/wechat/user/info')}}"
                    }
                })
            } else {
                mui('#sheet1').popover('toggle', document.getElementById('openPopover'));
            }

        })


        //日期选择器
        var result = document.getElementById("pickDate");
        if (result) {
            var myDate = getBeginDate();
            var dtPicker = new mui.DtPicker({type: 'date',beginDate:myDate});
            result.addEventListener('tap', function () {
                dtPicker.show(function (item) {
                    var endDate = item.y.text + '-' + item.m.text + '-' + item.d.text;
                    result.innerText = endDate;
                    result.value = endDate;
                });

            }, false);
        }

        //获取可选日期,先看是几点，再看是不是周末
        function getBeginDate() {
            var d = new Date();
            //当前时间>20点，加一天
            if(d.getHours() >= 20){
                d.setDate(d.getDate()+2);
            }else{
                d.setDate(d.getDate()+1);
            }
            //当前是周日，加一天,变成周一
            if(d.getDay() == 0){
                d.setDate(d.getDate()+1);
            }
            //当前是周六，加两天变成周一
            if(d.getDay() == 6){
                d.setDate(d.getDate()+2);
            }
            //new Date()月份0-11,所以输出的时候加1,但重新new的时候不需要
            console.log(d.getFullYear()+"年"+(d.getMonth()+1)+"月"+d.getDate()+"日");
            var mydate = new Date(d.getFullYear(),d.getMonth(),d.getDate());
            return mydate;
        }

        //借书
        function borrowBook(ISBN) {
            mui.post("{{url('wechat/book/borrowCheck')}}", {
                    ISBN: ISBN,
                    appley_ts: result.value,
                    _token: '{{csrf_token()}}',
                }, function (data) {
                    if (data.code == 200) {
                        mui.alert('恭喜你，借书成功!');
                        setTimeout(function () {
                            window.location.reload();
                            window.location.href = "{{url('wechat/history/index')}}";
                        }, 1000);
                     
                    } else {
                        mui.alert(data.msg);

                        //不是会员或者会员过期跳转到会员页
                        if (data.err_code == '101' || data.err_code == '102') {
                            setTimeout("goto()", 2000);
                        }
                    }
                }, 'json'
            );
            mui('#sheet1').popover('hide');
        }

        function goto() {
            window.location.href = "{{url('/wechat/user/vipcard')}}"
        }

    </script>


@endsection