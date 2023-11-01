@extends('weChat.layout.app')
@section('title', $title_name)
@section('css')
    <style type="text/css">
        html,
        body {
            background-color: #efeff4;
        }

        p {
            text-indent: 22px;
        }

        .mui-off-canvas-left {
            color: #fff;
        }

        .title {
            margin: 35px 15px 10px;
        }

        .title + .content {
            margin: 10px 15px 35px;
            color: #bbb;
            text-indent: 1em;
            font-size: 14px;
            line-height: 24px;
        }

        input {
            color: #000;
        }

        .mui-bar .mui-search:before {
            top: 20% !important;
        }

        .mui-search .mui-placeholder .mui-icon {
            margin-top: 0.625rem;
        }

        .mui-bar .mui-input-row .mui-input-clear ~ .mui-icon-clear,
        .mui-bar .mui-input-row .mui-input-speech ~ .mui-icon-speech {
            top: 24% !important;
            right: 0rem !important;
        }

        .mui-card li {
            padding: 0rem 0.5625rem !important;
        }

        .mui-card li img {
            /*border: 1px #000000 solid;*/
        }
    </style>
    <link href="{{asset('css/app.css')}}" rel="stylesheet">
@endsection
@section('body')
    <div class="mui-content">
        <div id="offCanvasWrapper" class="mui-off-canvas-wrap mui-draggable" >
            <!--侧滑菜单部分-->
            <aside id="offCanvasSide" class="mui-off-canvas-left">
                <div id="offCanvasSideScroll" class="mui-scroll-wrapper">
                    <div class="mui-scroll">
                        <div class="title" style="margin-bottom: 25px;">图书分类</div>
                        <ul class="mui-table-view mui-table-view-chevron mui-table-view-inverted">
                            @foreach($category as $item)
                                <li class="mui-table-view-cell">
                                    <a class="mui-navigate-right"
                                       href="{{url('/wechat/book/more?category_id='.$item->id)}}">
                                        {{$item->name}}
                                    </a>
                                </li>
                            @endforeach
                        </ul>
                    </div>
                </div>
            </aside>
            <!--主界面部分-->
            <div class="mui-inner-wrap">
                <header class="mui-bar mui-bar-nav">
                    <a href="#offCanvasSide" class="mui-icon mui-action-menu mui-icon-bars mui-pull-left"></a>
                    <h1 class="mui-title">
                        更多图书
                        {{--                        <div class="mui-input-row mui-search">--}}
                        {{--                            <input type="search" class="mui-input-clear" placeholder="">--}}
                        {{--                        </div>--}}
                    </h1>
                </header>
                <div id="offCanvasContentScroll" class="mui-content mui-scroll-wrapper">
                    <div class="mui-scroll">
                        <div id="list" class="mui-card">
                            <div class="mui-card-header">
                                <span style="font-size: 0.875rem;font-weight: bolder;">丨绘本精选</span>
                            </div>
                            <div class="mui-card-content">
                                <ul class="mui-table-view mui-grid-view mui-grid-9" style="background-color: #FFFFFF">
                                    @if(collect($lists)->isNotEmpty())
                                        @foreach($lists as $list)
                                            <li class="mui-table-view-cell mui-media mui-col-xs-4 mui-col-sm-4">
                                                <a href="{{url('/wechat/book/detail?ISBN='.$list->ISBN)}}">
                                                    <img src="{{asset('storage/'.$list->upload_url)}}" width="100%"
                                                         height="120">
                                                    <div class="mui-media-body"
                                                         style="text-align: left;font-size: 0.75rem;">{{$list->name}}</div>
                                                    <div class="mui-media-body"
                                                         style="text-align: left;font-size: 0.625rem;color: #555555;">
                                                        {{$list->reading??0}}人已读
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
                    </div>
                </div>
                <!-- off-canvas backdrop -->
                <div class="mui-off-canvas-backdrop"></div>
            </div>
        </div>
        <div class="footer">
{{--            <div style="height: 100px;"></div>--}}
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

@section('js')
    <script src="{{asset('/mui/js/jquery-3.3.1.min.js')}}"></script>
    <script src="{{asset('/mui/js/mui.min.js')}}"></script>
    <script>
        mui.init();
        mui('.mui-bar-tab').on('tap', 'a', function () {
            var str = (this.href.indexOf("?") != -1) ? "&" : "?";
            document.location.href = this.href+str+"timestamp=" + new Date().getTime();
        });
        //侧滑容器父节点
        var offCanvasWrapper = mui('#offCanvasWrapper');

        //移动效果是否为整体移动
        var moveTogether = false;

        //主界面和侧滑菜单界面均支持区域滚动；
        mui('#offCanvasSideScroll').scroll();
        mui('#offCanvasContentScroll').scroll();
        //实现ios平台原生侧滑关闭页面；
        if (mui.os.plus && mui.os.ios) {
            mui.plusReady(function () { //5+ iOS暂时无法屏蔽popGesture时传递touch事件，故该demo直接屏蔽popGesture功能
                plus.webview.currentWebview().setStyle({
                    'popGesture': 'none'
                });
            });
        }

        //解决 所有a标签 导航不能跳转页面
        mui('body').on('tap','a',function(){document.location.href=this.href;});


        //最外选项卡的自适应
        var aaa = document.getElementById("list").offsetHeight + 80;
        document.getElementById("list").style.height = aaa + "px";


    </script>
    @endsection


    </html>