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
                </div>
                <div class="ibox-content">

                    <table class="table table-stripped toggle-arrow-tiny" data-sort="false">
                        <thead>
                            <tr>
                                <th>名称</th>
                                <th>ISBN</th>
                                <th>在馆数量</th>
                                <th>借出数量</th>
                                <th>报废数量</th>
                                <th>总数量</th>
                                <th>阅读量</th>
                                <th>是否精选</th>
                                <th>操作</th>
                            </tr>
                        </thead>

                        <tbody>
                            @foreach($lists as $list)
                            <tr>
                                <td>{{$list->name}}</td>
                                <td>{{$list->ISBN}}</td>
                                <td>{{$list->store_num}}</td>
                                <td>{{$list->borrow_num}}</td>
                                <td>{{$list->invalid_num}}</td>
                                <td>{{$list->store_num +$list->borrow_num}}</td>
                                <td> {{$list->reading??0}} </td>
                                <td> {{$list->is_hot==1?'是':'否'}} </td>
                                {{--<td> {{$list->is_new==1?'是':'否'}} </td>--}}
                                <td>
                                    <button class="btn btn-success btn-xs" data-form="edit-model" data-toggle="modal" data-target="#formModal"
                                            data-name="{{$list->name}}"
                                            data-ISBN="{{$list->ISBN}}"
                                            data-reading="{{$list->reading}}"
                                            data-is_hot="{{$list->is_hot}}"
                                            data-is_new="{{$list->is_new}}"
                                    >设置</button>

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
                    <h4 class="modal-title">图书设置</h4>
                </div>
                <form method="post" id="form-validate-submit" class="form-horizontal m-t">
                    <div class="modal-body">
                        @include('backend.book.reading')
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
            var form_url = '{{route('backend.book.reading')}}';
            var index_url = window.location.href;
            var rules = [];
            subActionAjaxValidateForMime('#form-validate-submit', rules, form_url, index_url);

            /**
             * 点击修改按钮触发的操作
             */
            $('[data-form="edit-model"]').click(function () {
                var ISBN = $(this).attr('data-ISBN');
                var  name = $(this).attr('data-name');
                var  reading = $(this).attr('data-reading');
                var  is_hot = $(this).attr('data-is_hot');
                var  is_new = $(this).attr('data-is_new');

                $('#form-ISBN').val(ISBN);
                $('#form-name').val(name);
                $('#form-reading').val(reading);
                if(is_hot==1){
                    $('#is_hot').prop('checked',true);
                }else{
                    $('#not_hot').prop('checked',true);
                }
                if(is_new==1){
                    $('#is_new').prop('checked',true);
                }else{
                    $('#not_new').prop('checked',true);
                }

            });
         });

    </script>
@endsection