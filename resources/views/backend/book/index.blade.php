@extends('backend.layout.app')

@section('content')

    <div class="row">
        <div class="col-sm-12">
            <div class="ibox widget-box float-e-margins">
                <div class="ibox-title no-borders">
                    <form role="form" class="form-inline">

                        <div class="form-group form-group-sm">
                            <label>条码号：</label>
                            <input type="text" name="book_sn" value="{{$request_params->book_sn}}" class="form-control">
                        </div>

                        <div class="form-group form-group-sm">
                            <label>ISBN：</label>
                            <input type="text" name="ISBN" value="{{$request_params->ISBN}}" class="form-control">
                        </div>　　

                        <button class="btn btn-sm btn-primary" type="submit">查询</button>　　


                        <div class="form-group form-group-sm">
                            <label>书籍名称：</label>
                            <input type="text" name="name" value="{{$request_params->name}}" class="form-control">
                        </div>
                        <button class="btn btn-sm btn-primary" type="submit">查询</button>

                    </form>
                </div>

                <div class="ibox-title clearfix">
                    <h5>{{$list_title_name}}
                        <small>列表</small>
                    </h5>
                    <div class="pull-right">
                        <button class="btn btn-info btn-xs" data-form="add-model" data-toggle="modal" data-target="#formModal">图书添加</button>
                        &nbsp; &nbsp; &nbsp; &nbsp;
                    </div>
                </div>
                <div class="ibox-content">

                    <table class="table table-stripped toggle-arrow-tiny" data-sort="false">
                        <thead>
                            <tr>
                                <th>书籍ID</th>
                                <th>条码号</th>
                                <th>名称</th>
                                <th>作者</th>
                                <th>书籍类别</th>
                                <th>封面图</th>
                                <th>ISBN</th>
                                <th>出版社</th>
                                <th>价格</th>
                                <th>材质</th>
                                <th>简介</th>
                                <th>状态</th>
                                <th>操作</th>
                            </tr>
                        </thead>

                        <tbody>
                            @foreach($lists as $list)
                            <tr>
                                <td>{{$list->id}}</td>
                                <td>{{$list->book_sn}}</td>
                                <td>{{$list->name}}</td>
                                <td>{{$list->author}}</td>
                                <td>@if(!empty($list->category_id)) {{$list->category->name}} @endif</td>
                                <td>
                                    @if($list->upload_url)
                                        <img width="100" src="{{asset('storage/'.$list->upload_url)}}">
                                    @endif
                                </td>
                                <td>{{$list->ISBN}}</td>
                                <td>{{$list->publisher}}</td>
                                <td>{{$list->price}}</td>
                                <td>{{$list->texture}}</td>
                                <td> <?php if(!empty($list->remark)){echo truncate(strip_tags($list->remark),20);}  ?></td>
                                <td><span class="<?php getTextColor($list->status)?>">{{config('params')['book_status'][$list->status]}}</span></td>
                                <td><button class="btn btn-success btn-xs" data-form="edit-model" data-toggle="modal" data-target="#formModal"
                                        data-id="{{$list->id}}"
                                        data-book_sn="{{$list->book_sn}}"
                                        data-name="{{$list->name}}"
                                        data-author="{{$list->author}}"
                                        data-category_id="{{$list->category_id}}"
                                        data-upload_id="{{$list->upload_id}}"
                                        data-upload_url="{{$list->upload_url}}"
                                        data-ISBN="{{$list->ISBN}}"
                                        data-publisher="{{$list->publisher}}"
                                        data-price="{{$list->price}}"
                                        data-texture="{{$list->texture}}"
                                        data-remark="{{$list->remark}}"
                                        data-status="{{$list->status}}"
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
                        @include('backend.book.form')
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
        var form_url = '{{route('backend.book.save')}}';
        var index_url = window.location.href;
        var rules = [];
        subActionAjaxValidateForMime('#form-validate-submit', rules, form_url, index_url);


        /**
         * 点击添加按钮触发的操作
         */
        $('[data-form="add-model"]').click(function () {
            var n = '';
            $('#form-id').val(n);
            $('#form-book_sn').val(n);
            $('#form-name').val(n);
            $('#form-author').val(n);
            $('#form-ISBN').val(n);
            $('#form-category_id').val(n);
            $('#form-publisher').val(n);
            $('#form-price').val(n);
            $('#form-texture').val(n);
            $('#form-remark').val(n);
            $('#form-status').val(0).trigger('chosen:updated');
            $('[data-toggle="upload-idInput-one"]').val(n);
            $('[data-toggle="upload-progressInput-one"]').val(n);
        });
        /**
         * 点击修改按钮触发的操作
         */
        $('[data-form="edit-model"]').click(function () {
            var id = $(this).attr('data-id');
            var book_sn = $(this).attr('data-book_sn');
            var name = $(this).attr('data-name');
            var author = $(this).attr('data-author');
            var ISBN = $(this).attr('data-ISBN');
            var publisher = $(this).attr('data-publisher');
            var price = $(this).attr('data-price');
            var texture = $(this).attr('data-texture');
            var category_id = $(this).attr('data-category_id');
            var status = $(this).attr('data-status');
            var upload_id = $(this).attr('data-upload_id');
            var upload_url = $(this).attr('data-upload_url');
            var remark = $(this).attr('data-remark');
            console.log(upload_url);
            $('#form-id').val(id);
            $('#form-book_sn').val(book_sn);
            $('#form-name').val(name);
            $('#form-author').val(author);
            $('#form-ISBN').val(ISBN);
            $('#form-publisher').val(publisher);
            $('#form-price').val(price);
            $('#form-texture').val(texture);
            $('#form-category_id').val(category_id);
            $('#form-status').val(status).trigger('chosen:updated');
            $('[data-toggle="upload-idInput-one"]').val(upload_id);
            $('[data-toggle="upload-progressInput-one"]').val(upload_url);
            $('#form-remark').val(remark);
        });
    });

    function del(id){
        // alert(id);return false;
        var delete_url = '{{route('backend.book.del')}}';
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