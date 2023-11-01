@extends('weChat.layout.app')
@section('title', $title_name)
@section('body')
    <div class="mui-content">
        <form method="post" action="{{url('wechat/user/save_info')}}" name="editmember" id="form-add_class">

            <div class="mui-card">
                <div class="mui-card-header">
                    家长信息
                </div>
                <div class="mui-input-group">
                    <div class="mui-input-row">
                        <input type="text" name="name" placeholder="家长姓名" value="{{$list->name}}"
                               style="font-size: 0.875rem;">
                    </div>
                    <div class="mui-input-row">
                        <input type="text" placeholder="家长手机" value=" {{$list->phone}}" disabled="true"
                               style="font-size: 0.875rem;">
                    </div>
                </div>
            </div>
            <div class="mui-card">
                <div class="mui-card-header">
                    孩子信息
                </div>
                <div class="mui-input-group">
                    <div class="mui-input-row">
                        <input type="text" name="kid_name" placeholder="孩子姓名"
                               value="@if(!empty($kids['name'])){{$kids['name']}} @endif" style="font-size: 0.875rem;">
                    </div>
                    @if(empty($school_options))
                    @else
                        <div class="mui-input-row">
                            <select name="school_id" id="school" class="mui-btn-block mui-pull-left"
                                    style="margin-left: 0.9375rem;font-size: 0.875rem;">
                                @if(empty($kids['school_name']))
                                    <option value="-1">请选择</option>
                                @endif
                                @foreach($school_options as $item)
                                    <option value="{{$item->id}}"
                                            @if(!empty($kids['school_name'])&&$item->name==$kids['school_name']) selected @endif>  {{$item->name}}</option>
                                @endforeach
                            </select>
                        </div>

                        <div class="mui-input-row">
                            <select name="grade_id" id="grade" class="mui-btn-block mui-pull-left"
                                    style="margin-left: 0.9375rem;font-size: 0.875rem;">

                            </select>
                        </div>
                    @endif
                </div>

            </div>

            <input type="hidden" name="_token" value="{{ csrf_token() }}"/>
        </form>
    </div>
    <div class="mui-content-padded">
        <button class="mui-btn mui-btn-block" href="javascript:void(0)" id="btn_save"
                onClick="document.forms['editmember'].submit();" style="background-color: #FFD233;color: #0D0D0D;border: 0;"><b>保 存 修 改</b>
        </button>

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
@endsection
@section('js')
    <script src="{{asset('/mui/js/jquery-3.3.1.min.js')}}"></script>
    <script type="text/javascript">
        mui.init();
        mui('.mui-bar-tab').on('tap', 'a', function () {
            var str = (this.href.indexOf("?") != -1) ? "&" : "?";
            document.location.href = this.href+str+"timestamp=" + new Date().getTime();

        });
        (function ($, doc, $$) {
            var selectSchooleId = 0;
            var selectGradeId = 0;
            @if(!empty($kids['grade_id']))
                selectSchooleId = "{{$kids['school_id']}}";
            selectSchooleId = parseInt(selectSchooleId);
            @endif

                    @if(!empty($kids['grade_id']))
                selectGradeId = "{{$kids['grade_id']}}";
            selectGradeId = parseInt(selectGradeId);
            @endif

            $.ready(function () {

                if (selectSchooleId > 0) {
                    ajaxGrade(selectGradeId, selectSchooleId)
                }
                // console.log('grade_id:'+selectGradeId)
                jQuery('#school').on('change', function () {
                    var school_id = jQuery(this).val();
                    if (school_id > 0) {
                        ajaxGrade(selectGradeId, school_id)
                    }

                });
            });
        })(mui, document, jQuery);


        function ajaxGrade(selectGradeId, school_id) {
            $.ajax({
                type: 'get',
                url: "{{url('wechat/user/getGrade')}}",
                data: {
                    _token: '{{csrf_token()}}',
                    school_id: school_id,
                },
                dataType: 'json',
                success: function (data) {
                    setGrade(data, selectGradeId);
                },
                error: function () {
                    alert('error');
                }
            });
        }

        function setGrade(data, selectGradeId) {
            if (data.code == 200) {
                var gradeInfo = data.data;
                var option = "";
                $.each(gradeInfo, function (i, v) {
                    if (selectGradeId == v.id) {
                        option += "<option value='" + v.id + "' selected>" + v.name + "</option>";
                    } else {
                        option += "<option value='" + v.id + "'>" + v.name + "</option>";
                    }
                });
                $("#grade").html(option);

            } else {
                alert(data.msg)
            }
        }


    </script>
@endsection

