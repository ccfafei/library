@extends('backend.layout.app')

@section('content')

    <div class="row">
        <div class="col-sm-12">
            <div class="ibox widget-box float-e-margins">

                <div class="ibox-title clearfix">
                    <h5>{{$list_title_name}}
                        <small>列表</small>
                    </h5>
                    <div class="pull-right">
                        <button class="btn btn-info btn-xs" data-form="add-model" data-toggle="modal" data-target="#formModal">banner上传</button>
                        &nbsp; &nbsp; &nbsp; &nbsp;
                    </div>
                </div>
                <div class="ibox-content">

                    <table class="table table-stripped toggle-arrow-tiny" data-sort="false">
                        <thead>
                            <tr>
                                <th>顺序</th>
                                <th>标题</th>
                                <th>图片</th>
                                <th>链接地址</th>
                                <th>操作</th>
                            </tr>
                        </thead>

                        <tbody>
                            @foreach($lists as $list)
                            <tr>
                                <td>{{$list->sort}}</td>
                                <td>{{$list->title}}</td>
                                <td>
                                    @if($list->upload_url)
                                        <img width="100" src="{{asset('storage/'.$list->upload_url)}}">
                                    @endif
                                </td>

                                <td>{{$list->link_url}}</td>

                                <td><button class="btn btn-success btn-xs" data-form="edit-model" data-toggle="modal" data-target="#formModal"
                                            data-id="{{$list->id}}"
                                            data-title="{{$list->title}}"
                                            data-upload_id="{{$list->upload_id}}"
                                            data-upload_url="{{$list->upload_url}}"
                                            data-sort="{{$list->sort}}"
                                            data-link_url="{{$list->link_url}}"
                                    >修改</button>&nbsp;
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
                    <h4 class="modal-title">{{$list_title_name}}上传</h4>
                </div>
                <form method="post" id="form-validate-submit" class="form-horizontal m-t">
                    <div class="modal-body">
                        @include('backend.banner.form')
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
        var form_url = '{{route('backend.banner.save')}}';
        var index_url = window.location.href;
        var rules = [];
        subActionAjaxValidateForMime('#form-validate-submit', rules, form_url, index_url);

        /**
         * 点击添加按钮触发的操作
         */
        $('[data-form="add-model"]').click(function () {
            var n = '';
            $('#form-id').val(n);
            $('#form-title').val(n);
            $('#form-sort').val(n);
            $('#form-link_url').val(n);
            $('[data-toggle="upload-idInput-one"]').val(n);
            $('[data-toggle="upload-progressInput-one"]').val(n);
        });

        /**
         * 点击修改按钮触发的操作
         */
        $('[data-form="edit-model"]').click(function () {
            var id = $(this).attr('data-id');
            var title = $(this).attr('data-title');
            var upload_id = $(this).attr('data-upload_id');
            var upload_url = $(this).attr('data-upload_url');
            var sort = $(this).attr('data-sort');
            var link_url = $(this).attr('data-link_url');
            console.log(upload_url);
            $('#form-id').val(id);
            $('#form-title').val(title);
            $('#form-sort').val(sort);
            $('#form-link_url').val(link_url);
            $('[data-toggle="upload-idInput-one"]').val(upload_id);
            $('[data-toggle="upload-progressInput-one"]').val(upload_url);
        });



    });

    function del(id){
        // alert(id);return false;
        var delete_url = '{{route('backend.banner.del')}}';
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