@extends('backend.layout.app')

@section('content')

    <div class="row">
        <div class="col-sm-12">
            <div class="ibox widget-box float-e-margins">
                <div class="ibox-title no-borders">
                    <form role="form" class="form-inline">

                        <div class="form-group form-group-sm m-r">
                            <label>起始时间</label>
                            <input type="text" name="stime" id="search-stime" value="{{$request_params->stime}}" class="form-control m-r">
                            <label>截止时间</label>
                            <input type="text" name="etime" id="search-etime" value="{{$request_params->etime}}" class="form-control">
                        </div>



                        <div class="form-group form-group-sm m-r" >
                            <label>搜索内容：</label>
                            <input type="text" name="search" value="{{$request_params->phone}}" class="form-control input-lg" placeholder="请输入关键词">
                        </div>


                        <div class="form-group form-group-sm m-r">
                            <select name="type" class="form-control" id="search">
                                <option value="name">小朋友名字</option>
                                <option value="phone">手机号</option>
                                <option value="school">园所</option>
                                <option value="grade">班级</option>
                                <option value="book_name">书籍名称</option>
                            </select>

                        </div>


                        <div class="form-group form-group-sm m-r">
                            <label>选择状态：</label>
                            <select name="status" class="form-control" id="status">
                                <option value="">请选择</option>
                                <option value="0">已借</option>
                                <option value="1">已还</option>
                            </select>

                        </div>

                        <button class="btn btn-sm btn-primary m-r" type="submit" >查询</button>&nbsp;
                        <button class="btn btn-sm btn-danger m-r" type="button" id="outexcel">导出excel</button>

                    </form>
                </div>

                <div class="ibox-title clearfix">
                    <h5>{{$list_title_name}}
                        <small>列表</small>
                    </h5>
                    <div class="pull-right">
                        <button class="btn btn-info btn-xs" data-form="add-model" data-toggle="modal" data-target="#formModal">人工补录</button>
                    </div>
                </div>
                <div class="ibox-content">

                    <table class="table table-stripped toggle-arrow-tiny" data-sort="false">
                        <thead>
                            <tr>
                                <th>书籍名称</th>
                                <th>书籍编号</th>
                                <th>ISBN</th>
                                <th>小朋友</th>
                                <th>学校</th>
                                <th>班级</th>
                                <th>家长</th>
                                <th>电话</th>
                                <th>借书时间</th>
                                <th>还书时间</th>
                                <th>状态</th>
                                <th>操作人</th>
                                <th>更新时间</th>
                                <th>操作</th>
                            </tr>
                        </thead>

                        <tbody>
                            @if(collect($lists)->isEmpty())
                                <tr><td colspan="8">暂无记录</td></tr>
                            @endif
                            @foreach($lists as $list)
                                @if($list->user_id)
                                <tr>
                                    <td>{{$list->book->name}}</td>
                                    <td>{{$list->book_sn}}</td>
                                    <td>{{$list->ISBN}}</td>
                                    @if(collect($list->kid)->isNotEmpty())
                                    <td>{{$list->kid->name}}</td>
                                       <td>
                                           <?php
                                              $info = getKidInfos($list->kid_id);
                                              echo $info['school_name'];
                                           ?>
                                       </td>
                                        <td><?php echo $info['grade_name']?></td>
                                        @else
                                        <td></td>
                                        <td></td>
                                        <td></td>
                                    @endif
                                    <td>{{$list->user->name}}</td>
                                    <td>{{$list->user->phone}}</td>
                                    <td>{{$list->borrow_ts}}</td>
                                    <td>{{$list->back_ts}}</td>
                                    <td>{{$list->status==1?'已还':'已借'}}</td>
                                    @if(collect($list->admin)->isNotEmpty())
                                        <td>{{$list->admin->name}}</td>
                                    @else
                                        <td></td>
                                    @endif
                                    <td>{{$list->updated_at}}</td>
                                    <td>
                                        <button class="btn btn-success btn-xs" data-form="edit-model" data-toggle="modal" data-target="#formModal"
                                                data-id="{{$list->id}}"
                                                data-book_sn="{{$list->book_sn}}"
                                                data-book_name="@if($list->book){{$list->book->name}} @endif"
                                                data-user_name="@if($list->user){{$list->user->name}} @endif"
                                                data-phone="@if($list->user){{$list->user->phone}} @endif"
                                                data-status="{{$list->status}}"
                                                data-borrow_ts="{{$list->borrow_ts}}"
                                                data-back_ts="{{$list->back_ts}}"
                                        >修改</button>

                                        <button class="btn btn-danger btn-xs"  onclick="del('{{$list->id}}')">删除</button>

                                    </td>
                                </tr>
                                @endif
                            @endforeach
                        </tbody>
                    </table>

                    {{$lists->appends($request_params->all())->render()}}

                </div>

            </div>
        </div>
    </div>

    <div class="modal inmodal fade" id="formModal" tabindex="-1" role="dialog"  aria-hidden="true">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header">
                    <button type="button" class="close" data-dismiss="modal"><span aria-hidden="true">&times;</span><span class="sr-only">Close</span></button>
                    <h4 class="modal-title">{{$list_title_name}}编辑</h4>
                </div>
                <form method="post" id="form-validate-submit" class="form-horizontal m-t">
                    <div class="modal-body">
                        @include('backend.borrow.form')
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-white btn-sm" data-dismiss="modal">关闭</button>
                        <button type="submit" class="btn btn-primary btn-sm">保存</button>
                    </div>
                </form>
            </div>
        </div>
    </div>
@endsection

<!--    js    -->
@section('js_code')
<script src="{{asset('backend/js/xlsx.full.min.js')}}"></script>
<script>

    $(function () {
        //导出excel
        $("#outexcel").click(function () {
            var stime = $("#search-stime").val();
            var etime = $("#search-etime").val();
            var search = $("#search").val();
            var status = $("#stauts").val();
            var baseUrl = "{{url('backend/borrow')}}";
            var params = {
                type:1,
                stime:stime,
                etime:etime,
                search:search,
                status:status
            };
            $.ajax({
                type: "get",
                data:params,
                url: baseUrl,
                dataType:"json",
                success: function (res) {
                    if (res.code == 200) {
                        var datas = res.data;
                        var ws_name = "Sheet1";
                        var ws =  XLSX.utils.aoa_to_sheet(datas);
                        var wb = XLSX.utils.book_new();
                        XLSX.utils.book_append_sheet(wb, ws, ws_name);  //将数据添加到工作薄
                        return XLSX.writeFile(wb, '借阅信息.xlsx');
                    } else {
                        alert("导出失败！请稍后重试！");
                    }
                }
            });
        });

        var form_url = '{{route('backend.borrow.save')}}';
        var index_url = window.location.href;
        var rules = [];
        subActionAjaxValidateForMime('#form-validate-submit', rules, form_url, index_url);
        /**
         * 点击添加按钮触发的操作
         */
        $('[data-form="add-model"]').click(function () {
            var n = '';
            var borrow_ts ="{{date('Y-m-d H:i:s')}}";
            $('#form-id').val(n);
            $('#form-book_sn').val(n);
            $('#form-book_name').val(n);
            $('#form-phone').val(n);
            $('#form-user_name').val(n);
            $('#form-status').val(n);
            $('#form-borrow_ts').val(borrow_ts);
            $('#form-back_ts').val(n);

        });
        /**
         * 点击修改按钮触发的操作
         */
        $('[data-form="edit-model"]').click(function () {
            var id = $(this).attr('data-id');
            var book_sn = $(this).attr('data-book_sn');
            var book_name = $(this).attr('data-book_name');
            var phone = $(this).attr('data-phone');
            var user_name = $(this).attr('data-user_name');
            var status = $(this).attr('data-status');
            var borrow_ts = $(this).attr('data-borrow_ts');
            var back_ts = $(this).attr('data-back_ts');



            $('#form-id').val(id);
            $('#form-book_sn').val(book_sn);
            $('#form-book_name').val(book_name);
            $('#form-phone').val(phone);
            $('#form-user_name').val(user_name);
            $('#form-status').val(status).trigger('chosen:updated');
            $('#form-borrow_ts').val(borrow_ts);
            $('#form-back_ts').val(back_ts);

        });

        var var_borrow_ts = {
            elem: "#form-borrow_ts",
            format: "YYYY-MM-DD hh:mm:ss",
            min: "2010-01-01",
            max: "2037-12-31",
            istime: true,
            istoday: false,
            choose: function (datas) {}
        };
        laydate(var_borrow_ts);

        var var_back_ts = {
            elem: "#form-back_ts",
            format: "YYYY-MM-DD hh:mm:ss",
            min: "2010-01-01",
            max: "2037-12-31",
            istime: true,
            istoday: false,
            choose: function (datas) {}
        };
        laydate(var_back_ts);

        var start = {
            elem: "#search-stime",
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
            elem: "#search-etime",
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

    });

    function del(id){
        // alert(id);return false;
        var delete_url = '{{route('backend.borrow.del')}}';
        var index_url = window.location.href;
        swal({
            title: "此操作不可恢复，您确定要删除选中的信息吗",
            type: "error",
            showCancelButton: true,
            cancelButtonText: '取消',
            confirmButtonColor: "#f27474",
            confirmButtonText: "确定",
            closeOnConfirm: false
        }, function () {
            var data = {id:id};
            subActionAjaxForMime('post', delete_url, data, index_url);
        });
    }

</script>
@endsection
