@extends('backend.layout.app')

@section('content')

    <div class="row">
    <div class="col-sm-12">
        <div class="ibox-title clearfix">
            <span class="pull-left">
                @if(!empty($user))
                    当前用户: {{$user->name}}  &nbsp;&nbsp; 手机号: {{$user->phone}}
                @endif
            </span>
            <span class="pull-right">
                <a href="{{route('backend.customer.index')}}" class="btn btn-danger btn-xs">返回家长信息</a>
            </span>
        </div>
        <div class="ibox-content">

            <table class="table table-stripped toggle-arrow-tiny" data-sort="false">
                <thead>
                <tr>
                    <th>书籍名称</th>
                    <th>小朋友</th>
                    <th>学校</th>
                    <th>班级</th>
                    <th>家长</th>
                    <th>电话</th>
                    <th>借书时间</th>
                    <th>还书时间</th>
                    <th>状态</th>
                    <th>操作人</th>
                    <th>操作时间</th>
                </tr>
                </thead>

                <tbody>
                @if(collect($lists)->isEmpty())
                    <tr><td colspan="8">暂无记录</td></tr>
                @endif
                @foreach($lists as $list)
                    @if($list->user_id)
                        <tr>
                            <td>{{$list->book->name}}</td>
                            @if(collect($list->kid)->isNotEmpty())
                                <td>{{$list->kid->name}}</td>
                                <td>
                                    <?php
                                    $info = getKidInfos($list->kid_id);
                                    echo $info['school_name'];
                                    ?>
                                </td>
                                <td><?php echo $info['grade_name']?></td>
                            @else
                                <td></td>
                                <td></td>
                                <td></td>
                            @endif
                            <td>{{$list->user->name}}</td>
                            <td>{{$list->user->phone}}</td>
                            <td>{{$list->borrow_ts}}</td>
                            <td>{{$list->back_ts}}</td>
                            <td>{{$list->status?'已还':'已借'}}</td>
                            @if(collect($list->admin)->isNotEmpty())
                                <td>{{$list->admin->name}}</td>
                            @else
                                <td></td>
                            @endif
                            <td>{{$list->updated_at}}</td>

                        </tr>
                    @endif
                @endforeach
                </tbody>
            </table>

            {{$lists->appends($request_params->all())->render()}}

        </div>

    </div>
</div>
@endsection