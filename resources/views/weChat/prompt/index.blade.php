@extends('weChat.layout.app')
@section('title', '提示:')
@section('css')
<style type="text/css">
    li {
        padding: 0rem 0.5625rem !important;
    }

    li img {
        /*border: 1px #000000 solid;*/
    }
</style>
@endsection

@section('body')
@section('js')
    <script type="text/javascript">
        mui.init();
        mui.confirm('{{$data['msg']}}','提示',function (e){
            if (e.index == 1) {
                window.location.href = "{{$data['confirm_url']}}"
            }else{
                window.location.href = "{{$data['cancel_url']}}"
            }
        })

    </script>
@endsection
@endsection

