<div class="footer">
    <div style="margin-bottom: 5.125rem;text-align: center;height: 3.125rem;width: 100%;color: #555555;font-size: 0.75rem;">
        童悦儿童读物借阅系统
        <br>
        客服：{{config('params.service_phone')}}
    </div>
    <nav class="mui-bar mui-bar-tab" >
        <a class="mui-tab-item mui-active" href="{{url('/wechat/index')}}"   >
            <span class="mui-icon mui-icon-home"></span>
            <span class="mui-tab-label">首页</span>
        </a>
        <a class="mui-tab-item" href="{{url('/wechat/search')}}">
            <span class="mui-icon mui-icon-search"></span>
            <span class="mui-tab-label">寻书</span>
        </a>
        <a class="mui-tab-item" href="{{url('/wechat/history/index')}}">
            <span class="mui-icon mui-icon-refresh"></span>
            <span class="mui-tab-label">历史</span>
        </a>
        <a class="mui-tab-item" href="{{url('/wechat/user')}}">
            <span class="mui-icon mui-icon-contact"></span>
            <span class="mui-tab-label">我的</span>
        </a>
    </nav>
</div>
@section('js')
    <script type="text/javascript">
        mui('.mui-bar-tab').on('tap','a',function(){
             document.location.href=this.href;

        });
    </script>
@endsection

