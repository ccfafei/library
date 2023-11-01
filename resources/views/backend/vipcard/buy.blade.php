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

                        <div class="form-group form-group-sm">
                            <label>姓名：</label>
                            <input type="text" name="name" value="{{$request_params->name}}" class="form-control">
                        </div>
                        <div class="form-group form-group-sm">
                            <label>手机号：</label>
                            <input type="text" name="phone" value="{{$request_params->phone}}" class="form-control">
                        </div>
                        <button class="btn btn-sm btn-primary" type="submit">查询</button>

                    </form>
                </div>

                <div class="ibox-title clearfix">
                    <h5>
                        {{$list_title_name}}
                        <small>列表</small>
                        <span></span>
                        <span></span>
                    </h5>

                </div>
                <div class="ibox-content">

                    <table class="table table-stripped toggle-arrow-tiny" data-sort="false">
                        <thead>
                            <tr>
                                <th>购买时间</th>
                                <th>交易号</th>
                                <th>微信ID</th>
                                <th>昵称</th>
                                <th>卡号</th>
                                <th>姓名</th>
                                <th>电话</th>
                                <th>会员卡类型</th>
                                <th>到期时间</th>
                                <th>金额</th>
                                <th>状态</th>

                            </tr>
                        </thead>

                        <tbody>
                            @foreach($lists as $list)
                            <tr>
                                <td>{{$list->created_at}}</td>
                                <td>{{$list->trade_no}}</td>
                                <td>{{$list->user->openid}}</td>
                                <td>{{$list->user->wx_name}}</td>
                                <td>{{$list->user->member_no}}</td>
                                <td>{{$list->user->name}}</td>
                                <td>{{$list->user->phone}}</td>
                                <td>{{config('params')['vip_type'][$list->vip_type]}}</td>
                                <td>{{$list->vip_exp_at}}</td>
                                <td>{{$list->price}}</td>
                                <td>{{config('params')['status'][$list->status]}}</td>

                            </tr>
                            @endforeach
                        </tbody>

                    </table>
                    <span> 共<strong>{{$counts}}</strong>笔, 合计: <strong>{{$prices}} </strong> 元</span>
                    {{$lists->appends($request_params->all())->render()}}

                </div>

            </div>
        </div>
    </div>
@endsection

<!--    js    -->
@section('js_code')
    <script>

        $(function () {
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
    </script>
@endsection