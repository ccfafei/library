@extends('backend.layout.app')

@section('content')

    <div class="row">
        <div class="col-sm-12">
            <div class="ibox widget-box float-e-margins">
                <div class="ibox-title no-borders">
                    <form role="form" class="form-inline">
                        <div class="form-group form-group-sm">
                            <label>班级：</label>
                            <input type="text" name="name" value="{{$request_params->name}}" class="form-control">
                        </div>
                        <button class="btn btn-sm btn-primary" type="submit">查询</button>

                    </form>
                </div>

                <div class="ibox-title clearfix">
                    <h5>
                        <a href="{{url('/backend/school')}}">园所列表></a>
                        {{$list_title_name}}

                    </h5>
                    <div class="pull-right">
                        <button class="btn btn-info btn-xs" data-form="add-model" data-toggle="modal" data-target="#formModal">添加班级</button>
                        <a class="btn btn-dark-green btn-xs" style="margin-left: 15px;" target="_self"
                           href="{{url('/backend/school')}}">返回园所</a>
                    </div>
                </div>
                <div class="ibox-content">

                    <table class="table table-stripped toggle-arrow-tiny" data-sort="false">
                        <thead>
                            <tr>
                                <th>班级</th>
                                <th>所属园所</th>
                                <th>班级负责人</th>
                                <th>电话</th>
                                <th class="text-center"> 操作 </th>
                            </tr>
                        </thead>

                        <tbody>
                            @foreach($lists as $list)
                            <tr>
                                <td>{{$list->name}}</td>
                                {{--<td><a target="_blank" href="{{url('index/showstore',['id' => $list->id])}}">{{url('index/showstore',['id' => $list->id])}}</a></td>--}}
                                <td>{{$list->school->name}}</td>
                                <td>{{$list->leader}}</td>
                                <td>{{$list->tel}}</td>
                                <td class="text-center">
                                    <button class="btn btn-success btn-xs" data-form="edit-model" data-toggle="modal" data-target="#formModal"
                                        data-id="{{$list->id}}"
                                        data-school_id="{{$list->school_id}}"
                                        data-name="{{$list->name}}"
                                        data-leader="{{$list->leader}}"
                                        data-tel="{{$list->tel}}"
                                    >修改</button>
                                    <button class="btn btn-danger btn-xs"  onclick="del('{{$list->id}}')">删除</button>

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
                        @include('backend.grade.form')
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
        var form_url = '{{route('backend.grade.save')}}';
        var index_url = window.location.href;
        var rules = [];
        subActionAjaxValidateForMime('#form-validate-submit', rules, form_url, index_url);


        /**
         * 点击添加按钮触发的操作
         */
        $('[data-form="add-model"]').click(function () {
            var n = '';
            var school_id = "{{$school_id}}";
            $('#form-id').val(n);
            $('#form-name').val(n);
            $('#form-school_id').val(school_id);
            $('#form-leader').val(n);
            $('#form-tel').val(n);
        });
        /**
         * 点击修改按钮触发的操作
         */
        $('[data-form="edit-model"]').click(function () {
            var id = $(this).attr('data-id');
            var name = $(this).attr('data-name');
            var school_id = $(this).attr('data-school_id');
            var leader = $(this).attr('data-leader');
            var tel = $(this).attr('data-tel');
            $('#form-id').val(id);
            $('#form-name').val(name);
            $('#form-school_id').val(school_id);
            $('#form-leader').val(leader);
            $('#form-tel').val(tel);
        });
    });

    function del(id){
        // alert(id);return false;
        var delete_url = '{{route('backend.grade.del')}}';
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