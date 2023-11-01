@extends('backend.layout.app')

@section('content')

    <div class="row">
        <div class="col-sm-12">
            <div class="ibox widget-box float-e-margins">
                <div class="ibox-title no-borders">
                    <form role="form" class="form-inline">
                        <div class="form-group form-group-sm">
                            <label>手机号码查询：</label>
                            <input type="text" name="b_tel" value="" class="form-control" placeholder="请输入手机号码进行查询">
                        </div>
                        <button class="btn btn-sm btn-primary" type="submit">查询</button>
                        丨
                        <div class="form-group form-group-sm">
                            <label>用户姓名查询：</label>
                            <input type="text" name="b_name" value="" class="form-control" placeholder="请输入姓名进行查询">
                        </div>
                        <button class="btn btn-sm btn-primary" type="submit">查询</button>
                    </form>
                </div>

                <div class="ibox-title clearfix">
                    <h5>
                        <small>充值信息列表</small>
                    </h5>
                    <div class="pull-right">
                        <button class="btn btn-info" onclick="goToRecharges();">去充值</button>
                    </div>
                </div>
                <div class="ibox-content">

                    <table class="table table-stripped toggle-arrow-tiny" data-sort="false">
                        <thead>
                        <tr>
                            <th>ID</th>
                            <th>用户姓名</th>
                            <th>手机号</th>
                            <th>充值前金额</th>
                            <th>金额变化数</th>
                            <th>余额</th>
                            <th>充值时间</th>
                            <th>操作人</th>
                            <th>操作时间</th>

                        </tr>
                        </thead>

                        <tbody>
                        @if(collect($lists)->isEmpty())
                            <tr>
                                <td colspan="10">暂无记录</td>
                            </tr>
                        @endif
                        @foreach($lists as $list)

                            <tr>
                                <td>{{$list->id}}</td>
                                <td>@if($list->user){{$list->user->name}}@endif</td>
                                <td>@if($list->user){{$list->user->phone}}@endif</td>
                                <td>¥{{$list->last_balance}}</td>
                                <td style="color: green">+ ¥{{$list->amount}}</td>
                                <td>余额：¥{{$list->last_balance+$list->amount}}</td>
                                <td>{{$list->recharge_ts}}</td>
                                <td>{{$list->admin->name}}</td>
                                <td>{{$list->created_at}}</td>

                            </tr>
                        @endforeach
                        </tbody>
                    </table>

                    {{$lists->appends($request_params->all())->render()}}
                </div>

            </div>
        </div>
    </div>

    <div class="modal inmodal fade" id="formModal" tabindex="-1" role="dialog" aria-hidden="true">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header">
                    <button type="button" class="close" data-dismiss="modal"><span
                                aria-hidden="true">&times;</span><span class="sr-only">Close</span></button>
                    <h4 class="modal-title">添加{{$list_title_name}}</h4>
                </div>
                <form method="post" id="form-validate-submit" class="form-horizontal m-t">
                    <div class="modal-body">
                        @include('backend.recharges.form')
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-white btn-sm" data-dismiss="modal">关闭</button>
                        <button type="submit" class="btn btn-primary btn-sm">确认提交</button>
                    </div>
                </form>
            </div>
        </div>
    </div>
@endsection

<!--    js    -->
@section('js_code')
    <script>


        function goToRecharges(){
            var url = "{{route('backend.customer.index')}}"
            window.location.href = url;
        }
    </script>
@endsection