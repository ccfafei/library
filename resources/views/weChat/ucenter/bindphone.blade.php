@extends('weChat.layout.app')
@section('title', $title_name)
@section('body')
<div class="mui-content">
    <form id='login-form' class="mui-input-group" style="margin-top:20px;">

        <div class="mui-input-row">
            <input id='form-phone' name="phone" type="text" class="mui-input-clear mui-input" value="{{$user->phone}}" placeholder="请输入新手机号">
        </div>
        <div class="mui-input-row">
            <input id='vcode' type="text" class="mui-input" style="width: 65%;" placeholder="请输入验证码">
            <span class="spliter">|</span>
            <a href="javascript:void(0)" id="getVcode" style="font-size: 0.875rem;">获取验证码</a>
            {{--<a  id='forgetPassword'style="font-size: 0.875rem;">获取验证码</a>--}}
        </div>
    </form>
    <div class="mui-content-padded">
        <a id='btn_save' href="javascript:void(0)"  class="mui-btn mui-btn-block" style="background-color: #FFD233;color: #0D0D0D;border: 0;"><b>确 定</b></a>
    </div>
    <div class="mui-content-padded oauth-area">
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
</div>
@endsection
@section('js')
    <script>

        mui.init();

        mui('.mui-bar-tab').on('tap','a',function(){
            var str = (this.href.indexOf("?") != -1) ? "&" : "?";
            document.location.href = this.href+str+"timestamp=" + new Date().getTime();

        });
        var getSmsBtn = document.getElementById("getVcode");
        //监听点击事件
        getSmsBtn.addEventListener("tap",function () {
            var phone = mui("#form-phone")[0].value ;
            console.log(phone);
            var rs = checkPhone(phone);
            if(rs ==false){
                mui.alert('手机号不正确');
            }

            mui.ajax("{{url('wechat/getVCode')}}",{
                data:{
                    _token: '{{csrf_token()}}',
                    phone:phone,
                },
                dataType:'json',//服务器返回json格式数据
                type:'post',//HTTP请求类型
                timeout:10000,//超时时间设置为10秒；
                headers:{'Content-Type':'application/json'},
                success:function(data){
                    countdown(60);
                    //服务器返回响应，根据响应结果，分析是否登录成功；
                    console.log(data)

                },
                error:function(xhr,type,errorThrown){
                    //异常处理；
                    console.log(type);
                }
            });
        });

        var btn = document.getElementById("btn_save");
        //监听点击事件
        btn.addEventListener("tap",function () {
            var phone = mui("#form-phone")[0].value ;
            var rs = checkPhone(phone);
            if(rs ==false){
                mui.alert('手机号不正确');
            }

            var vcode =   mui("#vcode")[0].value;

            mui.ajax("{{url('wechat/user/update_phone')}}",{
                data:{
                    _token: '{{csrf_token()}}',
                    phone:phone,
                    vcode: vcode,
                },
                dataType:'json',//服务器返回json格式数据
                type:'post',//HTTP请求类型
                timeout:10000,//超时时间设置为10秒；
                headers:{'Content-Type':'application/json'},
                success:function(data){
                    //服务器返回响应，根据响应结果，分析是否登录成功；
                    console.log(data)
                    if(data.code == 200){
                        mui.toast('更改成功');
                        window.location.href ="{{url('wechat/user/')}}"
                    }else{
                        mui.alert(data.msg);
                    }

                },
                error:function(xhr,type,errorThrown){
                    //异常处理；
                    mui.alert('更新失败');
                    console.log(type);
                }
            });
        });

        //倒计时
        function countdown(s) {
            s--;
            if (s == 0) {
                getSmsBtn.innerHTML = '获取手机验证码';
            } else {
                getSmsBtn.innerHTML = s + '秒后可重发';
                setTimeout(function() {
                    countdown(s)
                }, 1000)
            }
         }

        function checkPhone(phone) {
            if(!(/^1[3|4|5|8][0-9]\d{4,8}$/.test(phone))){
                return false;
            }
            return true;
        }

    </script>

@endsection