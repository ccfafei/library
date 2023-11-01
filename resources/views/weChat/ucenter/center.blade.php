@extends('weChat.layout.app')
@section('title', $title_name)
@section('css')
    <style type="text/css">
        li a {
            font-size: 1rem !important;
        }

        b {
            font-size: 15px;
        }
    </style>
@endsection
@section('body')
    <div class="mui-content">
        <div style="background-color:#FFBF42;border-bottom-right-radius: 15%;border-bottom-left-radius: 15%;border: 0;overflow: hidden;">
            <div style="text-align: center;">
                <div class="mui-media-body">
                    <img src="{{$user->wx_avatar}}"
                         style="width: 72px !important;border-radius: 50%;margin: auto;margin-top: 20px;">
                    <div class="mui-row">
                        <span style="font-weight: bolder;color: #FFFFFF">{{$user->wx_name}}</span>
                    </div>
                    <div class="mui-row"
                         style="background: url('/images/uc_vip.png') no-repeat;background-size:100% 100%;height: 122px;overflow: hidden;padding-left: 50px;padding-top: 55px;">
                        <p class="mui-ellipsis"
                           style="font-weight: bolder;text-align: left;color:#FFFFFF;">@if(!empty($kids)){{$kids['school_name']}}@endif {{$user->vip?'-'.$user->vip:''}}</p>
                        <p class="mui-ellipsis" style="text-align: left;color:#FFFFFF; ">
                            @if($user->vip_type)
                                到期时间：{{$user->vip_exp_at}}
                            @endif
                        </p>
                    </div>
                </div>
            </div>
        </div>

        <div class="mui-card">
            <div class="mui-card-content">
                <ul class="mui-table-view">
                    <li class="mui-table-view-cell">
                        <a class="mui-navigate-right" href="{{url('wechat/user/vipcard')}}">
                            <span class="mui-icon icon-contact"
                                  style="background: url('/images/icon_vip.png') no-repeat;background-size: 100% 100%;width: 36px;height: 36px;float: left"></span>
                            <b style="line-height: 36px;float: left">童悦VIP</b>
                        </a>
                    </li>
                    <li class="mui-table-view-cell">
                        <a class="mui-navigate-right" href="{{url('wechat/user/info')}}">
                            <span class="mui-icon icon-contact"
                                  style="background: url('/images/icon_family.png') no-repeat;background-size: 100% 100%;width: 36px;height: 36px;float: left"></span>
                            <b style="line-height: 36px;float: left">家庭信息</b>
                        </a>
                    </li>
                    <li class="mui-table-view-cell">
                        <a class="mui-navigate-right" href="{{url('wechat/user/bindphone')}}">
                             <span class="mui-icon icon-contact"
                                   style="background: url('/images/icon_phone.png') no-repeat;background-size: 100% 100%;width: 36px;height: 36px;float: left"></span>
                            <b style="line-height: 36px;float: left">手机号码</b>
                            <p class="mui-pull-right"
                               style="margin-right: 1.25rem;line-height: 36px;">@if($user->phone){{$user->phone}}@endif</p>
                        </a>
                    </li>

                    <li class="mui-table-view-cell">
                        <a class="mui-navigate-right" href="{{url('wechat/history/index?status=2')}}">
                             <span class="mui-icon icon-contact"
                                   style="background: url('/images/icon_backbook.png') no-repeat;background-size: 100% 100%;width: 36px;height: 36px;float: left"></span>
                            <b style="line-height: 36px;float: left">一键还书</b>
                        </a>
                    </li>

                </ul>
            </div>
        </div>

        <div class="mui-card" style="margin-bottom: 5rem;">
            <div class="mui-card-content">
                <ul class="mui-table-view">
                    <li class="mui-table-view-cell">
                        <a class="mui-navigate-right" href="{{url('/wechat/support/novice')}}">
                             <span class="mui-icon icon-contact"
                                   style="background: url('/images/icon_new.png') no-repeat;background-size: 100% 100%;width: 36px;height: 36px;float: left"></span>
                            <b style="line-height: 36px;float: left">新手上路</b>
                        </a>
                    </li>
                    <li class="mui-table-view-cell">
                        <a class="mui-navigate-right" href="{{url('/wechat/support/problem')}}">
                             <span class="mui-icon icon-contact"
                                   style="background: url('/images/icon_question.png') no-repeat;background-size: 100% 100%;width: 36px;height: 36px;float: left"></span>
                            <b style="line-height: 36px;float: left">常见问题</b>
                        </a>
                    </li>
                    <li class="mui-table-view-cell">
                        <a>
                             <span class="mui-icon icon-contact"
                                   style="background: url('/images/icon_customer.png') no-repeat;background-size: 100% 100%;width: 36px;height: 36px;float: left"></span>
                            <b style="line-height: 36px;float: left">联系客服</b>
                            <p class="mui-pull-right"
                               style="line-height: 36px;">{{config('params.service_phone')}}</p>
                        </a>
                    </li>
                </ul>
            </div>
        </div>


        {{--@include('weChat.layout.tabbar',['on' => 'index'])--}}
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
