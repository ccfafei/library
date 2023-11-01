@extends('backend.layout.app')

@section('content')
    {{ csrf_field() }}
    <div class="row">
        <div class="col-sm-12">
            <div class="ibox widget-box float-e-margins">
                <div class="ibox-title no-borders">
                    <form role="form" class="form-inline" method="post" action="{{route('backend.costs.search')}}">
                        {{ csrf_field()}}
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
                        <small>消费信息列表</small>
                    </h5>
                    <div class="pull-right">
                        {{--<button class="btn btn-info btn-xs" data-form="add-model" data-toggle="modal" data-target="#formModal">添加</button>--}}
                    </div>
                </div>
                <div class="ibox-content">

                    @if($flag ==0)

                        <table class="table table-stripped toggle-arrow-tiny" data-sort="false">
                            <thead>
                            <tr>
                                <th>ID</th>
                                <th>用户姓名</th>
                                <th>手机号</th>
                                <th>消费前金额</th>
                                <th>金额变化数</th>>
                                <th>消费时间</th>
                                <th>操作人</th>
                                <th>操作时间</th>
                                <th data-hide="all">消费明细</th>
                                <th>操作</th>
                            </tr>
                            </thead>

                            <tbody>
                            @if(collect($default_lists)->isEmpty())
                                <tr>
                                    <td colspan="12">暂无记录</td>
                                </tr>
                            @else
                                @foreach($default_lists as $list)
                                    <tr>
                                        <td>{{$list->id}}</td>
                                        <td>{{$list->user->name}}</td>
                                        <td>{{$list->user->phone}}</td>
                                        <td>{{$list->last_balance}}</td>
                                        <td style="color: red">- ¥{{$list->amount}}</td>
                                        <td>{{$list->cost_ts}}</td>
                                        <td>{{$list->admin->name}}</td>
                                        <td>{{$list->created_at}}</td>
                                        <td>


                                        </td>
                                        <td>
                                            <button class="btn btn-danger btn-xs" data-form="edit-model"
                                                    data-toggle="modal"
                                                    data-target="#formModal"
                                                    data-user_id="{{$list->user_id}}"
                                                    data-name="{{$list->user->name}}"
                                                    data-phone="{{$list->user->phone}}"
                                                    data-balance="{{$list->user->balance}}"

                                            >消费
                                            </button>
                                        </td>

                                    </tr>
                                @endforeach
                            @endif
                            </tbody>
                        </table>

                        {{$default_lists->appends($request_params->all())->render()}}


                    @else
                        @if(empty($list['cost']))
                            @if(empty($list['user']))
                                <p>未查到该用户</p>
                            @else

                                <table class="table table-stripped toggle-arrow-tiny" data-sort="false">
                                    <thead>
                                    <tr>
                                        <th>ID</th>
                                        <th>用户姓名</th>
                                        <th>手机号</th>
                                        <th>昵称</th>
                                        <th>余额</th>
                                        <th data-hide="all">消费明细</th>
                                        <th>操作</th>
                                    </tr>
                                    </thead>
                                    <tbody>
                                    <tr>
                                        <td>{{$list['user']['id']}}</td>
                                        <td>{{$list['user']['name']}}</td>
                                        <td>{{$list['user']['phone']}}</td>
                                        <td>{{$list['user']['wx_name']}}</td>
                                        <td>¥{{$list['user']['balance']}}</td>
                                        <td>
                                            <?php
                                             echo "暂无记录";
                                            ?>

                                        </td>
                                        <td>
                                            <button class="btn btn-danger btn-xs" data-form="edit-model"
                                                    data-toggle="modal"
                                                    data-target="#formModal"
                                                    data-user_id="{{$list['user']['id']}}"
                                                    data-name="{{$list['user']['name']}}"
                                                    data-phone="{{$list['user']['phone']}}"
                                                    data-balance="{{$list['user']['balance']}}"
                                            >消费
                                            </button>
                                        </td>
                                    </tr>
                                    </tbody>
                                </table>
                            @endif
                        @else

                                <table class="table table-stripped toggle-arrow-tiny" data-sort="false">
                                    <thead>
                                    <tr>
                                        <th>ID</th>
                                        <th>用户姓名</th>
                                        <th>手机号</th>
                                        <th>消费前金额</th>

                                        <th>金额变化数</th>

                                        <th>消费时间</th>
                                        <th>操作人</th>
                                        <th>操作时间</th>
                                        <th data-hide="all">消费明细</th>
                                        <th>操作</th>
                                    </tr>
                                    </thead>
                                    <tbody>
                                      @foreach($list['cost'] as $rows)
                                          <tr>
                                            <td>{{$rows ->id}}</td>
                                            <td>{{$rows->user->name }}</td>
                                            <td>{{$rows ->user->phone}}</td>
                                            <td>{{$rows ->last_balance}}</td>
                                            <td style="color: red">- ¥{{$rows ->amount}}</td>
                                            <td>{{$rows ->cost_ts}}</td>
                                            <td>{{$rows ->admin->name}}</td>
                                            <td>{{$rows ->created_at}}</td>
                                            <td>


                                            </td>
                                            <td>
                                                <button class="btn btn-danger btn-xs" data-form="edit-model"
                                                        data-toggle="modal"
                                                        data-target="#formModal"
                                                        data-user_id="{{$rows->user_id}}"
                                                        data-name="{{$rows->user->name}}"
                                                        data-phone="{{$rows->user->phone}}"
                                                        data-balance="{{$rows->user->balance}}"

                                                >消费
                                                </button>
                                            </td>

                                          </tr>
                                      @endforeach
                                    </tbody>
                                </table>

                            {{$cost_lists->appends($request_params->all())->render()}}

                        @endif

                        {{--@else--}}
                            {{--<table class="table table-stripped toggle-arrow-tiny" data-sort="false">--}}
                                {{--<tr>--}}
                                    {{--<td colspan="12">暂无记录</td>--}}
                                {{--</tr>--}}
                            {{--</table>--}}
                        {{--@endif--}}

                    @endif
                </div>

            </div>
        </div>
    </div>

    <div class=" modal inmodal fade
                                    " id="formModal" tabindex="-1" role="dialog" aria-hidden="true">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header">
                    <button type="button" class="close" data-dismiss="modal"><span
                                aria-hidden="true">&times;</span><span
                                class="sr-only">Close</span></button>
                    <h4 class="modal-title">添加{{$list_title_name}}</h4>
                </div>
                <form method="post" id="form-validate-submit"
                      class="form-horizontal m-t">
                    <div class="modal-body">
                        @include('backend.costs.form')
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-white btn-sm"
                                data-dismiss="modal">关闭
                        </button>
                        {{csrf_field()}}

                        <button type="button" id="cost_save" class="btn btn-primary btn-sm">确认提交
                        </button>
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

            /**
             * 点击消费按钮触发的操作
             */
            $('[data-form="edit-model"]').click(function () {
                var user_id = $(this).attr('data-user_id');
                var name = $(this).attr('data-name');
                var phone = $(this).attr('data-phone');
                var balance = $(this).attr('data-balance');

                $('#form_user_id').val(user_id);
                $('#form_name').text(name);
                $('#form_phone').text(phone);
                $('#form_balance').text(balance);

                var store_id = 1


            });

            //数据提交
            $('#cost_save').click(function () {
                var cost = {};
                var details = [];
                var sundry = [];

                cost['user_id'] = $('#form_user_id').val();
                cost['last_balance'] = $('#form_balance').text();


                cost['money_count'] = parseFloat($('#moneycount').text().substring(1, $('#moneycount').text().length - 1));
                console.log(cost);

                cost['details'] = details;

                var sundry_name = $("#remark").val();
                var sundry_money = $("#money").val();
                undry = {
                    "sundry_name": sundry_name,
                    "sundry_money": sundry_money,
                    "cost_type": 1
                };

                cost['sundry'] = sundry;


                var json_data = JSON.stringify(cost);


                $.ajax({
                    type: "post",
                    url: "{{route('backend.costs.save')}}",
                    data: json_data,
                    dataType: "json",
                    success: function (data) {
                        if (data.code == 200) {
                            // alert('录入成功');
                            parent.layer.alert('录入成功')
                            window.location.href = "{{route('backend.costs.index')}}"
                        }else{

                            parent.layer.alert(data.msg);
                            return false;
                        }
                        console.log(data);

                    }
                });
            });


        });

        //计算总价
        function acount() {

            var moneycount = 0;


            //杂项的价格
            var mmoney = parseFloat($("#money").val());

            moneycount = moneycount + mmoney;


            $("#costsinfo tr").each(function () {
                moneycount += parseFloat($(this).find("td").eq(5).html().substring(1));

            })


            $("#moneycount").html("¥" + moneycount + ".00");

        }


        $('#money').bind('input propertychange', function () {
            acount();
        });


        $("#store_id").change(function(){
            var  store_id = 1;
        });

        //添加消费信息到列表
        function addcostsinfo(sid) {

            var sname = $("#s" + sid).data('sname');
            var sprice = $("#s" + sid).data('sprice');

            if ($("#b_s" + sid).length > 0) {

                //已有项目做修改
                //获取该元素数量
                var num = $("#b_snum" + sid).html();
                ++num;//+1

                // /alert(num);

                var s_bhtml = "            <td>\n" +
                    "               <input type='hidden' class='serverid' value='" + server_id + "'>\n" +
                    "                <div><strong>" + sname + "</strong>\n" +
                    "                </div>\n" +
                    "            </td>\n" +
                    "            <td id='b_snum" + sid + "'>" + num + "</td>\n" +
                    "            <td>¥<span id=b_sprice" + sid + ">" + sprice + "</span></td>\n" +
                    "            <td>¥" + sprice * num + ".00</td>\n";


                //alert(s_bhtml);
                $("#b_s" + sid).html(s_bhtml);

            }
            else {

                //若是新的项目

                var html = "<tr id='b_s" + sid + "'>\n" +
                    "            <td>\n" +
                    "               <input type='hidden' class='serverid' value='" + server_id + "'>\n" +
                    "                <div><strong>" + sname + "</strong>\n" +
                    "                </div>\n" +
                    "            </td>\n" +
                    "            <td id='b_snum" + sid + "'>1</td>\n" +
                    "            <td>¥<span id=b_sprice" + sid + ">" + sprice + "</span></td>\n" +

                    "            <td>¥" + sprice + "</td>\n" +
                    "        </tr>";

                $("#costsinfo").append(html);
            }
            acount();
        }


    </script>
@endsection