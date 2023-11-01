<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge, chrome=1">
    <meta name="viewport" content="width=device-width,initial-scale=1,user-scalable=0">
    <meta http-equiv="pragma" content="no-cache">
    <meta http-equiv="Cache-Control" content="no-cache, must-revalidate">
    <meta http-equiv="expires" content="0">

    <title>
        @section('title')
        @show
    </title>
    <script type="text/javascript" src="{{asset('mui/js/mui.js')}}"></script>
    <link rel="stylesheet" href="{{asset('mui/css/mui.css')}}"/>
    <link rel="stylesheet" href="{{asset('mui/css/mui.picker.css')}}"/>
    <script>window.FastClick = true;</script>

    @section('css')
    @show

</head>
<body ontouchstart>
<div class="container">
@yield('body')

</div>

<script type="text/javascript" src="{{asset('mui/js/mui.picker.min.js')}}"></script>
@section('js')
@show
</body>
</html>