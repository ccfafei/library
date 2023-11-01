@extends('weChat.layout.app')
@section('title', $title_name)

@section('body')


    <div class="mui-content">
        @if(empty($user->phone))
            <input id="info_null" value="-1" type="hidden">
        @elseif(empty($kids['name'])|| empty($kids['grade_name']) ||   empty($kids['grade_name']))
            <input id="info_null" value="0" type="hidden">
        @else
            <input id="info_null" value="1" type="hidden">
            <div style="text-align: center;margin-bottom: 20px;">
                <div class="mui-media-body"
                     style="background-color:#FFD233;border-bottom-right-radius: 30%;border-bottom-left-radius: 30%;border: 0;">

                    <div class="mui-row"
                         style="background: url('/images/vipcard.png') no-repeat;background-size:100% 100%;height: 160px;width: 335px;margin:auto;position: relative;top:10px;padding-top: 30px;">
                        <div class="mui-col-xs-4">
                            <img src="{{$user->wx_avatar}}"
                                 style="width: 54px !important;border-radius: 50%;">
                        </div>
                        <div class="mui-col-xs-8">
                            <p style="font-weight: bolder;color: #FFFFFF;text-align: left;">{{$user->wx_name}}</p>
                            <p class="mui-ellipsis"
                               style="font-weight: bolder;text-align: left;color:#FFFFFF;">@if(!empty($kids)){{$kids['school_name']}}@endif {{$user->vip?'-'.$user->vip:''}}</p>
                            <p style="text-align: left  ;">有效日期至：{{$user->vip_exp_at}}</p>

                        </div>
                        <input type="hidden" id="vip_info" value="">
                    </div>
                </div>
            </div>

            @if(!$is_buy_vip)
                {{--                <div class="mui-card">--}}
                {{--                    <div class="mui-card-header">--}}
                {{--                        <span style="font-size: 14px !important;">你是：童悦{{$user->vip}}</span>--}}
                {{--                        <span style="font-size: 14px !important;">有效日期至：{{$user->vip_exp_at}}</span>--}}
                {{--                    </div>--}}
                {{--                </div>--}}
            @else
                <div class="mui-card">
                    <div class="mui-card-header">
                        <b>童悦VIP</b>
                    </div>
                    <div class="mui-card-content" style="padding: 0.625rem;">
                        <form class="mui-input-group">
                            @if(collect($vip_options)->isNotEmpty())
                                @foreach($vip_options as $item)
                                    <div class="mui-row"
                                         style="background: url('/images/vipcard_bg.png') no-repeat;background-size: 100% 100%;width: 335px;height: 92px;margin-top: 10px;">
                                        <div class="mui-col-xs-8" style="padding-top: 22px;padding-left: 20px;">
                                            <p style="color: #FFFFFF;font-size: 18px;font-weight: bolder;">{{$item->name}}
                                                <span style="color: #FF6240"> ¥{{$item->price}}</span></p>
                                            <p style="color: #FFFFFF;font-size: 14px">{{$item->start_ts}}
                                                - {{$item->end_ts}}</p>
                                        </div>
                                        <div class="mui-col-xs-4" style="padding-top: 30px;">
                                            <button mydata="{{$item}}" myinfo="{{$user}}"
                                                    class="mui-btn mui-btn-block chooseWXPay"
                                                    style="height: 34px;width: 78px;border-radius: 20px;line-height: 5px;color: #FFFFFF; background-color: #3E2D2D;border: 0;font-size: 16px;">
                                                <b>开 通</b></button>
                                        </div>

                                    </div>
                                @endforeach
                            @else
                                暂无会员卡信息
                            @endif
                        </form>
                    </div>
                </div>

            @endif
            <div class="mui-card" style="margin-top: 20px;">
                <div class="mui-card-header">
                    <b>服务须知</b>
                </div>
                <div class="mui-card-content" style="padding: 0.625rem;line-height: 30px;color: #999793;">
                    1.会员卡限本人使用，一个会员卡限一个小朋友使用。<br>
                    2.会员卡分周卡和学期卡，周卡在购买一周后过期，学期卡分上学期和下学期。<br>
                    3.一个会员卡借书申请还书后可借第二本书。在有效期内不限次数。<br>
                </div>
            </div>


        @endif
    </div>
@endsection

@section('js')
    <script>
        window.FastClick = true;
    </script>
    <script src="{{asset('/mui/js/jquery-3.3.1.min.js')}}"></script>
    <script src="{{asset('/mui/js/mui.min.js')}}"></script>
    <script src="{{asset('/weui/js/jweixin-1.4.0.js')}}"></script>
    <script src="{{asset('/weui/js/jqForZy.js')}}"></script>
    <script type="text/javascript">


        mui.init();

        mui('.mui-bar-tab').on('tap', 'a', function () {
            var str = (this.href.indexOf("?") != -1) ? "&" : "?";
            document.location.href = this.href+str+"timestamp=" + new Date().getTime();

        });

        //初始化或击单选
        (function ($) {
            $.ready(function () {
                var info_null = document.getElementById("info_null").value;

                if (info_null == -1) {

                    mui.confirm('请完善手机号', '错误', function (e) {
                        if (e.index == 1) {
                            window.location.href = "{{url('wechat/user/bindphone')}}"
                        } else {
                            window.location.href = "{{url('/wechat/user')}}"
                        }
                    })
                }
                if (info_null == 0) {

                    mui.confirm('请完善儿童信息', '错误', function (e) {
                        if (e.index == 1) {
                            window.location.href = "{{url('wechat/user/info')}}"
                        } else {
                            window.location.href = "{{url('/wechat/user')}}"
                        }
                    })
                }

            });
        })(mui);

        wx.config({!! $app->jssdk->buildConfig(array('chooseWXPay'), env('WECHAT_DEBUG')) !!});
        // 10.1 发起一个支付请求
        mui(".mui-col-xs-4").on("tap", ".chooseWXPay", payInit);

        function payInit() {
            var mydata = $(this).attr("mydata");
            var vip_info = JSON.parse(mydata);
            @if(empty($user->phone))
            mui.alert('请先绑定手机号');
            var phoneUrl = "{{url('wechat/user/bindphone')}}";
            setTimeout("location.href='" + phoneUrl + "'", 1000);
            return false;
            @endif

            @if(empty($user->name))
            mui.alert('请先填写个人姓名');
            var url = "{{url('wechat/user/info')}}";
            setTimeout("location.href='" + url + "'", 1000);
            return false;
            @endif

            @if(empty($kids['school_name']) || empty($kids['grade_name']) || empty($kids['name']))
            mui.alert('请先完善儿童信息');
            var url = "{{url('wechat/user/editinfo')}}";
            setTimeout("location.href='" + url + "'", 1000);
            return false;
            @endif

            $.ajax({
                type: 'POST',
                url: "{{url('wechat/user/createTrade')}}",
                data: {
                    vip_id: vip_info.id,
                    vip_end_ts: vip_info.end_ts,
                    _token: '{{csrf_token()}}'
                },
                success: function (data) {
                    if (data.code == 200) {
                        var pay_data = data.data;
                        wxPay(pay_data);
                    } else {
                        mui.alert('支付失败');
                    }
                },
                dataType: "json"
            });
        };

        /**
         * 微信支付
         * @param pay_data
         */
        function wxPay(pay_data) {
            // 注意：此 Demo 使用 2.7 版本支付接口实现，建议使用此接口时参考微信支付相关最新文档。
            var sdk = pay_data.sdk;
            wx.chooseWXPay({
                timestamp: sdk.timestamp,
                nonceStr: sdk.nonceStr,
                package: sdk.package,
                signType: sdk.signType,
                paySign: sdk.paySign, // 支付签名
                success: function (res) {
                    if (res.errMsg == 'chooseWXPay:ok') {
                        mui.alert('支付成功');
                        window.location.href = "{{url('wechat/user')}}";
                    }
                }
            });
        }


    </script>
@endsection
