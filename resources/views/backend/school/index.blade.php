@extends('backend.layout.app')

@section('content')

    <div class="row">
        <div class="col-sm-12">
            <div class="ibox widget-box float-e-margins">
                <div class="ibox-title no-borders">
                    <form role="form" class="form-inline">
                        <div class="form-group form-group-sm">
                            <label>园所名称：</label>
                            <input type="text" name="name" value="{{$request_params->name}}" class="form-control">
                        </div>
                        <button class="btn btn-sm btn-primary" type="submit">查询</button>

                    </form>
                </div>

                <div class="ibox-title clearfix">
                    <h5>
                        {{$list_title_name}}
                        <small>列表</small>
                    </h5>
                    <div class="pull-right">
                        <button class="btn btn-info btn-xs" data-form="add-model" data-toggle="modal" data-target="#formModal">添加园所</button>
                    </div>
                </div>
                <div class="ibox-content">

                    <table class="table table-stripped toggle-arrow-tiny" data-sort="false">
                        <thead>
                            <tr>
                                <th>名称</th>
                                <th>地址</th>
                                <th>园区负责人</th>
                                <th>电话</th>
                                <th>状态</th>
                                <th class="text-center"> 操作 </th>
                            </tr>
                        </thead>

                        <tbody>
                            @foreach($lists as $list)
                            <tr>
                                <td>{{$list->name}}</td>
                                {{--<td><a target="_blank" href="{{url('index/showstore',['id' => $list->id])}}">{{url('index/showstore',['id' => $list->id])}}</a></td>--}}
                                <td>{{$list->address}}</td>
                                <td>{{$list->leader}}</td>
                                <td>{{$list->tel}}</td>
                                <td>{{config('params')['status'][$list->status]}}</td>
                                <td class="text-center">
                                    <button class="btn btn-success btn-xs" data-form="edit-model" data-toggle="modal" data-target="#formModal"
                                        data-id="{{$list->id}}"
                                        data-name="{{$list->name}}"
                                        data-address="{{$list->address}}"
                                        data-leader="{{$list->leader}}"
                                        data-tel="{{$list->tel}}"
                                        data-status="{{$list->status}}"
                                    >修改</button>
                                    <button class="btn btn-danger btn-xs"  onclick="del('{{$list->id}}')">删除</button>

                                    <a class="btn btn-dark-green btn-xs" style="margin-left: 15px;" target="_self"
                                       href="{{url('/backend/grade?school_id='.$list->id)}}">管理班级</a>
                                    <a class="btn btn-dark-blue btn-xs" style="margin-left: 15px;" target="_self"
                                       href="{{url('/backend/vipcard?school_id='.$list->id)}}">管理会员卡</a>

                                </td>
                            </tr>
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
                        @include('backend.school.form')
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
<script>

    $(function () {
        var form_url = '{{route('backend.school.save')}}';
        var index_url = window.location.href;
        var rules = [];
        subActionAjaxValidateForMime('#form-validate-submit', rules, form_url, index_url);


        /**
         * 点击添加按钮触发的操作
         */
        $('[data-form="add-model"]').click(function () {
            var n = '';
            $('#form-id').val(n);
            $('#form-name').val(n);
            $('#form-address').val(n);
            $('#form-leader').val(n);
            $('#form-tel').val(n);
            $('#form-status').val(1).trigger('chosen:updated');
        });
        /**
         * 点击修改按钮触发的操作
         */
        $('[data-form="edit-model"]').click(function () {
            var id = $(this).attr('data-id');
            var name = $(this).attr('data-name');
            var address = $(this).attr('data-address');
            var leader = $(this).attr('data-leader');
            var tel = $(this).attr('data-tel');
            var status = $(this).attr('data-status');
            $('#form-id').val(id);
            $('#form-name').val(name);
            $('#form-address').val(address);
            $('#form-leader').val(leader);
            $('#form-tel').val(tel);
            $('#form-status').val(status).trigger('chosen:updated');
        });
    });
    function del(id){
        // alert(id);return false;
        var delete_url = '{{route('backend.school.del')}}';
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