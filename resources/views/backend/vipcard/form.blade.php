
{{ csrf_field() }}
<input type="hidden" id="form-id" name="id">
<input type="hidden" id="form-school_id" name="school_id">
<div class="form-group form-group-sm">
    <label class="col-sm-3 control-label">会员卡名称</label>
    <div class="col-sm-8">
        <input type="text" class="form-control" name="name" id="form-name" required>
    </div>
</div>
<div class="form-group form-group-sm">
    <label class="col-sm-3 control-label">类型</label>
    <div class="col-sm-8">
        <select class="form-control" name="type" id="form-type">
            @foreach(config('params')['vip_type'] as $k=>$v)
                <option value="{{$k}}">{{$v}}</option>
             @endforeach
        </select>
    </div>
</div>
<div class="form-group form-group-sm">
    <label class="col-sm-3 control-label">价格</label>
    <div class="col-sm-8">
        <input type="text" class="form-control" name="price" id="form-price" required>
    </div>
</div>

<div class="form-group form-group-sm" id="start_ts" style="display: none">
    <label class="col-sm-3 control-label">开始时间</label>
    <div class="col-sm-8">
        <input type="text" class="form-control" name="start_ts" id="form-start_ts" >
    </div>
</div>

<div class="form-group form-group-sm" id="end_ts" style="display: none">
    <label class="col-sm-3 control-label">截止时间</label>
    <div class="col-sm-8">
        <input type="text" class="form-control" name="end_ts" id="form-end_ts">
    </div>
</div>


<div class="form-group form-group-sm">
    <label class="col-sm-3 control-label">状态</label>
    <div class="col-sm-8">
        <select class="form-control" name="status" id="form-status">
            @foreach(config('params')['status'] as $key => $value)
                <option value="{{$key}}">{{$value}}</option>
            @endforeach
        </select>
    </div>
</div>


