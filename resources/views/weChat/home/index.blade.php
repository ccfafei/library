@extends('weChat.layout.app')
@section('title', $title_name)
@section('css')
    <style type="text/css">
        li {
            padding: 0rem 0.5625rem !important;
        }

        li img {
            /*border: 1px #000000 solid;*/
        }

        #helpbtn span {
            width: 100%;
            height: 50%;
            line-height: 25px;
            float: left;
            display: block;
            color: #3c3c3c !important;
            text-align: left;
            padding-left: 20px;
        }

    </style>
@endsection

@section('body')
    <script type="text/javascript">
        mui.init();
    </script>
    <div class="mui-content">
        <div class="mui-slider">
            <div class="mui-slider-group">
                @if(collect($banner_list)->isEmpty())
                 <div class="mui-slider-item"><a href="{{url('/wechat/user/vipcard')}}"><img src="/images/banner-1.png"/></a></div>
                @else
                  @foreach($banner_list as $item)
                        <div class="mui-slider-item"><a href="{{$item->link_url}}"><img src="{{asset('storage/'.$item->upload_url)}}"/></a></div>
                  @endforeach
                @endif
            </div>
        </div>
{{--        <div id="helpbtn" style="height: 3.25rem;margin: 0.625rem;overflow: hidden">--}}
{{--            <a href="{{url('/wechat/support/novice')}}">--}}
{{--                <div style="width: 48%;float: left;height: 3.125rem;text-align: center;border-radius: 0.3125rem;background-color: #FCF2D9;position: relative;">--}}
{{--                    <span style="font-size: 14px;font-weight: bolder;margin-left: 60px;color: #FC852B !important;">新手上路</span>--}}
{{--                    <span style="font-size: 12px;color: rgba(41,41,41,0.64);margin-left: 60px;color: #FC852B !important;">让你更快上手</span>--}}
{{--                    <img src="/images/help-1.png"--}}
{{--                         style="width: 40px;height: 40px;position: absolute;top: 5px;left: 15px">--}}
{{--                </div>--}}
{{--            </a>--}}
{{--            <a href="{{url('/wechat/support/problem')}}">--}}
{{--                <div style="width: 48%;float: right;height: 3.125rem;text-align: center;border-radius: 0.3125rem;background-color: #EFF5D0;position: relative;">--}}
{{--                    <span style="font-size: 14px;font-weight: bolder;margin-left: 60px;color: #44B81B !important;">常见问题</span>--}}
{{--                    <span style="font-size: 12px;color: rgba(41,41,41,0.64);margin-left: 60px;color: #44B81B !important;">更快解决问题</span>--}}
{{--                    <img src="/images/help-2.png"--}}
{{--                         style="width: 34px;height: 34px;position: absolute;top: 7px;left: 15px">--}}
{{--                </div>--}}
{{--            </a>--}}
{{--        </div>--}}
        <div id="helpbtn" style="height: 5.3rem;margin: 0.625rem;overflow: hidden">
            <a href="{{url('/wechat/support/novice')}}">
                <div style="width: 48%;float: left;height: 5.3rem;text-align: center;border-radius: 0.3125rem;background-color: #FCF2D9;position: relative;background: url('/images/index-h1.png') no-repeat;background-size: 100%;">

                </div>
            </a>
            <a href="{{url('/wechat/support/problem')}}">
                <div style="width: 48%;float: right;height: 5.3rem;text-align: center;border-radius: 0.3125rem;background-color: #EFF5D0;position: relative;background: url('/images/index-h2.png') no-repeat;background-size:100%;">

                </div>
            </a>
        </div>

        <div class="mui-card">
            <div class="mui-card-header">
                <span style="font-size: 0.875rem;font-weight: bolder;"><b style="color: #FFEA00">丨</b>绘本精选</span>
                <a href="{{url('/wechat/book/more')}}"><span style="font-size: 0.75rem;color: #0D0D0D !important;">查看更多
                        <span class="mui-icon mui-icon-bars" style="font-size: 0.625rem;">

                        </span></span></a>

            </div>

            <div class="mui-card-content">
                <ul class="mui-table-view mui-grid-view mui-grid-9" style="background-color: #FFFFFF">
                    @if(collect($lists)->isNotEmpty())
                        @foreach($lists as $list)

                            <li class="mui-table-view-cell mui-media mui-col-xs-12 mui-col-sm-12" style="overflow-y:hidden;">

                                <a href="{{url('/wechat/book/detail?ISBN='.$list->ISBN)}}">
                                    <div class="mui-row">
                                        <div class="mui-col-xs-4 mui-col-sm-4">
                                            <img src="{{asset('storage/'.$list->upload_url)}}" width="100%"
                                                 height="120">
                                        </div>

                                        <div class="mui-col-xs-8 mui-col-sm-8" style="height: 120px;padding-left: 20px;">
                                            <div class="mui-media-body"
                                                 style="text-align: left;font-size: .9rem;height: 20px;font-weight: bolder;line-height:20px; ">{{$list->name}}</div>
                                            <div class="mui-media-body"
                                                 style="text-align: left;font-size: 0.75rem;height: 40px;line-height: 20px;white-space: normal;display: -webkit-box;-webkit-box-orient: vertical;
    -webkit-line-clamp: 2;color: #999793;width: 90%;">{{$list->remark}}</div>
                                            <div class="mui-media-body"
                                                 style="text-align: left;font-size: 10px;color: #555555;height: 40px;line-height: 40px;color: #B8B6B1">
                                                <b style="color: #FC852B;font-size: 16px;">{{$list->reading??0}}</b> 位小朋友已借阅
                                                <div class="mui-btn mui-pull-right" style="background-color: #FFD233;"><b>马上借阅</b></div>
                                            </div>
                                        </div>
                                    </div>
                                </a>

                            </li>
                        @endforeach
                    @else
                        <li class="mui-table-view-cell mui-media mui-col-xs-4 mui-col-sm-4">
                            暂无图书
                        </li>
                    @endif
                </ul>
            </div>

        </div>
        
        <div class="footer">
            <div style="margin-bottom: 5rem;text-align: center;height: 3.125rem;width: 100%;color: #555555;font-size: 0.75rem;">
                <a href="{{url('/wechat/book/more')}}" class="mui-btn" style="color: #999793;font-size: 12px;">探索更多</a>
            </div>
            <nav class="mui-bar mui-bar-tab" style="height: 60px;">
                <a class="mui-tab-item mui-active" href="{{url('/wechat/index')}}" >
                    <span class="mui-icon icon-home" style="background: url('/images/icon_index.png') no-repeat;background-size: 100% 100%;width: 30px;height: 30px;"></span>
                    <span class="mui-tab-label">首页</span>
                </a>
                <a class="mui-tab-item" href="{{url('/wechat/search')}}">
                    <span class="mui-icon icon-search" style="background: url('/images/icon_search_no.png') no-repeat;background-size: 100% 100%;width: 30px;height: 30px;"></span>
                    <span class="mui-tab-label">寻书</span>
                </a>
                <a class="mui-tab-item" href="{{url('/wechat/history/index')}}">
                    <span class="mui-icon icon-refresh" style="background: url('/images/icon_history_no.png') no-repeat;background-size: 100% 100%;width: 30px;height: 30px;"></span>
                    <span class="mui-tab-label">历史</span>
                </a>
                <a class="mui-tab-item" href="{{url('/wechat/user')}}">
                    <span class="mui-icon icon-contact" style="background: url('/images/icon_ucenter_no.png') no-repeat;background-size: 100% 100%;width: 30px;height: 30px;"></span>
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