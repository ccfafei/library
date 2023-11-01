@extends('backend.layout.app')
@section('content')
    <div class="row">
        <ul class="nav nav-tabs" id="myTab">
            <li class="active"><a href="#items0">全部</a></li>
            <li><a href="#items1">待配送</a></li>
            <li><a href="#items2">已取消</a></li>
            <li><a href="#items3">已配送</a></li>
            <li><a href="#items4">待回收</a></li>
            <li><a href="#items5">未完成</a></li>
            <li><a href="#items6">已完成</a></li>
        </ul>

        <div class="ibox-title no-borders">
            <form role="form" class="form-inline">

                <div class="form-group form-group-sm m-r">
                    <label>开始时间</label>
                    <input type="text" name="stime" id="search_stime" value="" class="form-control m-r">
                    <label>截止时间</label>
                    <input type="text" name="etime" id="search_etime" value="" class="form-control">
                </div>


                <div class="form-group form-group-sm m-r" >
                    <label>图书名称：</label>
                    <input type="text" name="book_name" value="" id="book_name" class="form-control input-lg" placeholder="请输入图书名称">
                </div>

                <div class="form-group form-group-sm m-r" >
                    <label>图书编号：</label>
                    <input type="text" name="book_sn" value="" id="book_sn"  class="form-control input-lg" placeholder="请输入图书编号">
                </div>

                <div class="form-group form-group-sm m-r" >
                    <label>ISBN：</label>
                    <input type="text" name="ISBN" value="" id="ISBN" class="form-control input-lg" placeholder="请输入ISBN">
                </div>

                <div class="form-group form-group-sm m-r" >
                    <label>手机号：</label>
                    <input type="text" name="phone" value="" id="phone" class="form-control input-lg" placeholder="请输入手机号">

                    <input id="status_index"  type="hidden" value="0"/>
                </div>


                <button class="btn btn-sm btn-primary m-r" type="button" id="search" >查询</button> &nbsp;
                <button class="btn btn-sm btn-danger m-r" type="button" id="outexcel">导出excel</button>

            </form>
        </div>
        <div class="ibox-title clearfix"></div>

        <div id="mytab-content" class="tab-content">

            <div class="tab-pane fade in active" id="items0">


            </div>

            <div class="tab-pane fade" id="items1">


            </div>

            <div class="tab-pane fade" id="items2">



            </div>

            <div class="tab-pane fade" id="items3">



            </div>

            <div class="tab-pane fade" id="items4">

            </div>

            <div class="tab-pane fade" id="items5">

            </div>

            <div class="tab-pane fade" id="items6">

            </div>


        </div>

        <div style="margin-left:10px;">
            <label class="m-r-xl">
                <input type="checkbox" class="" id="chElt" onclick="checkOrCancelAll();"/><span id="mySpan">全选</span><br/>
            </label>
            <label  class="m-r-xl">
                <button class="btn btn-warning btn-xs" onclick="applyCheck(1)">取消配送</button>
            </label>

            <label  class="m-r-xl">
                <button class="btn btn-info btn-xs" onclick="applyCheck(0)">恢复待配送</button>
            </label>

            <label  class="m-r-xl">
                <button class="btn btn-primary  btn-xs" onclick="applyCheck(2)">确认已配送</button>
            </label>
            <label  class="m-r-xl">
                <button class="btn btn-danger btn-xs" onclick="applyCheck(4)">确认未回收</button>
            </label>
            <label  class="m-r-xl">
                <button class="btn btn-success btn-xs" onclick="applyCheck(5)">确认回收</button>
            </label>

        </div>


    </div>
@endsection
@section('js_code')
    <script src="{{asset('backend/js/jq-paginator.min.js')}}"></script>
    <script src="{{asset('backend/js/xlsx.full.min.js')}}"></script>
    <script type="text/javascript">
        let index = 0;
        let currentPage = 1;
        let pageSize = 10;

        $(function(){
            $('#status_index').val(index);
            if(currentPage==1){
                getlist(currentPage);
            }

            $('#myTab').find("a").click(function(){
                $(this).tab('show');
                var href= $(this).attr("href");
                var index = href.replace('#items','');
                $('#status_index').val(index);
                getlist(currentPage);
            });

            $("#search").click(function () {
                getlist(currentPage);
            });

            //导出excel
            $("#outexcel").click(function () {
                var index = $('#status_index').val();
                var status = getStatus(index);
                var start_ts = $("#search_stime").val();
                var end_ts = $("#search_etime").val();
                var book_name = $("#book_name").val();
                var book_sn = $("#book_sn").val();
                var ISBN = $("#ISBN").val();
                var phone = $("#phone").val();
                var baseUrl = "{{url('backend/apply/exportExcel')}}";
                var params = {
                    status: status,
                    start_ts:start_ts,
                    end_ts:end_ts,
                    book_name:book_name,
                    book_sn:book_sn,
                    ISBN:ISBN,
                    phone:phone,
                };
                $.ajax({
                    type: "get",
                    data:params,
                    async: "false",
                    url: baseUrl,
                    dataType:"json",
                    success: function (res) {
                        if (res.code == 200) {
                            var datas = res.data;
                            var ws_name = "Sheet1";
                            var ws =  XLSX.utils.aoa_to_sheet(datas);
                            var wb = XLSX.utils.book_new();
                            XLSX.utils.book_append_sheet(wb, ws, ws_name);  //将数据添加到工作薄
                            return XLSX.writeFile(wb, '配送单数据.xlsx');
                        } else {
                            alert("导出失败！请稍后重试！");
                        }
                    }
                });
            });
            var start = {
                elem: "#search_stime",
                format: "YYYY-MM-DD",
                min: "2010-01-01",
                max: "2037-12-31",
                istime: true,
                istoday: false,
                choose: function (datas) {
                    end.min = datas;
                    end.start = datas
                }
            };
            var end = {
                elem: "#search_etime",
                format: "YYYY-MM-DD",
                min: "2010-01-01",
                max: "2037-12-31",
                istime: true,
                istoday: false,
                choose: function (datas) {
                    start.max = datas
                }
            };
            laydate(start);
            laydate(end);
        })

        function getlist(page) {
            var index = $('#status_index').val();
            var status = getStatus(index);
            var div = "#items"+(status+1);
            var send = index!=4 ?"配送时间":"回收时间";
            var dhtml = defaultHtml(send);
            var start_ts = $("#search_stime").val();
            var end_ts = $("#search_etime").val();
            var book_name = $("#book_name").val();
            var book_sn = $("#book_sn").val();
            var ISBN = $("#ISBN").val();
            var phone = $("#phone").val();
            $(div).html(dhtml);

            var baseUrl = "{{url('backend/apply/list')}}";
            var params = {
                status: status,
                start_ts:start_ts,
                end_ts:end_ts,
                book_name:book_name,
                book_sn:book_sn,
                ISBN:ISBN,
                phone:phone,
                page: page,
                pageSize: pageSize
            }
            $.ajax({
                type: "get",
                data:params,
                async: "false",
                url: baseUrl,
                dataType:"json",
                success: function (res) {
                    if (res.data.length == 0) {
                        var datas = [];
                    } else {
                        var datas = res.data.data;
                    }
                    var middle = getAjaxHtml(datas);
                    $(div).find('.contentHtml').html(middle);
                    var totalPage = res.data.last_page;
                    var total = res.data.total;
                    $('.pagination').jqPaginator({
                        totalPages: totalPage,
                        visiblePages: 10,
                        currentPage: page,
                        first: '<li class="first"><a href="javascript:void(0);">首页<\/a><\/li>',
                        prev: '<li class="prev"><a href="javascript:void(0);"><i class="arrow arrow2"><\/i>上一页<\/a><\/li>',
                        next: '<li class="next"><a href="javascript:void(0);">下一页<i class="arrow arrow3"><\/i><\/a><\/li>',
                        last: '<li class="last"><a href="javascript:void(0);">末页<\/a><\/li>',
                        page: '<li class="page"><a href="javascript:void(0);">@{{page}}<\/a><\/li>',
                        onPageChange: function (num, type) {
                           if(type =='change'){
                               getlist(num);
                           }
                        }
                    });

                }, error: function () {
                    alert("加载失败！请稍后重试！");
                }

            });


        }

        /**
         * 初始html
         * @returns {string}
         */
        function defaultHtml(send="配送时间") {

            var html =
                '<table class="table table-stripped toggle-arrow-tiny" data-sort="false">\n' +
                '                    <thead>\n' +
                '                        <tr>\n' +
                '                            <th></th>\n' +
                '                            <th>图书封面</th>\n' +
                '                            <th>图书名称</th>\n' +
                '                            <th>编号</th>\n' +
                '                            <th>ISBN</th>\n' +
                '                            <th>借阅人</th>\n' +
                '                            <th>手机号</th>\n' +
                '                            <th>申请时间</th>\n' +
                '                            <th>'+send+'</th>\n' +
                '                            <th>取消时间</th>\n' +
                '                            <th>归还时间</th>\n' +
                '                            <th>状态</th>\n' +
                '                        </tr>\n' +
                '                    </thead>\n' +
                '                    <tbody class="contentHtml">\n'+
                '                    </tbody> \n' +
                '</table>\n'+
                '<div  class="pagination"></div>\n';
            return html;
        }

        /**
         *
         * 处理ajax返回数据
         * 0=>'待配送',1=>'取消借阅',2=>'完成配送',3=>'待回收',4=>'未完成',5=>'已完成'
         * @param datas
         * @returns {*|string}
         */
        function getAjaxHtml(datas) {
            var middle = '';
            var storageurl = "{{asset('storage/')}}";
            if (datas.length > 0) {
                for (var i = 0; i < datas.length; i++) {
                    //console.log( datas[i])
                    var kids = datas[i]['kids'];
                    var book_img = '';
                    if(datas[i]['book'] != null){
                        book_img = storageurl + "/" + datas[i]['book']['upload_url'];
                    }
                    var borrow_ts = "";
                    var back_ts = "";
                    var cancel_ts = datas[i]['cancel_ts'];
                    cancel_ts = (cancel_ts == 'null') ? '' : cancel_ts;
                    if (datas[i]['borrow'] != null ) {
                        borrow_ts = datas[i]['borrow']['borrow_ts'];
                        borrow_ts = borrow_ts==null?'':borrow_ts;
                        back_ts = datas[i]['borrow']['back_ts'];
                        back_ts = back_ts==null?'':back_ts;
                    }

                    var click_time = datas[i]['status']==3?datas[i]['updated_at']:datas[i]['created_at'];
                    var all_school = '未知'
                    if(kids != null){
                        all_school =  kids['school_name'] + '-' + kids['grade_name'] + '-' + kids['name'];
                    }
                    middle += '    <tr>\n' +
                        '                            <td><input type="checkbox" class="applyCheck" value="' + datas[i]['id'] + '"></td>\n' +
                        '                            <td><img class="pull-left" src="' + book_img + '" style="width: 4.25rem;"></td>\n' +
                        '                            <td>' + datas[i]['book_name'] + '</td>\n' +
                        '                            <td>' + datas[i]['book_sn'] + '</td>\n' +
                        '                            <td>' + datas[i]['ISBN'] + '</td>\n' +
                        '                            <td>' + all_school+ '</td>\n' +
                        '                            <td>' + datas[i]['users']['phone']+ '</td>\n' +
                        '                            <td>' + click_time + '</td>\n' +
                        '                            <td>' + datas[i]['apply_ts'] + '</td>\n' +
                        '                            <td>' + cancel_ts + '</td>\n' +
                        '                            <td>' + back_ts + '</td>\n' +
                        '                            <td>' + datas[i]['status_text'] + '</td>\n' +
                        '                      </tr>\n';

                }
            }else{
                middle = "<tr><td colspan='12'>暂无记录</td></tr>"
            }
            return middle;
        }


        //根据tab的index获取申请状态
        function getStatus(index){
            var status = -1;
            return status= index-1;
        }

        //修改
        function editApply(id) {
            alert('此功能稍后开发');
        }


        //处理申请
        function applyCheck(status) {
            var ids = getAllIds();
            if(ids.length ==0){
                swal("请选择行", "", "error");
            }
            console.log(ids);
            var appleyUrl = "{{url('backend/apply/check')}}";
            $.ajax({
                type: "post",
                data:{"status":status,"ids":ids},
                async: "false",
                url: appleyUrl,
                dataType:"json",
                success: function (res) {
                    if(res.code==200){
                        swal(res.data)
                        location.reload()
                    }else{
                        swal(res.msg)
                    }

                }
            });

        }

        //页面加载的时候,所有的复选框都是未选中的状态
        function checkOrCancelAll(){
            var chElt=document.getElementById("chElt");
            var checkedElt=chElt.checked;
            //console.log(checkedElt)
            var allCheck=document.getElementsByClassName("applyCheck");
            if(checkedElt){
                for(var i=0;i<allCheck.length;i++){
                    allCheck[i].checked=true;
                }
            }else{
                for(var i=0;i<allCheck.length;i++){
                    allCheck[i].checked=false;
                }
            }
        }

        //获取所有id
        function getAllIds(){
            var check = document.getElementsByClassName('applyCheck');
            var ids = new Array();
            for(var i = 0; i < check.length; i++){
                if(check[i].checked){
                    ids.push(check[i].value);
                }
            }
            return ids;
        }

    </script>
@endsection

