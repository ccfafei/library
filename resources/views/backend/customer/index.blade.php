@extends('backend.layout.app')

@section('content')

    <div class="row">
        <div class="col-sm-12">
            <div class="ibox widget-box float-e-margins">
                <div class="ibox-title">
                    <form role="form" class="form-inline">
                        <div class="form-group form-group-sm">

                            <label>家长手机号：</label>
                            <input type="text" name="phone" value="{{$request_params->phone}}"
                                   class="form-control input-sm">
                            <button class="btn btn-sm btn-primary" type="submit">查询</button>
                            <input type="hidden" name="_token" value="">

                        </div>
                        |
                        <div class="form-group form-group-sm">
                            <label>家长姓名：</label>
                            <input type="text" name="name" value="{{$request_params->name}}"
                                   class="form-control input-sm">
                        </div>
                        <button class="btn btn-sm btn-primary" type="submit">查询</button>

                    </form>
                </div>

                <div class="ibox-title clearfix">
                    <h5>{{$list_title_name}}
                        <small>列表</small>
                    </h5>
                    {{--<div class="pull-right">--}}
                    {{--<button class="btn btn-info btn-xs" data-form="add-model" data-toggle="modal" data-target="#formModal">添加</button>--}}
                    {{--</div>--}}
                </div>
                <div class="ibox-content">

                    <table class="table table-stripped toggle-arrow-tiny" data-sort="false">
                        <thead>
                        <tr>
                            <th>昵称</th>
                            <th>手机号</th>
                            <th>家长姓名</th>
                            <th>会员卡名称</th>
                            <th>类型</th>
                            <th>注册时间</th>
                            <th>到期日期</th>
                            {{--<th>余额</th>--}}
                            <th>状态</th>
                            <th class="text-right">操作</th>
                            <th data-hide="all">孩子信息</th>
                        </tr>
                        </thead>

                        <tbody>
                        @foreach($lists as $list)
                            <tr>
                                <td>{{$list->wx_name}}</td>
                                <td>{{$list->phone}}</td>
                                <td>{{$list->name}}</td>
                                <td>{{$list->vip}}</td>
                                <td>@if(isset($list->vip_type)){{config('params')['vip_type'][$list->vip_type]}}@endif</td>
                                <td>{{$list->created_at}}</td>
                                <td>@if(!empty($list->vip_exp_at)){{$list->vip_exp_at}}@endif</td>
                                {{--<th>¥{{$list->balance??'0.00'}}</th>--}}
                                <td>{{config('params')['status'][$list->status]}}</td>
                                <td class="project-actions">

                                    <button class="btn btn-danger  btn-xs" data-form="edit-model" data-toggle="modal"
                                            data-target="#formModal"
                                            data-id="{{$list->id}}"
                                            data-phone="{{$list->phone}}"
                                            data-type="{{$list->type}}"
                                            data-name="{{$list->name}}"
                                            data-vip="{{$list->vip}}"
                                            data-vip_type="{{$list->vip_type}}"
                                            data-vip_exp_at="{{$list->vip_exp_at}}"
                                            data-status="{{$list->status}}"
                                    >修改
                                    </button>

                                    &nbsp;
                                    {{--<button class="btn btn-info btn-xs " data-form="add_recharges_model"--}}
                                            {{--data-toggle="modal"--}}
                                            {{--data-target="#formRechargeModal"--}}
                                            {{--data-user_id="{{$list->id}}"--}}
                                            {{--data-b_name="{{$list->name}}"--}}
                                            {{--data-b_phone="{{$list->phone}}"--}}
                                            {{--data-last_balance="{{$list->balance??'0.00'}}"--}}
                                            {{--data-admin_name="{{$admins->name}}"--}}
                                    {{-->充值--}}
                                    {{--</button>--}}
                                    &nbsp;
                                    <a  class="btn btn-dark-green btn-xs" href="{{url('/backend/customer/borrow/?user_id=').$list->id}}">借阅记录</a>


                                </td>
                                <td>
                                    <?php $kids = $list->kids; ?>
                                    @foreach($kids as $kid)
                                        {{$kid->name}} &nbsp;&nbsp;
                                        @if($kid->sex == 0) 男 @else 女 @endif &nbsp;&nbsp;
                                        {{$kid->DOB}} &nbsp;&nbsp;
                                        @if(!empty($kid->school_id))
                                            {{$kid->school->name}} &nbsp;&nbsp;
                                        @else
                                          未入园 &nbsp;&nbsp;
                                        @endif
                                        @if(!empty($kid->grade_id))
                                            {{$kid->grade->name}}
                                        @else
                                          无班级 &nbsp;&nbsp;
                                        @endif

                                        <br/>
                                    @endforeach

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
    <div class="modal inmodal fade" id="formModal" tabindex="-1" role="dialog" aria-hidden="true">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header">
                    <button type="button" class="close" data-dismiss="modal"><span
                                aria-hidden="true">&times;</span><span class="sr-only">Close</span></button>
                    <h4 class="modal-title">{{$list_title_name}}编辑</h4>
                </div>
                <form method="post" id="form-validate-submit" class="form-horizontal m-t">
                    <div class="modal-body">
                        @include('backend.customer.form')
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-white btn-sm" data-dismiss="modal">关闭</button>
                        <button type="submit" class="btn btn-primary btn-sm">保存</button>
                    </div>
                </form>
            </div>
        </div>
    </div>

    {{--<div class="modal inmodal fade" id="formRechargeModal" tabindex="-1" role="dialog" aria-hidden="true">--}}
        {{--<div class="modal-dialog">--}}
            {{--<div class="modal-content">--}}
                {{--<div class="modal-header">--}}
                    {{--<button type="button" class="close" data-dismiss="modal"><span--}}
                                {{--aria-hidden="true">&times;</span><span class="sr-only">Close</span></button>--}}
                    {{--<h4 class="modal-title">充值</h4>--}}
                {{--</div>--}}
                {{--<form method="post" id="form-validate-submit2" class="form-horizontal m-t">--}}
                    {{--<div class="modal-body">--}}
                        {{--@include('backend.recharges.form')--}}
                    {{--</div>--}}
                    {{--<div class="modal-footer">--}}
                        {{--<button type="button" class="btn btn-white btn-sm" data-dismiss="modal">关闭</button>--}}
                        {{--<button type="submit" class="btn btn-primary btn-sm">保存</button>--}}
                    {{--</div>--}}
                {{--</form>--}}
            {{--</div>--}}
        {{--</div>--}}
    {{--</div>--}}

@endsection
<!--    js    -->
@section('js_code')
    <script>
        $(function () {
            var form_url = '{{route('backend.user.save')}}';
            var index_url = window.location.href;
            var rules = [];
            subActionAjaxValidateForMime('#form-validate-submit', rules, form_url, index_url);


            /**
             * 点击添加按钮触发的操作
             */
            $('[data-form="add-model"]').click(function () {
                var n = '';
                $('#form-id').val(n);
                $('#form-phone').val(n);
                $('#form-vip').val(0);
                $('#form-vip_exp_at').val(n);
                $('#form-status').val(1).trigger('chosen:updated');
            });
            /**
             * 点击修改按钮触发的操作
             */
            $('[data-form="edit-model"]').click(function () {
                var id = $(this).attr('data-id');
                var phone = $(this).attr('data-phone');
                var name = $(this).attr('data-name');
                var type = $(this).attr('data-type');
                var vip = $(this).attr('data-vip');
                var vip_type = $(this).attr('data-vip_type');
                var vip_exp_at = $(this).attr('data-vip_exp_at');
                if (vip == 0) {
                    vip_exp_at = '';
                }
                var status = $(this).attr('data-status');
                $('#form-id').val(id);
                $('#form-phone').val(phone);
                $('#form-name').val(name);
                $('#form-type').val(type);
                $('#form-vip').val(vip);
                $('#form-vip_type').val(vip_type);
                $('#form-vip_exp_at').val(vip_exp_at);
                $('#form-status').val(status).trigger('chosen:updated');
            });
            var vip_at = {
                elem: "#form-vip_exp_at",
                format: "YYYY/MM/DD",
                min: "2010-01-01",
                max: "2037-12-31",
                istime: true,
                istoday: false,
                choose: function (datas) {
                }
            };
            laydate(vip_at);
        });

        //充值
        {{--$(function () {--}}
            {{--var form_url = '{{route('backend.recharges.save')}}';--}}
            {{--var index_url = '{{route('backend.recharges.index')}}';--}}
            {{--var rules = [];--}}
            {{--subActionAjaxValidateForMime('#form-validate-submit2', rules, form_url, index_url);--}}

            {{--/**--}}
             {{--* 点击添加按钮触发的操作--}}
             {{--*/--}}
            {{--$('[data-form="add_recharges_model"]').click(function () {--}}
                {{--var user_id = $(this).attr('data-user_id');--}}
                {{--var b_name = $(this).attr('data-b_name');--}}
                {{--var b_phone = $(this).attr('data-b_phone');--}}

                {{--var balance = $(this).attr('data-last_balance');--}}
                {{--var admin_name = $(this).attr('data-admin_name');--}}
                {{--//alert(user_id);--}}

                {{--var n = '';--}}
                {{--$('#form-user_id').val(user_id);--}}
                {{--$('#b_name').text(b_name);--}}
                {{--$('#b_phone').text(b_phone);--}}
                {{--$('#last_balance').text(balance);--}}

                {{--$('#form-amount').val(n);--}}
                {{--$('#form-admin_name').text(admin_name);--}}

            {{--});--}}

        {{--});--}}


    </script>
@endsection