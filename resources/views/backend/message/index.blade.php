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

                        <div class="form-group form-group-sm m-r" >
                            <label>关键词：</label>
                            <input type="text" name="sarch" placeholder="请输入标题/内容" value="{{$request_params->search}}" class="form-control">
                        </div>

                        <div class="form-group form-group-sm m-r" >
                            <label>类型：</label>
                            <select name="type" class="form-control" id="search">
                                <option value="0">所有</option>
                                <option value="1">短信</option>
                                <option value="2">微信</option>
                            </select>

                        </div>

                        <button class="btn btn-sm btn-primary m-r" type="submit" >查询</button>&nbsp;

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
                                <th>时间</th>
                                <th>标题</th>
                                <th>内容</th>
                                <th>用户id</th>
                                <th>姓名</th>
                                <th>手机号</th>
                                <th>微信id</th>
                                <th>微信昵称</th>
                                <th>发送方式</th>
                                <th>类型</th>
                            </tr>
                        </thead>

                        <tbody>
                            @if(collect($lists)->isEmpty())
                                <tr><td colspan="8">暂无记录</td></tr>
                            @endif
                            @foreach($lists as $list)

                            @endforeach
                        </tbody>
                    </table>

                    {{$lists->appends($request_params->all())->render()}}

                </div>

            </div>
        </div>
    </div>


@endsection

<!--    js    -->
@section('js_code')
<script src="{{asset('backend/js/xlsx.full.min.js')}}"></script>
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
