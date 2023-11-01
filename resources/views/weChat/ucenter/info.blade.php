@extends('weChat.layout.app')
@section('title', $title_name)
@section('body')
    <div class="mui-content">
        <div class="mui-card">
            <div class="mui-card-header">
                <span style="color:#999793;">家长信息</span>
            </div>

            @if(empty($list->name)&&empty($list->phone))
                <div class="mui-card-content" style="padding: 0.625rem;">
                    暂无信息
                </div>
            @else
                <div class="mui-card-content" style="padding: 0.625rem;">
                    <span style="color:#999793;">家长姓名</span><b class="mui-pull-right">{{$list->name}}</b>
                </div>
                <div class="mui-card-content" style="padding: 0.625rem;">
                    <span style="color:#999793;">联系电话</span><b class="mui-pull-right">{{$list->phone}}</b>
                <!-- 默认为用户绑定手机号 -->
                </div>
            @endif

        </div>
        <div class="mui-card">
            <div class="mui-card-header">
                <span style="color:#999793;">孩子信息</span>
            </div>
            @if(!empty($kids))
                <div class="mui-card-content" style="padding: 0.625rem;">
                    <span style="color:#999793;">孩子姓名</span><b class="mui-pull-right">{{$kids['name']}}</b>

                </div>
                <div class="mui-card-content" style="padding: 0.625rem;">
                    <span style="color:#999793;">幼儿园名称</span><b class="mui-pull-right">{{$kids['school_name']}}</b>
                </div>
                <div class="mui-card-content" style="padding: 0.625rem;">
                    <span style="color:#999793;">班级</span><b class="mui-pull-right">{{$kids['grade_name']}}</b>

                </div>
            @else

                <div class="mui-card-content" style="padding: 0.625rem;">
                    暂无信息
                </div>
            @endif
        </div>
    </div>

    <div class="mui-content-padded">
        <a href="{{url('/wechat/user/editinfo')}}">
            <button id='login' class="mui-btn mui-btn-block mui-btn-primary" style="background-color: #FFD233;color: #0D0D0D;border: 0;"><b>编 辑 信 息</b></button>
        </a>
    </div>
    <div class="footer">
        <nav class="mui-bar mui-bar-tab" style="height: 60px;">
            <a class="mui-tab-item" href="{{url('/wechat/index')}}">
                    <span class="mui-icon icon-home"
                          style="background: url('/images/icon_index_no.png') no-repeat;background-size: 100% 100%;width: 30px;height: 30px;"></span>
                <span class="mui-tab-label">首页</span>
            </a>
            <a class="mui-tab-item" href="{{url('/wechat/search')}}">
                    <span class="mui-icon icon-search"
                          style="background: url('/images/icon_search_no.png') no-repeat;background-size: 100% 100%;width: 30px;height: 30px;"></span>
                <span class="mui-tab-label">寻书</span>
            </a>
            <a class="mui-tab-item" href="{{url('/wechat/history/index')}}">
                    <span class="mui-icon icon-refresh"
                          style="background: url('/images/icon_history_no.png') no-repeat;background-size: 100% 100%;width: 30px;height: 30px;"></span>
                <span class="mui-tab-label">历史</span>
            </a>
            <a class="mui-tab-item mui-active" href="{{url('/wechat/user')}}">
                    <span class="mui-icon icon-contact"
                          style="background: url('/images/icon_ucenter.png') no-repeat;background-size: 100% 100%;width: 30px;height: 30px;"></span>
                <span class="mui-tab-label">我的</span>
            </a>
        </nav>
    </div>
@endsection
@section('js')
    <script type="text/javascript">
        mui('.mui-bar-tab').on('tap', 'a', function () {
            var str = (this.href.indexOf("?") != -1) ? "&" : "?";
            document.location.href = this.href+str+"timestamp=" + new Date().getTime();

        });
    </script>
@endsection