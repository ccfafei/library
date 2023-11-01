@extends('weChat.layout.app')
@section('title', $title_name)
@section('css')

@endsection

@section('body')
	<div class="mui-content" style="background: url('/images/FreshMan.png') no-repeat;background-size:100% 100%;width: 100%;height: 885px;margin: 0px;">

	</div>
@endsection

@section('js')
	<script type="text/javascript">
        mui('.mui-bar-tab').on('tap','a',function(){
            var str = (this.href.indexOf("?") != -1) ? "&" : "?";
            document.location.href = this.href+str+"timestamp=" + new Date().getTime();
        });
	</script>
@endsection