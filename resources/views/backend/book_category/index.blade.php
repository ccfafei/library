@extends('backend.layout.app')

@section('content')

    <div class="row">
        <div class="col-sm-12">
            <div class="ibox widget-box float-e-margins">
                <div class="ibox-title no-borders">
                    <form role="form" class="form-inline">
                        <div class="form-group form-group-sm">
                            <label>分类名称：</label>
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
                        <button class="btn btn-info btn-xs" data-form="add-model" data-toggle="modal" data-target="#formModal">添加分类</button>
                    </div>
                </div>
                <div class="ibox-content">

                    <table class="table table-stripped toggle-arrow-tiny" data-sort="false">
                        <thead>
                            <tr>
                                <th>ID</th>
                                <th>名称</th>
                                <th>说明</th>
                                <th class="text-center"> 操作 </th>
                            </tr>
                        </thead>

                        <tbody>
                            @foreach($lists as $list)
                            <tr>
                                <td>{{$list->id}}</td>
                                <td>{{$list->name}}</td>
                                <td>{{$list->remark}}</td>
                                <td class="text-center">
                                    <button class="btn btn-success btn-xs" data-form="edit-model" data-toggle="modal" data-target="#formModal"
                                        data-id="{{$list->id}}"
                                        data-name="{{$list->name}}"
                                        data-remark="{{$list->remark}}"
                                        data-leader="{{$list->leader}}"
                                    >修改</button>
                                    <button class="btn btn-danger btn-xs"  onclick="delCate('{{$list->id}}')">删除</button>
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
                        @include('backend.book_category.form')
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
        var form_url = '{{route('backend.book_category.save')}}';
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
            $('#form-remark').val(n);
        });
        /**
         * 点击修改按钮触发的操作
         */
        $('[data-form="edit-model"]').click(function () {
            var id = $(this).attr('data-id');
            var name = $(this).attr('data-name');
            var remark = $(this).attr('data-remark');
            $('#form-id').val(id);
            $('#form-name').val(name);
            $('#form-remark').val(remark);
        });


    });
    function delCate(id){
        // alert(id);return false;
        var delete_url = '{{route('backend.book_category.del')}}';
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