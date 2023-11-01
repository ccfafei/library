@extends('weChat.layout.app')
@section('title', $title_name)
@section('css')
    <style type="text/css">
        .mui-card li {
            padding: 0rem 0.5625rem !important;
        }

        .mui-card li img {
            /*border: 1px #000000 solid;*/
        }

    </style>

    <link rel="stylesheet" href="{{asset('css/app.css')}}">

@endsection

@section('body')
    <div class="mui-content" style="background-color:#f5f8fa">
        <form id="Form" style="margin-top: 10px;">
            <div class="mui-input-row mui-search mui-active">
                <input id="searchInput" type="search" class="mui-input-clear" name="book_name" placeholder="请输入书籍名称"
                       autofocus="autofocus" onkeyup="enterSearch(event)">
            </div>
        </form>
        @if(collect($lists)->isNotEmpty())
            <div class="mui-card">
                <div class="mui-card-header">
                    <span style="font-size: 0.875rem;font-weight: bolder;">丨为您找到{{$total}}本相关书籍</span>
                </div>
                <div class="mui-card-content">
                    <ul class="mui-table-view mui-grid-view mui-grid-9" style="background-color: #FFFFFF">
                        @foreach($lists as $list)
                            @if(!empty($list->upload_url))
                                <li class="mui-table-view-cell mui-media mui-col-xs-4 mui-col-sm-4">
                                    <a href="{{url('/wechat/book/detail?ISBN='.$list->ISBN)}}">
                                        <img src="{{asset('storage/'.$list->upload_url)}}" width="100%" height="120">
                                        <div class="mui-media-body"
                                             style="text-align: left;font-size: 1rem;">{{$list->name}}</div>
{{--                                        <div class="mui-media-body"--}}
{{--                                             style="text-align: left;font-size: 1rem;color: #555555;">--}}
{{--                                            {{$list->reading??0}}人已读--}}
{{--                                        </div>--}}
                                    </a>
                                </li>
                            @endif
                        @endforeach
                    </ul>
                </div>
            </div>
        @else
            <div style="text-align: center;margin-top: 100px;">
                <img src="/images/miss.png" width="60%"/>
                <span style="font-size: 15px;margin-top: 15px;display: block;color: #D6D3CE">未搜索到书籍</span>
            </div>

        @endif

    </div>
    <div class="footer">
        <nav class="mui-bar mui-bar-tab" style="height: 60px;">
            <a class="mui-tab-item" href="{{url('/wechat/index')}}">
                    <span class="mui-icon icon-home"
                          style="background: url('/images/icon_index_no.png') no-repeat;background-size: 100% 100%;width: 30px;height: 30px;"></span>
                <span class="mui-tab-label">首页</span>
            </a>
            <a class="mui-tab-item mui-active" href="{{url('/wechat/search')}}">
                    <span class="mui-icon icon-search"
                          style="background: url('/images/icon_search.png') no-repeat;background-size: 100% 100%;width: 30px;height: 30px;"></span>
                <span class="mui-tab-label">寻书</span>
            </a>
            <a class="mui-tab-item" href="{{url('/wechat/history/index')}}">
                    <span class="mui-icon icon-refresh"
                          style="background: url('/images/icon_history_no.png') no-repeat;background-size: 100% 100%;width: 30px;height: 30px;"></span>
                <span class="mui-tab-label">历史</span>
            </a>
            <a class="mui-tab-item" href="{{url('/wechat/user')}}">
                    <span class="mui-icon icon-contact"
                          style="background: url('/images/icon_ucenter_no.png') no-repeat;background-size: 100% 100%;width: 30px;height: 30px;"></span>
                <span class="mui-tab-label">我的</span>
            </a>
        </nav>
    </div>
@endsection
@section('js')
    <script type="text/javascript">
        mui.init({
            gestureConfig: {
                longtap: true //默认为false
            }
        });
        mui('.mui-bar-tab').on('tap', 'a', function () {
            var str = (this.href.indexOf("?") != -1) ? "&" : "?";
            document.location.href = this.href+str+"timestamp=" + new Date().getTime();

        });

        // 搜索事件,获取搜索关键词
        function enterSearch(event) {
            if (event.keyCode == 13) {//用户点击回车的事件号为13
                document.activeElement.blur();
                var keyword = document.getElementById('searchInput').value;
                event.preventDefault();
                // alert(keyword);
                $("#Form").submit();
            }
        }


    </script>

@endsection