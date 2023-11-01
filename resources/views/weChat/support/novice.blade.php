@extends('weChat.layout.app')
@section('title', $title_name)
@section('css')
<style>
	.mui-content{
		margin-top:15px;
		margin-bottom: 20px;
		padding-left: 10px;
		text-indent: 20px;
	}
</style>
@endsection

@section('body')
	<div class="mui-content" style="background: url('/images/FreshMan - 1.png') no-repeat;background-size:100% 100%;width: 100%;height: 1692px;margin: 0px;">

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